@extends('layouts.admin')

@section('title')
    الأجور والرواتب
@endsection

@section('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ url('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .modal-2xl {
            min-width: 100%;
        }
    </style>
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
                    بيانات رواتب الموظفين بالتفصيل للشهر المالي ({{ $financeMonth_data['month']['name'] }} لسنة
                    {{ $financeMonth_data['finance_yr'] }})
                </h3>
            </div>
            @if ($financeMonth_data['is_open'] == 1)
                <button class="btn btn-md btn-success col-md-2 m-1" data-toggle="modal" data-target="#add_salaryModal">
                    إضافة راتب يدوي
                    <i class="fas fa-plus ml-3"></i>
                </button>
            @endif
            <form method="POST" action="{{ route('mainsalaryemployee.printSearch') }}" target="_blank">
                @csrf
                <input type="hidden" id="finance_month_period_id" name="finance_month_period_id"
                    value="{{ $financeMonth_data['id'] }}" />
                <div class="row" style="padding: 0 5px">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="employee_code">رقم الموظف:</label>
                            <select class="form-control select2" id="employee_code_search" name="employee_code">
                                <option selected value="all">غير محدد</option>
                                @if (isset($other['employees']) and !empty($other['employees']))
                                    @foreach ($other['employees'] as $info)
                                        <option value="{{ $info->employee_code }}" data-salary="{{ $info->emp_sal }}">
                                            ({{ $info->employee_code }})
                                            {{ $info->emp_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="branch">الفرع:</label>
                            <select class="form-control select2" id="branch_search" name="branch">
                                <option selected value="all">غير محدد</option>
                                @if (isset($other['branches']) and !empty($other['branches']))
                                    @foreach ($other['branches'] as $info)
                                        <option value="{{ $info->id }}"> {{ $info->name }} </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="department">الادارة:</label>
                            <select class="form-control select2" id="department_search" name="department">
                                <option selected value="all">غير محدد</option>
                                @if (isset($other['departments']) and !empty($other['departments']))
                                    @foreach ($other['departments'] as $info)
                                        <option value="{{ $info->id }}"> {{ $info->name }} </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="job_type">الوظيفة:</label>
                            <select class="form-control select2" id="job_type_search" name="job_type">
                                <option selected value="all">غير محدد</option>
                                @if (isset($other['jobs']) and !empty($other['jobs']))
                                    @foreach ($other['jobs'] as $info)
                                        <option value="{{ $info->id }}"> {{ $info->name }} </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="functional_status">الحالة الوظيفية:</label>
                            <select name="functional_status" id="functional_status_search" class="form-control">
                                <option selected value="all"> الكل</option>
                                <option value="1">في الخدمة</option>
                                <option value="0">خارج الخدمة</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sal_cash_or_visa">طريقة صرف الراتب:</label>
                            <select name="sal_cash_or_visa" id="sal_cash_or_visa_search" class="form-control">
                                <option selected value="all">الكل</option>
                                <option value="1">نقداً</option>
                                <option value="2">بنك/فيزا</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="is_stoped">حالة الراتب:</label>
                            <select class="form-control" id="is_stoped_search" name="is_stoped">
                                <option selected value="all">غير محدد</option>
                                <option value="1">مفعل</option>
                                <option value="0">موقوف</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="is_archived">الحالة:</label>
                            <select class="form-control" id="is_archived_search" name="is_archived">
                                <option selected value="all">غير محدد</option>
                                <option value="1">مؤرشف</option>
                                <option value="0">غير مؤرشف (مفتوح)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button type="submet" name="print_search" value="detailed" class="btn btn-md btn-info">
                                طباعة البحث تفصيلي
                                <i class="fas fa-print"></i>
                            </button>
                            <button type="submet" name="print_search" value="summary" class="btn btn-md btn-primary">
                                طباعة البحث كلي
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card-body" id="ajax_response_searchDiv" style="padding: 0 5px">
            <h3 class="text-center border-top">
                مرآة الرواتب
            </h3>
            <table id="example2" class="table table-bordered table-hover text-center" style="width: 70%; margin: 15px auto;">
                <tr class="bg-gray">
                    <th style="vertical-align: middle"> عدد الرواتب </th>
                    <th style="vertical-align: middle"> الرواتب المؤرشفة </th>
                    <th style="vertical-align: middle"> الرواتب الغير المؤرشفة </th>
                    <th style="vertical-align: middle"> الرواتب الموقوفة </th>
                </tr>
                <tr>
                    <td>{{ $data->count() }}</td>
                    <td>{{ $data->where('is_archived', 1)->count() }}</td>
                    <td>{{ $data->where('is_archived', 0)->count() }}</td>
                    <td>{{ $data->where('is_stoped', 1)->count() }}</td>
                </tr>
            </table>
                @if (@isset($data) and !@empty($data) and count($data) > 0)
                    <table id="example2" class="table table-bordered table-hover text-center">
                        <thead class="custom_thead">
                            <th style="vertical-align: middle"> الكود </th>
                            <th style="vertical-align: middle"> الاسم </th>
                            <th style="vertical-align: middle"> الفرع </th>
                            <th style="vertical-align: middle"> الادارة </th>
                            <th style="vertical-align: middle"> الوظيفة </th>
                            <th style="vertical-align: middle"> صافي الراتب </th>
                            <th style="vertical-align: middle"> الأرشفة </th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($data as $info)
                                <tr @if($info->is_stoped == 1) style="background-color: #ff00003d;" @endif>
                                    <td style="vertical-align: middle">
                                        {{ $info->employee_code }}
                                    </td>
                                    <td style="vertical-align: middle">
                                        {{ $info->employeeData->emp_name }}
                                    </td>
                                    <td style="vertical-align: middle">
                                        {{ $info->branch }}
                                    </td>
                                    <td style="vertical-align: middle">
                                        {{ $info->department }}
                                    </td>
                                    <td style="vertical-align: middle">
                                        {{ $info->job }}
                                    </td>
                                    <td style="vertical-align: middle">
                                        {{ $info->employeeData->emp_sal * 1 }} رس
                                    </td>
                                    <td class="@if ($info->is_archived == 1) bg-success @else bg-info @endif"
                                        style="vertical-align: middle">
                                        @if ($info->is_archived == 1)
                                            تم الأرشفة
                                        @else
                                            لم يتم الأرشفة
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle">
                                        @if ($info->is_archived == 0)
                                            {{-- <form
                                                action="{{ route('mainsalaryemployee.delete_salary', ['id' => $info->id]) }}"
                                                method="get"> --}}
                                            <button data-id="{{ $info->id }}"
                                                data-finance_month_id="{{ $info->finance_month_id }}"
                                                class="btn btn-danger" type="submit" id="delete_salary">حذف</button>
                                            {{-- </form> --}}
                                        @endif

                                        <a href="{{ route('mainsalaryemployee.showSalDetails', $info->id) }}"
                                            class="btn btn-info">التفاصيل</a>
                                        <a target="_blank" href="{{ route('mainsalaryemployee.detailsPrintSalary', $info->id) }}" class="btn btn btn-primary mx-2"> طباعة </a>

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

    <!-- .addModal -->
    <div class="modal fade" id="add_salaryModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h4 class="modal-title">اضافة راتب لموظف ليس لديه راتب في الشهر الحالي
                        ({{ $financeMonth_data['month']['name'] }}) - (عدد {{ $other['nothavesal'] }})</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="add_salaryModalBody">
                    <form id="add_salaryForm">
                        <input type="hidden" name="finance_month_id" value="{{ $financeMonth_data['month']['id'] }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_code_add_salary">الموظف: <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2" id="employee_code_add_salary"
                                        name="employee_code_add_salary">
                                        <option selected value="">غير محدد</option>
                                        @if (isset($other['employees']) and !empty($other['employees']))
                                            @foreach ($other['employees'] as $info)
                                                @if ($info->counter > 0)
                                                    @continue
                                                @endif
                                                <option value="{{ $info->employee_code }}"
                                                    @if ($info->counter > 0) disabled title="لديه راتب بالفعل في هذا الشهر" @endif
                                                    data-salary="{{ $info['emp_sal'] }}">({{ $info->employee_code }})
                                                    {{ $info->emp_name }} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 related_employee_add" style="display:none">
                                <div class="form-group">
                                    <label for="emp_sal_add_salary">راتب الموظف:</label>
                                    <input readonly type="text" class="form-control" name="emp_sal_add_salary"
                                        id="emp_sal_add_salary" value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mx-auto col-md-2">
                                    <button type="submit" class="form-control btn-primary" id="do_add_salary"
                                        title="فتح سجل راتب للموظف لهذا الشهر">فتح سجل</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between bg-secondary">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
                    {{-- <button type="button" class="btn btn-outline-light">Save changes</button> --}}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.addModal -->

@endsection

@section('script')
    <script src="{{ url('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ url('assets/admin/js/main_salary_employee/show_scripts.js') }}"></script>

    <script>
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        var showAjaxSearchUrl = "{{ route('mainsalaryemployee.showAjaxSearch') }}";
        var addSalaryUrl = "{{ route('mainsalaryemployee.add_salary') }}";
        var deleteSalaryUrl = "{{ route('mainsalaryemployee.delete_salary') }}";
    </script>
@endsection
