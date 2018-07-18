<?php

namespace App\Repo;

use App\CV;
use Illuminate\Http\Request;

class CvsRepo
{

    public function __construct(Request $request, CV $cv)
    {
        $this->request = $request;
        $this->model = $cv;
    }

    // public function getJobById($id){
    //     return $this->model->find($id);
    // }

    // public function getJobBySlug($slug){
    //     return $this->model->where('slug','=',$slug)->first();
    // }

    public function getCvsByJobId($job_id ,$offset = 0,$limit = 10,$search = ""){
        $this->search = $search;
        if($search == ""){
            return $this->model->offset($offset)->limit($limit)->where('job_id','=',$job_id)->with('user')->orderBy('created_at', 'desc')->get();
        }else{
            return $this->model->offset($offset)->limit($limit)->where('job_id','=',$job_id)->whereHas('user', function($query) use($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })->orderBy('created_at', 'desc')->get();
        }

    }
    public function getTotalCvsByJobId($job_id ,$search = ""){
        $this->search = $search;
        if($search == ""){
            return $this->model->where('job_id','=',$job_id)->count();
        }else{
            return $this->model->where('job_id','=',$job_id)->whereHas('user', function($query) use($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })->count();
        }

    }
}