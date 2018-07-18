<?php

namespace App\Repo;

use App\CvStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CvStatusRepo {

    public function __construct( CvStatus $cvStatus)
    {
        $this->model = $cvStatus;
    }

    public function getCvStatusByUserId($user_id){
        $cvStatus = $this->model->where('user_id','=',$user_id)->first();
        return $cvStatus;
    }

    public function update($request){
        $status = false;
        $cvStatus = $this->getCvStatusByUserId($request['user']['sub']);
        if(empty($cvStatus)){
            $cvStatus = new $this->model;
        }

        $cvStatus->status = $request->status;
        $cvStatus->address = $request->address;
        $cvStatus->work_type = $request->work_type;
        $cvStatus->work_position = $request->work_position;
        $cvStatus->wage = $request->wage ;
        $cvStatus->youtube = $request->youtube ;
        $cvStatus->user_id = $request['user']['sub'];

        if($cvStatus->save()){
            $status = true;
        }

        return $status;
    }
}