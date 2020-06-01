<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolDetail extends Model
{
    //
    public function school() {
        return $this->belongsTo('App\School');
    }

    public function feesetup() {
        return $this->hasMany('App\Feesetup');
    }

    public function feetype() {
        return $this->hasMany('App\Feetype');
    }
}
