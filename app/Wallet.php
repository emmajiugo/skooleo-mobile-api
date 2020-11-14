<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //
    public function schooldetail() {
        return $this->belongsTo('App\SchoolDetail');
    }
}
