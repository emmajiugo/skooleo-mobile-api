<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    //
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function supportreply() {
        return $this->hasMany('App\SupportReply');
    }
}
