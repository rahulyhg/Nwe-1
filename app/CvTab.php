<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CvTab extends Model
{
    //

    public function rows(){
        return $this->hasMany('App\CvRow', 'cv_tab_id');
    }

    public function options(){
        return $this->hasMany('App\TabOption', 'tab_id');
    }
}
