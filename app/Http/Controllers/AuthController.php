<?php

namespace App\Http\Controllers;

use App\User;
use App\Traits\Skooleo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    use Skooleo;

    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'email_phone' => 'required|string',
            'password' => 'required|string',
        ]);

        try {

            // check if the app is under maintenance
            if (env('MAINTENANCE_MODE')) {

                // check allowed emails
                $allowedEmails = explode(",", env('ALLOWED_EMAILS'));

                if (!in_array($request->email_phone, $allowedEmails)) {
                    return response()->json($this->customResponse("failed", "The app is under maintenance! we will be back shortly."), 503);
                }

            }

            $login_type = filter_var($request->email_phone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

            // return $login_type;

            $credentials = [$login_type => $request->email_phone, 'password' => $request->password];

            if (!$token = Auth::attempt($credentials)) {
                return response()->json($this->customResponse("failed", "Unauthorized"), 401);
            }

            return $this->respondWithToken($token);

        } catch(JWTException $e) {

            return response()->json($this->customResponse("failed", "An error occured, please contact support.", null), 500);

        }
    }

    /**
     * Register
     */
    public function register(Request $request)
    {
        // check if the app is under maintenance
        if (env('MAINTENANCE_MODE')) return response()->json($this->customResponse("success", "The app is under maintenance! we will be back shortly."), 503);

        //validate incoming request
        $this->validate($request, [
            'fullname' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        try {

            $user = User::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ]);

            return response()->json($this->customResponse("success", "User created.", $user), 201);

        } catch (\Exception $e) {

            return response()->json($this->customResponse("failed", "User registration failed! Email or phone number exists in our record"), 409);
        }
    }
}
