<?php

namespace App\Providers;

use App\Listeners\ExampleListener;
use App\Events\PaymentConfirmationEvent;
use App\Listeners\VerifyPaymentListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PaymentConfirmationEvent::class => [
            VerifyPaymentListener::class,
        ],
    ];
}
