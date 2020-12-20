<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Traits\Skooleo;

use  App\User;

class PasswordController extends Controller
{
    use Skooleo;

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['forgotPassword']]);
    }


    public function forgotPassword(Request $request)
    {
        try {
            // check of email exists in the  db
            $user = User::whereEmail($request->email_or_phone)->orWhere('phone', $request->email_or_phone)->first();

            if($user) {
                // reset user password to the registered phone number
                $user->password = Hash::make($user->phone);
                $user->save();

                return response()->json($this->customResponse("success", "Your password have been reset to your registered phone number. Please login and change your password"), 200);
            }

        } catch (\Exception $e) {
            return response()->json($this->customResponse("failed", "An error occurred, please contact us."), 417);
        }

        return response()->json($this->customResponse("failed", "Email not  found in our record"), 404);
    }


    public function changePassword(Request $request)
    {
        try {
            $userId = Auth::user()->id;

            $user =  User::findOrFail($userId);

            if ($user){

                if ($request->new_password !== $request->confirm_new_password) return response()->json($this->customResponse("error", "Passwords do not match"), 407);

                // update the password with new password
                $user->password = Hash::make($request->new_password);
                $user->save();

                return response()->json($this->customResponse("success", "password changed", null), 200);
            }

            return response()->json($this->customResponse("failed", "Email not  found in our record"), 404);

        } catch (\Exception $e) {
            return response()->json($this->customResponse("failed", "Email not  found in our record"), 404);
        }



    }
}
