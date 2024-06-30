<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetConnectionRequest extends FormRequest
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
            'per_page' => [
                'numeric',
                'min:1',
                'max:100'
            ],
            'from_date' => [
                'date',
                'before_or_equal:to_date'
            ],
            'to_date' => [
                'date',
                'after_or_equal:from_date'
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
            'from_date.data' => 'Неверный формат даты!',
            'from_date.before_or_equal' => 'Дата должна быть меньше чем второе значение!',
            'to_date.data' => 'Неверный формат даты!',
            'to_date.after_or_equal' => 'Дата должна быть больше чем первое значение!',
            'per_page.numeric' => 'Укажите цифру от 1 до 100',
            'per_page.min' => 'Укажите цифру от 1 до 100',
            'per_page.max' => 'Укажите цифру от 1 до 100'
        ];
    }
}
