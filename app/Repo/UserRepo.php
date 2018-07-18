<?php

namespace App\Repo;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepo {

    public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->model = $user;
    }

    public function getUserById($id){
        return $this->model->find($id);
    }

    public function getUserBySlug($slug){
        return $this->model->where('slug','=',$slug)->first();
    }

    public function getUsers($offset = 0,$limit = 10,$search = ""){

        if($search == ""){
            return $this->model->offset($offset)->limit($limit)->orderBy('created_at', 'asc')->get();
        }else{
            return $this->model->offset($offset)->limit($limit)->where('name', 'like', '%'.$search.'%')
                ->orWhere('mobile_number', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%')
                ->orderBy('created_at', 'asc')->get();
        }

    }

    public function getTotalUsers($search = ""){

        if($search == ""){
            return $this->model->count();
        }else{
            return $this->model->where('name', 'like', '%'.$search.'%')
                ->orWhere('mobile_number', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%')
                ->count();
        }

    }

    public function create($request){
        $saved = false;
        $this->model->name = $request->name;
        $this->model->mobile_number = $request->mobile_number;
        $this->model->email = $request->email;
        $this->model->password = Hash::make($request->password);
        $this->model->active = 0;

        if($this->model->save()){
            if(!empty($request->avatar) && $request->avatar != "undefined"){
                $file =  $request->avatar;
                $path = 'uploads/users/'.$this->model->id.'/';
                $modifiedFileName = time().'-'.$file->getClientOriginalName();
                if($file->move($path,$modifiedFileName)){
                    $this->model->avatar = $path.$modifiedFileName;
                    $this->model->save();
                }
            }
            try {
                $getfly = $this->GetFly($this->model,'https://sachvidan.getflycrm.com/api/v3/account/','POST');
                if(!empty($getfly)){
                    if($getfly->code == 201 && !empty($getfly->account_id)){
                         $this->model->getfly_id = $getfly->account_id;
                         $this->model->save();
                    }
                }
                
            } catch (Exception $e) {
                
            }
            $saved = true;
        }

        return $saved;
    }

    public function update($model,$request){
        $saved = false;
        if(!empty($model)){
            $model->name = $request->name;
            $model->email = $request->email;
            $model->mobile_number = $request->mobile_number;

            // if(!empty($request->gender)) $model->gender = $request->gender;
            // if(!empty($request->birthday)) $model->birthday = $request->birthday;
            if(!empty($request->password) && $request->password != "undefined") $model->password = Hash::make($request->password);

            if($model->save()){
                if(!empty($request->avatar) && $request->avatar != "undefined"){
                    $file =  $request->avatar;
                    $path = 'uploads/users/'.$model->id.'/';
                    $modifiedFileName = time().'-'.$file->getClientOriginalName();
                    if($file->move($path,$modifiedFileName)){
                        $oldFile = str_replace(config('app.api_url'),'',$model->avatar);
                        if(is_file($oldFile)){
                            unlink($oldFile);
                        }
                        $model->avatar = $path.$modifiedFileName;
                        $model->save();
                    }
                }
                try {
                    if(!empty($model->getfly_id)){
                        $getfly = $this->GetFly($model,'https://sachvidan.getflycrm.com/api/v3/account/'.$model->getfly_id,'PUT');
                    }else{
                        $getfly = $this->GetFly($model,'https://sachvidan.getflycrm.com/api/v3/account/','POST');
                        if(!empty($getfly)){
                            if($getfly->code == 201 && !empty($getfly->account_id)){
                                 $model->getfly_id = $getfly->account_id;
                                 $model->save();
                            }
                        }
                    }
                } catch (Exception $e) {
                    
                }
                $saved = true;
            }
        }
        return $saved;
    }

    public function updateInfo($model,$request){
        $saved = false;
        if(!empty($model)){
            $model->address = $request->address;
            $model->status = $request->status;
            $model->gender = $request->gender;
            $model->birthday = $request->birthday;
            $model->work_type = $request->work_type;
            $model->work_form = $request->work_form;
            $model->work_address = $request->work_address;
            $model->work_wage = $request->work_wage;
            $model->work_position = $request->work_position;

            if($model->save()){
                try {
                    if(!empty($model->getfly_id)){
                        $getfly = $this->GetFly($model,'https://sachvidan.getflycrm.com/api/v3/account/'.$model->getfly_id,'PUT');
                    }
                } catch (Exception $e) {
                    
                }
                $saved = true;
            }
        }
        return $saved;
    }

    public function GetFly($model,$api_url,$method){
        $api_key = "rniVAK76SWRaS2JKjW83UaxbGoGkMc";
        //$api_url = "https://sachvidan.getflycrm.com/api/v3/account/";
       
        $httpheader = array('Content-Type: application/json','X-API-KEY:'.$api_key);
        $post_content = array(
            'account' => array(
                'account_name' => $model->name, 
                'phone_office' => $model->mobile_number,
                'email' => $model->email,
                'billing_address_street'=> $model->address,
                'birthday'=> $model->birthday,
                'gender' => ($model->gender == 1)?2:1,
                'account_type' => 175
              )  
          );
        if($method == 'POST'){
            $post_content = array(
            'account' => array(
                'account_name' => $model->name, 
                'phone_office' => $model->mobile_number,
                'email' => $model->email,
                'billing_address_street'=> $model->address,
                'birthday'=> $model->birthday,
                'gender' => ($model->gender == 1)?2:1,
                'account_type' => 175
              ),
              "contacts"=> array(
              
                "first_name"=> $model->name,
                "email"=> $model->mobile_number,
                "phone_mobile"=> $model->email,
                "title"=> ""
            )  
          );
           
        }
        //var_dump(json_encode($post_content));
        $curl_connection = curl_init();
        curl_setopt($curl_connection, CURLOPT_URL, $api_url);
        curl_setopt($curl_connection, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl_connection, CURLOPT_HTTPHEADER, $httpheader);
        curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_connection, CURLOPT_POSTFIELDS, json_encode($post_content));
        $answerFromServer = curl_exec($curl_connection);
        curl_close($curl_connection);
        return json_decode($answerFromServer);
    }
}