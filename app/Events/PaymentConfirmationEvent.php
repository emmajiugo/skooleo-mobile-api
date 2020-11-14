<?php

namespace App\Events;

class PaymentConfirmationEvent extends Event
{

    public $txRef;
    public $transactionId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($txRef, $transactionId)
    {
        $this->txRef = $txRef;
        $this->transactionId = $transactionId;
    }
}
