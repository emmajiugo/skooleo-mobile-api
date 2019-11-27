<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\Skooleo;

use App\SchoolDetail;
use App\Feetype;

class SchoolController extends Controller
{
    use Skooleo;

    // list all verified schools
    public function listVerifiedSchool(Request $request)
    {
        $isValid = $this->checkForAuthorization($request->header('Authorization'));

        if ($isValid) {
            $schools = SchoolDetail::select('id', 'schoolname', 'schooladdress')->where('verifystatus', 1)->get();
            // $schools = SchoolDetail::select('id', 'schoolname', 'schooladdress')->where([
            //     ['schoolname', 'LIKE', '%'.$request->school.'%'],
            //     ['verifystatus', '=', 1]
            // ])->take(5)->get();

            if ($schools) {
                return response()->json($schools);
            }
            return [];
        } else {

            return response()->json($this->accessDenied(), 401);
        }
    }

    // get school details and fee types
    public function getSchoolAndFeeType(Request $request)
    {
        $isValid = $this->checkForAuthorization($request->header('Authorization'));

        if ($isValid) {
            $school = SchoolDetail::select('id', 'schoolname', 'schooladdress')
                                    ->where('schoolname', $request->school)
                                    ->first();

            if ($school) {
                $school->feetype;

                return response()->json($school);
            }
            return $this->customResponse("error", "School not found");

        } else {

            return response()->json($this->accessDenied(), 401);
        }
    }

    // get fees for school
    public function getSchoolAndFees(Request $request)
    {
        # code...
    }
}
