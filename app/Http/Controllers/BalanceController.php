<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\PaymentMethod;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\Transaction as MTransaction;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use Redirect;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;

class BalanceController extends Controller
{
    private $_api_context;
    public function __construct()
    {
        // $paypal_configuration = \Config::get('paypal');
        // $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_configuration['client_id'], $paypal_configuration['secret']));
        // $this->_api_context->setConfig($paypal_configuration['settings']);
    }
    public function addBalance()
    {
        $paymentmethod = PaymentMethod::orderBy('id', 'DESC')->get();
        return view('balance.customer-add-balance', [
            'paymentmethod' => $paymentmethod,
        ]);
    }

    // public function loadPayment(Request $request)
    // {
    //     if ($request->paymentmethod == '1') { // other method
    //         $view = 'balance.customer-other-method';
    //     } else if ($request->paymentmethod == '2') { //stripe
    //         $view = 'balance.customer-stripe-method';
    //     } else {
    //         return redirect()
    //             ->route('balance.add')->withInput();
    //     }
    //     return view($view, ['paymentmethod' => $request->paymentmethod]);
    // }

    public function insertBalance(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required',
            'paymentmethod' => 'required',
        ]);

        if ($request->paymentmethod == '1') {
            // 1 is for other paymentmenthods
            $this->validate($request, [
                'transaction_id' =>
                'required|unique:transactions,transactionid',
                'description' => 'required',
                'image' => 'required|image',
            ]);
            $response = $this->otherMethodPayment($request);
        }
        if ($request->paymentmethod == '2') {
            // 2 is for strip paymentmenthods
            $response = $this->stripMethodPayment($request);
        }

        if ($response) {
            return redirect()
                ->route('balance.add')
                ->with('success', 'Balance request is saved successfully');
        } else {
            return redirect()
                ->route('balance.add')
                ->with('error', 'Something went wrong');
        }
    }

    //
    // public function paypalMethodPaymentsdk(Request $request)
    // {
    //     $payer = new Payer();
    //     $payer->setPaymentMethod('paypal');

    //     $item_1 = new Item();

    //     $item_1->setName('Product 1')
    //         ->setCurrency('USD')
    //         ->setQuantity(1)
    //         ->setPrice(1);

    //     $item_list = new ItemList();
    //     $item_list->setItems(array($item_1));

    //     $amount = new Amount();
    //     $amount->setCurrency('USD')
    //         ->setTotal(1);

    //     $transaction = new Transaction();
    //     $transaction->setAmount($amount)
    //         ->setItemList($item_list)
    //         ->setDescription('Enter Your transaction description');

    //     $redirect_urls = new RedirectUrls();
    //     $redirect_urls->setReturnUrl(URL::route('paypal.status'))
    //         ->setCancelUrl(URL::route('paypal.status'));

    //     $payment = new Payment();
    //     $payment->setIntent('Sale')
    //         ->setPayer($payer)
    //         ->setRedirectUrls($redirect_urls)
    //         ->setTransactions(array($transaction));
    //     try {
    //         $payment->create($this->_api_context);
    //     } catch (\PayPal\Exception\PPConnectionException$ex) {
    //         if (\Config::get('app.debug')) {
    //             \Session::put('error', 'Connection timeout');
    //             return Redirect::route('paywithpaypal');
    //         } else {
    //             \Session::put('error', 'Some error occur, sorry for inconvenient');
    //             return Redirect::route('paywithpaypal');
    //         }
    //     }

    //     foreach ($payment->getLinks() as $link) {
    //         if ($link->getRel() == 'approval_url') {
    //             $redirect_url = $link->getHref();
    //             break;
    //         }
    //     }

    //     Session::put('paypal_payment_id', getUniqueInvoiceForTransaction('paypal'));

    //     if (isset($redirect_url)) {
    //         return Redirect::away($redirect_url);
    //     }

    //     \Session::put('error', 'Unknown error occurred');
    //     return Redirect::route('paywithpaypal');
    // }

    // public function getPayPalStatus(Request $request)
    // {
    //     $payment_id = Session::get('paypal_payment_id');

    //     Session::forget('paypal_payment_id');
    //     if (empty($request->input('PayerID')) || empty($request->input('token'))) {
    //         \Session::put('error', 'Payment failed');
    //         return Redirect::route('paywithpaypal');
    //     }
    //     $payment = Payment::get($payment_id, $this->_api_context);
    //     $execution = new PaymentExecution();
    //     $execution->setPayerId($request->input('PayerID'));
    //     $result = $payment->execute($execution, $this->_api_context);

    //     if ($result->getState() == 'approved') {
    //         \Session::put('success', 'Payment success !!');
    //         return Redirect::route('paywithpaypal');
    //     }

    //     \Session::put('error', 'Payment failed !!');
    //     return Redirect::route('paywithpaypal');

    // }

    // public function paypalMethodPayment($request)
    // {
    //     $apiContext = new ApiContext(
    //         new OAuthTokenCredential(
    //             config('paypal.client_id'),
    //             config('paypal.secret')
    //         ));
    //     $apiContext->setConfig(config('paypal.settings'));

    //     //payer by
    //     $payer = new Payer();
    //     $payer->setPaymentMethod('PAYPAL');

    //     // item
    //     $item1 = new Item();
    //     $item1->setName('Credit Added')
    //         ->setCurrency('USD')
    //         ->setQuantity(1)
    //         ->setSku(getUniqueInvoiceForTransaction('paypal'))
    //         ->setPrice($request->amount);

    //     $details = new Details();
    //     $details->setShipping(1.2)
    //         ->setTax(1.3)
    //         ->setSubtotal($request->amount + 1.2 + 1.3);

    //     $itemList = new ItemList();
    //     $itemList->setItems([$item1]);

    //     $amount = new Amount();
    //     $amount->setCurrency('USD')
    //         ->setTotal($request->amount);

    //     $transaction = new Transaction();
    //     $transaction->setAmount($amount)
    //         ->setItemList($itemList)
    //         ->setDescription("Credit added to" . env('APP_NAME'))
    //         ->setInvoiceNumber(getUniqueInvoiceForTransaction('paypal'));

    //     $redirect_urls = new RedirectUrls();
    //     $redirect_urls->setReturnUrl(route('paypal.status'))
    //         ->setCancelUrl(route('paypal.status'));

    //     $payment = new Payment();
    //     $payment->setIntent('Sale')
    //         ->setPayer($payer)
    //         ->setRedirectUrls($redirect_urls)
    //         ->setTransactions(array($transaction));
    //     try {

    //         $payment->create($apiContext);

    //         $paymentmethod = PaymentMethod::findorFail($request->paymentmethod);
    //         DB::beginTransaction();

    //         // ticket insert
    //         $ticket = new Ticket();
    //         $ticket->user_id = Auth::guard('web')->user()->id;
    //         $ticket->title = "Payment added with $paymentmethod->title method";
    //         $ticket->department_id = 1; // 1 for sales
    //         $ticket->priority_id = 3; // 1 for high
    //         $ticket->status = 0; // 0 opening, 1 closed
    //         $ticket->user_type = 1; //  0 admin , 1 user
    //         $responseticket = $ticket->save();
    //         $ticketid = $ticket->id;

    //         // ticket detail insert
    //         $message =
    //         'Hello Sales Team,</br>' .
    //         Auth::guard('web')->user()->name .
    //         ' has added $' .
    //         $request->amount .
    //         ' in his account by using ' . $paymentmethod->title . ' payment method Payment descriptions is given below. Please verify and approve this payment.</br><strong>Payment Description:<strong></br>Customer paid ' . $paymentmethod->title . ' method';
    //         $ticketdetail = new TicketDetail();
    //         $ticketdetail->ticket_id = $ticketid;
    //         $ticketdetail->from_id = Auth::guard('web')->user()->id;
    //         // $ticketdetail->to_id  = $ticketid;
    //         $ticketdetail->message = $message;
    //         $ticketdetail->user_type = '0';
    //         $resposeticketdetail = $ticketdetail->save();

    //         // account insert
    //         $accountresponse = Account::where(
    //             'user_id',
    //             Auth::guard('web')->user()->id
    //         )->first();
    //         if ($accountresponse != null) {
    //             $accountresponse->update([
    //                 'pendingamount' =>
    //                 $accountresponse->pendingamount + $request->amount,
    //             ]);
    //         } else {
    //             $account = new Account();
    //             $account->user_id = Auth::guard('web')->user()->id;
    //             $account->pendingamount = $request->amount;
    //             $account->save();
    //         }

    //         // transaction insert
    //         $transaction = new MTransaction();
    //         $transaction->ticket_id = $ticketid;
    //         $transaction->user_id = Auth::guard('web')->user()->id;
    //         $transaction->amount = $request->amount;
    //         $transaction->payment_methods_id = $request->paymentmethod;
    //         $transaction->transactionid = $request->stripeToken;
    //         // $transaction->description = trim($request->description);
    //         // $transaction->image = $fileName;
    //         $resposetransaction = $transaction->save();
    //         if (!($resposetransaction && $responseticket && $resposeticketdetail)) {
    //             DB::rollback();
    //             return false;
    //         }

    //     } catch (\PayPal\Exception\PPConnectionException$ex) {
    //         if (\Config::get('app.debug')) {
    //             // \Session::put('error', 'Connection timeout');
    //             // return Redirect::route('paywithpaypal');
    //             return redirect()->route('balance.add')->with('error', 'Connection timeout');

    //         } else {
    //             // \Session::put('error', 'Some error occur, sorry for inconvenient');
    //             // return Redirect::route('paywithpaypal');
    //             return redirect()->route('balance.add')->with('error', 'sorry for inconvenient');
    //         }
    //     }

    //     foreach ($payment->getLinks() as $link) {
    //         if ($link->getRel() == 'approval_url') {
    //             $redirect_url = $link->getHref();
    //             break;
    //         }
    //     }

    //     Session::put('paypal_payment_id', $payment->getId());

    //     if (isset($redirect_url)) {
    //         // dd($redirect_url);

    //         // return Redirect::away($redirect_url);
    //         // return Redirect::away($redirect_url);
    //         // return Redirect::to($redirect_url);
    //         redirect()->away($redirect_url);

    //     }

    //     // \Session::put('error', 'Unknown error occurred');
    //     // return Redirect::route('paywithpaypal');
    //     return redirect()->route('balance.add')->with('error', 'Unknown error occurred');
    // }

    //oldpaypal
    // public function papalMethodPayment(Request $request)
    // {
    //     $data = [];
    //     $data['items'] = [
    //         [
    //             'name' => 'codesolutionstuff.com',
    //             'price' => 100,
    //             'desc' => 'Description for codesolutionstuff.com',
    //             'qty' => 1,
    //         ],
    //     ];

    //     $data['invoice_id'] = 1;
    //     $data['invoice_description'] = "Order #{" . getUniqueInvoiceForTransaction('paypal') . "} Invoice";
    //     $data['return_url'] = route('paypal.success');
    //     $data['cancel_url'] = route('paypal.cancel');
    //     $data['total'] = 100;
    //     $provider = new PayPalClient;

    //     // Through facade. No need to import namespaces
    //     $provider = \PayPal::setProvider();

    //     $data = json_decode('{
    //         "intent": "CAPTURE",
    //         "purchase_units": [
    //         {
    //             "amount": {
    //             "currency_code": "USD",
    //             "value": "100.00"
    //             }
    //         }
    //         ]
    //     }', true);

    //     $order = $provider->createOrder($data);

    // }

    // public function paypalCancel()
    // {
    //     return redirect()
    //         ->route('balance.add')
    //         ->with('error', 'Your payment is canceled.');

    // }

    // public function success(Request $request)
    // {
    //     $provider = new ExpressCheckout;

    //     $response = $provider->getExpressCheckoutDetails($request->token);
    //     if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
    //         dd('Your payment was successfully. You can create success page here.');
    //     }
    //     dd('Something is wrong.');
    // }

    public function stripMethodPayment($request)
    {
        $payment = PaymentMethod::findorFail($request->paymentmethod);
        Stripe::setApiKey($payment->secretkey);
        $customer = Customer::create(array(
            'email' => Auth::guard('web')->user()->email,
            'source' => $request->stripeToken,
        ));
        $transactionid = getUniqueInvoiceForTransaction('stripe');
        Charge::create([
            'customer' => $customer->id,
            "amount" => $request->amount . '00',
            "currency" => "usd",
            'metadata' => ['transactionid' => $transactionid],
            "description" => "Credit added to " . env('APP_NAME'),
        ]);
        DB::beginTransaction();

        // ticket insert
        $ticket = new Ticket();
        $ticket->user_id = Auth::guard('web')->user()->id;
        $ticket->title = "Payment added with $payment->title method";
        $ticket->department_id = 1; // 1 for sales
        $ticket->priority_id = 3; // 1 for high
        $ticket->status = 0; // 0 opening, 1 closed
        $ticket->user_type = 1; //  0 admin , 1 user
        $responseticket = $ticket->save();
        $ticketid = $ticket->id;

        // ticket detail insert
        $message =
        'Hello Sales Team,</br>' .
        Auth::guard('web')->user()->name .
        ' has added $' .
        $request->amount .
        ' in his account by using ' . $payment->title . ' payment method with transaction id=' . $request->stripeToken . ' Payment descriptions is given below. Please verify and approve this payment.</br><strong>Payment Description:<strong></br>Customer paid ' . $payment->title . ' method';
        $ticketdetail = new TicketDetail();
        $ticketdetail->ticket_id = $ticketid;
        $ticketdetail->from_id = Auth::guard('web')->user()->id;
        // $ticketdetail->to_id  = $ticketid;
        $ticketdetail->message = $message;
        $ticketdetail->user_type = '0';
        $resposeticketdetail = $ticketdetail->save();

        // account insert
        $accountresponse = Account::where(
            'user_id',
            Auth::guard('web')->user()->id
        )->first();
        if ($accountresponse != null) {
            $accountresponse->update([
                'pendingamount' =>
                $accountresponse->pendingamount + $request->amount,
            ]);
        } else {
            $account = new Account();
            $account->user_id = Auth::guard('web')->user()->id;
            $account->pendingamount = $request->amount;
            $account->save();
        }

        // transaction insert
        $transaction = new MTransaction();
        $transaction->ticket_id = $ticketid;
        $transaction->user_id = Auth::guard('web')->user()->id;
        $transaction->amount = $request->amount;
        $transaction->payment_methods_id = $request->paymentmethod;
        $transaction->transactionid = $transactionid;
        $transaction->payment_methods_id = $request->paymentmethod;
        $transaction->nameoncard = $request->nameoncard;
        $transaction->email = $request->email;
        $transaction->country = $request->country;
        $transaction->company = $request->company;
        $transaction->address = $request->address;
        $transaction->website = $request->website;
        $transaction->phone = $request->phone;
        $transaction->description = 'To verify payment check transactionid in meta Data of your stripe transactionid = ' . $transactionid;

        // $transaction->image = $fileName;
        $resposetransaction = $transaction->save();

        if ($resposetransaction && $responseticket && $resposeticketdetail) {
            DB::commit();
            return true;
        } else {
            DB::rollback();
            return false;
        }

    }

    public function otherMethodPayment($request)
    {
        $fileName = null;
        if (!empty($request->file('image'))) {
            $fileName = time() . '.' . $request->file('image')->extension();
            $request
                ->file('image')
                ->move(public_path('uploads/payment'), $fileName);
        }
        DB::beginTransaction();

        $payment = PaymentMethod::findorFail($request->paymentmethod);

        // ticket insert
        $ticket = new Ticket();
        $ticket->user_id = Auth::guard('web')->user()->id;
        $ticket->title = "Payment added with $payment->title method";
        $ticket->department_id = 1; // 1 for sales
        $ticket->priority_id = 3; // 1 for high
        $ticket->status = 0; // 0 opening, 1 closed
        $ticket->user_type = 1; //  0 admin , 1 user
        $responseticket = $ticket->save();
        $ticketid = $ticket->id;

        // ticket detail insert
        $message =
        'Hello Sales Team,</br>' .
        Auth::guard('web')->user()->name .
        ' has added $' .
        $request->amount .
        ' in his account by using other payment method with transaction id=' .
        $request->transaction_id .
            ' Payment descriptions is given below. Please verify and approve this payment.</br><strong>Payment Description:<strong></br>Customer paid other method';
        $ticketdetail = new TicketDetail();
        $ticketdetail->ticket_id = $ticketid;
        $ticketdetail->from_id = Auth::guard('web')->user()->id;
        // $ticketdetail->to_id  = $ticketid;
        $ticketdetail->message = $message;
        $ticketdetail->user_type = '0';
        $resposeticketdetail = $ticketdetail->save();

        // account insert
        $accountresponse = Account::where(
            'user_id',
            Auth::guard('web')->user()->id
        )->first();
        if ($accountresponse != null) {
            $accountresponse->update([
                'pendingamount' =>
                $accountresponse->pendingamount + $request->amount,
            ]);
        } else {
            $account = new Account();
            $account->user_id = Auth::guard('web')->user()->id;
            $account->pendingamount = $request->amount;
            $account->save();
        }

        // transaction insert
        $transaction = new MTransaction();
        $transaction->ticket_id = $ticketid;
        $transaction->user_id = Auth::guard('web')->user()->id;
        $transaction->amount = $request->amount;
        $transaction->payment_methods_id = $request->paymentmethod;
        $transaction->transactionid = $request->transaction_id;
        $transaction->description = trim($request->description);
        $transaction->image = $fileName;
        $resposetransaction = $transaction->save();
        if ($resposetransaction && $responseticket && $resposeticketdetail) {
            DB::commit();
            // event(new \App\Events\Ticket('Hello Ali raza'));
            // broadcast(new EventsTicket('1'))->toOthers(); //sales ticket event
            return true;
        } else {
            DB::rollback();
            return false;
        }
    }

    public function listBalance()
    {
        $sales = DB::table('tickets')
            ->select(
                'users.id AS userid',
                'users.name AS username',
                'tickets.id AS ticketid',
                'tickets.title AS tickettitle',
                'tickets.status AS ticketstatus',
                'transactions.id AS transid',
                'tickets.created_at AS ticketcreated_at',
                'priorities.priority AS ticketpriority',
                'transactions.status AS transstatus',
                DB::raw(
                    '(SELECT COUNT(id) AS isseen from ticket_details WHERE is_seen = 0 AND ticket_id = ticketid AND user_type = 1) AS isseen'
                )
            )
        // ->join('ticket_details', 'ticket_details.ticket_id', '=', 'tickets.id')
            ->join('transactions', 'transactions.ticket_id', '=', 'tickets.id')
            ->join('priorities', 'priorities.id', '=', 'tickets.priority_id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->where('tickets.department_id', '1') // 1 is for sales
            ->orderBy('ticketid')
            ->where('transactions.user_id', Auth::guard('web')->user()->id)
            ->get();

        return view('balance.customer-list-balance', ['sales' => $sales]);
    }

    public function view($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticketdetail = TicketDetail::where('ticket_id', $ticket->id)
            ->orderBy('id', 'DESC')
            ->get();
        return view('balance.customer-ticket-detail', [
            'ticket' => $ticket,
            'ticketdetail' => $ticketdetail,
        ]);
    }

    public function transactionView($id)
    {
        $sales = MTransaction::select(
            '*',
            'transactions.id AS transactionsID',
            'payment_methods.title AS paymenttitle',
            'transactions.image AS transectionimage'
        )
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->join(
                'payment_methods',
                'payment_methods.id',
                '=',
                'transactions.user_id'
            )
            ->findOrFail($id);
        return view('balance.transaction-detail', ['sales' => $sales]);
    }

}
