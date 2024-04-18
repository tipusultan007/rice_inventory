<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class PaidWithAccountIdRule implements Rule
{
    public function passes($attribute, $value)
    {
        $paid = request()->input('paid');
        $accountId = request()->input('account_id');

        // If paid is filled, account_id is required
        return !$paid || ($paid && $accountId);
    }

    public function message()
    {
        return 'If "paid" is filled, "account_id" is required.';
    }
}
