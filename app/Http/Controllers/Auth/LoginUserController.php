<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Employer;
use App\CvTab;
use AccountKit;
use App\WorkForm;
use App\WorkType;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client as GuzzleHttpClient;
use Socialite;

class LoginUserController extends Controller
{
    //

    public function __construct( User $user, Employer $employer, Request $request )
    {
        $this->client = new GuzzleHttpClient();
        $this->user = $user;
        $this->employer = $employer;
        $this->request = $request;
    }

	public function getLogin(){
		if (!Auth::guest() ||  !Auth::guard('web')->guest()) {
            return redirect('/');
        }
        return view('user.login');
	}

	public function getLogout(){
        if(Auth::guard('employers')->user()){
            Auth::guard('employers')->logout();
        }
        if(Auth::guard('web')->user()){
            Auth::guard('web')->logout();
        }
        return redirect('/');
    }

    public function otpLogin(Request $request){

        if (!Auth::guest() ||  !Auth::guard('web')->guest()) {
            return redirect('/');
        }
        // $otpLogin = AccountKit::accountKitData($request->code);
        $client = new GuzzleHttpClient();

        $url = 'https://graph.accountkit.com/v1.2/access_token?grant_type=authorization_code&code='.$request->code.'&access_token=AA|'.config('accountKit.appId').'|'.config('accountKit.appSecret');

        $apiRequest = $this->client->request('GET',$url);

        $body = json_decode($apiRequest->getBody());
        $appsecret_proof= hash_hmac('sha256', $body->access_token, config('accountKit.appSecret')); 
        $_request = $this->client->request('GET','https://graph.accountkit.com/v1.2/me?access_token='.$body->access_token.'&appsecret_proof='.$appsecret_proof);
        $data = json_decode($_request->getBody());
        
        if($request->role == "employer"){
            $auth = Auth::guard('employers');
            $model = $this->employer;
            $route = 'client.employer.register';
            $session_key = 'reg_employer_mobile';
        }else{
            $auth = Auth::guard('web');
            $model = $this->user;
             $route = 'client.user.register';
             $session_key = 'reg_user_mobile';
        }
        if(!empty($data->email)){
            $user = $model->where('email','=',$data->email->address)->first();
            if(!empty($user)){
                $auth->loginUsingId($user->id,true);
                if (!$auth->guest()) {
                    return redirect('/');
                }
            }else{
                $request->session()->put($session_key,$data->email->address);

                return redirect()->route($route,['email'=> $data->email->address]);
            }
        }
        if(!empty($data->phone)){
            $user = $model->where('mobile_number','=',"0".$data->phone->national_number)->first();
            if(!empty($user)){
                $auth->loginUsingId($user->id,true);
                if (!$auth->guest()) {
                    return redirect('/');
                }
            }else{
                $request->session()->put($session_key,"0".$data->phone->national_number);

                return redirect()->route($route,['mobile'=>"0".$data->phone->national_number]);
            }
        }
        
    }

    public function redirectToProvider($provider)
    {
        $this->request->session()->put('provider_to', 'user');
        return Socialite::driver($provider)->redirect();
    }

    public function redirectToProviderEmployer($provider){
        $this->request->session()->put('provider_to', 'employer');
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that 
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        if($user->email){
            if($this->request->session()->get('provider_to') == "employer"){
                $auth = Auth::guard('employers');
                $model = $this->employer;
                $route = 'client.employer.register';
                $session_key = 'reg_employer_mobile';
            }else{
                $auth = Auth::guard('web');
                $model = $this->user;
                 $route = 'client.user.register';
                 $session_key = 'reg_user_mobile';
            }
            $_user = $model->where('email','=',$user->email)->first();
            if(!empty($_user)){
                $auth->loginUsingId($_user->id,true);
                if (!$auth->guest()) {
                    return redirect('/');
                }
            }else{
                $this->request->session()->put($session_key,$user->email);
                return redirect()->route($route,['email'=> $user->email]);
            }
        }
       
    }

    public function putLogin(Request $request){
        $status = 0;
        $errors = [];
        // validate the info, create rules for the inputs
        $rules = array(
            'email' => 'required',
            'password' => 'required|min:6',
            'role' => 'required'
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $status = 0;
            $errors = ["code" => 200,"message"=> $validator->errors()];
        } else {
            if($request->role == "employer"){
                $auth = Auth::guard('employers');
            }else{
                $auth = Auth::guard('web');
            }
            $credentials = $request->only('email', 'password');

            
            if ($auth->attempt($credentials, $request->has('remember'))) {
                $auth->user()->role = $request->role;
                $status = 1;
            } else {
                $errors = ['code' => 101,'message'=>'Tài khoản hoặc mật khẩu không đúng!'];
            }
        }
        return response()->json(compact("status","errors"));
    }
   
    public function postLogin(Request $request){

        // validate the info, create rules for the inputs
        $rules = array(
            'email' => 'required',
            'password' => 'required|min:6',
            'role' => 'required'
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/user/login')->withErrors($validator);
        } else {
            if($request->role == "employer"){
                $auth = Auth::guard('employers');
            }else{
                $auth = Auth::guard('web');
            }
            $credentials = $request->only('email', 'password');

            
            if ($auth->attempt($credentials, $request->has('remember'))) {
                $auth->user()->role = $request->role;
                return redirect('/');
            } else {
                return back()->withErrors(['Tài khoản hoặc mật khẩu không đúng!']);
                $errors = ['code' => 101,'message'=>'username, email or password is incorrect'];
            }
          


        }
        
    }

    public function getProfile(){
    	$user = Auth::guard('web')->user();
        $role = 'user';
        if(Auth::guard('employers')->user()){
            $user = Auth::guard('employers')->user();
            $role = 'employer';
        }
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
        if(Auth::guard('employers')->user()){
            return view('user.employer_profile')->with(compact('user','tabs'));
        }
        $work_forms = WorkForm::all();
        $work_types = WorkType::all();
    	return view('user.profile')->with(compact('user','tabs','work_forms','work_types'));
    }

	public function getChangePassword(){
		return view('user.change_password');
	}

	public function postChangePassword(Request $request){

		 $rules = array(
           		'current_password' => 'required',
            	'password' => 'required|string|min:6|max:50|confirmed',
        	);

	        $validator = Validator::make($request->all(), $rules);

	        if ($validator->fails()) {
	            return redirect()->back()->withErrors($validator);
	        } else {

	        	if(!Auth::guard('employers')->guest()){
					$auth = Auth::guard('employers');
			    }
			    if(!Auth::guard('web')->guest()){
			    	$auth = Auth::guard('web');
			    }

			    if (!(Hash::check($request->get('current_password'), $auth->user()->password))) {
		            // The passwords matches
		            return redirect()->back()->with("error","Mật khẩu cũ không đúng!");
		        }
		 
		        if(strcmp($request->get('current_password'), $request->get('password')) == 0){
		            //Current password and new password are same
		            return redirect()->back()->with("error","Mật khẩu mới không được trùng với mật khẩu cũ!");
		        }

		        $user = $auth->user();
				$user->password = Hash::make($request->get('password'));
				$user->save();
                $request->session()->flash('alert-success', 'Success');
                $request->session()->flash('mg-success', 'Cập nhật thành công');
				return redirect()->back();

	        }
		
	}
}
