<?php

namespace App\Http\Controllers\Client;

use App\CvTab;
use App\CV;
use App\Job;
use App\Utility;
use App\WorkForm;
use App\WorkType;
use App\Employer;
use App\Review;
use App\Repo\JobsRepo;
use App\Repo\CvsRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\PusherEvent;
use App\Notifications\NotifyCv;

class CvController extends Controller{

	public function __construct( Request $request, JobsRepo $jobsRepo)
    {
        $this->request = $request;
        $this->jobsRepo = $jobsRepo;
    }

     public function getCvsByJob($id){
        $job = Job::where('id','=',$id)->where(function ($query) {
            $query->where('employer_id','=', Auth::guard('employers')->user()->id)->orWhere('employer_view', Auth::guard('employers')->user()->id);
        })->first();
        $utilities = [];
            if(!empty($job->utility_ids) && $job->utility_ids !='null'){
                $utilities =  Utility::whereIn('id',json_decode($job->utility_ids))->get();
            }
        $cvs = CV::where('job_id','=',$id)->with('user')->whereHas('job', function($query) {
                $query->where('employer_id', '=', Auth::guard('employers')->user()->id)->orWhere('employer_view', Auth::guard('employers')->user()->id);
            })->orderBy('created_at', 'desc')->get();

        if(!empty($cvs)){
            foreach ($cvs as $key => $cv) {
               $review = Review::where('user_id','=',$cv->user['id'])->where('job_id','=',$id)->where('type','=','user')->first();
               if(empty($review)){
                    $cv->review = true;
               }else{
                    $cv->review = false;
               }
            }
        }
        
        return view('user.employer_ajax_cvs')->with(compact('cvs','id','job','utilities')); 
    }

    public function getCvsByUser(){
        $cvs = CV::where('user_id','=',Auth::guard('web')->user()->id)->with('job')->orderBy('created_at', 'desc')->get();
        if(!empty($cvs)){
            foreach ($cvs as $key => $cv) {
                if(!empty($cv->job['employerView'])){
                   $cv->job['employer'] = $cv->job['employerView'];
                }

                $review = Review::where('user_id','=',Auth::guard('web')->user()->id)->where('job_id','=',$cv->job['id'])->where('type','=','job')->first();
               if(empty($review)){
                    $cv->review = true;
               }else{
                    $cv->review = false;
               }
            }
        }
        if(!empty($job->employerView)){
            
       }
        return view('user.user_cvs')->with(compact('cvs')); 
    }

    public function updateStatus($id){
        $status = 0;
        $errors = (object)[];
        $cv = (object)[];

        $rules = array( 
            'status'=>'required|in:interview,work',
        );
        // Create a new validator instance.
        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $errors = ['message'=> $errorsMessage];
            return response()->json(compact('status','errors','cv'));
        }

        $cv =  CV::where('id','=', $id)->whereHas('job', function ($query) {
                $query->where('employer_id', '=', Auth::guard('employers')->user()->id)->orWhere('employer_view', Auth::guard('employers')->user()->id);
            })->with('user')->with('job')->first();
        
