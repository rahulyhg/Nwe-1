<?php

namespace App\Http\Controllers;

use App\User;
use App\CV;
use App\Repo\JobsRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Notifications\NotifyCv;

class ApiCvsController extends Controller
{
	public function __construct( Request $request, JobsRepo $jobsRepo)
    {
        $this->request = $request;
        $this->jobsRepo = $jobsRepo;
    }

    public function getCvs($id = null){
        $status = 0;
        $errors = (object)[];
        $cvs = (object)[];

        if($this->request['user']['rol'] =="user"){
            $cvs = CV::where('user_id','=', $this->request['user']['sub'])->with('job')->get();
            $status = 1;
            return response()->json(compact('status','errors','cvs'));
        }
        if($this->request['user']['rol'] =="employer"){
            $cvs =  CV::where('job_id','=', $id)->whereHas('job', function ($query) {
                $query->where('employer_id', '=', $this->request['user']['sub'])->orWhere('employer_view', $this->request['user']['sub']);
            })->get();
            $status = 1;
            return response()->json(compact('status','errors','cvs'));
        }

    }

    public function createCV(){
        $status = 0;
        $errors = 0;
        $errors = (object)[];
        $cv = (object)[];
        if($this->request['user']['rol'] !="user"){
            $errors = ['code' => 201,'message'=>['other'=>'access denied']];
            return response()->json(compact('status','errors','cv'));
        }

        $rules = array(
            // 'email' => 'required|email',
            'job_id' => 'required',
        );

        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $status = 400;
            $errors = ['message'=> $errorsMessage];
        } else {
            $job = $this->jobsRepo->getJobById($this->request->job_id);

            if(!empty($job)){

                $user = User::where('id','=',$this->request['user']['sub'])->first();
                if(!empty($user)) {

                    $cv = CV::where('job_id','=', $job->id)->where('user_id','=',$user->id)->first();
                    if(empty($cv)) {
                        $cv = new CV();
                        $cv->job_id = $job->id;
                        $cv->user_id = $user->id;
                        $cv->status = 'pending';
                        if($cv->save()){
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
                        }else{
                            $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                        }
                        // $linkusers = makeurl(config('app.api_url'), '/job/'.$job->id.'/users');
                        // Mail::send('emails.put_cv',['linkusers'=> $linkusers,'user' => $user], function($m) use ($user) {
                        //     $m->to('khanhln.c1003j@gmail.com')->subject('Reset password');
                        // });
                    }else{
                        $status = 400;
                        $cv = (object)[];
                        $errors = ['message'=>'Bạn đã nộp hồ sơ'];
                    }
                }else{
                    $status = 401;
                    $errors = ['message'=>'user not found'];
                }
            }else{
                $status = 402;
                $errors = ['message'=>'job not found'];
            }
        }

