<?php

namespace App\Http\Controllers\Admin;

use App\CvTab;
use App\Job;
use App\Utility;
use App\WorkForm;
use App\WorkType;
use App\Employer;
use App\Repo\JobsRepo;
use App\Repo\CvsRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JobsController extends Controller
{
    //
    public function __construct( Request $request, JobsRepo $jobsRepo, CvsRepo $cvsRepo)
    {
        $this->request = $request;
        $this->jobsRepo = $jobsRepo;
        $this->cvsRepo = $cvsRepo;
    }

    public function jobs(){

        return view('admin.jobs.index')->with('jobs');
    }

    public function ajaxJobs(){
        if($this->request->ajax('GET')){

            $jobs = $this->jobsRepo->getJobs($this->request->start,$this->request->length,$this->request->search['value']);
            $jobsTotal = $this->jobsRepo->getTotalJobs($this->request->search['value']);
            $data = [];

            foreach($jobs as $job){
                $newdata = (object)[
                    'thumb'=> $job->thumb,
                    'job_name' => $job->job_name,
                    'company_name' => $job->employer['company_name'],

                    'id'=> [
                        'id'=> $job->id,
                        'cv'=> count($job->cv)
                    ]
                ];
                array_push($data,$newdata);
            }

            $result = (object)[
                "draw" => $this->request->draw,
                "recordsTotal" => $jobsTotal,
                "recordsFiltered" => $jobsTotal,
                "data" => $data
            ];

            return response()->json($result);
        }
    }

    public function getCreate(){
        $employers = Employer::all();

        return view('admin.jobs.create')->with(compact('employers'));
    }

    public function postCreate(){

        $result = $this->jobsRepo->create($this->request);

        if($result){
            $this->request->session()->flash('alert-success', 'Success');
            $this->request->session()->flash('mg-success', 'Thêm mới thành công');
            return redirect('/job/edit/'.$result);
        }else{
            $this->request->session()->flash('alert-danger', 'Error');
            $this->request->session()->flash('mg-danger', 'Thêm thất bại');
            return back();
        }
    }

    public function getEdit($id){
        $this->id = $id;
        
        if (Auth::guard('employers')->user()->isAdmin() ){
            $job = $this->jobsRepo->getJobById($id);
        }else{
            $job = Job::where('id','=',$id)->where('employer_id','=', Auth::guard('employers')->user()->id)->first();
        }
        if($job){
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
        }else{
            abort(404);
        }
        
        $employers = Employer::all();
        $work_forms = WorkForm::all();
        $work_types = WorkType::all();
        $utilities = Utility::all();
        return view('admin.jobs.edit')->with(compact('job','tabs','employers','work_forms','work_types','utilities'));
    }

    public function postEdit($id){
        //dd($this->request->tabs[10]['options']['PWU5sBrWd']['icon']);
        //dd($this->request->all());
        if (Auth::guard('employers')->user()->isAdmin() ){
            $job = $this->jobsRepo->getJobById($id);
        }else{
            $job = Job::where('id','=',$id)->where('employer_id','=', Auth::guard('employers')->user()->id)->first();
        }
        if(!empty($job)) {
            $result = $this->jobsRepo->edit($job, $this->request);
            if($result){
                $this->request->session()->flash('alert-success', 'Success');
                $this->request->session()->flash('mg-success', 'Sửa thành công');
                return back();
            }else{
                $this->request->session()->flash('alert-danger', 'Error');
                $this->request->session()->flash('mg-danger', 'Sửa thất bại');
                return back();
            }
        }else{
            $this->request->session()->flash('alert-danger', 'Error');
            $this->request->session()->flash('mg-danger', 'Sửa thất bại');
            return back();
        }
    }

    public function getDuplicate($id){
        if (Auth::guard('employers')->user()->isAdmin() ){
            $job = $this->jobsRepo->getJobById($id);
        }else{
            $job = Job::where('id','=',$id)->where('employer_id','=', Auth::guard('employers')->user()->id)->first();
        }
        if(!empty($job)) {
            $result = $this->jobsRepo->duplicate($job);
            if($result){
                $this->request->session()->flash('alert-success', 'Success');
                $this->request->session()->flash('mg-success', 'Tạo bản sao thành công');
                return redirect('/job/edit/'.$result);
            }else{
                $this->request->session()->flash('alert-danger', 'Error');
                $this->request->session()->flash('mg-danger', 'Sửa thất bại');
                return back('/jobs');
            }
        }else{
            $this->request->session()->flash('alert-danger', 'Error');
            $this->request->session()->flash('mg-danger', 'Sửa thất bại');
            return back('/jobs');
        }
    }

    public function postDelete($id)
    {
        $status = 0;
        $errors = 0;

        if (Auth::guard('employers')->user()->isAdmin() ){
            $job = $this->jobsRepo->getJobById($id);
        }else{
            $job = Job::where('id','=',$id)->where('employer_id','=', Auth::guard('employers')->user()->id)->first();
        }
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

    public function getUsers($id){
         if (Auth::guard('employers')->user()->isAdmin() ){
            $job = $this->jobsRepo->getJobById($id);
        }else{
            $job = Job::where('id','=',$id)->where('employer_id','=', Auth::guard('employers')->user()->id)->first();
        }
        if($job){
            if($this->request->ajax('GET')){
                $cvs = $this->cvsRepo->getCvsByJobId($job->id,$this->request->start,$this->request->length,$this->request->search['value']);
                $cvsTotal = $this->cvsRepo->getTotalCvsByJobId($job->id,$this->request->search['value']);
                $data = [];

                foreach($cvs as $cv){
                    $user = $cv->user;
                    $newdata = (object)[
                        'avatar'=> $user->thumb,
                        'name' => $user->name,
                        'mobile_number' => $user->mobile_number,
                        'email' => $user->email,
                        'slug'=> $user->slug,
                        'id'=> $user->id
                    ];
                    array_push($data,$newdata);
                }

                $result = (object)[
                    "draw" => $this->request->draw,
                    "recordsTotal" => $cvsTotal,
                    "recordsFiltered" => $cvsTotal,
                    "data" => $data
                ];

                return response()->json($result);
            }else{
                return view('admin.jobs.users')->with(compact('job'));
            }
        }else{
            abort(404);
        }
    }
}
