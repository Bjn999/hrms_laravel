@extends('layouts.admin')

@section('title')
الموظفين
@endsection

@section('css')
<!-- Select2 -->
<link rel="stylesheet" href="{{ url('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contentheader')
شؤون الموظفين
@endsection

@section('contentheaderactivelink')
<a href="{{ route('employees.index') }}">بيانات الموظفين</a>
@endsection

@section('contentheaderactive')
إضافة
@endsection

@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                إضافة موظف جديد
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('employees.store') }}" method="post" enctype="multipart/form-data">
                @csrf

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
                                <a class="nav-link" id="custom-content-above-home-tab" data-toggle="pill" href="#persional_data" role="tab" aria-controls="custom-content-above-home" aria-selected="true">البيانات الشخصية</a>
                            </li>
                            <li class="nav-item col-4 text-center">
                                <a class="nav-link" id="custom-content-above-profile-tab" data-toggle="pill" href="#job_data" role="tab" aria-controls="custom-content-above-profile" aria-selected="false">البيانات الوظيفية</a>
                            </li>
                            <li class="nav-item col-4 text-center">
                                <a class="nav-link active" id="custom-content-above-messages-tab" data-toggle="pill" href="#addional_data" role="tab" aria-controls="custom-content-above-messages" aria-selected="false">البيانات الإضافية</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="custom-content-above-tabContent">
                            <div class="tab-pane fade" id="persional_data" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                                <br>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="zketo_code">كود الموظف:</label>
                                            <input autofocus type="text" autofocus name="zketo_code" id="zketo_code" class="form-control" value="{{ old('zketo_code') }}">
                                            @error('zketo_code')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_name">اسم الموظف كاملاً:</label>
                                            <input type="text" autofocus name="emp_name" id="emp_name" class="form-control" value="{{ old('emp_name') }}">
                                            @error('emp_name')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_gender">نوع الجنس:</label>
                                            <select name="emp_gender" class="form-control" id="emp_gender">
                                                <option {{ old('emp_gender') == 1 ? 'selected' : '' }} value="1">ذكر</option>
                                                <option {{ old('emp_gender') == 2 ? 'selected' : '' }} value="2">أنثى</option>
                                            </select>
                                            @error('emp_gender')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="branch_id">الفرع:</label>
                                            <select name="branch_id" id="branch_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['branches']) and !empty($data['branches']))
                                                @foreach ($data['branches'] as $info)
                                                <option {{ old('branch_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('branch_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="qualifications_id">المؤهل التعليمي:</label>
                                            <select name="qualifications_id" id="qualifications_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['qualifications']) and !empty($data['qualifications']))
                                                @foreach ($data['qualifications'] as $info)
                                                <option {{ old('qualifications_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('qualifications_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="qualifications_year">سنة التخرج:</label>
                                            <input type="text" name="qualifications_year" id="qualifications_year" class="form-control" value="{{ old('qualifications_year') }}">
                                            @error('qualifications_year')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="graduation_estimate">تقدير التخرج:</label>
                                            <select name="graduation_estimate" class="form-control" id="graduation_estimate">
                                                <option {{ old('graduation_estimate') == 1 ? 'selected' : '' }} value="1">مقبول</option>
                                                <option {{ old('graduation_estimate') == 2 ? 'selected' : '' }} value="2">جيد</option>
                                                <option {{ old('graduation_estimate') == 3 ? 'selected' : '' }} value="3">جيد مرتفع</option>
                                                <option {{ old('graduation_estimate') == 4 ? 'selected' : '' }} value="4">جيد جداً</option>
                                                <option {{ old('graduation_estimate') == 5 ? 'selected' : '' }} value="5">إمتياز</option>
                                            </select>
                                            @error('graduation_estimate')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="graduation_specialization">التخصص:</label>
                                            <input type="text" name="graduation_specialization" id="graduation_specialization" class="form-control" value="{{ old('graduation_specialization') }}">
                                            @error('graduation_specialization')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_email">البريد الإلكتروني:</label>
                                            <input type="text" name="emp_email" id="emp_email" class="form-control" value="{{ old('emp_email') }}">
                                            @error('emp_email')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_birth_date">تاريخ الميلاد:</label>
                                            <input type="date" name="emp_birth_date" id="emp_birth_date" class="form-control" value="{{ old('emp_birth_date') }}">
                                            @error('emp_birth_date')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_national_identity">رقم بطاقة الهوية:</label>
                                            <input type="text" name="emp_national_identity" id="emp_national_identity" class="form-control" value="{{ old('emp_national_identity') }}">
                                            @error('emp_national_identity')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_end_identity_date">تاريخ انتهاء الهوية:</label>
                                            <input type="date" name="emp_end_identity_date" id="emp_end_identity_date" class="form-control" value="{{ old('emp_end_identity_date') }}">
                                            @error('emp_end_identity_date')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_identity_place">مكان إصدار بطاقة الهوية:</label>
                                            <input type="text" name="emp_identity_place" id="emp_identity_place" class="form-control" value="{{ old('emp_identity_place') }}">
                                            @error('emp_identity_place')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="blood_group_id">فصيلة الدم:</label>
                                            <select name="blood_group_id" id="blood_group_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['blood_groups']) and !empty($data['blood_groups']))
                                                @foreach ($data['blood_groups'] as $info)
                                                <option {{ old('blood_group_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('blood_group_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_nationality_id">الجنسية:</label>
                                            <select name="emp_nationality_id" id="emp_nationality_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['nationalities']) and !empty($data['nationalities']))
                                                @foreach ($data['nationalities'] as $info)
                                                <option {{ old('emp_nationality_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('emp_nationality_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_lang_id">اللغة الأم:</label>
                                            <select name="emp_lang_id" id="emp_lang_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['languages']) and !empty($data['languages']))
                                                @foreach ($data['languages'] as $info)
                                                <option {{ old('emp_lang_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('emp_lang_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_social_status_id">الحالة الاجتماعية:</label>
                                            <select name="emp_social_status_id" id="emp_social_status_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['social_status']) and !empty($data['social_status']))
                                                @foreach ($data['social_status'] as $info)
                                                <option {{ old('emp_social_status_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('emp_social_status_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_to_emp_social_status" style="display:none">
                                        <div class="form-group">
                                            <label for="children_number">عدد الأبناء:</label>
                                            <input type="number" name="children_number" id="children_number" class="form-control" value="{{ old('children_number') }}">
                                            @error('children_number')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="religion_id">الديانة:</label>
                                            <select name="religion_id" id="religion_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['religions']) and !empty($data['religions']))
                                                @foreach ($data['religions'] as $info)
                                                <option {{ old('religion_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('religion_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="country_id">الدولة:</label>
                                            <select name="country_id" id="country_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['countries']) and !empty($data['countries']))
                                                @foreach ($data['countries'] as $info)
                                                <option {{ old('country_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('country_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group" id="governorate_DIV">
                                            <label for="governorate_id">المحافظة:</label>
                                            <select name="governorate_id" id="governorate_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>

                                            </select>
                                            @error('governorate_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group" id="city_DIV">
                                            <label for="city_id">المدينة/المركز:</label>
                                            <select name="city_id" id="city_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>

                                            </select>
                                            @error('city_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="staies_address">مكان الإقامة الحالي:</label>
                                            <input type="text" name="staies_address" id="staies_address" class="form-control" value="{{ old('staies_address') }}">
                                            @error('staies_address')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_home_tel">هاتف المنزل:</label>
                                            <input type="text" name="emp_home_tel" id="emp_home_tel" class="form-control" value="{{ old('emp_home_tel') }}">
                                            @error('emp_home_tel')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_work_tel">هاتف العمل:</label>
                                            <input type="text" name="emp_work_tel" id="emp_work_tel" class="form-control" value="{{ old('emp_work_tel') }}">
                                            @error('emp_work_tel')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_military_id">حالة الخدمة العسكرية:</label>
                                            <select name="emp_military_id" id="emp_military_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['military_status']) and !empty($data['military_status']))
                                                @foreach ($data['military_status'] as $info)
                                                <option {{ old('emp_military_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('emp_military_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_military_1" style="display:none">
                                        <div class="form-group">
                                            <label for="emp_military_date_from">تاريخ بداية الخدمة:</label>
                                            <input type="date" name="emp_military_date_from" id="emp_military_date_from" class="form-control" value="{{ old('emp_military_date_from') }}">
                                            @error('emp_military_date_from')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_military_1" style="display:none">
                                        <div class="form-group">
                                            <label for="emp_military_date_to">تاريخ نهاية الخدمة:</label>
                                            <input type="date" name="emp_military_date_to" id="emp_military_date_to" class="form-control" value="{{ old('emp_military_date_to') }}">
                                            @error('emp_military_date_to')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_military_1" style="display:none">
                                        <div class="form-group">
                                            <label for="emp_military_weapon">سلاح الخدمة:</label>
                                            <input type="text" name="emp_military_weapon" id="emp_military_weapon" class="form-control" value="{{ old('emp_military_weapon') }}">
                                            @error('emp_military_weapon')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_military_2" style="display:none">
                                        <div class="form-group">
                                            <label for="exemption_date">تاريخ الإعفاء من الخدمة:</label>
                                            <input type="date" name="exemption_date" id="exemption_date" class="form-control" value="{{ old('exemption_date') }}">
                                            @error('exemption_date')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_military_2" style="display:none">
                                        <div class="form-group">
                                            <label for="exemption_reason">سبب الإعفاء من الخدمة:</label>
                                            <input type="text" name="exemption_reason" id="exemption_reason" class="form-control" value="{{ old('exemption_reason') }}">
                                            @error('exemption_reason')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_military_3" style="display:none">
                                        <div class="form-group">
                                            <label for="postponement_reason">سبب ومدة التأجيل للخدمة:</label>
                                            <input type="text" name="postponement_reason" id="postponement_reason" class="form-control" value="{{ old('postponement_reason') }}">
                                            @error('postponement_reason')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="does_has_driving_license">هل لديه رخصة قيادة:</label>
                                            <select name="does_has_driving_license" class="form-control" id="does_has_driving_license">
                                                <option {{ old('does_has_driving_license') == 0 ? 'selected' : '' }} value="0">لا</option>
                                                <option {{ old('does_has_driving_license') == 1 ? 'selected' : '' }} value="1">نعم</option>
                                            </select>
                                            @error('does_has_driving_license')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_to_driving_license" style="display:none">
                                        <div class="form-group">
                                            <label for="driving_license_num">رقم رخصة القيادة:</label>
                                            <input type="text" name="driving_license_num" id="driving_license_num" class="form-control" value="{{ old('driving_license_num') }}">
                                            @error('driving_license_num')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_to_driving_license" style="display:none">
                                        <div class="form-group">
                                            <label for="driving_license_type_id">نوع رخصة القيادة:</label>
                                            <select name="driving_license_type_id" id="driving_license_type_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['driving_license_type']) and !empty($data['driving_license_type']))
                                                @foreach ($data['driving_license_type'] as $info)
                                                <option {{ old('driving_license_type_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('driving_license_type_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="has_relatives">هل يمتلك أقارب في العمل:</label>
                                            <select name="has_relatives" class="form-control" id="has_relatives">
                                                <option {{ old('has_relatives') == 0 ? 'selected' : '' }} value="0">لا</option>
                                                <option {{ old('has_relatives') == 1 ? 'selected' : '' }} value="1">نعم</option>
                                            </select>
                                            @error('has_relatives')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-8 related_to_has_relatives" style="display:none">
                                        <div class="form-group">
                                            <label for="relatives_details">تفاصيل الأقارب:</label>
                                            <textarea type="text" name="relatives_details" id="relatives_details" class="form-control">{{ old('relatives_details') }}</textarea>
                                            @error('relatives_details')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_disabilities_processes">هل له إعاقة / عملية سابقة:</label>
                                            <select name="is_disabilities_processes" class="form-control" id="is_disabilities_processes">
                                                <option {{ old('is_disabilities_processes') == 0 ? 'selected' : '' }} value="0">لا</option>
                                                <option {{ old('is_disabilities_processes') == 1 ? 'selected' : '' }} value="1">نعم</option>
                                            </select>
                                            @error('is_disabilities_processes')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12 related_to_disabilities_processes" style="display:none">
                                        <div class="form-group">
                                            <label for="disabilities_processes">تفاصيل الإعاقة / العملية سابقة:</label>
                                            <textarea type="text" name="disabilities_processes" id="disabilities_processes" class="form-control">{{ old('disabilities_processes') }}</textarea>
                                            @error('disabilities_processes')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes">ملاحظات على الموظف:</label>
                                            <textarea type="text" name="notes" id="notes" class="form-control">
                                            {{ old('notes') }}
                                            </textarea>
                                            @error('notes')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="tab-pane fade" id="job_data" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
                                <br>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_start_date">تاريخ التعيين:</label>
                                            <input type="date" name="emp_start_date" id="emp_start_date" class="form-control" value="{{ old('emp_start_date') }}">
                                            @error('emp_start_date')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="functional_status">الحالة الوظيفية:</label>
                                            <select name="functional_status" class="form-control" id="functional_status">
                                                <option {{ old('functional_status') == 1 ? 'selected' : '' }} value="1">في الخدمة</option>
                                                <option {{ old('functional_status') == 0 and old('functional_status') != "" ? 'selected' : '' }} value="0">حارج الخدمة</option>
                                            </select>
                                            @error('functional_status')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_departments_id">الإدارة:</label>
                                            <select name="emp_departments_id" id="emp_departments_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['departments']) and !empty($data['departments']))
                                                @foreach ($data['departments'] as $info)
                                                <option {{ old('emp_departments_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('emp_departments_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_job_id">الوظيفة:</label>
                                            <select name="emp_job_id" id="emp_job_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['jobs']) and !empty($data['jobs']))
                                                @foreach ($data['jobs'] as $info)
                                                <option {{ old('emp_job_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('emp_job_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="does_has_attendance">ملزم بتسجيل بصمة حضور وانصراف:</label>
                                            <select name="does_has_attendance" class="form-control" id="does_has_attendance">
                                                <option {{ old('does_has_attendance') == 1 ? 'selected' : '' }} value="1">نعم</option>
                                                <option {{ old('does_has_attendance') == 0 and old('does_has_attendance') != "" ? 'selected' : '' }} value="0">لا</option>
                                            </select>
                                            @error('does_has_attendance')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_has_fixed_shift">له شفت ثابت:</label>
                                            <select name="is_has_fixed_shift" class="form-control" id="is_has_fixed_shift">
                                                {{-- <option value="">غير محدد</option> --}}
                                                <option {{ old('is_has_fixed_shift') == 1 ? 'selected' : '' }} value="1">نعم</option>
                                                <option {{ old('is_has_fixed_shift') == 0 and old('is_has_fixed_shift') != "" ? 'selected' : '' }} value="0">لا</option>
                                            </select>
                                            @error('is_has_fixed_shift')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_to_fixed_shifts_1">
                                        <div class="form-group">
                                            <label for="shift_type_id">الشفتات الثابتة:</label>
                                            <select name="shift_type_id" id="shift_type_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['shifts_type']) and !empty($data['shifts_type']))
                                                @foreach ($data['shifts_type'] as $info)
                                                <option {{ old('shift_type_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">

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
                                            @error('shift_type_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_to_fixed_shifts_0" @if(old('is_has_fixed_shift') !=1) style="display:none;" @endif>
                                        <div class="form-group">
                                            <label for="daily_work_hour">عدد الساعات اليومية:</label>
                                            <input type="number" min="0" value="0" name="daily_work_hour" id="daily_work_hour" class="form-control" value="{{ old('daily_work_hour') }}">
                                            @error('daily_work_hour')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_sal">راتب الموظف:</label>
                                            <input type="number" min="0" value="0" name="emp_sal" id="emp_sal" class="form-control" value="{{ old('emp_sal') }}">
                                            @error('emp_sal')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="day_price">سعر يومية الموظف:</label>
                                            <input type="number" min="0" value="0" name="day_price" id="day_price" class="form-control" value="{{ old('day_price') }}">
                                            @error('day_price')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sal_cash_or_visa">طريقة صرف الراتب:</label>
                                            <select name="sal_cash_or_visa" class="form-control" id="sal_cash_or_visa">
                                                <option value="">غير محدد</option>
                                                <option {{ old('sal_cash_or_visa') == 1 ? 'selected' : '' }} value="1">نقداً</option>
                                                <option {{ old('sal_cash_or_visa') == 2 ? 'selected' : '' }} value="2">بنك/فيزا</option>
                                            </select>
                                            @error('sal_cash_or_visa')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_to_sal_cash_or_visa" style="display:none;">
                                        <div class="form-group">
                                            <label for="bank_number_account">رقم الفيزا/الحساب البنكي للموظف :</label>
                                            <input type="text" name="bank_number_account" id="bank_number_account" class="form-control" value="{{ old('bank_number_account') }}" oninput="this.value = this.value.replace(/[^0-9]/g,'')">
                                            @error('bank_number_account')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="motivation_type">هل له حافز:</label>
                                            <select name="motivation_type" class="form-control" id="motivation_type">
                                                <option {{ old('motivation_type') == 0 ? 'selected' : '' }} value="0">لا يوجد</option>
                                                <option {{ old('motivation_type') == 1 ? 'selected' : '' }} value="1">ثابت</option>
                                                <option {{ old('motivation_type') == 2 ? 'selected' : '' }} value="2">متغير</option>
                                            </select>
                                            @error('motivation_type')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_to_motivation_type_1" style="display:none;">
                                        <div class="form-group">
                                            <label for="motivation">قيمة الحافز الثابت:</label>
                                            <input type="number" min="0" value="0" name="motivation" id="motivation" class="form-control" value="{{ old('motivation') }}">
                                            @error('motivation')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_social_insurance">هل له تأمين إجتماعي:</label>
                                            <select name="motivation_type" class="form-control" id="is_social_insurance">
                                                <option {{ old('is_social_insurance') == 1 ? 'selected' : '' }} value="1">نعم</option>
                                                <option {{ old('is_social_insurance') == 0 ? 'selected' : '' }} value="0">لا</option>
                                            </select>
                                            @error('is_social_insurance')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_to_is_social_insurance" style="display:none;">
                                        <div class="form-group">
                                            <label for="social_insurance_number">رقم التأمين الإجتماعي:</label>
                                            <input type="text" name="social_insurance_number" id="social_insurance_number" class="form-control" value="{{ old('social_insurance_number') }}">
                                            @error('social_insurance_number')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_to_is_social_insurance" style="display:none;">
                                        <div class="form-group">
                                            <label for="social_insurance_cut_monthly">قيمة التأمين الإجتماعي في الشهر:</label>
                                            <input type="number" min="0" value="0" name="social_insurance_cut_monthly" id="social_insurance_cut_monthly" class="form-control" value="{{ old('social_insurance_cut_monthly') }}">
                                            @error('social_insurance_cut_monthly')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_medical_insurance">هل له تأمين طبي:</label>
                                            <select name="motivation_type" class="form-control" id="is_medical_insurance">
                                                <option {{ old('is_medical_insurance') == 1 ? 'selected' : '' }} value="1">نعم</option>
                                                <option {{ old('is_medical_insurance') == 0 ? 'selected' : '' }} value="0">لا</option>
                                            </select>
                                            @error('is_medical_insurance')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_to_is_medical_insurance" style="display:none;">
                                        <div class="form-group">
                                            <label for="social_medical_number">رقم التأمين الطبي:</label>
                                            <input type="text" name="social_medical_number" id="social_medical_number" class="form-control" value="{{ old('social_medical_number') }}">
                                            @error('social_medical_number')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_to_is_medical_insurance" style="display:none;">
                                        <div class="form-group">
                                            <label for="social_medical_cut_monthly">قيمة التأمين الطبي في الشهر:</label>
                                            <input type="number" min="0" value="0" name="social_medical_cut_monthly" id="social_medical_cut_monthly" class="form-control" value="{{ old('social_medical_cut_monthly') }}">
                                            @error('social_medical_cut_monthly')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_active_for_vaccation">هل له رصيد إجازات:</label>
                                            <select name="motivation_type" class="form-control" id="is_active_for_vaccation">
                                                <option {{ old('is_active_for_vaccation') == 1 ? 'selected' : '' }} value="1">نعم</option>
                                                <option {{ old('is_active_for_vaccation') == 0 ? 'selected' : '' }} value="0">لا</option>
                                            </select>
                                            @error('is_active_for_vaccation')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="urgent_person_details">تفاصيل شخص يمكن الرجوع إليه للضرورة:</label>
                                            <textarea type="text" name="urgent_person_details" id="urgent_person_details" class="form-control">
                                            {{ old('urgent_person_details') }}
                                            </textarea>
                                            @error('urgent_person_details')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="addional_data" role="tabpanel" aria-labelledby="custom-content-above-messages-tab">
                                <br>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_cafel">اسم الكفيل:</label>
                                            <input type="text" name="emp_cafel" id="emp_cafel" class="form-control" value="{{ old('emp_cafel') }}">
                                            @error('emp_cafel')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_pasport_no">رقم الجواز:</label>
                                            <input type="text" name="emp_pasport_no" id="emp_pasport_no" class="form-control" value="{{ old('emp_pasport_no') }}">
                                            @error('emp_pasport_no')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_pasport_place">مكان اصدار الجواز:</label>
                                            <input type="text" name="emp_pasport_place" id="emp_pasport_place" class="form-control" value="{{ old('emp_pasport_place') }}">
                                            @error('emp_pasport_place')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_passport_exp">تاريخ انتهاء الجواز:</label>
                                            <input type="date" name="emp_passport_exp" id="emp_passport_exp" class="form-control" value="{{ old('emp_passport_exp') }}">
                                            @error('emp_passport_exp')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="home_address">مكان إقامة الموظف في البلد الأم:</label>
                                            <input type="text" name="home_address" id="home_address" class="form-control" value="{{ old('home_address') }}">
                                            @error('home_address')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="resignation_id">نوع ترك العمل:</label>
                                            <select name="resignation_id" id="resignation_id" class="form-control select2">
                                                <option selected value="">غير محدد</option>
                                                @if (isset($data['resignations']) and !empty($data['resignations']))
                                                @foreach ($data['resignations'] as $info)
                                                <option {{ old('resignation_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('resignation_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_to_resignation" style="display:none">
                                        <div class="form-group">
                                            <label for="resignation_date">تاريخ ترك العمل:</label>
                                            <input type="date" name="resignation_date" id="resignation_date" class="form-control" value="{{ old('resignation_date') }}">
                                            @error('resignation_date')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 related_to_resignation" style="display:none">
                                        <div class="form-group">
                                            <label for="resignation_cause">سبب ترك العمل:</label>
                                            <input type="text" name="resignation_cause" id="resignation_cause" class="form-control" value="{{ old('resignation_cause') }}">
                                            @error('resignation_cause')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="does_has_fixed_allowance">هل له بدلات ثابتة:</label>
                                            <select name="does_has_fixed_allowance" class="form-control" id="does_has_fixed_allowance">
                                                <option {{ old('does_has_fixed_allowance') == 1 ? 'selected' : '' }} value="1">نعم</option>
                                                <option {{ old('does_has_fixed_allowance') == 0 ? 'selected' : '' }} value="0">لا</option>
                                            </select>
                                            @error('does_has_fixed_allowance')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_sensitive_manager_data">هل بياناته حساسة:</label>
                                            <select name="is_sensitive_manager_data" class="form-control" id="is_sensitive_manager_data">
                                                <option {{ old('is_sensitive_manager_data') == 1 ? 'selected' : '' }} value="1">نعم</option>
                                                <option {{ old('is_sensitive_manager_data') == 0 ? 'selected' : '' }} value="0">لا</option>
                                            </select>
                                            @error('is_sensitive_manager_data')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_photo">صورة الموظف:</label>
                                            <input type="file" name="emp_photo" id="emp_photo" class="form-control" value="{{ old('emp_photo') }}">
                                            @error('emp_photo')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emp_cv">السيرة الذاتية للموظف:</label>
                                            <input type="file" name="emp_cv" id="emp_cv" class="form-control" value="{{ old('emp_cv') }}">
                                            @error('emp_cv')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button class="btn btn-success" type="submit" name="submit">إضافة الموظف</button>
                        <a href=" {{ route('employees.index') }} " class="btn btn-danger">إلغاء</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ url('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>

<script>
    //Initialize Select2 Elements
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    $(document).on("change", "#country_id", function(e) {
        ajax_getGovernorate();
    });

    ////////////////////////// Show Governorates
    function ajax_getGovernorate() {
        var country_id = $('#country_id').val();

        jQuery.ajax({
            url: "{{ route('employees.ajax_getGovernorate') }}"
            , type: 'post'
            , 'dataType': 'html'
            , cache: false
            , data: {
                "_token": '{{ csrf_token() }}'
                , country_id: country_id
            }
            , success: function(response) {
                $("#governorate_DIV").html(response);
            }
            , error: function() {
                alert("عفواً حدث خطأ ما");
            }
        });
    }

    ////////////////////////// Show Citys
    $(document).on("change", "#governorate_id", function(e) {
        ajax_getCity();
    });

    function ajax_getCity() {
        var governorate_id = $('#governorate_id').val();

        jQuery.ajax({
            url: "{{ route('employees.ajax_getCity') }}"
            , type: 'post'
            , 'dataType': 'html'
            , cache: false
            , data: {
                "_token": '{{ csrf_token() }}'
                , governorate_id: governorate_id
            }
            , success: function(response) {
                $("#city_DIV").html(response);
            }
            , error: function() {
                alert("عفواً حدث خطأ ما");
            }
        });
    }

    ////////////////////////// Employee Military Status 
    $(document).on("change", "#emp_military_id", function(e) {
        var emp_military_id = $(this).val();

        if (emp_military_id == 1) {
            $('.related_military_1').show();
            $('.related_military_2').hide();
            $('.related_military_3').hide();
        } else if (emp_military_id == 2) {
            $('.related_military_1').hide();
            $('.related_military_2').show();
            $('.related_military_3').hide();
        } else if (emp_military_id == 3) {
            $('.related_military_1').hide();
            $('.related_military_2').hide();
            $('.related_military_3').show();
        } else {
            $('.related_military_1').hide();
            $('.related_military_2').hide();
            $('.related_military_3').hide();
        }

    });

    ////////////////////////// Does Has Driving License 
    $(document).on('change', '#does_has_driving_license', function(e) {
        var does_have = $(this).val();

        if (does_have == 1) {
            $('.related_to_driving_license').show();
        } else {
            $('.related_to_driving_license').hide();
        }
    });

    ////////////////////////// Does Have Relatives 
    $(document).on('change', '#has_relatives', function(e) {
        var does_have = $(this).val();

        if (does_have == 1) {
            $('.related_to_has_relatives').show();
        } else {
            $('.related_to_has_relatives').hide();
        }
    });

    ////////////////////////// Does Have Disabilities Processes 
    $(document).on('change', '#is_disabilities_processes', function(e) {
        var does_have = $(this).val();

        if (does_have == 1) {
            $('.related_to_disabilities_processes').show();
        } else {
            $('.related_to_disabilities_processes').hide();
        }
    });

    ////////////////////////// His Social Status 
    $(document).on('change', '#emp_social_status_id', function(e) {
        var is_he = $(this).val();

        if (is_he != 1) {
            $('.related_to_emp_social_status').show();
        } else {
            $('.related_to_emp_social_status').hide();
        }
    });

    ////////////////////////// Does Have Shifts 
    $(document).on('change', '#is_has_fixed_shift', function(e) {
        var does_have = $(this).val();

        if (does_have == 0) {
            $('.related_to_fixed_shifts_1').hide();
            $('.related_to_fixed_shifts_0').show();
        } else if (does_have == 1) {
            $('.related_to_fixed_shifts_1').show();
            $('.related_to_fixed_shifts_0').hide();
        } else {
            $('.related_to_fixed_shifts_1').hide();
            $('.related_to_fixed_shifts_0').hide();
        }
    });

    ////////////////////////// Sal: Cash_or_visa 
    $(document).on('change', '#sal_cash_or_visa', function(e) {
        var does_have = $(this).val();

        if (does_have == 2) {
            $('.related_to_sal_cash_or_visa').show();
        } else {
            $('.related_to_sal_cash_or_visa').hide();
        }
    });

    ////////////////////////// Does Have Motivation Type 
    $(document).on('change', '#motivation_type', function(e) {
        var does_have = $(this).val();

        if (does_have == 1) {
            $('.related_to_motivation_type_1').show();
        } else {
            $('.related_to_motivation_type_1').hide();
        }
    });

    ////////////////////////// Does Have Social Insuranceype 
    $(document).on('change', '#is_social_insurance', function(e) {
        var does_have = $(this).val();

        if (does_have == 1) {
            $('.related_to_is_social_insurance').show();
        } else {
            $('.related_to_is_social_insurance').hide();
        }
    });

    ////////////////////////// Does Have Medical Insurance 
    $(document).on('change', '#is_medical_insurance', function(e) {
        var does_have = $(this).val();

        if (does_have == 1) {
            $('.related_to_is_medical_insurance').show();
        } else {
            $('.related_to_is_medical_insurance').hide();
        }
    });

    ////////////////////////// Does Have Medical Insurance 
    $(document).on('change', '#resignation_id', function(e) {
        var does_have = $(this).val();

        if (does_have != "") {
            $('.related_to_resignation').show();
        } else {
            $('.related_to_resignation').hide();
        }
    });

</script>
@endsection
