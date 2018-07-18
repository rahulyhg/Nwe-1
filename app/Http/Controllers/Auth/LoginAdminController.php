<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Employer;
use Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginAdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function getLoginAdmin(){
        if (!Auth::guest()) {
            return redirect('/dashboard');
        }
        return view('admin.login');
    }
    public function getLogoutAdmin(){
        Auth::logout();
        return redirect('login');
    }

    public function postLoginAdmin(Request $request){

        // validate the info, create rules for the inputs
        $rules = array(
            'email' => 'required|email',
            'password' => 'required|min:6'
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('login')->withErrors($validator);
        } else {
            $auth = Auth::guard('employers');
            $credentials = $request->only('email', 'password');

            //if($request->mobile_number =="123456788"){
                if ($auth->attempt($credentials, $request->has('remember'))) {
                    return redirect('dashboard');
                } else {
                    return redirect('/login')->withErrors(['email or password is incorrect!']);
                }
            //}


        }
        //return response()->json(compact('status','errors','user','token'));
    }

    public function getForgotPassword(){
        if (!Auth::guest()) {
            return redirect('/dashboard');
        }
        return view('admin.forgot_password');
    }

    public function postForgotPassword(Request $request){
         $session = $request->session()->get('key');
        if(empty( $session )){
            $session = $request->session()->put('key',(time() - 60));
        }

        $rules = array(
            'email' => 'required|email'
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/forgot-password')->withErrors($validator);
        } else {
            $employer = Employer::where('email','=',$request->email)->first();
            if(!empty($employer)){
                if( (time() - 15) < $session ){
                    $time = $session - (time() - 15);
                    return redirect('/forgot-password')->withErrors(['Spam!']);
                }

                $password = $this->generateRandomString();
                $employer->password = Hash::make($password);

                if($employer->save()) {
                    // Send activation link
                    Mail::send('emails.reset_password',['password'=> $password], function($m) use ($employer) {
                        $m->to($employer->email)->subject('Reset password');
                    });
                    $request->session()->put('key',time());  
                    return redirect('/forgot-password')->with('success','Success!');
                }else{
                    return redirect('/forgot-password')->withErrors(['Can not reset password!']);
                }
            }else{
                return redirect('/forgot-password')->withErrors(['Email chưa được đăng ký!']);
            }
        }
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
