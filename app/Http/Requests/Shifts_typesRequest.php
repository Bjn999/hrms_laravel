<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Shifts_typesRequest extends FormRequest
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
            //
            "type" => "required",
            "from_time" => "required",
            "to_time" => "required",
            "total_hour" => "required",
            
            "active" => "required",
        ];
    }

    public function messages()
    {
        return [
            "type.required" => "نوع الشفت مطلوب",
            "from_time.required" => "زمن بداية الشفت مطلوب",
            "to_time.required" => "زمن نهاية الشفت مطلوب",
            "total_hour.required" => "عدد ساعات العمل مطلوب",
            
            "active.required" => "حقل حالة التفعيل مطلوب",
        ];
    }
}
