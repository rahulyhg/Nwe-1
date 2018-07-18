<?php

namespace App\Http\Controllers;

use App\CvTab;
use App\Job;
use App\WorkForm;
use App\WorkType;
use App\Employer;
use App\Repo\JobsRepo;
use App\Repo\CvsRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ApiJobsController extends Controller
{
    //
    public function __construct( Request $request, JobsRepo $jobsRepo, CvsRepo $cvsRepo)
    {
        $this->request = $request;
        $this->jobsRepo = $jobsRepo;
        $this->cvsRepo = $cvsRepo;
    }

    public function getJobs(Job $jobs){
        $status = 0;
        $errors = (object)[];
        $offset = $this->request->pagination;
        $totalJob = "";

        $rules = array(
            'pagination'=> 'required|numeric|min:0',
        );
        // Create a new validator instance.
        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $errors = ['message'=> $errorsMessage];
        } else{

            $jobs = $jobs->newQuery();

            if($this->request['user']['rol'] =="employer"){
                $jobs->where('employer_id','=', $this->request['user']['sub']);
            }
            if (!empty($this->request->job_name)) {
                $jobs->where('job_name', 'like' ,'%'.$this->request->job_name.'%');
            }

            if (!empty($this->request->job_form)) {
                $jobs->where('job_form', '=' ,$this->request->job_form);
            }

            if (!empty($this->request->job_type)) {
                $jobs->where('job_type', '=', $this->request->job_type);
            }

            if (!empty($this->request->job_city)) {
                $jobs->where('job_city', 'like', "%".$this->request->job_city."%");
            }



            // if (!empty($this->request->job_wage)) {
            //     if($this->request->job_wage == "1"){
            //         $jobs->where('job_wage', '<', 2000000);
            //     }else if($this->request->job_wage == "2"){
            //         $jobs->where('job_wage', '>=', 2000000);
            //         $jobs->where('job_wage', '<=', 5000000);
            //     }
            //     else if($this->request->job_wage == "3"){
            //         $jobs->where('job_wage', '>', 5000000);
            //         $jobs->where('job_wage', '<=', 7000000);
            //     }
            //     else if($this->request->job_wage == "4"){
            //         $jobs->where('job_wage', '>', 7000000);
            //         $jobs->where('job_wage', '<=', 10000000);
            //     }
            //     else if($this->request->job_wage == "5"){
            //         $jobs->where('job_wage', '>', 10000000);
            //         $jobs->where('job_wage', '<=', 15000000);
            //     }
            //     else if($this->request->job_wage == "6"){
            //         $jobs->where('job_wage', '>=', 15000000);
            //     }

            //     if (!empty($this->request->job_wage_type)) {
            //         $jobs->where('job_wage_type', '=', $this->request->job_wage_type);
            //     }
            // }


            $jobs->offset($this->request->pagination*10)->limit(10)->with('work_form')->with('work_type')->with('employerView')->orderBy('created_at', 'desc');
            if($this->request['user']['rol'] =="employer"){
              $jobs = $jobs->where('employer_id','=',$this->request['user']['sub'])->orWhere('employer_view', $this->request['user']['sub']);
            }
            $totalJob = $jobs->count();
            $jobs = $jobs->get();
            if(!empty($jobs)){
                foreach ($jobs as $job) {
                    $job->job_description = json_decode($job->job_description);
                    $job->job_benefit = json_decode($job->job_benefit);
                    $job->job_request = json_decode($job->job_request);
                    if(!empty($job->employerView)){
                    	$job->employer = $job->employerView;
                    }else{
                        $job->employer = $job->employer;
                    }
                }
            }
            $status = 1;
        }

        return response()->json(compact('status','errors','jobs','totalJob'));
    }

    public function getJobById($id){
        $status = 0;
        $errors = (object)[];

        // if($this->request['user']['rol'] !="employer"){
        //     $errors = ['code' => 201,'message'=>['other'=>'access denied']];
        //     return response()->json(compact('status','errors','job'));
        // }
        $job = Job::where('id','=',$id);
        if($this->request['user']['rol'] =="employer"){
              
            $job = $job->where(function ($query) {
                $query->where('employer_id','=', $this->request['user']['sub'])->orWhere('employer_view', $this->request['user']['sub']);
            });
        }
        $job = $job->with('employer')->with('employerView')->with('work_form')->with('work_type')->first();
            
        if(!empty($job)){
            $status = 1;
        }else{
            $job = (object)[];
            $errors = ['code' => 400,'message'=>['other'=>'job not found']];
        }
        return response()->json(compact('status','errors','job'));
    }

    public function postUpdateJob(Request $request){
    	$status = 0;
        $errors = (object)[];
        $job = (object)[];
        if($request['user']['rol'] !="employer"){
            $errors = ['code' => 201,'message'=>['other'=>'access denied']];
            return response()->json(compact('status','errors','job'));
        }

        $rules = array(
            'thumb' => 'mimes:jpeg,jpg,png|max:2000',
        	'job_name'=> 'required',
        	'job_gender'=>'in:1,2,3',
        	'job_wage_type'=>'in:day,month'       
        );
        // Create a new validator instance.
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $errors = ['message'=> $errorsMessage];
        } else {
           
            $job = Job::where('id','=', $this->request->id)->where(function ($query) {
                $query->where('employer_id','=', $this->request['user']['sub'])->orWhere('employer_view', $this->request['user']['sub']);
            })->first();
            if(empty($job)){
            	if($this->request->id == "0"){
            		$job = new Job;
            	}else{
            		$errors = ['code' => 400,'message'=>['other'=>'job not found']];
            		return response()->json(compact('status','errors','job'));
            	}
            }
           
	        $job->job_name = $request->job_name;
	        $job->employer_id = $this->request['user']['sub'];
	        $job->job_status = $request->job_status;
	        $job->job_form = $request->job_form;
	        $job->job_type = $request->job_type;
	        $job->job_address = $request->job_address;
	        $job->job_city = $request->job_city;
	        $job->job_time = $request->job_time;
	        $job->job_date_start = $request->job_date_start;
	        $job->job_gender = $request->job_gender;
	        $job->job_age = $request->job_age;
	        $job->job_people = $request->job_people;
	        $job->job_end_cv = $request->job_end_cv;
	        $job->job_wage = $request->job_wage;
	        $job->job_wage_type = $request->job_wage_type;
	        $job->job_description = json_encode($request->job_description);
            $job->job_benefit = json_encode($request->job_benefit);
            $job->job_request = json_encode($request->job_request);
	        $job->lat = $request->lat;
	        $job->lng = $request->lng;
            if($job->save()){ // success
            	if(!empty($request->thumb)) {
	                $file = $request->thumb;
	                $path = 'uploads/jobs/' .$job->id . '/';
	                $modifiedFileName = time() . '-' . $file->getClientOriginalName();
	                if ($file->move($path, $modifiedFileName)) {
	                    $job->thumb = $path . $modifiedFileName;
	                    $job->save();
	                }
	            }
                $status = 1;
            }else{ // failed
                $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
            }
        }

        return response()->json(compact('status','errors','job'));
    }
}