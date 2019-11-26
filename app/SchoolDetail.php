<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolDetail extends Model
{
    //
    public function school() {
        return $this->belongsTo('App\School');
    }

    // public function bankdetail() {
    //     return $this->hasOne('App\BankDetail');
    // }

    public function feesetup() {
        return $this->hasMany('App\Feesetup');
    }

    public function feetype() {
        return $this->hasMany('App\Feetype');
    }

    public function paymenthistory() {
        return $this->hasMany('App\PaymentHistory');
    }

    public function supportticket() {
        return $this->hasMany('App\SupportTicket');
    }

    public function invoice() {
        return $this->hasMany('App\Invoice');
    }
}
