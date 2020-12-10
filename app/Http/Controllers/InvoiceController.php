<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\SchoolDetail;
use App\Feesbreakdown;
use App\Traits\Skooleo;

use Illuminate\Http\Request;
use App\Traits\PaymentGateway;
use Illuminate\Support\Facades\Auth;
use App\Events\PaymentConfirmationEvent;

class InvoiceController extends Controller
{
    use Skooleo; use PaymentGateway;

    private  $webSettings;

    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['invoiceStatus']]);

        $this->webSettings = \App\WebSettings::find(1);
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
            // $transactionFee = \App\WebSettings::find(1)->transaction_fee;

            $reference = $request->reference;
            $invoice = Invoice::where('invoice_reference', $reference)->first();

            if ($invoice) {
                $feebreakdown = Feesbreakdown::where('feesetup_id', $invoice->feesetup_id)->get(['description', 'amount']);
                $feesum = $feebreakdown->sum('amount');

                $data = [
                    "invoice_id" => $invoice->id,
                    "reference" => $invoice->invoice_reference,
                    "invoice_status" => $invoice->status,
                    "total" => $feesum,
                    "fee" => $this->webSettings->transaction_fee,
                    "grand_total" => ($feesum + $this->webSettings->transaction_fee),
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

                // if ($data != );

                return response()->json($this->customResponse("success", "Invoice generated", $data));
            } else {
                return response()->json($this->customResponse("failed", "Invoice not found", null));
            }

        } catch (\Exception $e) {
            return response()->json($this->customResponse("failed", $e->getMessage()), 500);
        }
    }

    public function singlePayment(Request $request) {

        $this->validate($request, [
            "invoice_id" => "required"
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        $grandTotal = ($invoice->amount + $this->webSettings->transaction_fee);
        $reference = $invoice->invoice_reference;

        return $this->invoicePayment($request, $reference, $grandTotal);
    }

    public function bulkPayment(Request $request) {

        $invoices = Invoice::where('user_id', Auth::user()->id)->where('status', 'UNPAID')->get(['amount', 'invoice_reference']);

        if (count($invoices)) {
            $grandTotal = ($invoices->sum('amount') + (count($invoices) * $this->webSettings->transaction_fee));
            $reference = $invoices->implode('invoice_reference', '_');

            return $this->invoicePayment($request, $reference, $grandTotal);
        }

        return response()->json($this->customResponse("failed", "All invoices have been paid for", null));

    }

    //direct user to payment checkout form
    private function invoicePayment($request, $reference, $grandTotal)
    {

        $payload = [
            'reference'     =>  $reference,
            'amount'        =>  $grandTotal,
            'email'         =>  Auth::user()->email,
            'user_phone'    =>  Auth::user()->phone,
            'user_name'     =>  Auth::user()->fullname,
            'callback'      =>  $request->root() . "/api/v1/payments/callback"
        ];

        $link = $request->root() . "/api/v1/payments/callback";
        return response()->json($this->customResponse("success", "Payment link", $link));

        //send to payment gateway to charge
        $paymentLink = $this->flutterwaveCheckoutForm($payload);
        // return $paymentLink;

        if ($paymentLink['status'] === 'success') {
            return response()->json($this->customResponse("success", "Payment link", $paymentLink['data']['link']));
        } else {
            return response()->json($this->customResponse("failed", $paymentLink['message'], null));
        }
    }

    /**
     * Verify payment and give value
     *
     * @return status
     */
    public function invoiceStatus(Request $request)
    {
        #http://127.0.0.1:8001/home/callback?status=successful&tx_ref=54257367&transaction_id=1480705

        // if ($request->status == "cancelled") {
        //     return view('confirmation', [
        //         'cancelled' => true,
        //         'email' => $this->webSettings->email,
        //         'phone' => $this->webSettings->phone
        //     ]);
        // }

        // $txRef = $request->tx_ref;
        // $transactionId = $request->transaction_id;

        // // create event here PaymentConfirmationEvent
        // event(new PaymentConfirmationEvent($txRef, $transactionId));

        return view('confirmation', [
            'cancelled' => false,
            'email' => $this->webSettings->email,
            'phone' => $this->webSettings->phone
        ]);

    }
}
