<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MacAddress;
use App\Rules\OnlyTrue;
use App\Rules\IssuanceOfCredit;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'credit' => [
                new IssuanceOfCredit,
                new OnlyTrue
            ],
            'device_1' => [
                Rule::unique('users', 'mac')
                    ->ignore('00:00:00:00:00:00', 'mac')
                    ->ignore(auth()->user()->mac, 'mac'),
                Rule::unique('users', 'mac2')
                    ->ignore('00:00:00:00:00:00', 'mac2'),
                Rule::unique('users', 'mac3')
                    ->ignore('00:00:00:00:00:00', 'mac3'),
                new MacAddress,
            ],
            'device_2' => [
                Rule::unique('users', 'mac')
                    ->ignore('00:00:00:00:00:00', 'mac'),
                Rule::unique('users', 'mac2')
                    ->ignore('00:00:00:00:00:00', 'mac2')
                    ->ignore(auth()->user()->mac2, 'mac2'),
                Rule::unique('users', 'mac3')
                    ->ignore('00:00:00:00:00:00', 'mac3'),
                new MacAddress,
            ],
            'device_3' => [
                Rule::unique('users', 'mac')
                    ->ignore('00:00:00:00:00:00', 'mac'),
                Rule::unique('users', 'mac2')
                    ->ignore('00:00:00:00:00:00', 'mac2'),
                Rule::unique('users', 'mac3')
                    ->ignore('00:00:00:00:00:00', 'mac3')
                    ->ignore(auth()->user()->mac3, 'mac3'),
                new MacAddress,
            ]
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'device_*.unique' => 'Этот MAC адрес уже используется.'
        ];
    }
}
