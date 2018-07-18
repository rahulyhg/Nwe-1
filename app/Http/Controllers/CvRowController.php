<?php

namespace App\Http\Controllers;

use App\CvRow;
use App\Repo\CvRowRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CvRowController extends Controller
{
    //
    public function __construct(CvRowRepo $cvRowRepo)
    {
        $this->cvRowRepo = $cvRowRepo;
    }

    public function getCvRowsByCvTabId(Request $request){
        $rows = $this->cvRowRepo->cvRowsByCvTabId($request->id, $request['user']['sub']);
        return $rows;
    }

    public function postUpdateCvRow(Request $request){
        $status = 0;
        $errors = 0;

        $rules = array(
            'name' => 'required',
            'cv_tab_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $status = 400;
            $errors = ['message'=> $errorsMessage];
        } else {

            $result = $this->cvRowRepo->update($request);
            if($result){
                $status = 1;
            }else{
                $status = 500;
                $errors = ['message'=>['other'=>'not saved to the database']];
            }
        }

        return response()->json(compact('status','errors'));
    }

    public function postDeleteCvRow(Request $request){
        $status = 0;
        $errors = 0;

        $cvRow = $this->cvRowRepo->cvRowById($request->id, $request['user']['sub']);

        if(!empty($cvRow)){
            $result = $this->cvRowRepo->delete($cvRow);
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

        return response()->json(compact('status','errors'));
    }
}
