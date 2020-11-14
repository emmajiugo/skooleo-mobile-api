<?php

namespace App\Listeners;

use App\Wallet;
use App\Invoice;
use App\Traits\PaymentGateway;
use App\Events\PaymentConfirmationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyPaymentListener implements ShouldQueue
{
    use PaymentGateway;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * Handle the event.
     *
     * @param  PaymentConfirmationEvent  $event
     * @return void
     */
    public function handle($event)
    {

        $transactionFee = \App\WebSettings::find(1)->transaction_fee;

        //verify the transaction using transaction ref passed
        $status = $this->flutterwaveVerifyTransaction($event->transactionId);

        if ($status['data']['status'] === 'successful') {
            //explode the reference passed to be able to handle multiple payments
            $refs = explode("_", $event->txRef);

            //update
            foreach ($refs as $ref) {
                $invoice = Invoice::where('invoice_reference', $ref)->first();
                $invoice->status = 'PAID';
                $invoice->payment_reference = $status['data']['flw_ref'];
                $invoice->payment_id = $status['data']['id'];
                $invoice->fee = $status['data']['app_fee'];
                $invoice->skooleo_fee = $transactionFee;
                $invoice->save();

                if ($invoice->settled == 0) {
                    // update wallet and settled to true
                    $wallet = Wallet::where('school_detail_id', $invoice->school_detail_id)->first();
                    $wallet->total_amount += $invoice->amount;
                    $wallet->save();

                    // mark transaction as settled
                    $invoice->settled = true;
                    $invoice->save();
                }
            }

            return true;
        } else {
            return false;
        }
    }
}
