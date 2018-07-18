<?php

namespace App;

use App\Review;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Job extends Model
{
    //
    use Sluggable;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'job_name'
            ]
        ];
    }

    public function employer(){
        return $this->belongsTo('App\Employer', 'employer_id');
    }
    public function employerView(){
        return $this->belongsTo('App\Employer', 'employer_view');
    }

    public function rows()
    {
        return $this->hasMany('App\CvRow', 'job_id');
    }

    public function cv(){
        return $this->hasMany('App\CV', 'job_id');
    }

    public function work_cv(){
        return $this->hasMany('App\CV', 'job_id')->where('active_work','=','1');
    }

    public function work_form(){
        return $this->belongsTo('App\WorkForm', 'job_form');
    }
    public function work_type(){
        return $this->belongsTo('App\WorkType', 'job_type');
    }

    public function star(){
        $id = $this->id;
        $star = 3;
        $count = 1;
        $reviews = Review::where('job_id','=',$id)->where('type','=','job')->get();
        if(!empty($reviews)){
            foreach ($reviews as $key => $review) {
                $star = $star + intval($review->star);
            }
            $count = $count + count($reviews);
        }
        return round($star/$count);
    }

    public function getThumbAttribute($value)
    {
        if(!empty($value)){
            return config('app.api_url').$value;
        }else{
            return $value;
        }

    }

    public function getJobDescriptionAttribute($value)
    {
        if(!empty($value)){
            $value = json_decode($value);
            $pattern = "/<a href=.*(.*?)<\/a>/";
            $value = preg_replace($pattern, "", $value);

            return json_encode($value);
        }else{
            return $value;
        }

    }

    public function getJobBenefitAttribute($value)
    {
        if(!empty($value)){
            $value = json_decode($value);
            $pattern = "/<a href=.*(.*?)<\/a>/";
            $value = preg_replace($pattern, "", $value);

            return json_encode($value);
        }else{
            return $value;
        }

    }

    public function getJobRequestAttribute($value)
    {
        if(!empty($value)){
            $value = json_decode($value);
            $pattern = "/<a href=.*(.*?)<\/a>/";
            $value = preg_replace($pattern, "", $value);

            return json_encode($value);
        }else{
            return $value;
        }

    }

    public function delete()
    {
        $this->rows()->delete();
        return parent::delete();
    }
}
