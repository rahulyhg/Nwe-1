<?php

namespace App\Repo;

use App\CvRow;
use App\Employer;
use App\Job;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobsRepo
{

    public function __construct(Request $request, Job $job)
    {
        $this->request = $request;
        $this->model = $job;
    }

    public function getJobById($id){
        // if ( Auth::guard('employers')->check() && !Auth::guard('employers')->user()->isAdmin() ){
        //     return $this->model->where('id','=',$id)->where('employer_id','=', Auth::guard('employers')->user()->id)->first();
        // }
        return $this->model->find($id);
    }

    public function getJobBySlug($slug){
        return $this->model->where('slug','=',$slug)->first();
    }

    public function getJobs($offset = 0,$limit = 10,$search = ""){
        $this->search = $search;
        if($search == ""){
            $query = $this->model->offset($offset)->limit($limit)->with('employer')->with('cv');
            if (!Auth::guard('employers')->user()->isAdmin() ){
                $query->where('employer_id','=', Auth::guard('employers')->user()->id);
            }
            $query = $query->orderBy('created_at', 'desc')->get();
            return $query;
        }else{
             $query = $this->model->offset($offset)->limit($limit)->whereHas('employer', function($query) use($search) {
                $query->where('company_name', 'like', '%'.$search.'%');
            })->orWhere('job_name','LIKE','%'.$search.'%')->orderBy('created_at', 'desc')->with('cv');
            if (!Auth::guard('employers')->user()->isAdmin() ){
                $query->where('employer_id','=', Auth::guard('employers')->user()->id);
            } 
            $query = $query->get();
            return $query;
        }

    }

    public function getTotalJobs($search = ""){

        if($search == ""){
            $query = $this->model->count();

            if (!Auth::guard('employers')->user()->isAdmin() ){
                $query = $this->model->where('employer_id','=', Auth::guard('employers')->user()->id)->count();
            }
            return $query;
        }else{

            $query = $this->model->whereHas('employer', function($query) use($search) {
                $query->where('company_name', 'like', '%'.$search.'%');
            })->orWhere('job_name','LIKE','%'.$search.'%')->count();

            if (!Auth::guard('employers')->user()->isAdmin() ){
                $query =  $this->model->whereHas('employer', function($query) use($search) {
                    $query->where('company_name', 'like', '%'.$search.'%');
                })->orWhere('job_name','LIKE','%'.$search.'%')->where('employer_id','=', Auth::guard('employers')->user()->id)->count();
            }

            return $query;
        }

    }

    public function create($request){

        $saved = false;

        $this->model->job_name = $request->job_name;
        if (Auth::guard('employers')->user()->isAdmin() ){
            if(!empty($request->employer_id)){
                $this->model->employer_id = $request->employer_id;
            }else{
                $this->model->employer_id = Auth::guard('employers')->user()->id;
            }
            if(!empty($request->employer_view)){
                $this->model->employer_view = $request->employer_view;
            }
        }else{
            $this->model->employer_id = Auth::guard('employers')->user()->id;
        }
        


        if($this->model->save()){
            if(!empty($request->thumb)) {
                $file = $request->thumb;
                $path = 'uploads/jobs/' . $this->model->id . '/';
                $modifiedFileName = time() . '-' . $file->getClientOriginalName();
                if ($file->move($path, $modifiedFileName)) {
                    $this->model->thumb = $path . $modifiedFileName;
                    $this->model->save();
                }
            }
            $saved = $this->model->id;
        }

        return $saved;
    }

    public function edit($model,$request){
        //dd($request->all());
        $status = false;
        if($request->thumb != null){
            $file =  $request->thumb;
            $path = 'uploads/jobs/'.$model->id.'/';
            $modifiedFileName = time().'-'.$file->getClientOriginalName();
            if($file->move($path,$modifiedFileName)){
                $oldFile = str_replace(config('app.api_url'),'',$model->thumb);
                if(is_file($oldFile)){
                    unlink($oldFile);
                }
                $model->thumb = $path.$modifiedFileName;
            }
        }
        $model->job_name = $request->job_name;
        if (Auth::guard('employers')->user()->isAdmin() ){
            
            if(!empty($request->employer_id)){
                $model->employer_id = $request->employer_id;
            }else{
                //$model->employer_id = Auth::guard('employers')->user()->id;
            }
            if(!empty($request->employer_view)){
                $model->employer_view = $request->employer_view;
            }
        }else{
            //$model->employer_id = Auth::guard('employers')->user()->id;
        }
        //$model->job_status = $request->job_status;
        $model->job_form = $request->job_form;
        $model->job_type = $request->job_type;
        $model->job_address = $request->job_address;
        $model->job_city = $request->job_city;
        $model->job_time = $request->job_time;
        $model->job_date_start = $request->job_date_start;
        $model->job_gender = $request->job_gender;
        $model->job_age = $request->job_age;
        $model->job_people = $request->job_people;
        $model->job_end_cv = $request->job_end_cv;
        $model->job_wage = $request->job_wage;
        $model->job_wage_type = $request->job_wage_type;
        $model->job_description = json_encode($request->job_description);
        $model->job_benefit = json_encode($request->job_benefit);
        $model->job_request = json_encode($request->job_request);
        $model->utility_ids = json_encode($request->utility_ids);
        $model->lat = $request->lat;
        $model->lng = $request->lng;
        $model->tab_sort = !empty($request->tab_sort)?json_encode($request->tab_sort):"[]";
        $model->tab_active = !empty($request->tab_active)?json_encode($request->tab_active):"[]";

        if(!empty($request->tabs)){
            foreach($request->tabs as $tab_id => $tab){
                if(!empty($tab['rows'])){
                    $rowSort = 0;
                    foreach($tab['rows'] as $row_id => $row){
                        $rowSort++;
                        $_row = CvRow::where('id','=',$row_id)->where('job_id','=',$model->id)->first();
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
                        $_row->job_id = $model->id;

                        if (Auth::guard('employers')->user()->isAdmin() ){
                            $_row->employer_create = $request->employer_id;
                            if(!empty($request->employer_id)){
                                $_row->employer_create = $request->employer_id;
                            }else{
                                $_row->employer_create = Auth::guard('employers')->user()->id;
                            }
                        }else{

                            $_row->employer_create = Auth::guard('employers')->user()->id;
                        }

                        if($_row->save()){
                            if(!empty($row['icon']) ){
                                $file =  $row['icon'];
                                $path = 'uploads/jobs/'.$model->id.'/';
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
            if($model->job_status != '1'){
                $model->job_status = '1';
                if(!empty($model->employer['getfly_id'])){
                    $getfly = $this->GetFly($model,'https://sachvidan.getflycrm.com/api/v3/orders/','POST');
                    //dd($getfly);    
                }
                $model->save();
            }
            $status = true;

        }
        return $status;
    }

    public function delete($model){
        $status = false;
        $oldDir = "uploads/jobs/".$model->id;
//        if(!empty($model->thumb) && is_file($oldFile)){
//            unlink($oldFile);
//        }
        if(is_dir($oldDir)){
            // remove folder scene
            File::cleanDirectory($oldDir);
            rmdir($oldDir);
        }

        if($model->delete()){
            $status = true;
        }

        return $status;
    }

    public function duplicate($model){
         $status = false;   
         $clonedMoel = $model->replicate();
         $clonedMoel->job_name = $clonedMoel->job_name.' copy';
         $oldFile = str_replace(config('app.api_url'),'',$model->thumb);
         $arr = explode('/', $oldFile);
        
         if($clonedMoel->save()){
            $status = $clonedMoel->id;

             if(is_file($oldFile)){
                $path = 'uploads/jobs/'.$clonedMoel->id.'/'.time().end($arr);
                mkdir('uploads/jobs/'.$clonedMoel->id);
                File::copy($oldFile, $path);
                $clonedMoel->thumb = $path;
                $clonedMoel->save();
             }
         }

         return $status;
    }

    public function GetFly($model,$api_url,$method){
        $api_key = "rniVAK76SWRaS2JKjW83UaxbGoGkMc";
        //$api_url = "https://sachvidan.getflycrm.com/api/v3/account/";
       
        $httpheader = array('Content-Type: application/json','X-API-KEY:'.$api_key);
    
        if($method == 'POST'){
            $post_content = array(
            'order_info' => array(
                'account_code' => 'KH'.$model->employer['getfly_id'],
                'account_name' => $model->employer['name'], 
                'account_phone' => $model->employer['mobile_number'],
                'account_email' => $model->employer['email'],
                'account_address'=> $model->employer['company_address'],
                "order_date" => date("d/m/Y"),
                "discount"=> 0,
                "discount_amount"=> 0,
                "vat"=> 0,
                "vat_amount"=> 0,
                "transport"=> 0,
                "transport_amount"=> 0,
                "installation"=> 0,
                "installation_amount"=> 0,
                'amount' => 100000,
              ),
            "products"=> array(
                array(
                "product_code"=> 'SP73171',
                "product_name"=> 'Tiva 01 tin tuyển dụng',
                "quantity"=> 1,
                "price" => 100000,
                "product_sale_off"=> 0, 
                "cash_discount"=> "0"
                ) 
            ),
            "terms" => array()
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
        //dd(json_encode($post_content));
        return json_decode($answerFromServer);
    }
}