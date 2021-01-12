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
        $reference = $this->getYearMonth();
        do {
            //generate 4 different random numbers and concat them
            for ($i = 0; $i < 6; $i++) {
                $reference .= mt_rand(1, 9);
            }
        } while (!empty(Invoice::where('invoice_reference', $reference)->first()));

        return $reference;
    }

    protected function getYearMonth()
    {
        return date("Y") . date("m");
    }
}
