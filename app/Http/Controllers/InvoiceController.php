<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\Skooleo;

use App\Invoice;
use App\SchoolDetail;
use App\Feesbreakdown;

class InvoiceController extends Controller
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

    public function getInvoices()
    {
        try {
            $invoices = Invoice::where('user_id', Auth::user()->id)
                                ->orderBy('created_at', 'desc')
                                ->get(['invoice_reference', 'studentname', 'status', 'amount', 'class', 'term', 'session']);

            return response()->json($this->customResponse("success", "Invoice Lists", $invoices));

        } catch (\Exception $e) {
            return response()->json($this->customResponse("failed", $e->getMessage()), 500);
        }
    }

    public function postInvoice(Request $request)
    {
        $this->validate($request, [
            'studentname' => 'required|string'
        ]);

        try {
            //insert into invoice tbl
            $invoice = new Invoice;
            $invoice->invoice_reference = $this->generateTrxId();
            $invoice->school_detail_id = $request->schoolid;
            $invoice->feesetup_id = $request->feesetupid;
            $invoice->feetype_name = $request->feetype;
            $invoice->section = $request->section;
            $invoice->class = $request->studentclass;
            $invoice->session = $request->session;
            $invoice->term = $request->term;
            $invoice->studentname = $request->studentname;
            $invoice->user_id = Auth::user()->id;
            $invoice->payername = Auth::user()->fullname;
            $invoice->payeremail = Auth::user()->email;
            $invoice->payerphone = Auth::user()->phone;
            $invoice->amount = $request->amount;
            $invoice->save();

            $data = [
                "invoice_reference" => $invoice->invoice_reference
            ];

            return response()->json($this->customResponse("success", "Invoice generated", $data));

        } catch (\Exception $e) {
            return response()->json($this->customResponse("failed", $e->getMessage()), 500);
        }
    }

    public function getSingleInvoice(Request $request)
    {
        try {
            $reference = $request->reference;
            $invoice = Invoice::where('invoice_reference', $reference)->first();
            $feebreakdown = Feesbreakdown::where('feesetup_id', $invoice->feesetup_id)->get(['description', 'amount']);
            $feesum = $feebreakdown->sum('amount');

            $data = [
                "reference" => $invoice->invoice_reference,
                "invoice_status" => $invoice->status,
                "total" => $feesum,
                "fee" => 300,
                "grand_total" => ($feesum + 300),
                "school" => [
                    "name" => $invoice->school_detail->schoolname,
                    "address" => $invoice->school_detail->schooladdress,
                    "email" => $invoice->school_detail->schoolemail,
                    "phone" => $invoice->school_detail->schoolphone,
                ],
                "student" => [
                    "name" => $invoice->studentname,
                    "class" => $invoice->class,
                    "term" => $invoice->term,
                    "session" => $invoice->session,
                    "feetype" => $invoice->feetype_name,
                ],
                "fee_breakdown" => $feebreakdown,
            ];

            return response()->json($this->customResponse("success", "Invoice generated", $data));

        } catch (\Exception $e) {
            return response()->json($this->customResponse("failed", $e->getMessage()), 500);
        }
    }
}
