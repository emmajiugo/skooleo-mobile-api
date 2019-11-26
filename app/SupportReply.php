<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportReply extends Model
{
    //
    public function supportticket() {
        return $this->belongsTo('App\SupportTicket');
    }
}
