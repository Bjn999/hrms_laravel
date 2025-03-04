<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OccasionsRequest extends FormRequest
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
            'name' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'days_counter' => 'required|numeric',
            'active' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم المناسبة مطلوب',
            'from_date.required' => 'تاريخ بداية المناسبة مطلوب',
            'to_date.required' => 'تاريخ نهاية المناسبة مطلوب',
            // 'to_date.gt' => 'تاريخ نهاية المناسبة يجب ان يكون أكبر أو يساوي تاريخ بداية المناسبة',
            'days_counter.required' => 'عدد الأيام مطلوب',
            'days_counter.numeric' => 'عدد الأيام عدد الأيام يجب أن يكون رقم',
            'active.required' => 'حالة التفعيل مطلوب',
        ];
    }
}
