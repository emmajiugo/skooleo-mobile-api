<?php
namespace App\Traits;

use Illuminate\Support\Facades\Request;

trait PaymentGateway
{
    private $baseUrl = "https://api.flutterwave.com/v3/";

    public function getListOfBanks()
    {
        $result = array();

        try {
            $secretKey = env('FLUTTERWAVE_SECRET_KEY');

            $url = $this->baseUrl . 'banks/NG';

            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', $url, [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $secretKey
                ]
            ]);
            $response = $res->getBody();

            return json_decode($response, true)['data'];

        } catch (\Throwable $th) {
            return array();
        }
    }

    public function flutterwaveCheckoutForm(array $data)
    {
        try {
            $url = $this->baseUrl . "payments";
            $secretKey = env('FLUTTERWAVE_SECRET_KEY');

            $client = new \GuzzleHttp\Client();
            $res = $client->request('POST', $url, [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $secretKey
                ],
                'json' => [
                    "tx_ref" => $data['reference'],
                    "amount" => $data['amount'],
                    "currency" => "NGN",
                    "redirect_url" => $data['callback'],
                    "payment_options" => "card",
                    "customer" => [
                        "email" => $data['email'],
                        "phonenumber" => $data['user_phone'],
                        "name" => $data['user_name']
                    ],
                    "customizations" => [
                        "title" => env('MODAL_TITLE'),
                        "description" => env('MODAL_DESCRIPTION'),
                    ]
                ]
            ]);

            $response = $res->getBody();

            return json_decode($response, true);

        } catch (\Throwable $th) {
            return ["status"=>"error", "message"=>"An error occurred. Please contact support. ".$th->getMessage()];
        }
    }

    public function flutterwaveVerifyTransaction($transactionId)
    {
        try {
            $secretKey = env('FLUTTERWAVE_SECRET_KEY');

            $url = $this->baseUrl . "transactions/" .$transactionId. "/verify";

            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', $url, [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $secretKey
                ]
            ]);
            $response = $res->getBody();

            return json_decode($response, true);

        } catch (\Throwable $th) {
            return ["status"=>"error", "message"=>"An error occurred. Please contact support."];
        }
    }

    public function flutterwaveTransfer(array $data) {
        try {
            $url = $this->baseUrl . "transfers";

            $secretKey = env('FLUTTERWAVE_SECRET_KEY');

            $client = new \GuzzleHttp\Client();
            $res = $client->request('POST', $url, [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $secretKey
                ],
                'json' => $data
            ]);
            $response = $res->getBody();

            return json_decode($response, true);

        } catch (\Throwable $th) {
            return ["status"=>"error", "message"=>"Transfer creation failed"];
        }
    }
}


