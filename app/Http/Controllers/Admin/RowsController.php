<?php

namespace App\Http\Controllers\Admin;

use App\CvRow;
use App\Repo\CvRowRepo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RowsController extends Controller
{
    //
    public function __construct( Request $request, CvRowRepo $cvRowRepo)
    {
        $this->request = $request;
        $this->cvRowRepo = $cvRowRepo;
    }

    public function deleteRow($id){
        $status = 0;
        $errors = 0;

        if ( Auth::guard('employers')->user()->isAdmin() ){
            $cvRow = CvRow::find($id);
        }else{
            $cvRow = CvRow::where('id','=',$id)->where('employer_create','=', Auth::guard('employers')->user()->id);
        }    
        

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
