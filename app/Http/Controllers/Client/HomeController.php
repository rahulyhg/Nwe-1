<?php

namespace App\Http\Controllers\Client;

use App\CV;
use App\CvRow;
use App\CvTab;
use App\Job;
use App\Utility;
use App\WorkForm;
use App\WorkType;
use App\Repo\EmployerRepo;
use App\Repo\CvRowRepo;
use App\Repo\JobsRepo;
use App\Repo\UserRepo;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use App\Events\PusherEvent;
use Carbon\Carbon;
use App\Notifications\NotifyCv;

class HomeController extends Controller
{
    //
    private $id;
    public function __construct( Request $request, JobsRepo $jobsRepo, EmployerRepo $employerRepo, UserRepo $userRepo, CvRowRepo $cvRowRepo)
    {
        $this->request = $request;
        $this->jobsRepo = $jobsRepo;
        $this->employerRepo = $employerRepo;
        $this->userRepo = $userRepo;
        $this->cvRowRepo = $cvRowRepo;
    }

    public function home(){

        $tabs = CvTab::where('user_type','like','%user%')->with('options')->orderBy('name', 'asc')->get();
        $work_forms = WorkForm::all();
        $work_types = WorkType::all();
        return view('client.home')->with(compact('tabs','work_forms','work_types'));
    }

    public function search(){

        $tabs = CvTab::where('user_type','like','%user%')->with('options')->orderBy('name', 'asc')->get();
        return view('client.search')->with(compact('tabs'));
    }

    public function getJobsSearch(){
        if($this->request->ajax('GET')){
            if($this->request->search == ""){
                $jobs = Job::with('employer')->offset($this->request->pagination*10)->limit(10)->orderBy('created_at', 'desc');
                if(!Auth::guard('employers')->guest() && !empty($this->request->employer)){
                    $jobs = $jobs->where('employer_id','=', Auth::guard('employers')->user()->id)->orWhere('employer_view', Auth::guard('employers')->user()->id)->with('cv');
                }else{
                	$jobs = $jobs->where('job_status','=', '1');
                }
                $jobs = $jobs->get();
            }else{
                $jobs = Job::where('job_name', 'like', '%'.$this->request->search.'%')->offset($this->request->pagination*10)->limit(10)->with('employer')->orderBy('created_at', 'desc');
                if(!Auth::guard('employers')->guest() && !empty($this->request->employer)){
                    $jobs = $jobs->where('employer_id','=', Auth::guard('employers')->user()->id)->orWhere('employer_view', Auth::guard('employers')->user()->id)->with('cv');
                }else{
                	$jobs = $jobs->where('job_status','=', '1');
                }
                $jobs = $jobs->get();
            }
            if(!empty($jobs)){
                foreach ($jobs as $key => $job) {
                   if(!empty($job->employerView)){
                        $job->employer = $job->employerView;
                   }
                }
            }
            $employer = $this->request->employer;
            return view('client.ajax.jobSearch')->with(compact('jobs','employer'));
        }else{
            abort(404);
        }

    }

    public function getJobsFilter(Job $jobs){
        $type = 'box';
        if($this->request->ajax('GET')){

            $jobs = $jobs->newQuery();

            // if (!empty($this->request->job_form)) {
            //     $jobs->where('job_form', '=' ,$this->request->job_form);

            // }
            // dd($this->request->job_type);
            if (!empty($this->request->job_search)) {
                
                $jobs->where('job_name','like' , '%'.$this->request->job_search.'%');

            }
            if (!empty($this->request->job_type)) {
                
                $jobs->whereIn('job_type', $this->request->job_type);

            }

            if (!empty($this->request->job_city)) {
                $jobs->where('job_city', '=', $this->request->job_city);
            }



            if (!empty($this->request->job_wage)) {
                
                $job_wage = explode(',', $this->request->job_wage);
                $jobs->where('job_wage', '>=', intval($job_wage[0]));
                $jobs->where('job_wage', '<=', intval($job_wage[1]));

                if (!empty($this->request->job_wage_type)) {
                    //$jobs->where('job_wage_type', '=', $this->request->job_wage_type);
                }
            }

            $totalJob = $jobs->where('job_status','=','1')->count();

            $jobs->where('job_status','=','1')->offset($this->request->pagination*10)->limit(10)->orderBy('created_at', 'desc');
            $jobs = $jobs->get();
            if(!empty($jobs)){
                foreach ($jobs as $key => $job) {
                   if(!empty($job->employerView)){
                        $job->employer = $job->employerView;
                   }
                }
            }
            return view('client.ajax.jobSearch')->with(compact('jobs','type','totalJob'));
        }else{
            abort(404);
        }

    }