        return response()->json(compact('status','errors'));
    }

    public function updateStatus($id){
    	$status = 0;
        $errors = (object)[];
        $cv = (object)[];

        if($this->request['user']['rol'] !="employer"){
            $errors = ['code' => 201,'message'=>['other'=>'access denied']];
            return response()->json(compact('status','errors','cv'));
        }

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
                $query->where('employer_id', '=', $this->request['user']['sub'])->orWhere('employer_view', $this->request['user']['sub']);
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
                    $device_tokens = $user['device_tokens'];
                    $user->notify(new NotifyCv($cv,$device_tokens));	
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
                    $device_tokens = $user['device_tokens'];
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
                    $device_tokens = $user['device_tokens'];
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
                    $device_tokens = $user['device_tokens'];
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

        // if($this->request['user']['rol'] !="user"){
        //     $errors = ['code' => 201,'message'=>['other'=>'access denied']];
        //     return response()->json(compact('status','errors','cv'));
        // }

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

        $cv =  CV::where('id','=', $id)->where('user_id','=',$this->request['user']['sub'])->first();
        if($this->request['user']['rol'] =="user"){
            $cv =  CV::where('id','=', $id)->where('user_id','=', $this->request['user']['sub'])->with('user')->with('job')->first();
            $notiUser = $cv->job['employer'];
            if(!empty($cv->job['employerView'])){
                $mulUser = $cv->job['employerView'];
            }
        }
        if($this->request['user']['rol'] =="employer"){
            $cv =  CV::where('id','=', $id)->whereHas('job', function ($query) {
                $query->where('employer_id', '=', $this->request['user']['sub'])->orWhere('employer_view', $this->request['user']['sub']);
            })->with('user')->with('job')->first();
            $notiUser = $cv->user;
        }

        if(!empty($cv)){
            $user = $cv->user;
            $job = $cv->job;
            if($this->request['user']['rol'] =="employer"){
                if($cv->status == "pending" && $this->request->active == "2"){
                    $cv->active_interview = $this->request->active;
                    if($cv->save()){
                        $status = 1; 
                        $cv->action = "active";
                        $cv->user_slug = $user->slug;
                        $cv->user_name = $user->name;
                        $cv->job_slug = $job->slug;
                        $cv->job_name = $job->job_name;
                        $cv->message = "Bạn bị từ chối phỏng vấn";        
                        
                        $device_tokens = $notiUser->device_tokens;
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
                        $cv->message = "Bạn bị từ chối mời thử việc";        
                        
                        $device_tokens = $notiUser['device_tokens'];
                        $notiUser->notify(new NotifyCv($cv,$device_tokens));
                        // event(new PusherEvent(json_encode($cv)));   
                    }else{
                        $errors = ['code' => 500,'message'=>['other'=>'not saved to the database']];
                    }
                }else if($cv->status == "interview" && $this->request->active == "2"){
                    $cv->active_work = $this->request->active;
                    if($cv->save()){
                        $status = 1; 
                        $cv->action = "active";
                        $cv->user_slug = $user->slug;
                        $cv->user_name = $user->name;
                        $cv->job_slug = $job->slug;
                        $cv->job_name = $job->job_name;
                        $cv->message = "Bạn bị từ chối mời làm việc";        
                     
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
                        $cv->message = "Bạn chưa hoàn thành";        
                        
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
        	if($this->request['user']['rol'] =="user"){
                if($cv->status == "interview" && $cv->active_interview == "0"){
                    $cv->active_interview = $this->request->active;
                    if($cv->save()){
                        $status = 1; 
                        $cv->action = "active";
                        $cv->user_slug = $user->slug;
                        $cv->user_name = $user->name;
                        $cv->job_slug = $job->slug;
                        $cv->job_name = $job->job_name;
                        $cv->message = $user->name.($this->request->active=="1")?" đã xác nhận phỏng vấn":" đã từ chối phỏng vấn";        
                       
                        // event(new PusherEvent(json_encode($cv)));
                        $device_tokens = $notiUser['device_tokens'];
                        $notiUser->notify(new NotifyCv($cv,$device_tokens));
                        if(!empty($mulUser)){
                            $mulUser->notify(new NotifyCv($cv,$mulUser['device_tokens']));
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
                        $cv->message = $user->name.($this->request->active=="1")?" đã xác nhận thử việc":" đã từ chối thử việc";        
                        
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
                        $status = 1;
                        $cv->action = "active";
                        $cv->user_slug = $user->slug;
                        $cv->user_name = $user->name;
                        $cv->job_slug = $job->slug;
                        $cv->job_name = $job->job_name;
                        $cv->message = $user->name.($this->request->active=="1")?" đã xác nhận làm việc":" đã từ chối làm việc";        
                        
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
        if($this->request['user']['rol'] !="employer"){
            $cv =  CV::where('id','=', $id)->whereHas('job', function ($query) {
                $query->where('employer_id', '=', $this->request['user']['sub']);
            })->first();
        }else if($this->request['user']['rol'] !="user"){
           	$cv =  CV::where('id','=', $id)->where('user_id','=',$this->request['user']['sub'])->first();
        }

        if(!empty($cv)){
        	if($cv->delete()){
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