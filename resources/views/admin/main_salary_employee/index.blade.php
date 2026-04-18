@extends('layouts.admin')

@section('title')
الأجور والرواتب
@endsection

@section('contentheader')
قائمة الأجور والرواتب
@endsection

@section('contentheaderactivelink')
<a href="{{ route('mainsalaryemployee.index') }}">رواتب الموضفين</a>
@endsection

@section('contentheaderactive')
عرض
@endsection

@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                بيانات رواتب الموظفين بالتفصيل
            </h3>
        </div>

        <div class="row" style="padding: 5px">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="finance_yr">السنة المالية:</label>
                    <select name="finance_yr" id="finance_yr" class="form-control">
                        <option value="all">بحث الكل</option>
                        @foreach ($finance_years as $info)
                        <option value="{{ $info->finance_yr }}">{{ $info->finance_yr }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body" id="ajax_response_searchDiv" style="padding: 0 5px">
            @if (@isset($data) and !@empty($data) and count($data) > 0)
            <table id="example2" class="table table-bordered table-hover text-center">
                <thead class="custom_thead">
                    <th style="vertical-align: middle"> اسم الشهر عربي </th>
                    <th style="vertical-align: middle"> تاريخ البداية </th>
                    <th style="vertical-align: middle"> تاريخ النهاية </th>
                    <th style="vertical-align: middle"> بداية البصمة </th>
                    <th style="vertical-align: middle"> نهاية البصمة </th>
                    <th style="vertical-align: middle"> عدد الأيام </th>
                    <th style="vertical-align: middle"> حالة الشهر </th>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr>
                        <td style="vertical-align: middle"> {{ $info->month->name }} </td>
                        <td style="vertical-align: middle"> {{ $info->start_date_m }} </td>
                        <td style="vertical-align: middle"> {{ $info->end_date_m }} </td>
                        <td style="vertical-align: middle"> {{ $info->start_date_for_pasma }} </td>
                        <td style="vertical-align: middle"> {{ $info->end_date_for_pasma }} </td>
                        <td style="vertical-align: middle"> {{ $info->number_of_days }} </td>
                        <td class="d-flex justify-content-around align-items-center" style="vertical-align: middle">
                            @if ($info->is_open == 1)
                            مفتوح
                            @elseif ($info->is_open == 2)
                            مغلق ومؤرشف
                            @else
                            بانتظار الفتح
                            @endif

                            @if ($info->is_open != 0)
                            <a href="{{ route('mainsalaryemployee.show', $info->id) }}" class="btn btn-success btn-sm">عرض الرواتب</a>
                            @endif

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="col-md-12 mt-3">
                {{ $data->links('pagination::bootstrap-5') }}
            </div>

            @else
            <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
            @endif
        </div>
    </div>
</div>

@endsection

@section('script')

<script src="{{ url('assets\admin\js\main_salary_employee\index_scripts.js') }}"></script>

<script>
    
    var ajax_searchUrl = "{{ route('mainsalaryemployee.ajaxSearch') }}";
    
</script>

@endsection
