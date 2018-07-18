<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TabOption extends Model
{
    //
    public function getIconAttribute($value)
    {
        if(!empty($value)){
            return config('app.api_url').$value;
        }else{
            return $value;
        }
    }

    public function rows()
    {
        return $this->hasMany('App\CvRow', 'option_id');
    }

    public function delete()
    {
        $this->rows()->delete();
        return parent::delete();
    }
}