        if(!empty($cv)){
            $user = $cv->user;
            $job = $cv->job;
            if($cv->status == "pending" && $this->request->status == "interview"){
                $cv->status = $this->request->status;
                $cv->active_interview = 0;
                if($cv->save()){
                    $status = 1;
                    $cv->action = "change-status";
                    $cv->user_slug = $user->slug;
                    $cv->user_name = $user->name;
                    $cv->job_slug = $job->slug;
                    $cv->job_name = $job->job_name;
                    $cv->message = "Bạn được mời phỏng vấn";
                    // event(new PusherEvent(json_encode($cv)));
                    $device_tokens = $user->device_tokens;
                    $user->notify(new NotifyCv($cv,$device_tokens));
                    //event(new PusherEvent(json_encode($cv)));
                }else{

                    $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                }
            }else if($cv->status == "interview" && $cv->active_interview == "1" && $this->request->status == "trial_work"){
                $cv->status = $this->request->status;
                $cv->active_trial_work = 0;
                if($cv->save()){
                    $status = 1;
                    $cv->action = "change-status";
                    $cv->user_slug = $user->slug;
                    $cv->user_name = $user->name;
                    $cv->job_slug = $job->slug;
                    $cv->job_name = $job->job_name;
                    $cv->message = "Bạn được mời thử việc";
                    // event(new PusherEvent(json_encode($cv)));
                    $device_tokens = $user->device_tokens;
                    $user->notify(new NotifyCv($cv,$device_tokens));    
                }else{
                    $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                }
            }else if($cv->status == "interview" && $cv->active_interview == "1" && $this->request->status == "work"){
                $cv->status = $this->request->status;
                $cv->active_work = 0;
                if($cv->save()){
                    $status = 1;
                    $cv->action = "change-status";    
                    $cv->user_slug = $user->slug;
                    $cv->user_name = $user->name;
                    $cv->job_slug = $job->slug;
                    $cv->job_name = $job->job_name;
                    $cv->message = "Bạn đc mời làm việc";
                    // event(new PusherEvent(json_encode($cv)));
                    $device_tokens = $user->device_tokens;
                    $user->notify(new NotifyCv($cv,$device_tokens));
                }else{
                    $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                }
            }else if($cv->status == "work" && $cv->active_work == "1" && $this->request->status == "complete_work"){
                $cv->status = $this->request->status;
                $cv->active_complete_work = 0;
                if($cv->save()){
                    $status = 1;
                    $cv->action = "change-status";   
                    $cv->user_slug = $user->slug;
                    $cv->user_name = $user->name;
                    $cv->job_slug = $job->slug;
                    $cv->job_name = $job->job_name;
                    $cv->message = "Bạn cần xác nhận hoàn thành";
                    // event(new PusherEvent(json_encode($cv)));
                    $device_tokens = $user->device_tokens;
                    $user->notify(new NotifyCv($cv,$device_tokens));
                }else{
                    $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                }
            }else{
                $errors = ['code' => 202,'message'=>['other'=>'user chưa xác nhận hoặc đã từ chối trạng thái trước đó']];
            }
        }else{
            $cv = (object)[];
            $errors = ['code' => 400,'message'=>['other'=>'cv not found']];
        }

