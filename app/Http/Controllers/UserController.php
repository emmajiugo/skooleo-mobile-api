<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\Skooleo;

use App\User;

class UserController extends Controller
{
    use Skooleo;

    //login user
    public function loginUser(Request $request)
    {
        $response = User::where('email', $request->email_or_phone)
                    ->orWhere('phone', $request->email_or_phone)->first();

        if($response) {
            $password = Hash::check($request->password, $response->password);

            if ($password) {
                return response()->json($response);
            }
        }

        return response()->json($this->customResponse("error", "Unauthorized user"), 401);
    }

    //register user
    public function registerUser(Request $request)
    {
        $result = User::where('email', $request->email)
                    ->orWhere('phone', $request->phone)->first();

        if(!$result) {
            $user = User::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'api_token' => hash("sha512", "SKOOLEO".$request->email)
            ]);

            return response()->json($this->customResponse("OK", "user created", $user), 201);

        } else {

            return response()->json($this->customResponse("error", "An error occured, please contact support"), 417);
        }
    }

    //get user details
    //update user record
}
