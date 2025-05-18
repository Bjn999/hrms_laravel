<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeesRequest;
use App\Models\Blood_Group;
use App\Models\Branche;
use App\Models\Center;
use App\Models\Country;
use App\Models\Department;
use App\Models\Driving_license_type;
use App\Models\Employee;
use App\Models\Employee_File;
use App\Models\Governorate;
use App\Models\Jobs_categories;
use App\Models\Language;
use App\Models\Military_Status;
use App\Models\Nationality;
use App\Models\Qualification;
use App\Models\Religion;
use App\Models\Resignation;
use App\Models\shifts_type;
use App\Models\Social_Status_Type;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class EmployeesController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Employee(), array('*'), array('com_code' => $com_code), "id", "DESC", P_C);

        $other['branches'] = get_cols_where(new Branche(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['departments'] = get_cols_where(new Department(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['jobs'] = get_cols_where(new Jobs_categories(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));

        return view('admin.employees.index', ['data' => $data, 'other' => $other]);
    }

    public function create()
    {
        $com_code = auth()->user()->com_code;
        $data['branches'] = get_cols_where(new Branche(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['departments'] = get_cols_where(new Department(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['jobs'] = get_cols_where(new Jobs_categories(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['qualifications'] = get_cols_where(new Qualification(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['blood_groups'] = get_cols_where(new Blood_Group(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['nationalities'] = get_cols_where(new Nationality(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'ASC');
        $data['languages'] = get_cols_where(new Language(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['social_status'] = get_cols_where(new Social_Status_Type(), array('id', 'name'), array('active' => 1), 'id', 'ASC');
        $data['religions'] = get_cols_where(new Religion(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['countries'] = get_cols_where(new Country(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['governorates'] = get_cols_where(new Governorate(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['cities'] = get_cols_where(new Center(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['military_status'] = get_cols_where(new Military_Status(), array('id', 'name'), array('active' => 1), 'id', 'ASC');
        $data['driving_license_type'] = get_cols_where(new Driving_license_type(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['shifts_type'] = get_cols_where(new shifts_type(), array('id', 'type', 'from_time', 'to_time', 'total_hour'), array('com_code' => $com_code, 'active' => 1), 'id', 'ASC');
        $data['resignations'] = get_cols_where(new Resignation(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'ASC');

        return view('admin.employees.create', ['data' => $data]);
    }

    public function store(EmployeesRequest $request)
    {

        try {
            $com_code = auth()->user()->com_code;

            // تأكد من ان اسم الموظف لا يتكرر ابداً
            $checkExist = get_cols_where_row(new Employee(), array('id'), array('emp_name' => $request->emp_name, 'com_code' => $com_code));
            if (!empty($checkExist)) {
                return redirect()->back()->with(['error' => 'عفواً اسم الموظف مسجل من قبل'])->withInput();
            }

            // تأكد من ان كود بصمة الموظف لا يتكرر ابداً
            // $checkExist_zketo_code = get_cols_where_row(new Employee(), array('id'), array('zketo_code' => $request->zketo_code, 'com_code' => $com_code));
            $checkExist_zketo_code = Employee::select('id')->where(['zketo_code' => $request->zketo_code, 'com_code' => $com_code])->where('zketo_code', '!=', '')->first();
            if (!empty($checkExist_zketo_code)) {
                return redirect()->back()->with(['error' => 'عفواً كود بصمة الموظف مسجل من قبل'])->withInput();
            }

            // نجيب كود الخاص لاخر موضف تم اضافته في جدول الموضفين 
            $last_employee = get_cols_where_row_orderby(new Employee(), array('employee_code'), array('com_code' => $com_code), 'employee_code', 'DESC');

            DB::beginTransaction();

            // قيمة كود الموظف حسب اخر موضف واذا لا يوجد موضف ياخذ قيمة 1
            if (!empty($last_employee)) {
                $dataToInsert['employee_code'] = $last_employee['employee_code'] + 1;
            } else {
                $dataToInsert['employee_code'] = 1;
            }

            ////////////// البيانات الشخصية

            $dataToInsert['zketo_code'] = $request->zketo_code;
            $dataToInsert['emp_name'] = $request->emp_name;
            $dataToInsert['emp_gender'] = $request->emp_gender;
            $dataToInsert['branch_id'] = $request->branch_id;
            $dataToInsert['qualifications_id'] = $request->qualifications_id;
            $dataToInsert['qualifications_year'] = $request->qualifications_year;
            $dataToInsert['graduation_estimate'] = $request->graduation_estimate;
            $dataToInsert['graduation_specialization'] = $request->graduation_specialization;
            $dataToInsert['emp_email'] = $request->emp_email;
            $dataToInsert['emp_birth_date'] = $request->emp_birth_date;
            $dataToInsert['emp_national_identity'] = $request->emp_national_identity;
            $dataToInsert['emp_end_identity_date'] = $request->emp_end_identity_date;
            $dataToInsert['emp_identity_place'] = $request->emp_identity_place;
            $dataToInsert['blood_group_id'] = $request->blood_group_id;
            $dataToInsert['emp_nationality_id'] = $request->emp_nationality_id;
            $dataToInsert['emp_lang_id'] = $request->emp_lang_id;
            $dataToInsert['emp_social_status_id'] = $request->emp_social_status_id;
            $dataToInsert['children_number'] = $request->children_number;
            $dataToInsert['religion_id'] = $request->religion_id;
            $dataToInsert['country_id'] = $request->country_id;
            $dataToInsert['governorate_id'] = $request->governorate_id;
            $dataToInsert['city_id'] = $request->city_id;
            $dataToInsert['staies_address'] = $request->staies_address;
            $dataToInsert['emp_home_tel'] = $request->emp_home_tel;
            $dataToInsert['emp_work_tel'] = $request->emp_work_tel;
            $dataToInsert['emp_military_id'] = $request->emp_military_id;
            $dataToInsert['emp_military_date_from'] = $request->emp_military_date_from;
            $dataToInsert['emp_military_date_to'] = $request->emp_military_date_to;
            $dataToInsert['emp_military_weapon'] = $request->emp_military_weapon;
            $dataToInsert['exemption_date'] = $request->exemption_date;
            $dataToInsert['exemption_reason'] = $request->exemption_reason;
            $dataToInsert['postponement_reason'] = $request->postponement_reason;
            $dataToInsert['does_has_driving_license'] = $request->does_has_driving_license;
            $dataToInsert['driving_license_num'] = $request->driving_license_num;
            $dataToInsert['driving_license_type_id'] = $request->driving_license_type_id;
            $dataToInsert['has_relatives'] = $request->has_relatives;
            $dataToInsert['relatives_details'] = $request->relatives_details;
            $dataToInsert['is_disabilities_processes'] = $request->is_disabilities_processes;
            $dataToInsert['disabilities_processes'] = $request->disabilities_processes;
            $dataToInsert['notes'] = $request->notes;

            ////////////// البيانات الوظيفية

            $dataToInsert['emp_start_date'] = $request->emp_start_date;
            $dataToInsert['functional_status'] = $request->functional_status;
            $dataToInsert['emp_departments_id'] = $request->emp_departments_id;
            $dataToInsert['emp_job_id'] = $request->emp_job_id;
            $dataToInsert['does_has_attendance'] = $request->does_has_attendance;
            $dataToInsert['is_has_fixed_shift'] = $request->is_has_fixed_shift;
            $dataToInsert['shift_type_id'] = $request->shift_type_id;
            $dataToInsert['daily_work_hour'] = $request->daily_work_hour;
            $dataToInsert['emp_sal'] = $request->emp_sal;
            if (!empty($request->emp_sal > 0)) {
                $dataToInsert['day_price'] = $request->day_price;
            }
            $dataToInsert['sal_cash_or_visa'] = $request->sal_cash_or_visa;
            $dataToInsert['bank_number_account'] = $request->bank_number_account;
            $dataToInsert['motivation_type'] = $request->motivation_type;
            $dataToInsert['motivation'] = $request->motivation;
            $dataToInsert['is_social_insurance'] = $request->is_social_insurance;
            $dataToInsert['social_insurance_cut_monthly'] = $request->social_insurance_cut_monthly;
            $dataToInsert['social_insurance_number'] = $request->social_insurance_number;
            $dataToInsert['is_medical_insurance'] = $request->is_medical_insurance;
            $dataToInsert['medical_insurance_cut_monthly'] = $request->medical_insurance_cut_monthly;
            $dataToInsert['medical_insurance_number'] = $request->medical_insurance_number;
            $dataToInsert['is_active_for_vaccation'] = $request->is_active_for_vaccation;
            $dataToInsert['urgent_person_details'] = $request->urgent_person_details;

            ////////////// البيانات الإضافية

            $dataToInsert['emp_cafel'] = $request->emp_cafel;
            $dataToInsert['emp_pasport_no'] = $request->emp_pasport_no;
            $dataToInsert['emp_pasport_place'] = $request->emp_pasport_place;
            $dataToInsert['emp_passport_exp'] = $request->emp_passport_exp;
            $dataToInsert['home_address'] = $request->home_address;
            $dataToInsert['resignation_id'] = $request->resignation_id;
            $dataToInsert['resignation_date'] = $request->resignation_date;
            $dataToInsert['resignation_cause'] = $request->resignation_cause;
            //// Uploading The photo of the employee 
            if ($request->has('emp_photo')) {
                $request->validate([
                    'emp_photo' => 'required|mimes:png,jpg,jpeg|max:2000'
                ]);
                $file_path = uploadImage('assets/admin/uploads', $request->emp_photo);
                $dataToInsert['emp_photo'] = $file_path;
            }
            //// Uploading The CV of the employee 
            if ($request->has('emp_cv')) {
                $request->validate([
                    'emp_cv' => 'required|mimes:png,jpg,jpeg,doc,docs,pdf|max:2000'
                ]);
                $file_path = uploadImage('assets/admin/uploads', $request->emp_cv);
                $dataToInsert['emp_cv'] = $file_path;
            }
            $dataToInsert['date'] = date('Y-m-d');
            $dataToInsert['does_has_fixed_allowance'] = $request->does_has_fixed_allowance;
            // $dataToInsert['is_done_vaccation_formula'] = $request->is_done_vaccation_formula;
            $dataToInsert['is_sensitive_manager_data'] = $request->is_sensitive_manager_data;
            $dataToInsert['com_code'] = $com_code;
            $dataToInsert['added_by'] = auth()->user()->id;

            insert(new Employee(), $dataToInsert);

            DB::commit();

            return redirect()->route('employees.index')->with(['success' => 'تم اضافة الموضف بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما ' . $ex->getMessage()])->withInput();
        }
    }

    public function ajax_getGovernorate(Request $request)
    {
        if ($request->ajax()) {
            $country_id = $request->country_id;
            $com_code = auth()->user()->com_code;

            $data['governorates'] = get_cols_where(new Governorate(), array('id', 'name'), array('com_code' => $com_code, 'country_id' => $country_id, 'active' => '1'));

            return view('admin.employees.ajax_getGovernorate', ['data' => $data]);
        }
    }

    public function ajax_getCity(Request $request)
    {
        if ($request->ajax()) {
            $governorate_id = $request->governorate_id;
            $country_id = $request->country_id;
            $com_code = auth()->user()->com_code;

            $data['cities'] = get_cols_where(new Center(), array('id', 'name'), array('com_code' => $com_code, 'governorate_id' => $governorate_id, 'active' => '1'));

            return view('admin.employees.ajax_getCity', ['data' => $data]);
        }
    }

    public function edit($id)
    {
        $com_code = auth()->user()->com_code;

        $data = get_cols_where_row(new Employee(), array('*'), array('id' => $id, 'com_code' => $com_code));

        if (empty($data)) {
            return redirect()->route('employees.index')->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
        }

        $other['branches'] = get_cols_where(new Branche(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['departments'] = get_cols_where(new Department(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['jobs'] = get_cols_where(new Jobs_categories(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['qualifications'] = get_cols_where(new Qualification(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['blood_groups'] = get_cols_where(new Blood_Group(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['nationalities'] = get_cols_where(new Nationality(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['languages'] = get_cols_where(new Language(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['social_status'] = get_cols_where(new Social_Status_Type(), array('id', 'name'), array('active' => 1), 'id', 'ASC');
        $other['religions'] = get_cols_where(new Religion(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['countries'] = get_cols_where(new Country(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['governorates'] = get_cols_where(new Governorate(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['cities'] = get_cols_where(new Center(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['military_status'] = get_cols_where(new Military_Status(), array('id', 'name'), array('active' => 1), 'id', 'ASC');
        $other['driving_license_type'] = get_cols_where(new Driving_license_type(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $other['shifts_type'] = get_cols_where(new shifts_type(), array('id', 'type', 'from_time', 'to_time', 'total_hour'), array('com_code' => $com_code, 'active' => 1), 'id', 'ASC');
        $other['resignations'] = get_cols_where(new Resignation(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'ASC');

        return view('admin.employees.edit', ['data' => $data, 'other' => $other]);
    }

    public function update(EmployeesRequest $request, $id)
    {

        try {
            $com_code = auth()->user()->com_code;

            // تأكد من امكانية الوصول للبيانات
            $data = get_cols_where_row(new Employee(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('employees.index')->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            // تأكد من ان اسم الموظف لا يتكرر ابداً
            $checkExist = Employee::select('id')->where(['emp_name' => $request->emp_name, 'com_code' => $com_code])->where('id', '!=', $id)->first();
            if (!empty($checkExist)) {
                return redirect()->back()->with(['error' => 'عفواً اسم الموظف مسجل من قبل'])->withInput();
            }

            // تأكد من ان كود بصمة الموظف لا يتكرر ابداً
            // $checkExist_zketo_code = Employee::select('id')->where(['zketo_code' => $request->zketo_code, 'com_code' => $com_code])->where('id', '!=', $id)->first();
            $checkExist_zketo_code = Employee::select('id')->where(['zketo_code' => $request->zketo_code, 'com_code' => $com_code])->where('id', '!=', $id)->where('zketo_code', '!=', '')->first();
            if (!empty($checkExist_zketo_code)) {
                return redirect()->back()->with(['error' => 'عفواً كود بصمة الموظف مسجل من قبل'])->withInput();
            }

            DB::beginTransaction();

            ////////////// البيانات الشخصية

            $dataToUpdate['zketo_code'] = $request->zketo_code;
            $dataToUpdate['emp_name'] = $request->emp_name;
            $dataToUpdate['emp_gender'] = $request->emp_gender;
            $dataToUpdate['branch_id'] = $request->branch_id;
            $dataToUpdate['qualifications_id'] = $request->qualifications_id;
            $dataToUpdate['qualifications_year'] = $request->qualifications_year;
            $dataToUpdate['graduation_estimate'] = $request->graduation_estimate;
            $dataToUpdate['graduation_specialization'] = $request->graduation_specialization;
            $dataToUpdate['emp_email'] = $request->emp_email;
            $dataToUpdate['emp_birth_date'] = $request->emp_birth_date;
            $dataToUpdate['emp_national_identity'] = $request->emp_national_identity;
            $dataToUpdate['emp_end_identity_date'] = $request->emp_end_identity_date;
            $dataToUpdate['emp_identity_place'] = $request->emp_identity_place;
            $dataToUpdate['blood_group_id'] = $request->blood_group_id;
            $dataToUpdate['emp_nationality_id'] = $request->emp_nationality_id;
            $dataToUpdate['emp_lang_id'] = $request->emp_lang_id;
            $dataToUpdate['emp_social_status_id'] = $request->emp_social_status_id;
            $dataToUpdate['children_number'] = $request->children_number;
            $dataToUpdate['religion_id'] = $request->religion_id;
            $dataToUpdate['country_id'] = $request->country_id;
            $dataToUpdate['governorate_id'] = $request->governorate_id;
            $dataToUpdate['city_id'] = $request->city_id;
            $dataToUpdate['staies_address'] = $request->staies_address;
            $dataToUpdate['emp_home_tel'] = $request->emp_home_tel;
            $dataToUpdate['emp_work_tel'] = $request->emp_work_tel;
            $dataToUpdate['emp_military_id'] = $request->emp_military_id;
            $dataToUpdate['emp_military_date_from'] = $request->emp_military_date_from;
            $dataToUpdate['emp_military_date_to'] = $request->emp_military_date_to;
            $dataToUpdate['emp_military_weapon'] = $request->emp_military_weapon;
            $dataToUpdate['exemption_date'] = $request->exemption_date;
            $dataToUpdate['exemption_reason'] = $request->exemption_reason;
            $dataToUpdate['postponement_reason'] = $request->postponement_reason;
            $dataToUpdate['does_has_driving_license'] = $request->does_has_driving_license;
            $dataToUpdate['driving_license_num'] = $request->driving_license_num;
            $dataToUpdate['driving_license_type_id'] = $request->driving_license_type_id;
            $dataToUpdate['has_relatives'] = $request->has_relatives;
            $dataToUpdate['relatives_details'] = $request->relatives_details;
            $dataToUpdate['is_disabilities_processes'] = $request->is_disabilities_processes;
            $dataToUpdate['disabilities_processes'] = $request->disabilities_processes;
            $dataToUpdate['notes'] = $request->notes;

            ////////////// البيانات الوظيفية

            $dataToUpdate['emp_start_date'] = $request->emp_start_date;
            $dataToUpdate['functional_status'] = $request->functional_status;
            $dataToUpdate['emp_departments_id'] = $request->emp_departments_id;
            $dataToUpdate['emp_job_id'] = $request->emp_job_id;
            $dataToUpdate['does_has_attendance'] = $request->does_has_attendance;
            $dataToUpdate['is_has_fixed_shift'] = $request->is_has_fixed_shift;
            $dataToUpdate['shift_type_id'] = $request->shift_type_id;
            $dataToUpdate['daily_work_hour'] = $request->daily_work_hour;
            $dataToUpdate['emp_sal'] = $request->emp_sal;
            if (!empty($request->emp_sal > 0)) {
                $dataToUpdate['day_price'] = $request->day_price;
            }
            $dataToUpdate['bank_number_account'] = $request->bank_number_account;
            $dataToUpdate['motivation_type'] = $request->motivation_type;
            $dataToUpdate['motivation'] = $request->motivation;
            $dataToUpdate['is_social_insurance'] = $request->is_social_insurance;
            $dataToUpdate['social_insurance_cut_monthly'] = $request->social_insurance_cut_monthly;
            $dataToUpdate['social_insurance_number'] = $request->social_insurance_number;
            $dataToUpdate['is_medical_insurance'] = $request->is_medical_insurance;
            $dataToUpdate['medical_insurance_cut_monthly'] = $request->medical_insurance_cut_monthly;
            $dataToUpdate['medical_insurance_number'] = $request->medical_insurance_number;
            $dataToUpdate['is_active_for_vaccation'] = $request->is_active_for_vaccation;
            $dataToUpdate['urgent_person_details'] = $request->urgent_person_details;
            $dataToUpdate['sal_cash_or_visa'] = $request->sal_cash_or_visa;

            ////////////// البيانات الإضافية

            $dataToUpdate['emp_cafel'] = $request->emp_cafel;
            $dataToUpdate['emp_pasport_no'] = $request->emp_pasport_no;
            $dataToUpdate['emp_pasport_place'] = $request->emp_pasport_place;
            $dataToUpdate['emp_passport_exp'] = $request->emp_passport_exp;
            $dataToUpdate['home_address'] = $request->home_address;
            $dataToUpdate['resignation_id'] = $request->resignation_id;
            $dataToUpdate['resignation_date'] = $request->resignation_date;
            $dataToUpdate['resignation_cause'] = $request->resignation_cause;
            $dataToUpdate['does_has_fixed_allowance'] = $request->does_has_fixed_allowance;
            $dataToUpdate['is_sensitive_manager_data'] = $request->is_sensitive_manager_data;
            $dataToUpdate['updated_by'] = auth()->user()->id;
            //// Updating The photo of the employee 
            if ($request->has('emp_photo')) {
                $request->validate([
                    'emp_photo' => 'required|mimes:png,jpg,jpeg|max:2000'
                ]);
                $file_path = uploadImage('assets/admin/uploads', $request->emp_photo);
                $dataToUpdate['emp_photo'] = $file_path;

                if (file_exists('assets/admin/uploads/' . $data['emp_photo']) and !empty($data['emp_photo'])) {
                    unlink('assets/admin/uploads/' . $data['emp_photo']);
                }
            }
            //// Updating The CV of the employee 
            if ($request->has('emp_cv')) {
                $request->validate([
                    'emp_cv' => 'required|mimes:png,jpg,jpeg,doc,docs,pdf|max:2000'
                ]);
                $file_path = uploadImage('assets/admin/uploads', $request->emp_cv);
                $dataToUpdate['emp_cv'] = $file_path;

                if (file_exists('assets/admin/uploads/' . $data['emp_cv']) and !empty($data['emp_cv'])) {
                    unlink('assets/admin/uploads/' . $data['emp_cv']);
                }
            }

            update(new Employee(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->route('employees.index')->with(['success' => 'تم تحديث البيانات بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما ' . $ex->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $com_code = auth()->user()->com_code;

            $data = get_cols_where_row(new Employee(), array('*'), array('com_code' => $com_code, 'id' => $id));

            if (empty($data)) {
                return redirect()->route('employees.index')->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            /////
            ///// التحقق من الصلاحية والتأكد من عدم استخدام الموظف للنظام كلياً
            /////

            DB::beginTransaction();

            destroy(new Employee(), array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->back()->with(['success' => 'تم حذف الموظف بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما ' . $ex->getMessage()])->withInput();
        }
    }

    public function ajax_search(Request $request)
    {
        if ($request->ajax()) {
            $radio_code_search = $request->radio_code_search;
            $searchBy_code = $request->searchBy_code;
            $emp_name = $request->emp_name;
            $branch_id = $request->branch_id;
            $emp_departments_id = $request->emp_departments_id;
            $emp_job_id = $request->emp_job_id;
            $functional_status = $request->functional_status;
            $sal_cash_or_visa = $request->sal_cash_or_visa;
            $emp_gender = $request->emp_gender;

            // A Code 
            if ($searchBy_code == "") {
                # Here is a condition always is enabled 
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            } else {
                if ($radio_code_search == 'employee_code') {
                    $field1 = "employee_code";
                    $operator1 = "=";
                    $value1 = $searchBy_code;
                } else {
                    $field1 = "zketo_code";
                    $operator1 = "=";
                    $value1 = $searchBy_code;
                }
            }

            // Emp Name 
            if ($emp_name == "") {
                # Here is a condition always is enabled 
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "emp_name";
                $operator2 = "like";
                $value2 = "%{$emp_name}%";
            }

            // Branch ID 
            if ($branch_id == "all") {
                # Here is a condition always is enabled 
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
            } else {
                $field3 = "branch_id";
                $operator3 = "=";
                $value3 = $branch_id;
            }

            // Departments ID 
            if ($emp_departments_id == "all") {
                # Here is a condition always is enabled 
                $field4 = "id";
                $operator4 = ">";
                $value4 = 0;
            } else {
                $field4 = "emp_departments_id";
                $operator4 = "=";
                $value4 = $emp_departments_id;
            }

            // Job ID 
            if ($emp_job_id == "all") {
                # Here is a condition always is enabled 
                $field5 = "id";
                $operator5 = ">";
                $value5 = 0;
            } else {
                $field5 = "emp_job_id";
                $operator5 = "=";
                $value5 = $emp_job_id;
            }

            // Functional Status 
            if ($functional_status == "all") {
                # Here is a condition always is enabled 
                $field6 = "id";
                $operator6 = ">";
                $value6 = 0;
            } else {
                $field6 = "functional_status";
                $operator6 = "=";
                $value6 = $functional_status;
            }

            // Salary by Cash or Visa 
            if ($sal_cash_or_visa == "all") {
                # Here is a condition always is enabled 
                $field7 = "id";
                $operator7 = ">";
                $value7 = 0;
            } else {
                $field7 = "sal_cash_or_visa";
                $operator7 = "=";
                $value7 = $sal_cash_or_visa;
            }

            // Gender 
            if ($emp_gender == "all") {
                # Here is a condition always is enabled 
                $field8 = "id";
                $operator8 = ">";
                $value8 = 0;
            } else {
                $field8 = "emp_gender";
                $operator8 = "=";
                $value8 = $emp_gender;
            }

            $data = Employee::select('*')
                ->where($field1, $operator1, $value1)
                ->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)
                ->where($field4, $operator4, $value4)
                ->where($field5, $operator5, $value5)
                ->where($field6, $operator6, $value6)
                ->where($field7, $operator7, $value7)
                ->where($field8, $operator8, $value8)
                ->orderBy('id', 'DESC')->paginate(P_C);

            return view("admin.employees.ajax_search", ['data' => $data]);
        }
    }

    public function show($id)
    {
        $com_code = auth()->user()->com_code;

        $data = get_cols_where_row(new Employee(), array('*'), array('id' => $id, 'com_code' => $com_code));

        if (empty($data)) {
            return redirect()->route('employees.index')->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
        }

        $other['branches'] = get_cols_where(new Branche(), array('id', 'name'), array('com_code' => $com_code, 'id' => $data['branch_id']));
        $other['departments'] = get_cols_where(new Department(), array('id', 'name'), array('com_code' => $com_code, 'id' => $data['emp_departments_id']));
        $other['jobs'] = get_cols_where(new Jobs_categories(), array('id', 'name'), array('com_code' => $com_code, 'id' => $data['emp_job_id']));
        $other['qualifications'] = get_cols_where(new Qualification(), array('id', 'name'), array('com_code' => $com_code, 'id' => $data['qualifications_id']));
        $other['blood_groups'] = get_cols_where(new Blood_Group(), array('id', 'name'), array('com_code' => $com_code, 'id' => $data['blood_group_id']));
        $other['nationalities'] = get_cols_where(new Nationality(), array('id', 'name'), array('com_code' => $com_code, 'id' => $data['emp_nationality_id']));
        $other['languages'] = get_cols_where(new Language(), array('id', 'name'), array('com_code' => $com_code, 'id' => $data['emp_lang_id']));
        $other['social_status'] = get_cols_where(new Social_Status_Type(), array('id', 'name'), array('id' => $data['emp_social_status_id']), 'id', 'ASC');
        $other['religions'] = get_cols_where(new Religion(), array('id', 'name'), array('com_code' => $com_code, 'id' => $data['religion_id']));
        $other['countries'] = get_cols_where(new Country(), array('id', 'name'), array('com_code' => $com_code, 'id' => $data['country_id']));
        $other['governorates'] = get_cols_where(new Governorate(), array('id', 'name'), array('com_code' => $com_code, 'id' => $data['governorate_id']));
        $other['cities'] = get_cols_where(new Center(), array('id', 'name'), array('com_code' => $com_code, 'id' => $data['city_id']));
        $other['military_status'] = get_cols_where(new Military_Status(), array('id', 'name'), array('id' => $data['emp_military_id']), 'id', 'ASC');
        $other['driving_license_type'] = get_cols_where(new Driving_license_type(), array('id', 'name'), array('com_code' => $com_code, 'id' => $data['driving_license_type_id']));
        $other['shifts_type'] = get_cols_where(new shifts_type(), array('id', 'type', 'from_time', 'to_time', 'total_hour'), array('com_code' => $com_code, 'id' => $data['shift_type_id']), 'id', 'ASC');
        $other['resignations'] = get_cols_where(new Resignation(), array('id', 'name'), array('com_code' => $com_code, 'id' => $data['resignation_id']), 'id', 'ASC');
        
        $other['employee_files'] = get_cols_where(new Employee_File(), array('*'), array('com_code' => $com_code, 'employee_id' => $id));

        return view('admin.employees.show', ['data' => $data, 'other' => $other]);
    }

    public function download($id, $field_name)
    {
        $com_code = auth()->user()->com_code;

        $data = get_cols_where_row(new Employee(), array($field_name), array('id' => $id, 'com_code' => $com_code));

        if (empty($data)) {
            return redirect()->route('employees.index')->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
        }

        $file_path = "assets/admin/uploads/" . $data[$field_name];

        return response()->download($file_path);
    }

    public function add_files($id, Request $request)
    {
 
        try {
            $com_code = auth()->user()->com_code;

            // تأكد من امكانية الوصول للبيانات
            $data = get_cols_where_row(new Employee(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            // تأكد من ان اسم الملف لا يتكرر ابداً
            $checkExist = Employee_File::select('id')->where(['name' => $request->name, 'com_code' => $com_code])->where('employee_id', '!=', $id)->first();
            if (!empty($checkExist)) {
                return redirect()->back()->with(['error' => 'عفواً اسم الملف مسجل من قبل']);
            }

            DB::beginTransaction();

            $dataToInsert['name'] = $request->name;
            $dataToInsert['employee_id'] = $id;
            
            if ($request->has('file_path')) {
                $request->validate([
                    'file_path' => 'required|mimes:png,jpg,jpeg|max:2000'
                ]);
                $file_path = uploadImage('assets/admin/uploads', $request->file_path);
                $dataToInsert['file_path'] = $file_path;
            }

            $dataToInsert['added_by'] = auth()->user()->id;
            $dataToInsert['com_code'] = $com_code;

            insert(new Employee_File(), $dataToInsert);

            DB::commit();

            return redirect()->back()->with(['success' => 'تم إضافة البيانات بنجاح', 'tab3' => '1']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما ' . $ex->getMessage()]);
        }
    }

    
    public function destroy_file($id)
    {
        try {
            $com_code = auth()->user()->com_code;

            $data = get_cols_where_row(new Employee_File(), array('id'), array('com_code' => $com_code, 'id' => $id));

            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            /////
            ///// التحقق من الصلاحية والتأكد من عدم استخدام الموظف للنظام كلياً
            /////

            DB::beginTransaction();

            destroy(new Employee_File(), array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->back()->with(['success' => 'تم حذف الموظف بنجاح', 'tab3' => '1']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما ' . $ex->getMessage()])->withInput();
        }
    }

    
    public function download_file($id)
    {
        $com_code = auth()->user()->com_code;

        $data = get_cols_where_row(new Employee_File(), array('file_path'), array('id' => $id, 'com_code' => $com_code));

        if (empty($data)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
        }

        $file_path = "assets/admin/uploads/" . $data['file_path'];

        return response()->download($file_path);
    }
}
