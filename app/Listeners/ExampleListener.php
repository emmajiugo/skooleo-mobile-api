<?php

namespace App\Listeners;


use App\Events\PaymentConfirmationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ExampleListener
{

    /**
     * Handle the event.
     *
     * @param  PaymentConfirmationEvent  $event
     * @return void
     */
    public function handle(PaymentConfirmationEvent $event)
    {
        dump("Example listener here");
    }
}
