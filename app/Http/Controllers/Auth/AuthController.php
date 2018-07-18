<?php

namespace App\Http\Controllers\Auth;

use App\Employer;
use App\Http\Controllers\Controller;
use App\Repo\EmployerRepo;
use App\User;
use App\WorkForm;
use App\WorkType;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Repo\UserRepo;
use App\Mail\resetPassword;

class AuthController extends Controller
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
    //protected $redirectTo = '/home';

    protected function createToken($user,$role)
    {
        $payload = [
            'sub' => $user->id,
            'rol' => $role,
            'iat' => time(),
            'exp' => time() + (2 * 7 * 24 * 60 * 60)
        ];
        return JWT::encode($payload, config('app.token_secret'));
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->client = new Client();
        $this->user = $user;
        //$this->middleware('guest')->except('logout');
    }

    public function postValidateEmployer(Request $request, UserRepo $userRepo){
        $status = 0;
        $errors = 0;

        $rules = array(
            // 'first_name' => 'required|min:3|max:32',
            // 'last_name' =>  'required|min:3|max:32',
            'role' => 'required',
            'email' => 'required|email|unique:employers,email',
            'mobile_number' => 'required|unique:employers,mobile_number',
            'password' => 'required|min:6',
            'position' => 'required'
        );

        // Create a new validator instance.
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $status = 400;
            $errors = ['message'=> $errorsMessage];
        } else {
            $status = 1;
        }

        return response()->json(compact('status','errors'));
    }

    public function postRegister(Request $request, UserRepo $userRepo, EmployerRepo $employerRepo){
        $status = 0;
        $errors = (object)[];
        if($request->role =="employer"){
            $table = 'employers';
            $rules = array(
                // 'first_name' => 'required|min:3|max:32',
                // 'last_name' =>  'required|min:3|max:32',
                "role"=> "required|in:employer,user",
                'avatar' => 'mimes:jpeg,jpg,png|max:1000',
                "name"=> "required|max:50",
                'mobile_number' => 'required|max:50|unique:'.$table.',mobile_number',
                'email' => 'required|email|max:255|unique:'.$table.',email',
                "password" => "required|min:6|max:255",
                'position' => 'required|max:255',
                'company_name' => 'required|max:255',
                'company_mobile' => 'required|max:255',
                'company_address' => 'required|max:255',
                'company_activity' => 'required|max:255'
            );
        }else{
            $table = 'users';
            $rules = array(
                // 'first_name' => 'required|min:3|max:32',
                // 'last_name' =>  'required|min:3|max:32',
                "role"=> "required|in:employer,user",
                'avatar' => 'mimes:jpeg,jpg,png|max:1000',
                "name"=> "required|max:50",
                'mobile_number' => 'required|max:50|unique:'.$table.',mobile_number',
                'email' => 'required|email|max:255|unique:'.$table.',email',
                "password" => "required|min:6|max:255"

            );
        }

        // Create a new validator instance.
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $status = 400;
            $errors = ['message'=> $errorsMessage];
        } else {
            if($request->role =="employer"){
                $result = $employerRepo->create($request);
            }else{
                $result = $userRepo->create($request);
            }

            if($result){ // success
                $status = 1;
            }else{ // failed
                $status = 500;
                $errors = ['message'=>["other"=>'not saved to the database']];
            }
        }

        return response()->json(compact('status','errors'));
    }

    public function postLogin(Request $request){

        $status = 0;
        $errors = (object)[];
        $user = (object)[];
        $token = "";

        // validate the info, create rules for the inputs
        $rules = array(
            "role"=> "required|in:employer,user",
            "email" => "required|email|max:255",
            "password" => "required|min:6|max:255"
            
        );

        // $message = array(
        //     "email.required" => "Email không được để trống.",
        //     "email.email" => "Email phải là địa chỉ email hợp lệ.",
        //     "email.max" => "Email không được lớn hơn 255 ký tự.",
        //     "password.required" => "Mật khẩu không được để trống.",
        //     "password.min" => "Mật khẩu không được nhỏ hơn 6 ký tự.",
        //     "password.max" => "Mật khẩu không được lớn hơn 255 ký tự.",
        //     "role.required" => "Vai trò không được để trống.",
        //     "role.in" => "Vai trò đã chọn không hợp lệ." 
        // );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $status = 0;
            $errors = ["code" => 200,"message"=> $errorsMessage];
        } else {
            $credentials = $request->only("email", "password");
            if($request->role == "employer"){
                $auth = Auth::guard("employers");
            }else{
                $auth = Auth::guard("web");
            }

            if ($auth->attempt($credentials, $request->has("remember"))) {
                $status = 1;
                $user = $auth->user();
                if(!empty($request->fcm_token)){
                    $user->device_tokens = $request->fcm_token;
                    $user->save();
                }
                if(!empty($user->company_description)){
                        $user->company_description = json_decode($user->company_description);
                        //$user->company_description = json_decode($user->company_description);
                    }
                $token = $this->createToken($auth->user(),$request->role);
            } else {
                $status = 0;
                $errors = ["code" => 201,"message"=>["other"=>"Email hoặc mật khẩu không chính xác."]];
            }
        }
        return response()->json(compact("status","errors","user","token"));
    }

    public function postLoginProvider(Request $request)
    {
        // dd([$request->providerId,$request->accessToken]);
        $status = 0;
        $errors = (object)[];
        $user = (object)[];
        $token = "";
        $key_first = "";
        $key_last = "";

        // validate the info, create rules for the inputs
          $rules = array(
                'role' => 'required',
              'providerId' => 'required',
              'accessToken' => 'required'
          );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $status = 0;
            $errors = ['code' => 200,'message'=> $errorsMessage];
        } else {
            $client = new Client();
            $fields = 'id,email,first_name,last_name,link,name';
            if($request->providerId == "facebook.com"){

                $profileResponse = $client->request('GET', 'https://graph.facebook.com/v2.5/me', [
                    'query' => [
                        'access_token' => $request->accessToken,
                        'fields' => $fields
                    ]
                ]);
                $key_first = 'first_name';
                $key_last = 'last_name';
            }
            if($request->providerId == "google.com"){
                $profileResponse = $client->request('GET', 'https://www.googleapis.com/plus/v1/people/me/openIdConnect', [
                    'headers' => array('Authorization' => 'Bearer ' . $request->accessToken)
                ]);
                $key_first = 'given_name';
                $key_last = 'family_name';
            }



            $profile = json_decode($profileResponse->getBody(), true);
            if($request->role == "employer"){
                $user = Employer::where('email', '=', $profile['email']);
            }else{
                $user = User::where('email', '=', $profile['email']);
            }
            
            if ($user->first())
            {
                $token = $this->createToken($user);
                $status = 1;
                return response()->json(compact('status','errors','user','token'));
                // return response()->json(['status'=> 1,'errors'=>0,'user'=> $user,'token' => $this->createToken($user)]);
            }else{
                $errors = ['code' => 300,'message'=>['other'=>'Email này chưa được đăng kí']];
                return response()->json(compact('status','errors'));
                // $user = new User;
                // $user->email = $profile['email'];
                // $user->name = $profile[$key_first].' '.$profile[$key_last];
                // $user->active = 1;
                // $user->password = "123456789";
                // $user->save();
            }

            $token = $this->createToken($user);
            $status = 1;
        }


        return response()->json(compact('status','errors','user','token'));


