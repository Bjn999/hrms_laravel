<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeesRequest extends FormRequest
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
            'emp_name' => 'required',
            'emp_gender' => 'required',
            'branch_id' => 'required',
            'emp_national_identity' => 'required',
            'emp_end_identity_date' => 'required',
            'emp_identity_place' => 'required',
            // 'blood_group_id' => 'required',
            'emp_nationality_id' => 'required',
            'emp_lang_id' => 'required',
            'emp_social_status_id' => 'required',
            'religion_id' => 'required',
            'country_id' => 'required',
            // 'governorate_id' => 'required',
            // 'city_id' => 'required',
            'staies_address' => 'required',
            'emp_home_tel' => 'required',
            'emp_work_tel' => 'required',
            // Required in case military id has value
            'emp_military_date_from' => 'required_if:emp_military_id,1',
            'emp_military_date_to' => 'required_if:emp_military_id,1',
            'emp_military_weapon' => 'required_if:emp_military_id,1',
            'exemption_date' => 'required_if:emp_military_id,2',
            'exemption_reason' => 'required_if:emp_military_id,2',
            'postponement_reason' => 'required_if:emp_military_id,3',
            // Required in case driving license with YES
            'driving_license_num' => 'required_if:does_has_driving_license,1',
            'driving_license_type_id' => 'required_if:does_has_driving_license,1',
            // Required in case has relatives with YES
            'relatives_details' => 'required_if:has_relatives,1',
            // Required in case has disabilities processes with YES
            'disabilities_processes' => 'required_if:is_disabilities_processes,1',
            
            'emp_start_date' => 'required',
            'functional_status' => 'required',
            'emp_departments_id' => 'required',
            'emp_job_id' => 'required',
            'is_has_fixed_shift' => 'required',
            // Required in case has fixed shift with YES or NO
            'shift_type_id' => 'required_if:is_has_fixed_shift,1',
            'daily_work_hour' => 'required_if:is_has_fixed_shift,0',
            ////
            'emp_sal' => 'required',
            'sal_cash_or_visa' => 'required',
            // Required in case has sal_cash_or_visa with Visa/Cash choice
            'bank_number_account' => 'required_if:sal_cash_or_visa,2',
            'motivation' => 'required_if:motivation_type,1',
            // Required in case has is_social_insurance with YES
            'social_insurance_number' => 'required_if:is_social_insurance,1',
            'social_insurance_cut_monthly' => 'required_if:is_social_insurance,1',
            // Required in case has is_medical_insurance with YES
            'medical_insurance_number' => 'required_if:is_medical_insurance,1',
            'medical_insurance_cut_monthly' => 'required_if:is_medical_insurance,1',
            ////

        ];
    }

    public function messages()
    {
        return [
            'emp_name.required' => 'اسم الموظف/ـة مطلوب',
            'emp_gender.required' => 'نوع جنس الموظف/ـة مطلوب',
            'branch_id.required' => 'الفرع المتواجد فيه الموظف/ـة مطلوب',
            'emp_national_identity.required' => 'رقم هوية الموظف/ـة مطلوب',
            'emp_end_identity_date.required' => 'تاريخ انتهاء هوية الموظف/ـة مطلوب',
            'emp_identity_place.required' => 'مكان اصدار هوية الموظف/ـة مطلوب',
            // 'blood_group_id.required' => 'فصيلة دم الموظف/ـة مطلوب',
            'emp_nationality_id.required' => 'جنسية الموظف/ـة مطلوب',
            'emp_lang_id.required' => 'الغة الأم للموظف/ـة مطلوب',
            'emp_social_status_id.required' => 'الحالة الاجتماعية للموظف/ـة مطلوب',
            'religion_id.required' => 'ديانة الموظف/ـة مطلوب',
            'country_id.required' => 'دولة الموظف/ـة مطلوبة',
            // 'governorate_id.required' => 'المحافظة الموظف/ـة مطلوبة',
            // 'city_id.required' => 'المدينة الموظف/ـة مطلوبة',
            'staies_address.required' => 'مكان اقامة الموظف/ـة مطلوبة',
            'emp_home_tel.required' => 'رقم هاتف المنزل للموظف/ـة مطلوب',
            'emp_work_tel.required' => 'رقم هاتف العمل للموظف/ـة مطلوب',
            'emp_military_date_from.required_if' => 'تاريخ بداية الخدمة مطلوبة',
            'emp_military_date_to.required_if' => 'تاريخ نهاية الخدمة مطلوبة',
            'emp_military_weapon.required_if' => 'سلاح الخدمة مطلوبة',
            'exemption_date.required_if' => 'تاريخ الإعفاء مطلوب',
            'exemption_reason.required_if' => 'سبب الإعفاء مطلوب',
            'postponement_reason.required_if' => 'سبب التأجيل مطلوب',
            'driving_license_num.required_if' => 'رقم رخصة القيادة مطلوب',
            'driving_license_type_id.required_if' => 'نوع رخصة القيادة مطلوب',
            'relatives_details.required_if' => 'تفاصيل الأقارب مطلوب',
            'disabilities_processes.required_if' => 'تفاصيل الإعاقة/العملية مطلوبة',

            'emp_start_date.required' => 'تاريخ تعيين الموظف/ـة مطلوب',
            'functional_status.required' => 'الحالة الوظيفية للموظف/ـة مطلوبة',
            'emp_departments_id.required' => 'الإدارة التي يعمل تحتها الموظف/ـة مطلوبة',
            'emp_job_id.required' => 'عنوان وظيفة الموظف/ـة مطلوب',
            'is_has_fixed_shift.required' => 'يجب تحدد نوع الشفت للموظف/ـة',
            'shift_type_id.required_if' => 'يجب تحدد شفت الموظف/ـة',
            'daily_work_hour.required_if' => 'يجب تحدد عدد الساعات اليومية للموظف/ـة',
            'emp_sal.required' => 'مبلغ راتب الموظف/ـة مطلوب',
            'sal_cash_or_visa.required' => 'طريقة صرف الراتب للموظف/ـة مطلوب',
            'bank_number_account.required_if' => 'رقم الحساب البنكي للموظف/ـة مطلوب',
            'motivation.required_if' => 'قيمة الحافز للموظف/ـة مطلوب',
            'social_insurance_number.required_if' => 'رقم التأمين الاجتماعي مطلوب',
            'social_insurance_cut_monthly.required_if' => 'المبلغ المستقطع شهرياً للتأمين الاجتماعي مطلوبة',
            'medical_insurance_number.required_if' => 'رقم التأمين الطبي مطلوب',
            'medical_insurance_cut_monthly.required_if' => 'المبلغ المستقطع شهرياً للتأمين الطبي مطلوبة',

        ];
    }
}
