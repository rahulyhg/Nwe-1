<?php

namespace App\Repo;

use App\Utility;

class UtilityRepo {

    public function __construct( Utility $utility)
    {
        $this->model = $utility;
    }

    public function getUtilityById($id){

        return $this->model->find($id);
    }

    public function getUtilities($offset = 0,$limit = 10,$search = ""){

        if($search == ""){
            return $this->model->offset($offset)->limit($limit)->orderBy('name', 'asc')->get();
        }else{
            return $this->model->offset($offset)->limit($limit)->where('name', 'like', '%'.$search.'%')->orderBy('name', 'asc')->get();
        }

    }

    public function getTotalUtilities($search = ""){

        if($search == ""){
            return $this->model->count();
        }else{
            return $this->model->where('name', 'like', '%'.$search.'%')->count();
        }

    }

    public function create($request){

        $status = false;
        $this->model->name = $request->name;
        if($this->model->save()){
            $status = true;
        }
        return $status;
    }

    public function edit($modal,$request){

        $status = false;
        $modal->name = $request->name;
        if($modal->save()){
            $status = true;
        }
        return $status;
    }

    public function delete($modal){

        $status = false;
        if($modal->delete()){
            $status = true;
        }
        return $status;
    }

}