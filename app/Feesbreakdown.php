<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feesbreakdown extends Model
{
    //
    public function feesetup() {
        return $this->belongsTo('App\Feesetup');
    }
}
