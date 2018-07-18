<?php

namespace App\Http\Controllers;

use App\Repo\CvStatusRepo;
use App\Repo\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CvStatusController extends Controller
{
    //
    public function __construct(UserRepo $userRepo, CvStatusRepo $cvStatusRepo)
    {
        $this->userRepo = $userRepo;
        $this->cvStatusRepo = $cvStatusRepo;
    }

    public function getCvStatus(Request $request){
        $cvStatus = $this->cvStatusRepo->getCvStatusByUserId($request['user']['sub']);
        return $cvStatus;
    }

    public function postCvStatus(Request $request){
        $status = 0;
        $errors = 0;

        $rules = array(
            'status' => 'required',
            'address' => 'required',
            'work_type' => 'required',
            'work_position' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $status = 400;
            $errors = ['message'=> $errorsMessage];
        } else {
            $user = $this->userRepo->getUserById($request['user']['sub']);
            if(!empty($user)){
                $result = $this->cvStatusRepo->update($request);
                if($result){
                    $status = 1;
                }else{
                    $status = 500;
                    $errors = ['message'=>'not saved to the database'];
                }
            }else{
                $status = 400;
                $errors = ['message'=>'user not found'];
            }
        }

        return response()->json(compact('status','errors'));
    }
}
