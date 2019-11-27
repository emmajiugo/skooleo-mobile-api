<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\Skooleo;

use  App\User;

class PasswordController extends Controller
{
    use Skooleo;

    //forgot password
    public function forgotPassword(Request $request)
    {
        if($request->email != null) {
            // check of email exists in the  db
            $email = User::whereEmail($request->email)->first();

            if($email) {
                // send a password change email
                // {...}

                return response()->json($this->customResponse("OK", "Please check your email to complete the process"), 417);
            }

            return response()->json($this->customResponse("error", "Email not  found in our record"), 404);
        }

        return response()->json($this->customResponse("error", "Email is  required"), 417);
    }

    //change password
    public function changePassword(Request $request)
    {
        $isValid = $this->checkForAuthorization($request->header('Authorization'));

        if ($isValid) {
            $user =  User::whereEmail($request->email)->first();

            if ($user){
                // update the password with new password
                $user->password = Hash::make($request->new_password);
                $user->api_token = hash("sha512", "SKOOLEO".$request->email."".$request->new_password);
                $user->save();

                return response()->json($this->customResponse("OK", "password changed", $user), 201);
            }

            return response()->json($this->customResponse("error", "Email not  found in our record"), 404);

        } else {

            return response()->json($this->accessDenied(), 401);
        }
    }
}
