@extends('layouts.admin')

@section('title')
    الضبط العام للنظام
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('admin_panel_settings.index') }}">الضبط العام</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> بيانات الضبط العام للنظام </h3>
            </div>

            <div class="card-body">
                @if (@isset($data) and !@empty($data))
                <table id="example2" class="table table-bordered table-hover">
                    {{-- <thead class="custom_thead">

                    </thead> --}}
                    <tr>
                        <td class="width30">اسم الشركة</td>
                        <td>{{ $data['company_name'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">حالة التفعيل</td>
                        <td>@if($data['system_status'] == 1) مفعل @else غير مفعل @endif</td>
                    </tr>
                    <tr>
                        <td class="width30">هاتف الشركة</td>
                        <td>{{ $data['phones'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">عنوان الشركة</td>
                        <td>{{ $data['address'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">بريد الشركة</td>
                        <td>{{ $data['email'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">بعد كم دقيقة نحتسب تأخير حضور</td>
                        <td>{{ $data['after_miniute_calculate_delay'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">بعد كم دقيقة نحتسب انصراف مبكر</td>
                        <td>{{ $data['after_miniute_calculate_early_departure'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">بعد كم دقيقة مجموع الحضور المتأخر أو الإنصراف المبكر نخصم ربع يوم</td>
                        <td>{{ $data['after_miniute_quarterday'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">بعد كم مرة تأخير أو إنصراف مبكر نخصم نصف يوم</td>
                        <td>{{ $data['after_time_half_daycut'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">بعد كم مرة تأخير أو إنصراف مبكر نخصم يوم كامل</td>
                        <td>{{ $data['after_time_allday_daycut'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">رصيد إجازات الموظف الشهري</td>
                        <td>{{ $data['monthly_vacation_balance'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">بعد كم يوم ينزل للموظف رصيد إجازات</td>
                        <td>{{ $data['after_days_begin_vacation'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">الرصيد الأولي المرحل عند تفعيل الاجازات للموظف (مثل نزول عشرة أيام ونص بعد ستة شهور للموظف)</td>
                        <td>{{ $data['first_balance_begin_vacation'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">قيمة خصم الأيام بعد أول غياب بدون عذر (أيام)</td>
                        <td>{{ $data['sanctions_value_first_abcence'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">قيمة خصم الأيام بعد ثاتي غياب بدون عذر (أيام)</td>
                        <td>{{ $data['sanctions_value_second_abcence'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">قيمة خصم الأيام بعد ثالث غياب بدون عذر (أيام)</td>
                        <td>{{ $data['sanctions_value_third_abcence'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">قيمة خصم الأيام بعد رابع غياب بدون عذر (أيام)</td>
                        <td>{{ $data['sanctions_value_fourth_abcence'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            <a class="btn btn-sm btn-danger" href="{{ route('admin_panel_settings.edit') }}">
                                تعديل
                            </a>
                        </td>
                    </tr>
                </table>
                @else
                <p class="text-danger text-center my-5">لا توجد بيانات لعرضها</p>
                @endif
            </div>
        </div>
    </div>

@endsection


