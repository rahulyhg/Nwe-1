<?php

namespace App\Repo;

use App\CvRow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CvRowRepo {

    public function __construct( CvRow $cvRow)
    {
        $this->model = $cvRow;
    }

    public function cvRowsByCvTabId($cv_tab_id,$user_id){
        $cvStatus = $this->model->where('cv_tab_id','=',$cv_tab_id)->where('user_id','=',$user_id)->get();
        return $cvStatus;
    }

    public function cvRowById($id, $user_id){
        $cvRow =  $this->model->where('id','=',$id)->where($request['user']['rol'].'_id','=',$user_id)->first();
        return $cvRow;
    }

    public function update($request){
        $status = false;
        $cvRow = $this->cvRowById($request->id, $request['user']['sub']);
        if(empty($cvRow)){
            if($request->id == "0"){
                $cvRow = new $this->model;    
            }else{
                return $status;
            }
        }else{
            return $status;
        }
        $cvRow->name = $request->name;
        $cvRow->description = (!empty($request->description) && $request->description !="null")?$request->description:null;
        $cvRow->date_start = (!empty($request->date_start) && $request->date_start !="null")?$request->date_start:null;
        $cvRow->date_end = (!empty($request->date_end) && $request->date_end !="null")?$request->date_end:null;
        $cvRow->cv_tab_id = $request->cv_tab_id;

        if( $request['user']['rol'] == "employer" ){
            $cvRow->employer_id = $request['user']['sub'];
        }else if ($request['user']['rol'] == "user"){
            $cvRow->user_id = $request['user']['sub'];
        }
        

        if($cvRow->save()){
            if(!empty($request->icon) && $request->icon != "undefined"){
                $file =  $request->icon;
                $path = 'uploads/'.$request['user']['sub'].'/';
                $modifiedFileName = time().'-'.$file->getClientOriginalName();
                if($file->move($path,$modifiedFileName)){
                    $oldFile = str_replace(config('app.api_url'),'',$cvRow->icon);
                    if(!empty($cvRow->icon) && is_file($oldFile)){
                        unlink($oldFile);
                    }
                    $cvRow->icon = $path.$modifiedFileName;
                    $cvRow->save();
                }
            }
            $status = true;
        }

        return $status;
    }

    public function delete($modal){
        $status = false;
        if(!empty($modal->icon)){
            $oldFile = str_replace(config('app.api_url'),'',$modal->icon);
            if(!empty($modal->icon) && is_file($oldFile)){
                unlink($oldFile);
            }
        }

        if($modal->delete()){
            $status = true;
        }

        return $status;
    }
}