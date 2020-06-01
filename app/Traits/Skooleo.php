<?php
namespace App\Traits;

use App\User;
use App\Invoice;

trait Skooleo
{
    /**
     * Custom response returned
     * @return Array
     */
    public function customResponse($status, $message, $data = null)
    {
        return [
            "status"    => $status,
            "message"   => $message,
            "data"      => $data
        ];
    }

    // generate transaction reference
    public function generateTrxId()
    {
        $trxid = "";
        do {
            //generate 8 different random numbers and concat them
            for ($i = 0; $i < 8; $i++) {
                $trxid .= mt_rand(1, 9);
            }
        } while (!empty(Invoice::where('invoice_reference', $trxid)->first()));

        return $trxid;
    }
}
