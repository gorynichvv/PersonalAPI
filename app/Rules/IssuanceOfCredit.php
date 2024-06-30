<?php

namespace App\Rules;

use App\Services\UserService;
use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IssuanceOfCredit implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return UserService::isAllowedCredit();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = 'Вы уже воспользовались услугой';

        !(Auth::user()->prepay_day_traf === 0)
            ?: $message = 'Услуга будет доступна по окончании срока действия тарифа';

        return $message;
    }
}
