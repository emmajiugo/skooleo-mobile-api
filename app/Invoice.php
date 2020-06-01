<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    public function school_detail() {
        return $this->belongsTo('App\SchoolDetail');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
