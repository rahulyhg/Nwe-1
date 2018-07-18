<?php

namespace App\Repo;

use App\CvTab;

class TabsRepo {

    public function __construct( CvTab $cvTab)
    {
        $this->model = $cvTab;
    }

    public function getTabById($id){

        return $this->model->find($id);
    }

    public function getTabs($offset = 0,$limit = 10,$search = ""){

        if($search == ""){
            return $this->model->offset($offset)->limit($limit)->orderBy('name', 'asc')->get();
        }else{
            return $this->model->offset($offset)->limit($limit)->where('name', 'like', '%'.$search.'%')->orderBy('name', 'asc')->get();
        }

    }

    public function getTotalTabs($search = ""){

        if($search == ""){
            return $this->model->count();
        }else{
            return $this->model->where('name', 'like', '%'.$search.'%')->count();
        }

    }

    public function create($request){

        $status = false;
        $this->model->name = $request->name;
        $this->model->user_type = json_encode($request->user_type);
        $this->model->input_type = $request->input_type;
        if($this->model->save()){
            $status = true;
        }
        return $status;
    }

    public function edit($modal,$request){

        $status = false;
        $modal->name = $request->name;
        $modal->user_type = json_encode($request->user_type);
        $modal->input_type = $request->input_type;
        if($modal->save()){
            $status = true;
        }
        return $status;
    }

}