//        return response()->json(['user'=> $user,'token' => $this->createToken($user)]);
    }

    public function otpLogin(Request $request){
        $status = 0;
        $errors = (object)[];
        // $otpLogin = AccountKit::accountKitData($request->code);
        $client = new Client();

        $url = 'https://graph.accountkit.com/v1.2/access_token?grant_type=authorization_code&code='.$request->code.'&access_token=AA|'.config('accountKit.appId').'|'.config('accountKit.appSecret');

        $apiRequest = $this->client->request('GET',$url);

        $body = json_decode($apiRequest->getBody());
        $appsecret_proof= hash_hmac('sha256', $body->access_token, config('accountKit.appSecret')); 
        $_request = $this->client->request('GET','https://graph.accountkit.com/v1.2/me?access_token='.$body->access_token.'&appsecret_proof='.$appsecret_proof);
        $data = json_decode($_request->getBody());
        
        $auth = Auth::guard('web');
        $model = $this->user;
           

        if(!empty($data->phone)){
            $user = $model->where('mobile_number','=',"0".$data->phone->national_number)->first();
            if(!empty($user)){
                $status = 1;
                $token = $this->createToken($user);
                return response()->json(compact('status','errors','user','token'));
            }else{
                $errors = ['code' => 300,'message'=>['other'=>'Số di động này chưa được đăng kí']];
                return response()->json(compact('status','errors'));
            }
        }
        
         return response()->json(compact('status','errors','user','token'));
    }

    public function getProfile(Request $request,  UserRepo $userRepo, EmployerRepo $employerRepo){

        if($request->role =="employer"){
            $user = $employerRepo->getEmployerById($request['user']['sub']);
        }else{
            $user = $userRepo->getUserById($request['user']['sub']);
        }

        return $user;
    }
    public function updateProfile(Request $request, UserRepo $userRepo, EmployerRepo $employerRepo){
        $status = 0;
        $errors = (object)[];
        $user = (object)[];
        if($request['user']['rol'] =="employer"){
            $table = 'employers';
            $rules = array(
                'avatar' => 'mimes:jpeg,jpg,png|max:1000',
                'name' =>  'required|min:3|max:50',
                'email' => 'required|email|unique:'.$table.',email,'.$request['user']['sub'],
                'mobile_number' => 'required|unique:'.$table.',mobile_number,'.$request['user']['sub'],
                'position' => 'max:255',
            );
        }else{
            $table = 'users';
            $rules = array(
                'avatar' => 'mimes:jpeg,jpg,png|max:1000',
                'name' =>  'required|min:3|max:50',
                'email' => 'required|email|unique:'.$table.',email,'.$request['user']['sub'],
                'mobile_number' => 'required|unique:'.$table.',mobile_number,'.$request['user']['sub'],
            );
        }
        if(!empty($request->password) && $request->password != "undefined"){
            $rules['password'] = "min:6|max:255";
        }
        // Create a new validator instance.
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $errors = ['message'=> $errorsMessage];
        } else {
            if($request->role =="employer"){
                $user = $employerRepo->getEmployerById($request['user']['sub']);
                $result = $employerRepo->update($user,$request);
            }else{
                $user = $userRepo->getUserById($request['user']['sub']);
                $result = $userRepo->update($user,$request);
            }

            if($result){ // success
                $status = 1;
            }else{ // failed
                $errors = ['message'=>['other'=>'not saved to the database']];
            }
        }

        return response()->json(compact('status','errors','user'));
    }

    public function updateInfo(Request $request, UserRepo $userRepo, EmployerRepo $employerRepo){
        $status = 0;
        $errors = (object)[];
        $user = (object)[];
        if($request['user']['rol'] =="employer"){
            $table = 'employers';
            $rules = array();
            if(!empty($request->company_name) && $request->company_name != "undefined"){
                $rules['company_name'] = 'required|max:255|unique:'.$table.',company_name,'.$request['user']['sub'];
            }
            if(!empty($request->company_mobile) && $request->company_mobile != "undefined"){
                $rules['company_mobile'] = 'max:255';
            }
            if(!empty($request->company_address) && $request->company_address != "undefined"){
                $rules['company_address'] = 'max:255';
            }
            if(!empty($request->company_activity) && $request->company_activity != "undefined"){
                $rules['company_activity'] = 'max:255';
            }
            if(!empty($request->company_employees) && $request->company_employees != "undefined"){
                $rules['company_employees'] = 'max:255';
            }
            if(!empty($request->company_branches) && $request->company_branches != "undefined"){
                $rules['company_branches'] = 'max:255';
            }
            if(!empty($request->galleries) && $request->galleries != "undefined"){
                $rules['galleries.*'] = 'required|mimes:jpeg,jpg,png|max:2000';
            }
        }else{
            $table = 'users';
            $rules = array(
                'address'=> 'max:255',
                'status' => 'in:0,1',
                'gender' => 'in:1,2',
                'birthday'=> 'max:255',
                'work_type'=> 'max:255',
                'work_form'=> 'max:255',
                'work_address'=> 'max:255',
                'work_wage'=> 'max:255',
                'work_position'=> 'max:255'
            );
        }
        // Create a new validator instance.
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $errors = ['message'=> $errorsMessage];
        } else {
            if($request['user']['rol'] =="employer"){
                $user = $employerRepo->getEmployerById($request['user']['sub']);
                $result = $employerRepo->updateInfo($user,$request);
            }else{
                $user = $userRepo->getUserById($request['user']['sub']);
                $result = $userRepo->updateInfo($user,$request);
            }

            if($result){ // success
                $status = 1;
            }else{ // failed
                $errors = ['message'=>['other'=>'not saved to the database']];
            }
        }

        return response()->json(compact('status','errors','user'));
    }

    public function postForgotPassword(Request $request){
        $status = 0;
        $errors = (object)[];

        // validate the info, create rules for the inputs
        $rules = array(
            "role"=> "required|in:employer,user",
            'email' => 'required|email|max:255',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $errors = ['code' => 200,'message'=> $errorsMessage];
        } else {
            if($request->role =="employer"){
                $user = Employer::where('email', $request->input('email'))->first();
            }else{
                $user = User::where('email', $request->input('email'))->first();
            }

            if(is_null($user)) {
                $errors = ['code' => 400,'message'=>['other'=>'user not found']];
            }else{
                $password = $this->generateRandomString();
                $user->password = Hash::make($password);

                if($user->save()) {

                    Mail::send('emails.reset_password',['password'=> $password], function($m) use ($user) {
                        $m->to($user->email)->subject('Reset password');
                    });

                    $status = 1;
                }else{
                    $errors = ['code' => 500,'message'=>['other'=>'Can not save database']];
                }
            }
        }

        return response()->json(compact('status','errors'));
    }

    public function validateKeyForgotPasswword(Request $request){
        $status = 0;
        $errors = 0;

        if(!empty($request->role) && !empty($request->key)){
            if($request->role =="employer"){
                $user = Employer::where('hash_forgot_password', $request->key)->first();
            }else{
                $user = User::where('hash_forgot_password', $request->key)->first();
            }

            if(!empty($user)){
                $status = 1;
            }else{
                $errors = ['code' => 400,'message'=>'user not found'];
            }
        }else{
            $errors = ['code' => 401,'message'=>'key not found'];
        }

        return response()->json(compact('status','errors'));
    }

    public function getCities(){
        $data = file_get_contents('tiva/js/city.json');
        $data = (array) json_decode($data);
        usort($data, function($a, $b){
            return strcmp($a->name, $b->name);
        });

        return $data;
    }

    public function workType(){
        return WorkType::all();
    }

    public function workForm(){
        return WorkForm::all();
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
