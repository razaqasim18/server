<?php

namespace App\Rules;

use App\Models\Account;
use Auth;
use Illuminate\Contracts\Validation\Rule;

class userBalanceCheck implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $account = Account::where('user_id', Auth::user()->id)->first();
        if ($account) {
            $response = ($account->addedamount < $value) ? false : true;
        } else {
            $response = false;
        }
        return $response;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You have insufficent balance.';
    }
}
