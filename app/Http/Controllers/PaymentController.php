<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function stripSuccess(Request $request)
    {
        Storage::put('stripsuccess.txt', $request);

    }
    public function stripCancel(Request $request)
    {
        Storage::put('stripcancel.txt', $request);
    }

    public function paypalCancel()
    {
        return redirect()
            ->route('balance.add')
            ->with('error', 'Your payment is canceled.');

    }

    public function paypalSuccess(Request $request)
    {
        Storage::put('paypalsuccess.txt', $request);
        // Storage::put('paypalitem_number.txt', $request->item_number);

        $itemNo = $_REQUEST['item_number'];
        $itemTransaction = $_REQUEST['tx']; // Paypal transaction ID
        $itemPrice = $_REQUEST['amt']; // Paypal received amount
        $itemCurrency = $_REQUEST['cc']; // Paypal received currency type

        $price = '20.00';
        $currency = 'USD';

        if ($itemPrice == $price && $itemCurrency == $currency) {
            echo "Payment Successful";
        } else {
            echo "Payment Failed";
        }
    }

    public function getPaymentStatus(Request $request)
    {
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.secret')
            ));
        $apiContext->setConfig(config('paypal.settings'));

        $payment_id = Session::get('paypal_payment_id');

        Session::forget('paypal_payment_id');
        if (empty($request->input('PayerID')) || empty($request->input('token'))) {
            // \Session::put('error','Payment failed');
            // return Redirect::route('paywithpaypal');
            return redirect()->route('balance.add')->with('error', 'Payment failed');

        }
        $payment = Payment::get($payment_id, $apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));
        $result = $payment->execute($execution, $apiContext);

        if ($result->getState() == 'approved') {
            // \Session::put('success','Balance request is saved successfully');
            // return Redirect::route('paywithpaypal');
            return redirect()->route('balance.add')->with('success', 'Balance request is saved successfullysss');

        }
        // \Session::put('error','Payment failed !!');
        // return Redirect::route('paywithpaypal');
        return redirect()->route('balance.add')->with('error', 'Payment failed !!');
    }

}
