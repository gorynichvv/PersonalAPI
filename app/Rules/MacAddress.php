<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * This rule was taken in this repo
 * https://github.com/mattkingshott/axiom/blob/master/src/Rules/MacAddress.php
 */

class MacAddress implements Rule
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
        return preg_match('/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', $value) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Неверный формат MAC адреса.';
    }
}
