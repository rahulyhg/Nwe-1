<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Employer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Repo\EmployerRepo;
use App\Repo\UserRepo;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EmployerRepo $employerRepo, UserRepo $userRepo)
    {
        $this->middleware('guest');
        $this->employerRepo = $employerRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function getRegisterEmployer(Request $request){
        if(!empty($request->mobile)){
            if($request->session()->get('reg_employer_mobile') !== $request->mobile){
                return 'access denied';
            }
            $mobile = $request->mobile;
            return view('user.employer_register')->with(compact('mobile'));
        }
        if(!empty($request->email)){
            if($request->session()->get('reg_employer_mobile') !== $request->email){
                return 'access denied';
            }
            $email = $request->email;
            return view('user.employer_register')->with(compact('email'));
        }

        return view('user.employer_register');
    }

    public function getRegisterUser(Request $request){
        if(!empty($request->mobile)){
            if($request->session()->get('reg_user_mobile') !== $request->mobile){
                return 'access denied';
            }
            $mobile = $request->mobile;
            return view('user.user_register')->with(compact('mobile'));
        }
        if(!empty($request->email)){
            if($request->session()->get('reg_user_mobile') !== $request->email){
                return 'access denied';
            }
            $email = $request->email;
            return view('user.user_register')->with(compact('email'));
        }

        return view('user.user_register');
    }

    public function registerEmployer(Request $request){

        $rules = array(
            // 'first_name' => 'required|min:3|max:32',
            // 'last_name' =>  'required|min:3|max:32',
            'avatar' => 'mimes:jpeg,jpg,png|max:2000',
            "name"=> "required|max:50",
            'mobile_number' => 'required|max:50|unique:employers,mobile_number',
            'email' => 'required|email|max:255|unique:employers,email',
            "password" => "required|confirmed|min:6|max:255",
            'position' => 'required|max:255',
            'company_name' => 'required|max:255',
            'company_mobile' => 'required|max:255',
            'company_address' => 'required|max:255',
            'company_activity' => 'required|max:255'
        );
        // Create a new validator instance.
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            
            $result = $this->employerRepo->create($request);
            

            if($result){ // success
                $employer = Employer::where('mobile_number','=', $request->mobile_number)->first();
                if(!empty($employer)){
                    Auth::guard('employers')->loginUsingId($employer->id);
                    if(!Auth::guard('employers')->guest()){
                        return redirect('/');
                    }else{
                        return redirect('/');
                    }
                }else{
                    return redirect('/');
                }
            }else{ // failed
                return back()->withErrors(['<script>$(document).ready(function(){alertify.error("Đăng ký thất bại");})</script>']);
            }
        }
    }

    public function registerUser(Request $request){

        $rules = array(
            // 'first_name' => 'required|min:3|max:32',
            // 'last_name' =>  'required|min:3|max:32',
            'avatar' => 'mimes:jpeg,jpg,png|max:2000',
            "name"=> "required|max:50",
            'mobile_number' => 'required|max:50|unique:users,mobile_number',
            'email' => 'required|email|max:255|unique:users,email',
            "password" => "required|confirmed|min:6|max:255",
        );
        // Create a new validator instance.
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            
            $result = $this->userRepo->create($request);
            

            if($result){ // success
                $user = User::where('mobile_number','=', $request->mobile_number)->first();
                if(!empty($user)){
                    Auth::guard('web')->loginUsingId($user->id);
                    if(!Auth::guard('web')->guest()){
                        return redirect('/');
                    }else{
                        return redirect('/');
                    }
                }else{
                    return redirect('/');
                }
            }else{ // failed
                return back()->withErrors(['<script>$(document).ready(function(){alertify.error("Đăng ký thất bại");})</script>']);
            }
        }
    }
}
