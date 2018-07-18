<?php

namespace App\Repo;

use App\CvRow;
use App\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class EmployerRepo {

    public function __construct(Request $request, Employer $employer)
    {
        $this->request = $request;
        $this->model = $employer;
    }

    public function getEmployerById($id){
        return $this->model->find($id);
    }

    public function getEmployerBySlug($slug){
        return $this->model->where('company_slug','=',$slug)->first();
    }

    public function getEmployers($offset = 0,$limit = 10,$search = ""){

        if($search == ""){
            return $this->model->offset($offset)->limit($limit)->orderBy('name', 'asc')->get();
        }else{
            return $this->model->offset($offset)->limit($limit)->where('name', 'like', '%'.$search.'%')->orWhere('company_name','like','%'.$search.'%')->orderBy('name', 'asc')->get();
        }

    }

    public function getTotalEmployers($search = ""){

        if($search == ""){
            return $this->model->count();
        }else{
            return $this->model->where('name', 'like', '%'.$search.'%')->count();
        }

    }

    public function create($request){
        $saved = false;

        $this->model->name = $request->name;
        $this->model->mobile_number = $request->mobile_number;
        $this->model->email = $request->email;
        $this->model->password = Hash::make($request->password);
        $this->model->position = $request->position;
        $this->model->company_name = $request->company_name;
        $this->model->company_mobile = $request->company_mobile;
        $this->model->company_address = $request->company_address;
        $this->model->company_activity = $request->company_activity;
        $this->model->active = 0;

        if($this->model->save()){
            if(!empty($request->avatar) && $request->avatar != "undefined"){
                $file =  $request->avatar;
                $path = 'uploads/employer/'.$this->model->id.'/';
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
            $model->position = $request->position;

            // if(!empty($request->gender)) $model->gender = $request->gender;
            // if(!empty($request->birthday)) $model->birthday = $request->birthday;
            if(!empty($request->password) && $request->password != "undefined"){
                $model->password = Hash::make($request->password);
            }

            if($model->save()){
                if(!empty($request->avatar) && $request->avatar != "undefined"){
                    $file =  $request->avatar;
                    $path = 'uploads/employer/'.$model->id.'/';
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
            if($request->ajax()){
                if(!empty($request->company_name) && $request->company_name != "undefined"){
                    $model->company_name = $request->company_name;
                }
                if(!empty($request->company_mobile) && $request->company_mobile != "undefined"){
                    $model->company_mobile = $request->company_mobile;
                }
                if(!empty($request->company_address) && $request->company_address != "undefined"){
                     $model->company_address = $request->company_address;
                }
                if(!empty($request->company_activity) && $request->company_activity != "undefined"){
                    $model->company_activity = $request->company_activity;
                }
                if(!empty($request->company_employees) && $request->company_employees != "undefined"){
                    $model->company_employees = $request->company_employees;
                }
                if(!empty($request->company_branches) && $request->company_branches != "undefined"){
                    $model->company_branches = $request->company_branches;
                }
                if(!empty($request->company_description) && $request->company_description != "undefined"){
                    $model->company_description = json_encode($request->company_description);
                }
                if(!empty($request->company_website) && $request->company_website != "undefined"){
                    $model->company_website = $request->company_website;
                }
                if(!empty($request->company_facebook) && $request->company_facebook != "undefined"){
                    $model->company_facebook = $request->company_facebook;
                }              
                if(empty($model->company_gallery) || $model->company_gallery == "null"){
                    $gallery = [];
                }else{
                    $gallery = json_decode($model->company_gallery);
                }
                if($request->galleries != null){
                    $files =  $request->file('galleries');
                    //dd($files[0]);
                    foreach ( $files as $idx => $file) {
                        $path = 'uploads/employer/'.$model->id.'/gallery/';
                        $modifiedFileName = time().'-'.$file->getClientOriginalName();
                        if($file->move($path,$modifiedFileName)){
                            //array_push($saved,$path.$modifiedFileName);
                            array_push($gallery,$path.$modifiedFileName);
                        }
                    }
                    $model->company_gallery = json_encode($gallery);
                }
            }else{
                $model->company_name = $request->company_name;
                $model->company_mobile = $request->company_mobile;
                $model->company_address = $request->company_address;
                $model->company_activity = $request->company_activity;
                $model->company_employees = $request->company_employees;
                $model->company_branches = $request->company_branches;
                $model->company_description = json_encode($request->company_description);
                $model->company_website = $request->company_website;
                $model->company_facebook = $request->company_facebook;
            }
            
            if(!empty($request->gallery)){
                $model->company_gallery = json_encode($request->gallery);
            }
            if(!empty($request->tabs)){
                foreach($request->tabs as $tab_id => $tab){
                    if(!empty($tab['rows'])){
                        $rowSort = 0;
                        foreach($tab['rows'] as $row_id => $row){
                            $rowSort++;
                            $_row = CvRow::where('id','=',$row_id)->where('employer_id','=',$model->id)->first();
                            if(empty($_row)){
                                $_row = new CvRow;
                            }
                            if(!empty($tab['input_type'])){
                                if($tab['input_type'] == "select"){
                                    $_row->option_id = $row['option_id'];
                                }
                                if($tab['input_type'] == "text"){
                                    $_row->name = $row['name'];

                                }
                            }
                            $_row->description = $row['description'];
                            $_row->date_start = $row['date_start'];
                            $_row->date_end = $row['date_end'];
                            $_row->sort = $rowSort;
                            //$_row->date_end = (!empty($request->date_end) && $request->date_end !="null")?$request->date_end:null;
                            $_row->cv_tab_id = $tab_id;
                            $_row->employer_id = $model->id;

                            $_row->employer_create = $model->id;
                            
                            if($_row->save()){
                                if(!empty($row['icon']) ){
                                    $file =  $row['icon'];
                                    $path = 'uploads/employer/'.$model->id.'/';
                                    $modifiedFileName = time().'-'.$file->getClientOriginalName();
                                    if($file->move($path,$modifiedFileName)){
                                        $oldFile = str_replace(config('app.api_url'),'',$_row->icon);
                                        if(!empty($_row->icon) && is_file($oldFile)){
                                            unlink($oldFile);
                                        }
                                        $_row->icon = $path.$modifiedFileName;
                                        $_row->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if($model->save()){
               
                $saved = true;
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
            }
        }
        return $saved;
    }

    public function a_create($request){

        $saved = false;

        $this->model->name = $request->name;
        $this->model->mobile_number = $request->mobile_number;
        $this->model->email = $request->email;
        $this->model->position = $request->position;
        $this->model->company_name = $request->company_name;
        $this->model->password = "123456";
        $this->model->active = 0;

        if($this->model->save()){
            $file =  $request->avatar;
            $path = 'uploads/employer/'.$this->model->id.'/';
            $modifiedFileName = time().'-'.$file->getClientOriginalName();
            if($file->move($path,$modifiedFileName)){
                $this->model->avatar = $path.$modifiedFileName;
                $this->model->save();
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
            $saved = $this->model->id;
        }

        return $saved;
    }

    public function a_edit($model,$request){
        //dd($request->all());
        $status = false;
        if($request->avatar != null){
            $file =  $request->avatar;
            $path = 'uploads/employer/'.$model->id.'/';
            $modifiedFileName = time().'-'.$file->getClientOriginalName();
            if($file->move($path,$modifiedFileName)){
                $oldFile = str_replace(config('app.api_url'),'',$model->avatar);
                if(is_file($oldFile)){
                    unlink($oldFile);
                }
                $model->avatar = $path.$modifiedFileName;
            }
        }
        $model->name = $request->name;
        $model->mobile_number = $request->mobile_number;
        $model->email = $request->email;
        $model->position = $request->position;

        if(!Auth::guard('employers')->user()->isAdmin() && !empty($request->password) && $request->password != "undefined"){
            $model->password = Hash::make($request->password);
        }

        $model->company_name = $request->company_name;
        $model->company_mobile = $request->company_mobile;
        $model->company_address = $request->company_address;
        $model->company_activity = $request->company_activity;
        $model->company_employees = $request->company_employees;
        $model->company_branches = $request->company_branches;
        $model->company_description = json_encode($request->company_description);
        $model->company_website = $request->company_website;
        $model->company_facebook = $request->company_facebook;
        $model->tab_sort = !empty($request->tab_sort)?json_encode($request->tab_sort):"[]";
        $model->tab_active = !empty($request->tab_active)?json_encode($request->tab_active):"[]";

        if(!empty($request->tabs)){
            foreach($request->tabs as $tab_id => $tab){
                if(!empty($tab['rows'])){
                    $rowSort = 0;
                    foreach($tab['rows'] as $row_id => $row){
                        $rowSort++;
                        $_row = CvRow::where('id','=',$row_id)->where('employer_id','=',$model->id)->first();
                        if(empty($_row)){
                            $_row = new CvRow;
                        }
                        if(!empty($tab['input_type'])){
                            if($tab['input_type'] == "select"){
                                $_row->option_id = $row['option_id'];
                            }
                            if($tab['input_type'] == "text"){
                                $_row->name = $row['name'];

                            }
                        }
                        $_row->description = $row['description'];
                        $_row->date_start = $row['date_start'];
                        $_row->date_end = $row['date_end'];
                        $_row->sort = $rowSort;
                        //$_row->date_end = (!empty($request->date_end) && $request->date_end !="null")?$request->date_end:null;
                        $_row->cv_tab_id = $tab_id;
                        $_row->employer_id = $model->id;

                        $_row->employer_create = $model->id;
                        
                        if($_row->save()){
                            if(!empty($row['icon']) ){
                                $file =  $row['icon'];
                                $path = 'uploads/employer/'.$model->id.'/';
                                $modifiedFileName = time().'-'.$file->getClientOriginalName();
                                if($file->move($path,$modifiedFileName)){
                                    $oldFile = str_replace(config('app.api_url'),'',$_row->icon);
                                    if(!empty($_row->icon) && is_file($oldFile)){
                                        unlink($oldFile);
                                    }
                                    $_row->icon = $path.$modifiedFileName;
                                    $_row->save();
                                }
                            }
                        }
                    }
                }
            }
        }

        $model->company_gallery = !empty($request->gallery)?json_encode($request->gallery):"[]";



        if($model->save()){
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
            $status = true;
        }
        return $status;
    }

    public function updateGallery($model, $request){
        $saved = [];
        if(empty($model->company_gallery) || $model->company_gallery == "null"){
            $gallery = [];
        }else{
            $gallery = json_decode($model->company_gallery);
        }
        if($request->files != null){
            $files =  $request->file('files');
            //dd($files[0]);
            foreach ( $files as $idx => $file) {
                $path = 'uploads/employer/'.$model->id.'/gallery/';
                $modifiedFileName = time().'-'.$file->getClientOriginalName();
                if($file->move($path,$modifiedFileName)){
                    array_push($saved,$path.$modifiedFileName);
                    array_push($gallery,$path.$modifiedFileName);
                }
            }
            $model->company_gallery = json_encode($gallery);
        }


        if($model->save()){

        }
        return $saved;
    }

    public function deleteGallery($model, $request){
        $saved = false;
        if(!empty($model->company_gallery)){
            $gallery = json_decode($model->company_gallery);
            if (($key = array_search($request->image, $gallery)) !== false) {
                if(is_file($gallery[$key])){
                    unlink($gallery[$key]);
                }
                array_splice($gallery,$key,1);
            }
            $model->company_gallery = json_encode($gallery);
            if($model->save()){
                $saved = true;
            }

        }
        return $saved;
    }

    public function delete($model){
        $status = false;
        if($model->id != "1"){
            $oldDir = "uploads/employer/".$model->id."/gallery";
//
            if(is_dir($oldDir)){
                // remove folder scene
                File::cleanDirectory($oldDir);
                rmdir($oldDir);
            }

            $oldDir = "uploads/employer/".$model->id;
//
            if(is_dir($oldDir)){
                // remove folder scene
                File::cleanDirectory($oldDir);
                rmdir($oldDir);
            }

            if($model->delete()){
                $status = true;
            }
        }

        return $status;
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
                "website"=> $model->company_website,
                'account_type' => 174
              ),
             "custom_fields"=> array(
                "ten_cong_ty"=> $model->company_name
            )
          );
        if($method == 'POST'){
            $post_content = array(
            'account' => array(
                'account_name' => $model->name, 
                'phone_office' => $model->mobile_number,
                'email' => $model->email,
                "website"=> $model->company_website,
                'account_type' => 174
              ),
            "contacts"=> array(
              
                "first_name"=> $model->name,
                "email"=> $model->mobile_number,
                "phone_mobile"=> $model->email,
                "title"=> ""
            ),
            "custom_fields"=> array(
                "ten_cong_ty"=> $model->company_name
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