<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Traits\Skooleo;

use App\SchoolDetail;
use App\Feetype;
use App\Feesetup;
use App\Feesbreakdown;
use App\Session;
use App\User;

class SchoolController extends Controller
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

    // list all verified schools
    public function listVerifiedSchool(Request $request)
    {
        try {
            $schools = SchoolDetail::select('id', 'schoolname', 'schooladdress')->where([
                ['schoolname', 'LIKE', '%'. $request->query('school') .'%'],
                ['verifystatus', '=', 1]
            ])->take(5)->get();

            $data = [];

            foreach($schools as $school) {
                $data[] = array(
                    "id" => $school->id,
                    "text" => $school->schoolname." (".$school->schooladdress.")"
                );
            }

            return response()->json($this->customResponse("success", "Search result", $data));

            // if ($schools) {
            //     return response()->json($this->customResponse("success", "Search result", $schools));
            // }
            // return response()->json($this->customResponse("failed", "School not found."), 404);
        } catch (\Exception $e) {
            return response()->json($this->customResponse("failed", $e->getMessage()), 500);
        }
    }

    // get school details and fee types
    public function getSchoolAndFeeType(Request $request)
    {
        try {
            $school = SchoolDetail::findOrFail($request->schoolId);
            $feetypes = Feetype::where('school_detail_id', $request->schoolId)->where('del_status', 0)->get(['id', 'feename']);
            $session = Session::all('sessionname');

            $data = [
                'schoolid' => $school->id,
                'schoolname' => $school->schoolname,
                'schooladdress' => $school->schooladdress,
                'schoolemail' => $school->schoolemail,
                'schoolphone' => $school->schoolphone,
                'feetypes' => $feetypes,
                'session' => $session,
            ];

            return response()->json($this->customResponse("success", "Search result", $data));

        } catch (\Exception $e) {
            return response()->json($this->customResponse("failed", $e->getMessage()), 401);
        }
    }

    public function getSchoolAndFees(Request $request)
    {
        try {
            $feesetup = Feesetup::where([
                                    'school_detail_id' => $request->school_id,
                                    'feetype_id' => $request->feetype,
                                    'section' => $request->section,
                                    'session' => $request->session,
                                    'term' => $request->term,
                                    'class' => $request->class,
                                ])->first();

            if ($feesetup) {
                $totalFees = Feesbreakdown::where('feesetup_id', $feesetup->id)->sum('amount');
                $school = SchoolDetail::findOrFail($request->school_id);
                $feetype = Feetype::findOrFail($request->feetype);

                $data = [
                    "school_id" => $feesetup->school_detail_id,
                    "feesetup_id" => $feesetup->id,
                    "amount" => $totalFees,
                    "fee_name" => $feetype->feename,
                    "section" => $feesetup->section,
                    "session" => $feesetup->session,
                    "term" => $feesetup->term,
                    "class" => $feesetup->class,
                    "school_name" => $school->schoolname,
                    "school_address" => $school->schooladdress,
                ];

                return response()->json($this->customResponse("success", "Search result", $data));
            }

            return response()->json($this->customResponse("failed", "No result found for the search query"), 404);

        } catch (\Exception $e) {
            return response()->json($this->customResponse("failed", $e->getMessage()), 401);
        }
    }
}
