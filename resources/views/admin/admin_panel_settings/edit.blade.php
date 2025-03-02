@extends('layouts.admin')

@section('title')
    الضبط العام للنظام
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('admin_panel_settings.edit') }}">تعديل الضبط العام</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection

@section('content')

<div class="card" style="width: 100% !important;">
    <div class="card-header">
        <h3 class="card-title card_title_center">تعديل بيانات الضبط العام للنظام</h3>
    </div>
    
    <div class="card-body">

        @if (@isset($data) and !@empty($data))
        <form action="{{ route('admin_panel_settings.update') }}" method="POST">
            @csrf
            <div class="col-md-4">
                <div class="form-group">
                    <label for="com_code">كود الشركة:</label>
                    <input type="text" disabled name="com_code" id="com_code" value="{{ $data['com_code'] }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_name">اسم الشركة:</label>
                        <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name', $data['company_name']) }}" placeholder="أدخل اسم الشركة">
                        @error('company_name')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phones">هاتف الشركة:</label>
                        <input type="text" name="phones" id="phones" class="form-control" value="{{ old('phones', $data['phones']) }}" placeholder="أدخل رقم الشركة">
                        @error('phones')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="address">عنوان الشركة:</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $data['address']) }}" placeholder="أدخل عنوان الشركة">
                        @error('address')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">بريد الشركة:</label>
                        <input type="text" name="email" id="email" class="form-control" value="{{ old('email', $data['email'])  }}" placeholder="أدخل بريد الشركة">
                        @error('email')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                {{-- <div class="col-md-4">
                    <div class="form-group">
                        <label for="com_code">كود الشركة:</label>
                        <input type="text" name="com_code" id="com_code" class="form-control" value="{{ $data['com_code'] }}" disabled>
                    </div>
                </div> --}}
                
                <hr class="bg-grey p-1 col-md-11 rounded">

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="after_miniute_calculate_delay">بعد كم دقيقة نحتسب تأخير حضور:</label>
                        <input type="text" name="after_miniute_calculate_delay" id="after_miniute_calculate_delay" class="form-control" value="{{ old('after_miniute_calculate_delay', $data['after_miniute_calculate_delay']) }}">
                        @error('after_miniute_calculate_delay')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="after_miniute_calculate_early_departure">بعد كم دقيقة نحتسب انصراف مبكر:</label>
                        <input type="text" name="after_miniute_calculate_early_departure" id="after_miniute_calculate_early_departure" class="form-control" value="{{ old('after_miniute_calculate_early_departure', $data['after_miniute_calculate_early_departure']) }}">
                        @error('after_miniute_calculate_early_departure')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="after_miniute_quarterday">بعد كم دقيقة مجموع الحضور المتأخر أو الإنصراف المبكر نخصم ربع يوم:</label>
                        <input type="text" name="after_miniute_quarterday" id="after_miniute_quarterday" class="form-control" value="{{ old('after_miniute_quarterday', $data['after_miniute_quarterday']) }}">
                        @error('after_miniute_quarterday')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="after_time_half_daycut">بعد كم مرة تأخير أو إنصراف مبكر نخصم نصف يوم:</label>
                        <input type="text" name="after_time_half_daycut" id="after_time_half_daycut" class="form-control" value="{{ old('after_time_half_daycut', $data['after_time_half_daycut']) }}">
                        @error('after_time_half_daycut')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="after_time_allday_daycut">بعد كم مرة تأخير أو إنصراف مبكر نخصم يوم كامل:</label>
                        <input type="text" name="after_time_allday_daycut" id="after_time_allday_daycut" class="form-control" value="{{ old('after_time_allday_daycut', $data['after_time_allday_daycut']) }}">
                        @error('after_time_allday_daycut')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                        <div class="form-group">
                            <label for="monthly_vacation_balance">رصيد إجازات الموظف الشهري:</label>
                            <input type="text" name="monthly_vacation_balance" id="monthly_vacation_balance" class="form-control" value="{{ old('monthly_vacation_balance', $data['monthly_vacation_balance']) }}">
                            @error('monthly_vacation_balance')
                            <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="after_days_begin_vacation">بعد كم يوم ينزل للموظف رصيد إجازات:</label>
                        <input type="text" name="after_days_begin_vacation" id="after_days_begin_vacation" class="form-control" value="{{ old('after_days_begin_vacation', $data['after_days_begin_vacation']) }}">
                        @error('after_days_begin_vacation')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="first_balance_begin_vacation">الرصيد الأولي المرحل عند تفعيل الاجازات للموظف (مثل نزول عشرة أيام ونص بعد ستة شهور للموظف):</label>
                        <input type="text" name="first_balance_begin_vacation" id="first_balance_begin_vacation" class="form-control" value="{{ old('first_balance_begin_vacation', $data['first_balance_begin_vacation']) }}">
                        @error('first_balance_begin_vacation')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                
                <hr class="bg-grey p-1 col-md-11 rounded">
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="sanctions_value_first_abcence">قيمة خصم الأيام بعد أول غياب بدون عذر (أيام):</label>
                        <input type="text" name="sanctions_value_first_abcence" id="sanctions_value_first_abcence" class="form-control" value="{{ old('sanctions_value_first_abcence', $data['sanctions_value_first_abcence']) }}">
                        @error('sanctions_value_first_abcence')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="sanctions_value_second_abcence">قيمة خصم الأيام بعد ثاني غياب بدون عذر (أيام):</label>
                        <input type="text" name="sanctions_value_second_abcence" id="sanctions_value_second_abcence" class="form-control" value="{{ old('sanctions_value_second_abcence', $data['sanctions_value_second_abcence']) }}">
                        @error('sanctions_value_second_abcence')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="sanctions_value_third_abcence">قيمة خصم الأيام بعد ثالث غياب بدون عذر (أيام):</label>
                        <input type="text" name="sanctions_value_third_abcence" id="sanctions_value_third_abcence" class="form-control" value="{{ old('sanctions_value_third_abcence', $data['sanctions_value_third_abcence']) }}">
                        @error('sanctions_value_third_abcence')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="sanctions_value_fourth_abcence">قيمة خصم الأيام بعد رابع غياب بدون عذر (أيام):</label>
                        <input type="text" name="sanctions_value_fourth_abcence" id="first_balance_begin_vacation" class="form-control" value="{{ old('sanctions_value_fourth_abcence', $data['sanctions_value_fourth_abcence']) }}">
                        @error('sanctions_value_fourth_abcence')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-12 text-center">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">تحديث</button>
                    </div>
                </div>
            </div>
        </form>

        @else
        <p class="text-danger text-center">لا توجد بيانات لعرضها</p>
        @endif
    </div>
</div>

@endsection

