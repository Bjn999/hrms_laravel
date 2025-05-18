@extends('layouts.admin')

@section('title')
الموظفين
@endsection

@section('css')
<!-- Select2 -->
<link rel="stylesheet" href="{{ url('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<style>
    .emp_logo {
        border-radius: 50%;
        width: 80px;
        height: 80px;
    }

</style>
@endsection

@section('contentheader')
شؤون الموظفين
@endsection

@section('contentheaderactivelink')
<a href="{{ route('employees.index') }}">بيانات الموظفين</a>
@endsection

@section('contentheaderactive')
تعديل
@endsection

@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                تعديل بيانات موظف
                <a href="{{ route('employees.edit', $data['id']) }}" class="btn btn-sm btn-success">تعديل</a>
                <a href="{{ route('employees.index') }}" class="btn btn-sm btn-warning">عودة</a>
            </h3>
        </div>
        <div class="card-body">

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title col-12 right">
                        البيانات المطلوبة للموظف
                        <i class="fas fa-edit"></i>
                    </h3>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                        <li class="nav-item col-4 text-center">
                            <a class="nav-link @if (!Session::has('tab3')) active @endif" id="custom-content-above-home-tab" data-toggle="pill" href="#persional_data" role="tab" aria-controls="custom-content-above-home" aria-selected="true">البيانات الشخصية</a>
                        </li>
                        <li class="nav-item col-4 text-center">
                            <a class="nav-link" id="custom-content-above-profile-tab" data-toggle="pill" href="#job_data" role="tab" aria-controls="custom-content-above-profile" aria-selected="false">البيانات الوظيفية</a>
                        </li>
                        <li class="nav-item col-4 text-center">
                            <a class="nav-link @if (Session::has('tab3')) active @endif" id="custom-content-above-messages-tab" data-toggle="pill" href="#addional_data" role="tab" aria-controls="custom-content-above-messages" aria-selected="false">البيانات الإضافية</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="custom-content-above-tabContent">
                        <div class="tab-pane fade @if (!Session::has('tab3')) show active @endif" id="persional_data" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="zketo_code">كود بصمة الموظف:</label>
                                        <input disabled type="text" name="zketo_code" id="zketo_code" class="form-control" value="{{ $data['zketo_code'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_name">اسم الموظف كاملاً: <span class="text-danger">*</span></label>
                                        <input disabled type="text" autofocus name="emp_name" id="emp_name" class="form-control" value="{{ $data['emp_name'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_gender">نوع الجنس: <span class="text-danger">*</span></label>
                                        <select disabled name="emp_gender" class="form-control" id="emp_gender">
                                            <option selected value="">غير محدد</option>
                                            <option {{ $data['emp_gender'] == 1 ? 'selected' : '' }} value="1">ذكر</option>
                                            <option {{ $data['emp_gender'] == 2 ? 'selected' : '' }} value="2">أنثى</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="branch_id">الفرع: <span class="text-danger">*</span></label>
                                        <select disabled name="branch_id" id="branch_id" class="form-control select2">
                                            <option selected value="">غير محدد</option>
                                            @if (isset($other['branches']) and !empty($other['branches']))
                                            @foreach ($other['branches'] as $info)
                                            <option {{ $data['branch_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="qualifications_id">المؤهل التعليمي:</label>
                                        <select disabled name="qualifications_id" id="qualifications_id" class="form-control select2">
                                            <option selected value="">غير محدد</option>
                                            @if (isset($other['qualifications']) and !empty($other['qualifications']))
                                            @foreach ($other['qualifications'] as $info)
                                            <option {{ $data['qualifications_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="qualifications_year">سنة التخرج:</label>
                                        <input disabled type="text" name="qualifications_year" id="qualifications_year" class="form-control" value="{{ $data['qualifications_year'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="graduation_estimate">تقدير التخرج:</label>
                                        <select disabled name="graduation_estimate" class="form-control" id="graduation_estimate">
                                            <option {{ $data['graduation_estimate'] == 1 ? 'selected' : '' }} value="1">مقبول</option>
                                            <option {{ $data['graduation_estimate'] == 2 ? 'selected' : '' }} value="2">جيد</option>
                                            <option {{ $data['graduation_estimate'] == 3 ? 'selected' : '' }} value="3">جيد مرتفع</option>
                                            <option {{ $data['graduation_estimate'] == 4 ? 'selected' : '' }} value="4">جيد جداً</option>
                                            <option {{ $data['graduation_estimate'] == 5 ? 'selected' : '' }} value="5">إمتياز</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="graduation_specialization">التخصص:</label>
                                        <input disabled type="text" name="graduation_specialization" id="graduation_specialization" class="form-control" value="{{ $data['graduation_specialization'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_email">البريد الإلكتروني:</label>
                                        <input disabled type="text" name="emp_email" id="emp_email" class="form-control" value="{{ $data['emp_email'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_birth_date">تاريخ الميلاد:</label>
                                        <input disabled type="date" name="emp_birth_date" id="emp_birth_date" class="form-control" value="{{ $data['emp_birth_date'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_national_identity">رقم بطاقة الهوية: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="emp_national_identity" id="emp_national_identity" class="form-control" value="{{ $data['emp_national_identity'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_end_identity_date">تاريخ انتهاء الهوية: <span class="text-danger">*</span></label>
                                        <input disabled type="date" name="emp_end_identity_date" id="emp_end_identity_date" class="form-control" value="{{ $data['emp_end_identity_date'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_identity_place">مكان إصدار بطاقة الهوية: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="emp_identity_place" id="emp_identity_place" class="form-control" value="{{ $data['emp_identity_place'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="blood_group_id">فصيلة الدم:</label>
                                        <select disabled name="blood_group_id" id="blood_group_id" class="form-control select2">
                                            <option selected value="">غير محدد</option>
                                            @if (isset($other['blood_groups']) and !empty($other['blood_groups']))
                                            @foreach ($other['blood_groups'] as $info)
                                            <option {{ $data['blood_group_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_nationality_id">الجنسية: <span class="text-danger">*</span></label>
                                        <select disabled name="emp_nationality_id" id="emp_nationality_id" class="form-control select2">
                                            <option selected value="">غير محدد</option>
                                            @if (isset($other['nationalities']) and !empty($other['nationalities']))
                                            @foreach ($other['nationalities'] as $info)
                                            <option {{ $data['emp_nationality_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_lang_id">اللغة الأم: <span class="text-danger">*</span></label>
                                        <select disabled name="emp_lang_id" id="emp_lang_id" class="form-control select2">
                                            <option selected value="">غير محدد</option>
                                            @if (isset($other['languages']) and !empty($other['languages']))
                                            @foreach ($other['languages'] as $info)
                                            <option {{ $data['emp_lang_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_social_status_id">الحالة الاجتماعية: <span class="text-danger">*</span></label>
                                        <select disabled name="emp_social_status_id" id="emp_social_status_id" class="form-control select2">
                                            <option selected value="">غير محدد</option>
                                            @if (isset($other['social_status']) and !empty($other['social_status']))
                                            @foreach ($other['social_status'] as $info)
                                            <option {{ $data['emp_social_status_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 related_to_emp_social_status" @if($data['emp_social_status_id']==1 || $data['emp_social_status_id']=='' ) style="display:none;" @endif>
                                    <div class="form-group">
                                        <label disabled for="children_number">عدد الأبناء:</label>
                                        <input type="text" name="children_number" id="children_number" class="form-control" value="{{ $data['children_number'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="religion_id">الديانة: <span class="text-danger">*</span></label>
                                        <select disabled name="religion_id" id="religion_id" class="form-control select2">
                                            <option selected value="">غير محدد</option>
                                            @if (isset($other['religions']) and !empty($other['religions']))
                                            @foreach ($other['religions'] as $info)
                                            <option {{ $data['religion_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country_id">الدولة: <span class="text-danger">*</span></label>
                                        <select disabled name="country_id" id="country_id" class="form-control select2">
                                            <option selected value="">غير محدد</option>
                                            @if (isset($other['countries']) and !empty($other['countries']))
                                            @foreach ($other['countries'] as $info)
                                            <option {{ $data['country_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" id="governorate_DIV">
                                        <label for="governorate_id">المحافظة:</label>
                                        <select disabled name="governorate_id" id="governorate_id" class="form-control select2">
                                            <option selected value="">اختر محافظة</option>

                                            @if (isset($other['governorates']) and !empty($other['governorates']))
                                            @foreach ($other['governorates'] as $info)
                                            <option {{ $data['governorate_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" id="city_DIV">
                                        <label for="city_id">المدينة:</label>
                                        <select disabled name="city_id" id="city_id" class="form-control select2">
                                            <option selected value="">اختر مدينة</option>

                                            @if (isset($other['cities']) and !empty($other['cities']))
                                            @foreach ($other['cities'] as $info)
                                            <option {{ $data['city_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="staies_address">مكان الإقامة الحالي: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="staies_address" id="staies_address" class="form-control" value="{{ $data['staies_address'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_home_tel">هاتف المنزل: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="emp_home_tel" id="emp_home_tel" class="form-control" value="{{ $data['emp_home_tel'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_work_tel">هاتف العمل: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="emp_work_tel" id="emp_work_tel" class="form-control" value="{{ $data['emp_work_tel'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_military_id">حالة الخدمة العسكرية:</label>
                                        <select disabled name="emp_military_id" id="emp_military_id" class="form-control select2">
                                            <option selected value="">غير محدد</option>
                                            @if (isset($other['military_status']) and !empty($other['military_status']))
                                            @foreach ($other['military_status'] as $info)
                                            <option {{ $data['emp_military_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 related_military_1" @if ($data['emp_military_id'] !=1) style="display:none" @endif>
                                    <div class="form-group">
                                        <label for="emp_military_date_from">تاريخ بداية الخدمة: <span class="text-danger">*</span></label>
                                        <input disabled type="date" name="emp_military_date_from" id="emp_military_date_from" class="form-control" value="{{ $data['emp_military_date_from'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4 related_military_1" @if ($data['emp_military_id'] !=1) style="display:none" @endif>
                                    <div class="form-group">
                                        <label for="emp_military_date_to">تاريخ نهاية الخدمة: <span class="text-danger">*</span></label>
                                        <input disabled type="date" name="emp_military_date_to" id="emp_military_date_to" class="form-control" value="{{ $data['emp_military_date_to'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4 related_military_1" @if ($data['emp_military_id'] !=1) style="display:none" @endif>
                                    <div class="form-group">
                                        <label for="emp_military_weapon">سلاح الخدمة: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="emp_military_weapon" id="emp_military_weapon" class="form-control" value="{{ $data['emp_military_weapon'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4 related_military_2" @if ($data['emp_military_id'] !=2) style="display:none" @endif>
                                    <div class="form-group">
                                        <label for="exemption_date">تاريخ الإعفاء من الخدمة: <span class="text-danger">*</span></label>
                                        <input disabled type="date" name="exemption_date" id="exemption_date" class="form-control" value="{{ $data['exemption_date'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4 related_military_2" @if ($data['emp_military_id'] !=2) style="display:none" @endif>
                                    <div class="form-group">
                                        <label for="exemption_reason">سبب الإعفاء من الخدمة: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="exemption_reason" id="exemption_reason" class="form-control" value="{{ $data['exemption_reason'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4 related_military_3" @if ($data['emp_military_id'] !=3) style="display:none" @endif>
                                    <div class="form-group">
                                        <label for="postponement_reason">سبب ومدة التأجيل للخدمة: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="postponement_reason" id="postponement_reason" class="form-control" value="{{ $data['postponement_reason'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="does_has_driving_license">هل لديه رخصة قيادة:</label>
                                        <select disabled name="does_has_driving_license" class="form-control" id="does_has_driving_license">
                                            <option {{ $data['does_has_driving_license'] == 0 ? 'selected' : '' }} value="0">لا</option>
                                            <option {{ $data['does_has_driving_license'] == 1 ? 'selected' : '' }} value="1">نعم</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 related_to_driving_license" @if ($data['does_has_driving_license']) !=1) style="display:none" @endif>
                                    <div class="form-group">
                                        <label for="driving_license_num">رقم رخصة القيادة: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="driving_license_num" id="driving_license_num" class="form-control" value="{{ $data['driving_license_num'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4 related_to_driving_license" @if ($data['does_has_driving_license'] !=1) style="display:none" @endif>
                                    <div class="form-group">
                                        <label for="driving_license_type_id">نوع رخصة القيادة: <span class="text-danger">*</span></label>
                                        <select disabled name="driving_license_type_id" id="driving_license_type_id" class="form-control select2">
                                            <option selected value="">غير محدد</option>
                                            @if (isset($other['driving_license_type']) and !empty($other['driving_license_type']))
                                            @foreach ($other['driving_license_type'] as $info)
                                            <option {{ $data['driving_license_type_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="has_relatives">هل يمتلك أقارب في العمل:</label>
                                        <select disabled name="has_relatives" class="form-control" id="has_relatives">
                                            <option {{ $data['has_relatives'] == 0 ? 'selected' : '' }} value="0">لا</option>
                                            <option {{ $data['has_relatives'] == 1 ? 'selected' : '' }} value="1">نعم</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 related_to_has_relatives" @if ($data['has_relatives'] !=1) style="display:none" @endif>
                                    <div class="form-group">
                                        <label for="relatives_details">تفاصيل الأقارب: <span class="text-danger">*</span></label>
                                        <textarea disabled type="text" name="relatives_details" id="relatives_details" class="form-control">{{ old('relatives_details', $data['relatives_details']) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="is_disabilities_processes">هل له إعاقة / عملية سابقة:</label>
                                        <select disabled name="is_disabilities_processes" class="form-control" id="is_disabilities_processes">
                                            <option {{ $data['is_disabilities_processes'] == 0 ? 'selected' : '' }} value="0">لا</option>
                                            <option {{ $data['is_disabilities_processes'] == 1 ? 'selected' : '' }} value="1">نعم</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 related_to_disabilities_processes" @if ($data['is_disabilities_processes'] !=1) style="display:none" @endif>
                                    <div class="form-group">
                                        <label disabled for="disabilities_processes">تفاصيل الإعاقة / العملية سابقة: <span class="text-danger">*</span></label>
                                        <textarea type="text" name="disabilities_processes" id="disabilities_processes" class="form-control">{{ $data['disabilities_processes'] }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات على الموظف:</label>
                                        <textarea disabled type="text" name="notes" id="notes" class="form-control">{{ $data['notes'] }}</textarea>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="tab-pane fade" id="job_data" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_start_date">تاريخ التعيين: <span class="text-danger">*</span></label>
                                        <input disabled type="date" name="emp_start_date" id="emp_start_date" class="form-control" value="{{ $data['emp_start_date'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="functional_status">الحالة الوظيفية: <span class="text-danger">*</span></label>
                                        <select disabled name="functional_status" class="form-control" id="functional_status">
                                            <option selected value=""> غير محدد</option>
                                            <option {{ $data['functional_status'] == 1 ? 'selected' : '' }} value="1">في الخدمة</option>
                                            <option {{ $data['functional_status'] == 0 && $data['functional_status'] != "" ? 'selected' : '' }} value="0">خارج الخدمة</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_departments_id">الإدارة: <span class="text-danger">*</span></label>
                                        <select disabled name="emp_departments_id" id="emp_departments_id" class="form-control select2">
                                            <option selected value="">غير محدد</option>
                                            @if (isset($other['departments']) and !empty($other['departments']))
                                            @foreach ($other['departments'] as $info)
                                            <option {{ $data['emp_departments_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_job_id">الوظيفة: <span class="text-danger">*</span></label>
                                        <select disabled name="emp_job_id" id="emp_job_id" class="form-control select2">
                                            <option selected value="">غير محدد</option>
                                            @if (isset($other['jobs']) and !empty($other['jobs']))
                                            @foreach ($other['jobs'] as $info)
                                            <option {{ $data['emp_job_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="does_has_attendance">ملزم بتسجيل بصمة حضور وانصراف:</label>
                                        <select disabled name="does_has_attendance" class="form-control" id="does_has_attendance">
                                            <option {{ $data['does_has_attendance'] == 1 ? 'selected' : '' }} value="1">نعم</option>
                                            <option {{ $data['does_has_attendance'] == 0 and $data['does_has_attendance'] != "" ? 'selected' : '' }} value="0">لا</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="is_has_fixed_shift">له شفت ثابت: <span class="text-danger">*</span></label>
                                        <select disabled name="is_has_fixed_shift" class="form-control" id="is_has_fixed_shift">
                                            <option value="">غير محدد</option>
                                            <option {{ $data['is_has_fixed_shift'] == 1 ? 'selected' : '' }} value="1">نعم</option>
                                            <option {{ $data['is_has_fixed_shift'] == 0 && $data['is_has_fixed_shift'] != "" ? 'selected' : '' }} value="0">لا</option>
                                            {{-- <option @if($data['is_has_fixed_shift'] == 0) selected @endif value="0">لا</option> --}}
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-md-4 related_to_fixed_shifts_1" @if($data['is_has_fixed_shift'] == 0 or $data['is_has_fixed_shift'] == "") style="display:none;" @endif> --}}
                                <div class="col-md-4 related_to_fixed_shifts_1" @if($data['is_has_fixed_shift'] !=1) style="display:none;" @endif>
                                    <div class="form-group">
                                        <label for="shift_type_id">الشفتات الثابتة:</label>
                                        <select disabled name="shift_type_id" id="shift_type_id" class="form-control select2">
                                            <option selected value="">غير محدد</option>
                                            @if (isset($other['shifts_type']) and !empty($other['shifts_type']))
                                            @foreach ($other['shifts_type'] as $info)
                                            <option {{ $data['shift_type_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">

                                                @if ($info->type == 1) صباحي @elseif ($info->type) مسائي @endif
                                                من
                                                @php
                                                $dt = new DateTime($info->from_time);
                                                $time = $dt->format('h:i');
                                                $newTime = (($dt->format('A') == "AM") ? 'صباحاً' : 'مساءاً');
                                                @endphp
                                                {{ $time }}
                                                {{ $newTime }}
                                                إلى
                                                @php
                                                $dt = new DateTime($info->to_time);
                                                $time = $dt->format('h:i');
                                                $newTime = (($dt->format('A') == "AM") ? 'صباحاً' : 'مساءاً');
                                                @endphp
                                                {{ $time }}
                                                {{ $newTime }}
                                                عدد
                                                {{ $info->total_hour*1 }} ساعات

                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 related_to_fixed_shifts_0" @if($data['is_has_fixed_shift']==1 or $data['is_has_fixed_shift']=="" ) style="display:none;" @endif>
                                    <div class="form-group">
                                        <label for="daily_work_hour">عدد الساعات اليومية:</label>
                                        <input disabled type="trxt" name="daily_work_hour" id="daily_work_hour" class="form-control" value="{{ $data['daily_work_hour'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_sal">راتب الموظف: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="emp_sal" id="emp_sal" class="form-control" value="{{ $data['emp_sal'] }} رس" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="day_price">سعر يومية الموظف:</label>
                                        <input disabled type="text" name="day_price" id="day_price" class="form-control" value="{{ $data['day_price'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sal_cash_or_visa">طريقة صرف الراتب: <span class="text-danger">*</span></label>
                                        <select disabled name="sal_cash_or_visa" class="form-control" id="sal_cash_or_visa">
                                            <option value="">غير محدد</option>
                                            <option {{ $data['sal_cash_or_visa'] == 1 ? 'selected' : '' }} value="1">نقداً</option>
                                            <option {{ $data['sal_cash_or_visa'] == 2 ? 'selected' : '' }} value="2">بنك/فيزا</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 related_to_sal_cash_or_visa" @if($data['sal_cash_or_visa'] !=2) style="display:none;" @endif>
                                    <div class="form-group">
                                        <label for="bank_number_account">رقم الفيزا/الحساب البنكي للموظف: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="bank_number_account" id="bank_number_account" class="form-control" value="{{ $data['bank_number_account'] }}" oninput="this.value = this.value.replace(/[^0-9]/g,'')">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="motivation_type">هل له حافز:</label>
                                        <select disabled name="motivation_type" class="form-control" id="motivation_type">
                                            <option {{ $data['motivation_type'] == 0 and $data['motivation_type'] != "" ? 'selected' : '' }} value="0">لا يوجد</option>
                                            <option {{ $data['motivation_type'] == 1 ? 'selected' : '' }} value="1">ثابت</option>
                                            <option {{ $data['motivation_type'] == 2 ? 'selected' : '' }} value="2">متغير</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 related_to_motivation_type_1" @if($data['motivation_type'] !=1) style="display:none;" @endif>
                                    <div class="form-group">
                                        <label for="motivation">قيمة الحافز: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="motivation" id="motivation" class="form-control" value="{{ $data['motivation'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="is_social_insurance">هل له تأمين إجتماعي:</label>
                                        <select disabled name="is_social_insurance" class="form-control" id="is_social_insurance">
                                            <option {{ $data['is_social_insurance'] == 1 ? 'selected' : '' }} value="1">نعم</option>
                                            <option {{ $data['is_social_insurance'] == 0 ? 'selected' : '' }} value="0">لا</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 related_to_is_social_insurance" @if($data['is_social_insurance'] !=1) style="display:none;" @endif>
                                    <div class="form-group">
                                        <label for="social_insurance_number">رقم التأمين الإجتماعي: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="social_insurance_number" id="social_insurance_number" class="form-control" value="{{ $data['social_insurance_number'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4 related_to_is_social_insurance" @if($data['is_social_insurance'] !=1) style="display:none;" @endif>
                                    <div class="form-group">
                                        <label for="social_insurance_cut_monthly">قيمة التأمين الإجتماعي في الشهر: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="social_insurance_cut_monthly" id="social_insurance_cut_monthly" class="form-control" value="{{ $data['social_insurance_cut_monthly'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="is_medical_insurance">هل له تأمين طبي:</label>
                                        <select disabled name="is_medical_insurance" class="form-control" id="is_medical_insurance">
                                            <option {{ $data['is_medical_insurance'] == 1 ? 'selected' : '' }} value="1">نعم</option>
                                            <option {{ $data['is_medical_insurance'] == 0 ? 'selected' : '' }} value="0">لا</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 related_to_is_medical_insurance" @if($data['is_medical_insurance'] !=1) style="display:none;" @endif>
                                    <div class="form-group">
                                        <label for="medical_insurance_number">رقم التأمين الطبي: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="medical_insurance_number" id="medical_insurance_number" class="form-control" value="{{ $data['medical_insurance_number'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4 related_to_is_medical_insurance" @if($data['is_medical_insurance'] !=1) style="display:none;" @endif>
                                    <div class="form-group">
                                        <label for="medical_insurance_cut_monthly">قيمة التأمين الطبي في الشهر: <span class="text-danger">*</span></label>
                                        <input disabled type="text" name="medical_insurance_cut_monthly" id="medical_insurance_cut_monthly" class="form-control" value="{{ $data['medical_insurance_cut_monthly'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="is_active_for_vaccation">هل له رصيد إجازات:</label>
                                        <select disabled name="is_active_for_vaccation" class="form-control" id="is_active_for_vaccation">
                                            <option {{ $data['is_active_for_vaccation'] == 1 ? 'selected' : '' }} value="1">نعم</option>
                                            <option {{ $data['is_active_for_vaccation'] == 0 ? 'selected' : '' }} value="0">لا</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="urgent_person_details">تفاصيل شخص يمكن الرجوع إليه للضرورة:</label>
                                        <textarea disabled type="text" max-line="3" name="urgent_person_details" id="urgent_person_details" class="form-control">{{ $data['urgent_person_details'] }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade @if (Session::has('tab3')) show active @endif" id="addional_data" role="tabpanel" aria-labelledby="custom-content-above-messages-tab">
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_cafel">اسم الكفيل:</label>
                                        <input disabled type="text" name="emp_cafel" id="emp_cafel" class="form-control" value="{{ $data['emp_cafel'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_pasport_no">رقم الجواز:</label>
                                        <input disabled type="text" name="emp_pasport_no" id="emp_pasport_no" class="form-control" value="{{ $data['emp_pasport_no'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_pasport_place">مكان اصدار الجواز:</label>
                                        <input disabled type="text" name="emp_pasport_place" id="emp_pasport_place" class="form-control" value="{{ $data['emp_pasport_place'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emp_passport_exp">تاريخ انتهاء الجواز:</label>
                                        <input disabled type="date" name="emp_passport_exp" id="emp_passport_exp" class="form-control" value="{{ $data['emp_passport_exp'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="home_address">مكان إقامة الموظف في البلد الأم:</label>
                                        <input disabled type="text" name="home_address" id="home_address" class="form-control" value="{{ $data['home_address'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="resignation_id">نوع ترك العمل:</label>
                                        <select disabled name="resignation_id" id="resignation_id" class="form-control select2">
                                            <option selected value="0">غير محدد</option>
                                            @if (isset($other['resignations']) and !empty($other['resignations']))
                                            @foreach ($other['resignations'] as $info)
                                            <option {{ $data['resignation_id'] == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 related_to_resignation" @if($data['resignation_id']==0 ) style="display:none;" @endif>
                                    <div class="form-group">
                                        <label for="resignation_date">تاريخ ترك العمل:</label>
                                        <input disabled type="date" name="resignation_date" id="resignation_date" class="form-control" value="{{ $data['resignation_date'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4 related_to_resignation" @if($data['resignation_id']==0 ) style="display:none;" @endif>
                                    <div class="form-group">
                                        <label for="resignation_cause">سبب ترك العمل:</label>
                                        <input disabled type="text" name="resignation_cause" id="resignation_cause" class="form-control" value="{{ $data['resignation_cause'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="does_has_fixed_allowance">هل له بدلات ثابتة:</label>
                                        <select disabled name="does_has_fixed_allowance" class="form-control" id="does_has_fixed_allowance">
                                            <option {{ $data['does_has_fixed_allowance'] == 1 ? 'selected' : '' }} value="1">نعم</option>
                                            <option {{ $data['does_has_fixed_allowance'] == 0 ? 'selected' : '' }} value="0">لا</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="is_sensitive_manager_data">هل بياناته حساسة:</label>
                                        <select disabled name="is_sensitive_manager_data" class="form-control" id="is_sensitive_manager_data">
                                            <option {{ $data['is_sensitive_manager_data'] == 1 ? 'selected' : '' }} value="1">نعم</option>
                                            <option {{ $data['is_sensitive_manager_data'] == 0 ? 'selected' : '' }} value="0">لا</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4 d-flex align-items-center">
                                    <div class="form-group">
                                        <label for="emp_photo">صورة الموظف:</label>
                                        @if (!empty($data['emp_photo']))
                                        <img class="emp_logo rounded-circle mx-3" src="{{ url('assets/admin/uploads').'/'.$data['emp_photo'] }}">
                                        <a href="{{ route('employees.download', ['id' => $data['id'], 'field_name' => 'emp_photo']) }}" class="btn btn-success mx-2" onclick="return confirm('هل تريد تحميل الصورة الشخصية؟')">تحميل <span class="fa fa-download"></span></a>
                                        @else
                                        @if ($data['emp_gender'] == 1)
                                        <img class="emp_logo rounded-circle" src="{{ url('assets/admin/imgs/default_m.png') }}">
                                        @else
                                        <img class="emp_logo rounded-circle" src="{{ url('assets/admin/imgs/default_f.png') }}">
                                        @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <div class="form-group">
                                        <label for="emp_cv">السيرة الذاتية للموظف:</label>
                                        @if (!empty($data['emp_cv']))
                                        <a href="{{ route('employees.download', ['id' => $data['id'], 'field_name' => 'emp_cv']) }}" class="btn btn-success mx-2" onclick="return confirm('هل تريد تحميل السيرة الذاتية؟')">تحميل <span class="fa fa-download"></span></a>
                                        @else
                                        <span class="text-danger mx-3">
                                            لم يتم الإرفاق
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr />
                                    <h5 class="text-center col-12 mb-3" style="font-weight: bold">
                                        الملفات المرفقة للموظف
                                    </h5>

                                    @if (isset($other['employee_files']) and !empty($other['employee_files']) and count($other['employee_files']) > 0)
                                    <table id="example2" class="table table-bordered table-hover text-center">
                                        <thead class="custom_thead">
                                            <tr>
                                                <th style="vertical-align: middle"> الاسم </th>
                                                <th style="vertical-align: middle"> الصورة </th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ( $other['employee_files'] as $info )
                                            <tr>
                                                <td style="vertical-align: middle"> {{ $info->name }} </td>

                                                <td style="vertical-align: middle">
                                                    @if (!empty($info->file_path))
                                                    <img class="emp_logo rounded-circle" src="{{ url('assets/admin/uploads').'/'.$info->file_path }}">
                                                    @else
                                                    لم يتم الإرفاق
                                                    @endif
                                                </td>

                                                <td style="vertical-align: middle">

                                                    <a href="{{ route('employees.destroy_file', $info->id) }}" class="btn btn-danger r_u_sure">حذف <span class="fa fa-trash"></span></a>
                                                    <a href="{{ route('employees.download_file', ['id' => $info->id]) }}" class="btn btn-primary" onclick="return confirm('هل تريد تحميل هذا الملف؟')">تحميل <span class="fa fa-download"></span></a>

                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    @else
                                    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
                                    @endif

                                    <button id="upload_new_file" data-toggle="modal" data-target="#add_files_modal" class="btn btn-sm btn-success"> إرفاق ملف جديد <i class="fa fa-upload"></i></button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</div>

<!-- .modal -->
<div class="modal fade" id="add_files_modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-secondary">
            <div class="modal-header">
                <h4 class="modal-title">إضافة مرفقات الموظف</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employees.add_files', $data['id']) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">اسم الملف:</label>
                                <input type="text" name="name" id="name" class="form-control" value="" required oninvalid="setCustomValidity('الحقل مطلوب')" oninput="try{setCustomValidity('')}catch(e){}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="file_path">الملف:</label>
                                <input type="file" name="file_path" id="file_path" class="form-control" value="" required oninvalid="setCustomValidity('الحقل مطلوب')" oninput="try{setCustomValidity('')}catch(e){}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <button class="btn btn-success" type="submit" name="submit">إضافة الموظف</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@endsection
