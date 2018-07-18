<?php

namespace App\Http\Controllers;

use App\User;
use App\Employer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Notification;

class NotificationController extends Controller
{
    //
    public function get()
    {
    	if(!Auth::guard('employers')->guest()){
    		return Auth::guard('employers')->user()->unreadNotifications;
    	}else if(!Auth::guard('web')->guest()){
    		return Auth::guard('web')->user()->unreadNotifications;
    	}
    }

    public function read(Request $request){
    	
    	if(!Auth::guard('employers')->guest()){
    		Auth::guard('employers')->user()->unreadNotifications()->find($request->id)->markAsRead();
            //$notification->delete();
    		return 'employers';
    	}else if(!Auth::guard('web')->guest()){
    		Auth::guard('web')->user()->unreadNotifications()->find($request->id)->markAsRead();
    		return 'users';
    	}
    	return 1;
    }

    public function apiGet(Request $request)
    {
        if($request['user']['rol'] =="employer"){
            $employer = Employer::find($request['user']['sub']);
            if(!empty($employer)){
                return $employer->unreadNotifications;
            }
            
        }else if($request['user']['rol'] =="user"){
            $user = User::find($request['user']['sub']);
            if(!empty($user)){
                return $user->unreadNotifications;
            }
        }
    }

    public function apiRead(Request $request){
        
        if($request['user']['rol'] =="employer"){
            $employer = Employer::find($request['user']['sub']);
            if(!empty($employer)){
                $employer->unreadNotifications()->find($request->id)->markAsRead();
                return 'employers';
            }
            
        }else if($request['user']['rol'] =="user"){
            $user = User::find($request['user']['sub']);
            if(!empty($user)){
                $user->unreadNotifications()->find($request->id)->markAsRead();
                return 'users';
            }
        }
        return 'error';
    }
}
