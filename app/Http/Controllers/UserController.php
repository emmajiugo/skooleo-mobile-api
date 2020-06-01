<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Traits\Skooleo;

use App\User;

class UserController extends Controller
{
    use Skooleo;

    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * Update user details
     *
     * @return Response
     */
    public function updateProfile(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'fullname' => 'required|string',
            'phone' => 'required|string',
        ]);

        try {

            $duplicatePhone = User::where('phone', $request->phone)->first();

            if(!$duplicatePhone) {

                $update = Auth::user();

                $update->phone = $request->phone;
                $update->fullname = $request->fullname;
                $update->save();

                return response()->json($this->customResponse("success", "User profile updated"));
            }

            return response()->json($this->customResponse("failed", "Phone number already exists in our record"));

        } catch (\Exception $e) {

            return response()->json($this->customResponse("failed", "User profile updated"), 409);
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        Auth::logout();

        return response()->json($this->customResponse("success", "Successfully logged out"));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }
}