        return response()->json(compact('status','errors','cv'));
    }
    
    public function activeStatus($id){
        $status = 0;
        $errors = (object)[];
        $cv = (object)[];

        $rules = array( 
            'active'=> 'required|in:1,2'
        );
        // Create a new validator instance.
        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $errors = ['message'=> $errorsMessage];
            return response()->json(compact('status','errors','cv'));
        }
        if(!Auth::guard('web')->guest()){
            $cv =  CV::where('id','=', $id)->where('user_id','=',Auth::guard('web')->user()->id)->with('user')->with('job')->first();
            $notiUser = $cv->job->employer;
            if(!empty($cv->job->employerView)){
                $mulUser = $cv->job->employerView;
            }
        }
        if(!Auth::guard('employers')->guest()){
            $cv =  CV::where('id','=', $id)->whereHas('job', function ($query) {
                $query->where('employer_id', '=', Auth::guard('employers')->user()->id)->orWhere('employer_view', Auth::guard('employers')->user()->id);
            })->with('user')->with('job')->first();
            $notiUser = $cv->user;
        }

        if(!empty($cv)){
            $user = $cv->user;
            $job = $cv->job;
            if(!Auth::guard('employers')->guest()){
                if($cv->status == "pending" && $this->request->active == "2"){
                    $cv->active_interview = $this->request->active;
                    if($cv->save()){
                        $user->users_cost = $user->users_cost + 10000;
                        $user->save();
                        $status = 1; 
                        $cv->action = "active";
                        $cv->user_slug = $user->slug;
                        $cv->user_name = $user->name;
                        $cv->job_slug = $job->slug;
                        $cv->job_name = $job->job_name;
                        // if(!Auth::guard('web')->guest()){
                        //     $cv->message = "Bạn đc mời phỏng vấn";        
                        // }
                        if(!Auth::guard('employers')->guest()){
                            $cv->message = "Bạn bị từ chối phỏng vấn";        
                        }
                        // event(new PusherEvent(json_encode($cv)));
                        $device_tokens = $notiUser['device_tokens'];
                        $notiUser->notify(new NotifyCv($cv,$device_tokens));
                        // event(new PusherEvent(json_encode($cv)));   
                    }else{
                        $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                    }
                }else if($cv->status == "interview1" && $this->request->active == "2"){
                    $cv->active_trial_work = $this->request->active;
                    if($cv->save()){
                        $status = 1; 
                        $cv->action = "active";
                        $cv->user_slug = $user->slug;
                        $cv->user_name = $user->name;
                        $cv->job_slug = $job->slug;
                        $cv->job_name = $job->job_name;
                        // if(!Auth::guard('web')->guest()){
                        //     $cv->message = "Bạn đc mời phỏng vấn";        
                        // }
                        if(!Auth::guard('employers')->guest()){
                            $cv->message = "Bạn bị từ chối mời thử việc";        
                        }
                        // event(new PusherEvent(json_encode($cv)));
                        
                        $device_tokens = $notiUser['device_tokens'];
                        $notiUser->notify(new NotifyCv($cv,$device_tokens));
                        // event(new PusherEvent(json_encode($cv)));   
                    }else{
                        $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                    }
                }else if($cv->status == "interview" && $this->request->active == "2"){
                    $cv->active_work = $this->request->active;
                    if($cv->save()){
                         $user->users_cost = $user->users_cost + 10000;
                        $user->save();
                        $status = 1; 
                        $cv->action = "active";
                        $cv->user_slug = $user->slug;
                        $cv->user_name = $user->name;
                        $cv->job_slug = $job->slug;
                        $cv->job_name = $job->job_name;
                        // if(!Auth::guard('web')->guest()){
                        //     $cv->message = "Bạn đc mời phỏng vấn";        
                        // }
                        if(!Auth::guard('employers')->guest()){
                            $cv->message = "Bạn bị từ chối mời làm việc";        
                        }
                        // event(new PusherEvent(json_encode($cv)));
                        $device_tokens = $notiUser['device_tokens'];
                        $notiUser->notify(new NotifyCv($cv,$device_tokens));
                        // event(new PusherEvent(json_encode($cv)));   
                    }else{
                        $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                    }
                }else if($cv->status == "work" && $this->request->active == "2"){
                    $cv->active_complete_work = $this->request->active;
                    if($cv->save()){
                        $status = 1; 
                        $cv->action = "active";
                        $cv->user_slug = $user->slug;
                        $cv->user_name = $user->name;
                        $cv->job_slug = $job->slug;
                        $cv->job_name = $job->job_name;
                        // if(!Auth::guard('web')->guest()){
                        //     $cv->message = "Bạn đc mời phỏng vấn";        
                        // }
                        if(!Auth::guard('employers')->guest()){
                            $cv->message = "Bạn chưa hoàn thành";        
                        }
                        // event(new PusherEvent(json_encode($cv)));
                        $device_tokens = $notiUser['device_tokens'];
                        $notiUser->notify(new NotifyCv($cv,$device_tokens));
                        // event(new PusherEvent(json_encode($cv)));   
                    }else{
                        $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                    }
                }else{
                    $errors = ['code' => 203,'message'=>['other'=>'not active']];
                } 
            }
            if(!Auth::guard('web')->guest()){
                if($cv->status == "interview" && $cv->active_interview == "0"){
                    $cv->active_interview = $this->request->active;
                    if($cv->save()){
                        if($this->request->active!="1") {
                            $user->users_cost = $user->users_cost + 10000;
                            $user->save();
                        }
                        $status = 1; 
                        $cv->action = "active";
                        $cv->user_slug = $user->slug;
                        $cv->user_name = $user->name;
                        $cv->job_slug = $job->slug;
                        $cv->job_name = $job->job_name;
                        $cv->message = ($this->request->active=="1")?$user->name." đã xác nhận phỏng vấn":$user->name." đã từ chối phỏng vấn";        
                        
                        // event(new PusherEvent(json_encode($cv)));
                        $device_tokens = $notiUser['device_tokens'];
                        $notiUser->notify(new NotifyCv($cv,$device_tokens));
                        if(!empty($mulUser)){
                            $device_tokens = $mulUser['device_tokens'];
                            $mulUser->notify(new NotifyCv($cv,$device_tokens));
                        }
                    }else{
                        $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                    }
                }else if($cv->status == "trial_work" && $cv->active_interview =="1" && $cv->active_trial_work == "0"){
                    $cv->active_trial_work = $this->request->active;
                    if($cv->save()){
                        $status = 1;
                        $cv->action = "active";
                        $cv->user_slug = $user->slug;
                        $cv->user_name = $user->name;
                        $cv->job_slug = $job->slug;
                        $cv->job_name = $job->job_name;
                       
                        $cv->message = ($this->request->active=="1")?$user->name." đã xác nhận thử việc":$user->name." đã từ chối thử việc";        
                      
                        // event(new PusherEvent(json_encode($cv)));
                        $device_tokens = $notiUser['device_tokens'];
                        $notiUser->notify(new NotifyCv($cv,$device_tokens));
                        if(!empty($mulUser)){
                            $mulUser->notify(new NotifyCv($cv,$mulUser['device_tokens']));
                        }       
                    }else{
                        $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                    }
                }else if($cv->status == "work" && $cv->active_interview =="1" && $cv->active_work == "0"){
                    $cv->active_work = $this->request->active;
                    if($cv->save()){
                        if($this->request->active!="1") {
                            $user->users_cost = $user->users_cost + 10000;
                            $user->save();
                        }
                        $status = 1;
                        $cv->action = "active";
                        $cv->user_slug = $user->slug;
                        $cv->user_name = $user->name;
                        $cv->job_slug = $job->slug;
                        $cv->job_name = $job->job_name;
                       
                         $cv->message = ($this->request->active=="1")?$user->name." đã xác nhận làm việc":$user->name." đã từ chối làm việc";        
                        // event(new PusherEvent(json_encode($cv)));
                        $device_tokens = $notiUser['device_tokens'];
                        $notiUser->notify(new NotifyCv($cv,$device_tokens));
                        if(!empty($mulUser)){
                            $mulUser->notify(new NotifyCv($cv,$mulUser['device_tokens']));
                        } 
                    }else{
                        $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                    }
                }else if($cv->status == "complete_work" && $cv->active_work =="1" && $cv->active_complete_work == "0" && $this->request->active == "1"){
                    $cv->active_complete_work = $this->request->active;
                    if($cv->save()){
                        $status = 1;
                        $cv->action = "active";
                        $cv->user_slug = $user->slug;
                        $cv->user_name = $user->name;
                        $cv->job_slug = $job->slug;
                        $cv->job_name = $job->job_name;
                       
                        $cv->message = $user->name." đã xác nhận hoàn thành";        
                      
                        // event(new PusherEvent(json_encode($cv)));
                        $device_tokens = $notiUser['device_tokens'];
                        $notiUser->notify(new NotifyCv($cv,$device_tokens));
                        if(!empty($mulUser)){
                            $mulUser->notify(new NotifyCv($cv,$mulUser['device_tokens']));
                        }
                    }else{
                        $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                    }
                }else{
                    $errors = ['code' => 203,'message'=>['other'=>'not active']];
                }
            }
        }else{
            $cv = (object)[];
            $errors = ['code' => 400,'message'=>['other'=>'cv not found']];
        }

        return response()->json(compact('status','errors','cv'));
    }

    public function deleteCV($id){
        $status = 0;
        $errors = (object)[];
        $cv = "";
        if(!Auth::guard('employers')->guest()){
            $cv =  CV::where('id','=', $id)->whereHas('job', function ($query) {
                $query->where('employer_id', '=', Auth::guard('employers')->user()->id)->orWhere('employer_view', Auth::guard('employers')->user()->id);
            })->first();
        }else if(!Auth::guard('web')->guest()){
            $cv =  CV::where('id','=', $id)->where('user_id','=',Auth::guard('web')->user()->id)->first();
        }

        if(!empty($cv)){
            if($cv->delete()){
                $cv->action = "delete";
                event(new PusherEvent(json_encode($cv)));       
                $status = 1;
            }else{
                $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
            }
        }else{
            $errors = ['code' => 400,'message'=>['other'=>'cv not found']];
        }
        return response()->json(compact('status','errors'));
    }
}