<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Admin_panel_settingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => 'required',
            'phones' => 'required',
            'address' => 'required',
            'email' => 'required',
            'after_miniute_calculate_delay' => 'required',
            'after_miniute_calculate_early_departure' => 'required',
            'after_miniute_quarterday' => 'required',
            'after_time_half_daycut' => 'required',
            'after_time_allday_daycut' => 'required',
            'monthly_vacation_balance' => 'required',
            'after_days_begin_vacation' => 'required',
            'first_balance_begin_vacation' => 'required',
            'sanctions_value_first_abcence' => 'required',
            'sanctions_value_second_abcence' => 'required',
            'sanctions_value_third_abcence' => 'required',
            'sanctions_value_fourth_abcence' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'company_name.required' => 'الرجاء ادخال اسم الشركة',
            'phones.required' => 'الرجاء ادخال رقم هاتف الشركة',
            'address.required' => 'الرجاء ادخال عنوان الشركة',
            'email.required' => 'الرجاء ادخال بريد الشركة',
            'after_miniute_calculate_delay.required' => 'الخانة مطلوبة',
            'after_miniute_calculate_early_departure.required' => 'الخانة مطلوبة',
            'after_miniute_quarterday.required' => 'الخانة مطلوبة',
            'after_time_half_daycut.required' => 'الخانة مطلوبة',
            'after_time_allday_daycut.required' => 'الخانة مطلوبة',
            'monthly_vacation_balance.required' => 'الخانة مطلوبة',
            'after_days_begin_vacation.required' => 'الخانة مطلوبة',
            'first_balance_begin_vacation.required' => 'الخانة مطلوبة',
            'sanctions_value_first_abcence.required' => 'الخانة مطلوبة',
            'sanctions_value_second_abcence.required' => 'الخانة مطلوبة',
            'sanctions_value_third_abcence.required' => 'الخانة مطلوبة',
            'sanctions_value_fourth_abcence.required' => 'الخانة مطلوبة',
        ];
    }

}
