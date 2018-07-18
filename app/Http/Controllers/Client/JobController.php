<?php

namespace App\Http\Controllers\Client;

use App\CvTab;
use App\CV;
use App\Job;
use App\Review;
use App\Utility;
use App\WorkForm;
use App\WorkType;
use App\Employer;
use App\Repo\JobsRepo;
use App\Repo\CvsRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NotifyCv;

class JobController extends Controller
{
    //
    public function __construct( Request $request, JobsRepo $jobsRepo)
    {
        $this->request = $request;
        $this->jobsRepo = $jobsRepo;
    }

    public function getJobs(){
        $jobs = Job::where('employer_id','=',Auth::guard('employers')->user()->id)->orWhere('employer_view', Auth::guard('employers')->user()->id)->orderBy('created_at','desc')->get();
        return view('user.employer_jobs')->with(compact('jobs'));
    }

    public function getCreate(){
        // $cv = CV::where('user_id','=',18)->first();  
        //  $no = Auth::guard('employers')->user()->notify(new NotifyCv($cv));
        //  return var_dump($no);
        return view('user.employer_job_create');
    }

    public function postCreate(){
        $status = 0;
        $errors = (object)[];
    	$rules = array(
    		'thumb' => 'mimes:jpeg,jpg,png|max:2000',
            'job_name' => 'required|max:255',
            //'job_id' => 'required',
        );
        
        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $status = 400;
            $errors = ['message' => $errorsMessage];
            //return back()->withErrors($errorsMessage)->withInput();
        }

        $result = $this->jobsRepo->create($this->request);

