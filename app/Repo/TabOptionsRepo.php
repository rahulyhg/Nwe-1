<?php

namespace App\Repo;

use App\TabOption;

class TabOptionsRepo {

    public function __construct( TabOption $tabOption)
    {
        $this->model = $tabOption;
    }

    public function getTabOptionById($id){

        return $this->model->find($id);
    }

    public function getTabOptions($tab_id,$offset = 0,$limit = 10,$search = ""){

        if($search == ""){
            return $this->model->where('tab_id','=',$tab_id)->offset($offset)->limit($limit)->orderBy('name', 'asc')->get();
        }else{
            return $this->model->where('tab_id','=',$tab_id)->offset($offset)->limit($limit)->where('name', 'like', '%'.$search.'%')->orderBy('name', 'asc')->get();
        }

    }

    public function getTotalTabs($tab_id,$search = ""){

        if($search == ""){
            return $this->model->where('tab_id','=',$tab_id)->count();
        }else{
            return $this->model->where('tab_id','=',$tab_id)->where('name', 'like', '%'.$search.'%')->count();
        }

    }

    public function create($tab_id,$request){

        $status = false;

        $this->model->name = $request->name;
        $this->model->tab_id = $tab_id;

        if($this->model->save()){
            if(!empty($request->icon)){
                $file =  $request->icon;
                $path = 'uploads/options/';
                $modifiedFileName = time().'-'.$file->getClientOriginalName();
                if($file->move($path,$modifiedFileName)){
                    $this->model->icon = $path.$modifiedFileName;
                    $this->model->save();
                }
            }
            $status = true;
        }



        return $status;
    }

    public function edit($model,$request){

        $status = false;
        if($request->icon != null){
            $file =  $request->icon;
            $path = 'uploads/options/';
            $modifiedFileName = time().'-'.$file->getClientOriginalName();
            if($file->move($path,$modifiedFileName)){
                $oldFile = str_replace(config('app.api_url'),'',$model->icon);
                if(is_file($oldFile)){
                    unlink($oldFile);
                }
                $model->icon = $path.$modifiedFileName;
            }
        }
        $model->name = $request->name;

        if($model->save()){
            $status = true;
        }
        return $status;
    }

    public function delete($model){
        $status = false;

        $oldFile = str_replace(config('app.api_url'),'',$model->icon);
        if(is_file($oldFile)){
            unlink($oldFile);
        }

        if($model->delete()){
            $status = true;
        }

        return $status;
    }

}