    public function getJobs(){
        $jobs = Job::all();
        return $jobs;
    }

    public function getJobBySlug($slug){

        $job = $this->jobsRepo->getJobBySlug($slug);
        if($job){
            if(!empty($job->employerView)){
                $job->employer = $job->employerView;
           }
            if(empty($job->tab_active)){
                $job->tab_active = "[]";
            }
            $job->tab_active = json_decode($job->tab_active);
            $tabs = CvTab::where('user_type','like','%job%')->with('options')->with(['rows' => function ($query) use($job) {
                $query->where('job_id', '=', $job->id)->with('option')->orderBy('sort','asc');
            }])->orderBy('name', 'asc')->get();
            if(!empty($tabs)) {
                foreach ($tabs as $tab) {
                    if (!empty($tab->options)) {
                        foreach ($tab->options as $option) {

                            if (!empty($tab->rows)) {
                                foreach ($tab->rows as $row) {
                                    if ($option->id == $row->option_id) {
                                        $option->disabled = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($job->tab_sort)){
                //$newTabs = [];
                foreach(json_decode($job->tab_sort) as $key => $job_id){
                    foreach($tabs as $tab) {
                        if ($tab->id == $job_id) {
                            $tab->sort = $key;
                        }
                    }
                }
                $tabs = $tabs->sortBy('sort');
            }
            $utilities = [];
            if(!empty($job->utility_ids) && $job->utility_ids !='null'){
                $utilities =  Utility::whereIn('id',json_decode($job->utility_ids))->get();
            }
            if(!Auth::guard('web')->guest()){
                $cv = CV::where('job_id','=',$job->id)->where('user_id','=',Auth::guard('web')->user()->id)->count();
                if($cv > 0){
                    $job->cv_active = true;
                }
            }
            if(!empty($job->employer_view)){
                $jobs = Job::where('id','!=', $job->id)->
                where(function ($query) use ($job) {
                    $query->where('employer_id','=',$job->employer_view)->orWhere('employer_view','=',$job->employer_view);
                })->get();
            }else{
                $jobs = Job::where('id','!=', $job->id)->where('employer_id','=',$job->employer_id)->get();
            }
            
        }else{
            abort(404);
        }
        

        return view('client.job')->with(compact('job','jobs','tabs','utilities'));
    }

    public function getEmployerBySlug($slug){

        $employer = $this->employerRepo->getEmployerBySlug($slug);
        if($employer){
            if(empty($employer->tab_active)){
                $employer->tab_active = "[]";
            }
            $employer->tab_active = json_decode($employer->tab_active);
            $tabs = CvTab::where('user_type','like','%employer%')->with('options')->with(['rows' => function ($query) use($employer){
                $query->where('employer_id', '=', $employer->id)->with('option')->orderBy('sort','asc');
            }])->orderBy('name', 'asc')->get();
            if(!empty($tabs)) {
                foreach ($tabs as $tab) {
                    if (!empty($tab->options)) {
                        foreach ($tab->options as $option) {

                            if (!empty($tab->rows)) {
                                foreach ($tab->rows as $row) {
                                    if ($option->id == $row->option_id) {
                                        $option->disabled = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($employer->tab_sort)){
                //$newTabs = [];
                foreach(json_decode($employer->tab_sort) as $key => $tab_id){
                    foreach($tabs as $tab) {
                        if ($tab->id == $tab_id) {
                            $tab->sort = $key;
                        }
                    }
                }
                $tabs = $tabs->sortBy('sort');
            }
            $jobs = Job::where('employer_view','=',$employer->id)->orWhere(function ($query) use ($employer) {
                    $query->where('employer_id','=',$employer->id)->whereNull('employer_view');
                })->get();
            if(!empty($jobs)){
                foreach ($jobs as $key => $job) {
                   if(!empty($job->employerView)){
                        $job->employer = $job->employerView;
                   }
                }
            }
        }else{
            abort(404);
        }

        return view('client.employer')->with(compact('employer','tabs','jobs'));
    }

    public function getUserBySlug($slug){
        $user = $this->userRepo->getUserBySlug($slug);
        if($user){
            if(empty($user->tab_active)){
                $user->tab_active = "[]";
            }
            $user->tab_active = json_decode($user->tab_active);
            $tabs = CvTab::where('user_type','like','%user%')->with('options')->with(['rows' => function ($query) use($user){
                $query->where('user_id', '=', $user->id)->with('option')->orderBy('sort','asc');
            }])->orderBy('name', 'asc')->get();
            if(!empty($tabs)) {
                foreach ($tabs as $tab) {
                    if (!empty($tab->options)) {
                        foreach ($tab->options as $option) {

                            if (!empty($tab->rows)) {
                                foreach ($tab->rows as $row) {
                                    if ($option->id == $row->option_id) {
                                        $option->disabled = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($user->tab_sort)){
                //$newTabs = [];
                foreach(json_decode($user->tab_sort) as $key => $tab_id){
                    foreach($tabs as $tab) {
                        if ($tab->id == $tab_id) {
                            $tab->sort = $key;
                        }
                    }
                }
                $tabs = $tabs->sortBy('sort');
            }
        }else{
            abort(404);
        }

        return view('client.user')->with(compact('user','tabs'));
    }

    public function getJobById($id){
        $this->id = $id;
        $job = $this->jobsRepo->getJobById($id);
        if($job){
            if(!empty($job->employerView)){
                $job->employer = $job->employerView;
           }
            if(empty($job->tab_active)){
                $job->tab_active = "[]";
            }
            $job->tab_active = json_decode($job->tab_active);
            $tabs = CvTab::where('user_type','like','%job%')->with('options')->with(['rows' => function ($query) {
                $query->where('job_id', '=', $this->id)->with('option')->orderBy('sort','asc');
            }])->orderBy('name', 'asc')->get();
            if(!empty($tabs)) {
                foreach ($tabs as $tab) {
                    if (!empty($tab->options)) {
                        foreach ($tab->options as $option) {

                            if (!empty($tab->rows)) {
                                foreach ($tab->rows as $row) {
                                    if ($option->id == $row->option_id) {
                                        $option->disabled = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($job->tab_sort)){
                //$newTabs = [];
                foreach(json_decode($job->tab_sort) as $key => $job_id){
                    foreach($tabs as $tab) {
                        if ($tab->id == $job_id) {
                            $tab->sort = $key;
                        }
                    }
                }
                $tabs = $tabs->sortBy('sort');
            }
            $utilities = [];
            if(!empty($job->utility_ids) && $job->utility_ids !='null'){
                $utilities =  Utility::whereIn('id',json_decode($job->utility_ids))->get();
            }
            if(!Auth::guard('web')->guest()){
                $cv = CV::where('job_id','=',$job->id)->where('user_id','=',Auth::guard('web')->user()->id)->count();
                if($cv > 0){
                    $job->cv_active = true;
                }
            }
            
        }

        return view('client.ajax.job')->with(compact('job','tabs','utilities'));
    }

    public function putCV(){
        //dd($this->request->all());
        $status = 0;
        $errors = 0;
        $session = $this->request->session()->get('key');
        if(empty( $session )){
            $session = $this->request->session()->put('key',(time() - 60));
        }

        if( (time() - 15) < $session ){
            $time = $session - (time() - 15);
            $status = 450;
            $errors = ['message'=>'Xin mời quay lại sau '.$time.'s nữa!'];
            return response()->json(compact('status','errors'));
        }else{

        }

        $rules = array(
            'job_id' => 'required',
        );

        $validator = Validator::make($this->request->all(), $rules,[
            'job_id.required' => 'Please enter job id'
        ]);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $status = 400;
            $errors = ['message'=> $errorsMessage];
        } else {
            $job = $this->jobsRepo->getJobById($this->request->job_id);

            if(!empty($job)){

                $user = Auth::guard('web')->user();

                if(!empty($user)) {
                    if($user->users_cost >= 10000) {
                        $cv = CV::where('job_id','=', $job->id)->where('user_id','=',$user->id)->first();
                        if(empty($cv)) {
                            $cv = new CV();
                            $cv->job_id = $job->id;
                            $cv->user_id = $user->id;
                            $cv->status = 'pending';
                            $cv->save();
                            $user->users_cost=$user->users_cost - 10000;
                            $user->save();
                            $status = 1;
                            $cv->action = "add";
                            $cv->user_slug = $user->slug;
                            $cv->user_name = $user->name;
                            $cv->job_slug = $job->slug;
                            $cv->job_name = $job->job_name;
                            $cv->message = $user->name." đã nộp cv";
                            // event(new PusherEvent(json_encode($cv)));
                            $device_tokens = $job->employer['device_tokens'];
                            $job->employer->notify(new NotifyCv($cv,$device_tokens));
                             if(!empty($job->employerView)){
                                $job->employerView->notify(new NotifyCv($cv, $job->employerView['device_tokens']));
                            }    
                            $this->request->session()->put('key',time());   
                            $linkusers = makeurl(config('app.api_url'), 'employer/jobs#'.$job->id);
                            Mail::send('emails.put_cv',['linkusers'=> $linkusers,'user' => $user, 'job' => $job], function($m) use ($user,$job) {
                                $m->to('khanhln.c1003j@gmail.com')->subject('New CV ('.$job->job_name.')');
                            });
                        }else{
                            $status = 400;
                            $errors = ['message'=>'Bạn đã nộp hồ sơ'];
                        }
                    } else {
                        $status = 400;
                        $errors = ['message'=>'Tài khoản không đủ để thực hiện giao dịch'];
                    }
                    
                }else{
                    $status = 401;
                    $errors = ['message'=>'Bạn chưa tạo hồ sơ'];
                }
            }else{
                $status = 402;
                $errors = ['message'=>'Công việc này không tồn tại'];
            }
        }


        return response()->json(compact('status','errors'));
    }

    public function regCV()
    {
        $status = 0;
        $errors = 0;
        $session = $this->request->session()->get('key');
        if (empty($session)) {
            $session = $this->request->session()->put('key', (time() - 60));
        }

        if ((time() - 15) < $session) {
            $time = $session - (time() - 15);
            $status = 450;
            $errors = ['message' => 'Xin mời quay lại sau ' . $time . ' giây nữa!'];
            return response()->json(compact('status', 'errors'));
        } else {

        }

        $rules = array(
            'name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:users,email',
            'mobile_number' => 'required|max:20|unique:users,mobile_number',
            'password' => 'required|min:6|max:50'
            //'job_id' => 'required',
        );

        $validator = Validator::make($this->request->all(), $rules, [
            'email.required' => 'Bạn chưa nhập email',
            'email.email' => 'Email không đúng định dạng',

        ]);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $status = 400;
            $errors = ['message' => $errorsMessage];
        } else {
            $user = new User();
            $user->name = $this->request->name;
            $user->birthday = $this->request->birthday;
            $user->address = $this->request->address;
            $user->email = $this->request->email;
            $user->mobile_number = $this->request->mobile_number;
            $user->gender = $this->request->gender;
            $user->work_type = $this->request->work_type;
            $user->work_address = $this->request->work_address;
            $user->work_wage = $this->request->work_wage;
            $user->work_position = $this->request->work_position;
            $user->password = Hash::make($this->request->password);
            $user->active = 0;
            if($user->save()){
                $status = 1;
                if(!empty($this->request->tabs)){
                    foreach($this->request->tabs as $tab_id => $tab){
                        if(!empty($tab['rows'])){
                            $rowSort = 0;
                            foreach($tab['rows'] as $row_id => $row){
                                $rowSort++;
                                
                                $_row = new CvRow;
                                
                                if(!empty($tab['input_type'])){
                                    if($tab['input_type'] == "select"){
                                        $_row->option_id = $row['option_id'];
                                    }
                                    if($tab['input_type'] == "text"){
                                        $_row->name = $row['name'];

                                    }
                                }
                                $_row->description = $row['description'];
                                $_row->date_start = $row['date_start'];
                                $_row->date_end = $row['date_end'];
                                $_row->sort = $rowSort;
                                //$_row->date_end = (!empty($request->date_end) && $request->date_end !="null")?$request->date_end:null;
                                $_row->cv_tab_id = $tab_id;
                                $_row->user_id = $user->id;

                                if($_row->save()){

                                }
                            }
                        }
                    }
                }
            }
        }

        return response()->json(compact('status','errors'));
    }


    public function updateProfile(){
        $rules = array(
            'avatar' => 'mimes:jpeg,jpg,png|max:2000',
            'name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:users,email,'.Auth::guard('web')->user()->id,
            'mobile_number' => 'required|max:20|unique:users,mobile_number,'.Auth::guard('web')->user()->id,
            //'job_id' => 'required',
        );
        
        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
       
        $user = $this->userRepo->getUserById(Auth::guard('web')->user()->id);
       
        if(!empty($user)) {
            $result = $this->userRepo->update($user, $this->request);
            if($result){
                $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.success("Cập nhật thành công");})</script>');
                return back();
            }else{
                $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.error("Cập nhật thất bại");})</script>');
                return back();
            }
        }else{
            $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.error("Cập nhật thất bại");})</script>');
            return back();
        }
    }

    public function getInfoCv(){
        $user = Auth::guard('web')->user();
        $role = 'user';
        
        if($user){
            if(empty($user->tab_active)){
                $user->tab_active = "[]";
            }
            $user->tab_active = json_decode($user->tab_active);
            $tabs = CvTab::where('user_type','like','%'.$role.'%')->with('options')->with(['rows' => function ($query) use($user,$role){
                $query->where($role.'_id', '=', $user->id)->with('option')->orderBy('sort','asc');
            }])->orderBy('name', 'asc')->get();
            if(!empty($tabs)) {
                foreach ($tabs as $tab) {
                    if (!empty($tab->options)) {
                        foreach ($tab->options as $option) {

                            if (!empty($tab->rows)) {
                                foreach ($tab->rows as $row) {
                                    if ($option->id == $row->option_id) {
                                        $option->disabled = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($user->tab_sort)){
                //$newTabs = [];
                foreach(json_decode($user->tab_sort) as $key => $tab_id){
                    foreach($tabs as $tab) {
                        if ($tab->id == $tab_id) {
                            $tab->sort = $key;
                        }
                    }
                }
                $tabs = $tabs->sortBy('sort');
            }
        }else{
            abort(404);
        }
        
        $work_forms = WorkForm::all();
        $work_types = WorkType::all();
        return view('user.user_info_cv')->with(compact('user','tabs','work_forms','work_types'));
    }

    public function updateCV(){
        
        $rules = array(
            'birthday' => 'required|max:50',
            'address' => 'required|max:255',
            
            //'job_id' => 'required',
        );

        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            return back()->withErrors($errorsMessage);
        } else {
            
            $user = $this->userRepo->getUserById(Auth::guard('web')->user()->id);
            if($user){
                
                $user->birthday = $this->request->birthday;
                $user->address = $this->request->address;
                $user->gender = $this->request->gender;
                $user->work_type = $this->request->work_type;
                $user->work_form = $this->request->work_form;
                $user->work_address = $this->request->work_address;
                $user->work_wage = $this->request->work_wage;
                $user->work_position = $this->request->work_position;
                $user->description = $this->request->description;
                // $file =  $this->request->avatar;
                // $path = 'uploads/users/'.$user->id.'/';
                // $modifiedFileName = time().'-'.$file->getClientOriginalName();
                // if($file->move($path,$modifiedFileName)){
                //     $user->avatar = $path.$modifiedFileName;
                // }
                if(!empty($this->request->tabs)){

                    foreach($this->request->tabs as $tab_id => $tab){
                        if(!empty($tab['rows'])){
                            $rowSort = 0;
                            //dd($this->request->tabs);
                            foreach($tab['rows'] as $row_id => $row){
                                $rowSort++;
                                $_row = CvRow::where('id','=',$row_id)->where('user_id','=',Auth::guard('web')->user()->id)->first();
                                if(empty($_row)){
                                    $_row = new CvRow();
                                }

                                if(!empty($tab['input_type'])){
                                    if($tab['input_type'] == "select"){
                                        $_row->option_id = $row['option_id'];
                                    }
                                    if($tab['input_type'] == "text"){
                                        $_row->name = $row['name'];

                                    }
                                }
                                $_row->description = $row['description'];
                                $_row->date_start = $row['date_start'];
                                $_row->date_end = $row['date_end'];
                                $_row->sort = $rowSort;
                                //$_row->date_end = (!empty($request->date_end) && $request->date_end !="null")?$request->date_end:null;
                                $_row->cv_tab_id = $tab_id;
                                $_row->user_id = $user->id;

                                $_row->save();
                            }
                            //dd($rowSort);
                        }
                    }
                }
                if($user->save()){
                    
                    try {
                        if(!empty($user->getfly_id)){
                            $getfly = $this->userRepo->GetFly($user,'https://sachvidan.getflycrm.com/api/v3/account/'.$user->getfly_id,'PUT');
                        }
                    } catch (Exception $e) {
                        
                    }

                    $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.success("Cập nhật thành công");})</script>');
                    return back();
                   
                }else{
                    $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.error("Cập nhật thất bại");})</script>');
                    return back();
                }
            }
            
        }

        return response()->json(compact('status','errors'));
    }

    public function deleteRow($id){
        $status = 0;
        $errors = 0;

        if(Auth::guard('employers')->user()){
            $cvRow = CvRow::where('id','=',$id)->where('employer_create','=',Auth::guard('employers')->user()->id);
        }else{
            $cvRow = CvRow::where('id','=',$id)->where('user_id','=',Auth::guard('web')->user()->id);
        }

        if(!empty($cvRow)){

            $result = $this->cvRowRepo->delete($cvRow);
            if($result){
                $status = 1;
            }else{
                $status = 500;
                $errors = ['message'=>'not saved to the database'];
            }
        }else{
            $status = 400;
            $errors = ['message'=>'user not found'];
        }

        return response()->json(compact('status','errors'));
    }
}