        if($result){
            $status = 1;
            
        }else{
            $status = 500;
            $errors = ['message'=>'not saved to the database'];
        }
        return response()->json(compact('status', 'errors', 'result'));
    }

    public function getEdit($id){
        
        $job = Job::where('id','=',$id)->where(function ($query) {
            $query->where('employer_id','=', Auth::guard('employers')->user()->id)->orWhere('employer_view', Auth::guard('employers')->user()->id);
        })->first();
       
        if($job){
            if(empty($job->tab_active)){
                $job->tab_active = "[]";
            }
            $job->tab_active = json_decode($job->tab_active);
            $tabs = CvTab::where('user_type','like','%job%')->with('options')->with(['rows' => function ($query) use ($id) {
                $query->where('job_id', '=', $id)->with('option')->orderBy('sort','asc');
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
        }else{
            abort(404);
        }
        
        $work_forms = WorkForm::all();
        $work_types = WorkType::all();
        $utilities = Utility::all();
        return view('user.employer_job_edit')->with(compact('job','tabs','work_forms','work_types','utilities'));
    }

    public function postEdit($id){

        
        $rules = array(
            'thumb' => 'mimes:jpeg,jpg,png|max:2000',
            'job_name' => 'required|max:255',
            //'job_end_cv'=>'required|max:20'
            //'job_id' => 'required',
        );
        
        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            //$status = 400;
            //$errors = ['message' => $errorsMessage];
            return back()->withErrors($errorsMessage)->withInput();
        }

        $job = Job::where('id','=',$id)->where(function ($query) {
            $query->where('employer_id','=', Auth::guard('employers')->user()->id)->orWhere('employer_view', Auth::guard('employers')->user()->id);
        })->first();
        
        if(!empty($job)) {
            if($job->job_status != '1'){
                if(!empty($job->employer_view)) {
                    $employer = $job->employerView;
                    if($employer->employer_cost >= 100000) {
                        $employer->employer_cost = $employer->employer_cost -100000;
                        $employer->save();
                    } else {
                        $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.error("Tài khoản không đủ tiền!!");})</script>');
                        //return redirect('/employer/jobs#'.$job->id.'&1');
                        return back();
                    }
                    
                } else {
                    $employer = $job->employer;
                    if($employer->employer_cost >= 100000) {
                        $employer->employer_cost = $employer->employer_cost -100000;
                        $employer->save();
                    } else {
                        $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.error("Tài khoản không đủ tiền!!");})</script>');
                        //return redirect('/employer/jobs#'.$job->id.'&1');
                        return back();
                    }
                }
            }
            $result = $this->jobsRepo->edit($job, $this->request);
            if($result){
                $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.success("Cập nhật thành công");})</script>');
                //return redirect('/employer/jobs#'.$job->id.'&1');
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

    public function deleteJob($id){
        $status = 0;
        $errors = 0;

        $job = Job::where('id','=',$id)->where(function ($query) {
            $query->where('employer_id','=', Auth::guard('employers')->user()->id)->orWhere('employer_view', Auth::guard('employers')->user()->id);
        })->first();
        if (!empty($job)) {
            $result = $this->jobsRepo->delete($job);
            if($result){
                $status = 1;
            }else{
                $status = 500;
                $errors = ['message'=>'not saved to the database'];
            }
        }else{
            $status = 400;
            $errors = ['message'=>'job not found'];
        }
        return response()->json(compact('status','errors'));
    }
   
    public function getDuplicate($id){
        
        $job = Job::where('id','=',$id)->where(function ($query) {
            $query->where('employer_id','=', Auth::guard('employers')->user()->id)->orWhere('employer_view', Auth::guard('employers')->user()->id);
        })->first();
     
        if(!empty($job)) {
            $result = $this->jobsRepo->duplicate($job);
            if($result){
                $this->request->session()->flash('alert-success', 'Success');
                $this->request->session()->flash('mg-success', 'Tạo bản sao thành công');
                return redirect('/employer/job/edit/'.$result);
            }else{
                $this->request->session()->flash('alert-danger', 'Error');
                $this->request->session()->flash('mg-danger', 'Sửa thất bại');
                return back('/employer/jobs');
            }
        }else{
            $this->request->session()->flash('alert-danger', 'Error');
            $this->request->session()->flash('mg-danger', 'Sửa thất bại');
            return back('/employer/jobs');
        }
    }

    public function putReview(){
        $rules = array(
            'user_id' => 'required',
            'job_id'=>'required'
        );
        
        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $status = 400;
            $errors = ['message' => $errorsMessage];
            //return back()->withErrors($errorsMessage)->withInput();
        }

        $review = Review::where('user_id','=',$this->request->user_id)->where('job_id','=',$this->request->job_id)->where('type','=','user')->first();
        
        if(empty($review)) {
            $review = new Review;
            $review->star = $this->request->star;
            $review->reason = json_encode($this->request->reason);
            $review->content = $this->request->content;
            $review->user_id = $this->request->user_id;
            $review->job_id = $this->request->job_id;
            $review->type = 'user';
            if ($review->save()) {
                $status = 1;
            }else{
                $status = 500;
                $errors = ['message'=>'not saved to the database'];
            }

        }else{
            $status = 401;
            $errors = ['message'=>'Đã đánh giá'];
        }

        return response()->json(compact('status','errors'));
    }

    public function putUserReview(){
        
        $rules = array(
            'user_id' => 'required',
            'job_id'=>'required'
        );
        
        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $status = 400;
            $errors = ['message' => $errorsMessage];
            //return back()->withErrors($errorsMessage)->withInput();
        }

        $review = Review::where('user_id','=',$this->request->user_id)->where('job_id','=',$this->request->job_id)->where('type','=','job')->first();
        
        if(empty($review)) {
            $review = new Review;
            $review->star = $this->request->star;
            $review->reason = json_encode($this->request->reason);
            $review->content = $this->request->content;
            $review->user_id = $this->request->user_id;
            $review->job_id = $this->request->job_id;
            $review->type = 'job';
            $id = $review->job_id;
            if ($review->save()) {
                $status = 1;
            }else{
                $status = 500;
                $errors = ['message'=>'not saved to the database'];
            }

        }else{
            $status = 401;
            $errors = ['message'=>'Đã đánh giá'];
        }

        return response()->json(compact('id','status','errors'));
    }
}
