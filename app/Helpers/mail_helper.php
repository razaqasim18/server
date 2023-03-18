<?php

use Illuminate\Support\Facades\Mail;

if (!function_exists('orderServerMail')) {
    function orderServerMail($userid)
    {
        $result = DB::table('users')
            ->where('id', $userid)
            ->first();
        $data['email'] = $result->email;
        $data['name'] = $result->name;
        Mail::send('email.order_server_mail', $data, function ($message) use ($data) {
            $message->to($data['email'], $data['name']);
            $message->subject('Order Server');
            $message->from(env('MAIL_FROM_ADDRESS'), env('APP_MAIL_NAME'));
        });
    }
}

if (!function_exists('approvePaymentMail')) {
    function approvePaymentMail($userid, $transid)
    {
        $result = DB::table('users')
            ->where('id', $userid)
            ->first();

        $transData = DB::table('transactions')
            ->where('id', $transid)
            ->first();

        $paymentData = DB::table('payment_methods')
            ->where('id', $transData->payment_methods_id)
            ->first();

        $data['email'] = $result->email;
        $data['name'] = $result->name;
        $data['amount'] = $transData->amount;
        $data['payment'] = $paymentData->slug;

        Mail::send('email.approve_payment_mail', $data, function ($message) use ($data) {
            $message->to($data['email'], $data['name']);
            $message->subject('Payment Approval');
            $message->from(env('MAIL_FROM_ADDRESS'), env('APP_MAIL_NAME'));
        });
    }
}

if (!function_exists('installServerMail')) {
    function installServerMail($serverid)
    {
        $server = DB::table('servers')
            ->where('id', $serverid)
            ->first();

        $result = DB::table('users')
            ->where('id', $server->user_id)
            ->first();

        $data['server'] = $server;
        $data['name'] = $result->name;
        $data['email'] = $result->email;

        Mail::send('email.install_server_mail', $data, function ($message) use ($data) {
            $message->to($data['email'], $data['name']);
            $message->subject('Server Installion');
            $message->from(env('MAIL_FROM_ADDRESS'), env('APP_MAIL_NAME'));
        });
    }
}

if (!function_exists('userregisterMail')) {
    function userregisterMail($name, $email, $password)
    {
        $data['name'] = $name;
        $data['email'] = $email;
        $data['password'] = $password;
        Mail::send('email.user_resgister_mail', $data, function ($message) use ($data) {
            $message->to($data['email'], $data['name']);
            $message->subject('User Registration');
            $message->from(env('MAIL_FROM_ADDRESS'), env('APP_MAIL_NAME'));
        });
    }
}
