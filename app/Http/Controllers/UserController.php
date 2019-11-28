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
                return response()->json($this->customResponse("OK", "Authentication successful", $response));
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
                'api_token' => hash("sha512", "SKOOLEO".$request->email."".$request->password)
            ]);

            return response()->json($this->customResponse("OK", "user created", $user), 201);

        } else {

            return response()->json($this->customResponse("error", "A user with the record [email/phone] already exists"), 417);
        }
    }

    //get user details
    public function getUserDetails(Request $request, $userId)
    {

        $isValid = $this->checkForAuthorization($request->header('Authorization'));

        if ($isValid) {
            $user = User::find($userId);

            if ($user) {

                return response()->json($this->customResponse("OK", "user found", $user));
            }
            return response()->json($this->customResponse("error", "user not found"));

        } else {

            return response()->json($this->accessDenied(), 401);
        }
    }

    //update user record
    public function updateUserDetails(Request $request)
    {

        $isValid = $this->checkForAuthorization($request->header('Authorization'));

        if ($isValid) {

            $user = User::where('phone', $request->phone)->first();

            if(!$user) {
                $update = User::whereEmail($request->email)->first();

                if($update) {
                    $update->phone = $request->phone;
                    $update->fullname = $request->fullname;
                    $update->save();

                    return response()->json($this->customResponse("OK", "update successful", $update));
                }

                return response()->json($this->customResponse("error", "user not found"));
            }

            return response()->json($this->customResponse("error", "phone number already found in our record"));

        } else {

            return response()->json($this->accessDenied(), 401);
        }
    }
}
