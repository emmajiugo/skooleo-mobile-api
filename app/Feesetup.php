<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feesetup extends Model
{
    //
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function feetype() {
        return $this->belongsTo('App\Feetype');
    }

    public function feesbreakdowns() {
        return $this->hasMany('App\Feesbreakdown');
    }
}
