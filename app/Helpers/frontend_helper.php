<?php

if (!function_exists('getUserdetailDependsbyType')) {
    function getUserdetailDependsbyType($id, $usertype)
    {
        if ($usertype == '1') {
            $result = DB::table('admins')
                ->where('id', $id)
                ->first();
        } else {
            $result = DB::table('users')
                ->where('id', $id)
                ->first();
        }
        return $result;
    }
}

if (!function_exists('getUserCurrentBalance')) {
    function getUserCurrentBalance($id)
    {
        $result = DB::table('accounts')
            ->where('user_id', $id)
            ->first();
        $response = $result->addedamount ? $result->addedamount : 0;
        return $response;
    }
}

if (!function_exists('responseGenrate')) {
    function responseGenrate($type, $message)
    {
        $result = [
            'type' => $type,
            'message' => $message,
        ];
        return json_encode($result);
        exit();
    }
}

if (!function_exists('getPaymentPublicKeyByslug')) {
    function getPaymentPublicKeyByslug($slug)
    {
        $payment = DB::table('payment_methods')->where('slug', $slug)->first();
        return $payment->publickey;
    }
}

if (!function_exists('getUniqueInvoiceForTransaction')) {
    function getUniqueInvoiceForTransaction($type)
    {
        #Store Unique Transaction Number
        $unique_no = DB::table('transactions')->orderBy('id', 'DESC')->pluck('id')->first();
        if ($unique_no == null or $unique_no == "") {
            $unique_no = 1;
        } else {
            $unique_no = $unique_no + 1;
        }
        $unique_no = Str::upper($type) . $unique_no . str_pad($unique_no + 1, 8, "0", STR_PAD_LEFT);
        return $unique_no;
    }
}

if (!function_exists('getListCategory')) {
    function getListCategory()
    {
        $category = \DB::table('categories')->get();
        return $category;
    }
}

if (!function_exists('dateDiff')) {
    function dateDiff($start, $end)
    {
        $date1 = date_create(date("Y-m-d", strtotime($start)));
        $date2 = date_create(date("Y-m-d", strtotime($end)));
        $diff = date_diff($date1, $date2);
        return $diff->format("%a days");
    }
}

if (!function_exists('dateDiffdays')) {
    function dateDiffdays($start, $end)
    {
        $date1 = date_create(date("Y-m-d", strtotime($start)));
        $date2 = date_create(date("Y-m-d", strtotime($end)));
        $diff = date_diff($date1, $date2);
        return $diff->format("%a");
    }
}
