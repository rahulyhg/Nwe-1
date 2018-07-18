<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CvRow extends Model
{
    //

    public function option(){
        return $this->belongsTo('App\TabOption', 'option_id');
    }

    public function getIconAttribute($value)
    {
        if(!empty($value))
        return config('app.api_url').$value;
    }
}
