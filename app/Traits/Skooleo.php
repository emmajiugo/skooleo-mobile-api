<?php
namespace App\Traits;

use App\User;

trait Skooleo
{
    public function checkForAuthorization($auth)
    {
        $isValid = User::whereApiToken($auth)->first();

        return $isValid;
    }

    public function accessDenied()
    {
        return $error = [
            "status" => "error",
            "message" => "Access denied. Authorization required."
        ];
    }

    public function customResponse($status, $message, $data = null)
    {
        return [
            "status"    => $status,
            "message"   => $message,
            "data"      => $data
        ];
    }
}
