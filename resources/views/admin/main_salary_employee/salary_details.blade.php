@extends('layouts.admin')

@section('title')
    تفاصيل الراتب
@endsection

@section('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ url('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .modal-2xl {
            min-width: 100%;
        }

        .custom_td_fisrt {
            width: 30%;
            background-color: lightcyan;
        }

        td,
        th {
            text-align: center;
            color: black;
        }

        .underPrag {
            text-decoration: underline;
            font-size: 16px;
            color: black;
            font-weight: bold;
            margin: 7px;
        }
    </style>
@endsection

@section('contentheader')
    تفاصيل الراتب
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('mainsalaryemployee.index') }}">تفاصيل الراتب</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">
                    تفاصيل راتب الموظف للشهر المالي ({{ $financeMonth_data['month']['name'] }} لسنة
                    {{ $financeMonth_data['finance_yr'] }})
                    <a href="{{ route('mainsalaryemployee.show', $financeMonth_data->id) }}" class="btn btn-sm btn-info mx-2"> عودة </a>
                    <a target="_blank" href="{{ route('mainsalaryemployee.detailsPrintSalary', $data->id) }}" class="btn btn-sm btn-info mx-2"> طباعة </a>
                </h3>
            </div>
            <div class="card-body">
                @if (@isset($data) and !@empty($data))
                    @if ($financeMonth_data->is_open == 1 and $data->is_archived == 0)
                        @if ($data->is_stoped == 0)
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <span class="text-success">هذا الراتب مفعل </span>
                                <a id="stopSalary" href="{{ route('mainsalaryemployee.stopSalary', $data->id) }}"
                                    class="btn btn-sm btn-warning mx-2"> ايقاف </a>
                                <a id="detailsDeleteSalary"
                                    href="{{ route('mainsalaryemployee.detailsDeleteSalary', $data->id) }}"
                                    class="btn btn-sm btn-danger mx-2"> حذف </a>
                                <button id="detailsShowArchiveSalary" class="btn btn-sm btn-success mx-2" data-url="{{ route('mainsalaryemployee.detailsShowArchiveSalary', ['id' => $data['id']]) }}"> ارشفة </button>
                            </div>
                        @else
                            <div class="d-flex justify-content-center align-items-center mb-3 mx-3 py-3 rounded bg-danger">
                                <span class="text-white">هذا الراتب موقوف</span>
                                <a id="resumeSalary" href="{{ route('mainsalaryemployee.resumeSalary', $data->id) }}"
                                    class="btn btn-sm btn-success mx-3"> تفعيل </a>
                            </div>
                        @endif
                    @elseif ($data->is_archived == 1)
                        <div class="d-flex justify-content-center align-items-center mb-3 mx-3 py-3 rounded bg-info">
                            <span class="text-white">تم أرشفة هذا الراتب</span>
                        </div>
                    @endif

                    <table dir="rtl" cellspacing="1" cellpadding="3" border="2"
                        style="text-align:right;border-color: black; width: 97%;  margin: 0 auto; background-color: lightgray ">
                        <tr>
                            <td style="width: 20%">اسم الموظف</td>
                            <td>{{ $data->emp_name }} (الكود: {{ $data->employee_code }})</td>
                        </tr>
                        <tr>
                            <td style="width: 20%"> الوظيفة</td>
                            <td colspan="2">{{ $data->job }} </td>
                        </tr>
                        {{-- @if ($data['last_salary_remain_blance'] < 0) --}}
                        <tr style="background-color: lightblue">
                            <td colspan="3"> ملحوظة الرصيد المرحل من الشهر السابق
                                {{ $data['last_salary_remain_blance'] * 1 }} رس</td>
                        </tr>
                        {{-- @endif --}}
                    </table>
                    <p class="underPrag mr-3">أولاً : الاستحقاقات</p>
                    <table dir="rtl" cellspacing="1" cellpadding="3" border="2"
                        style="text-align:right;border-color: black; width: 97%;  margin: 0 auto;">
                        <tr>
                            <td rowspan="7" style="width: 10%;-webkit-transform: rotate(-90deg) !important;"> الاستحقاقات
                            </td>
                            <td colspan="2" style="width: 50%; background-color: lightgray !important; "> الراتب الاساسي
                            </td>
                            <td style=" background-color: lightgray !important; ;">{{ $data['emp_sal'] * 1 }} رس</td>
                        </tr>
                        <tr>
                            <td colspan="2">مكافئة مالية</td>
                            <td>{{ $data['reward'] * 1 }} رس</td>
                        </tr>
                        <tr>
                            <td colspan="2">حافز الثابت</td>
                            <td>{{ $data['motivation'] * 1 }} رس</td>
                        </tr>
                        <tr>
                            <td rowspan="2"> بدلات </td>
                            <td>بدلات ثابتة </td>
                            <td>{{ $data['fixed_allowances'] * 1 }} رس</td>
                        </tr>
                        <tr>
                            <td> بدلات متغيرة </td>
                            <td>{{ $data['changable_allowances'] * 1 }} رس</td>
                        </tr>
                        <tr>
                            <td colspan="2"> اضافي الايام </td>
                            <td>
                                @if ($data['additional_days_counter'] > 0)
                                    {{ $data['additional_days_total'] * 1 }} رس <br />
                                    عدد الايام : ({{ $data['additional_days_counter'] * 1 }})
                                @else
                                    0 رس
                                @endif
                            </td>
                        </tr>
                        <tr style=" background-color: lightgreen !important;">
                            <td colspan="2">اجمالي الاستحقاقات</td>
                            <td>{{ $data['total_benefits'] * 1 }} رس</td>
                        </tr>
                    </table>
                    <p class="underPrag mr-3">ثانياً : الاستقطاعات</p>
                    <table dir="rtl" cellspacing="1" cellpadding="3" border="2"
                        style="text-align:right;border-color: black; width: 97%;  margin: 0 auto;">
                        <tr>
                            {{-- <td rowspan=@if (!empty($data['main_salary_employee_discount'])) "{{ count($data['main_salary_employee_discount']) + 12 }}" @else "{{ 12 }}" @endif --}}
                            <td rowspan="9" style="width: 10%;-webkit-transform: rotate(-90deg)!important;"> الاستقطاعات
                            </td>
                            <td colspan="2"> تأمين اجتماعي </td>
                            <td> {{ $data['socialinsurancecutmonthly'] * 1 }} رس</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="width: 50%"> تأمين طبي </td>
                            <td> {{ $data['medicalinsurancecutmonthly'] * 1 }} رس</td>
                        </tr>
                        <tr>
                            <td colspan="2"> الغياب</td>
                            <td>
                                {{-- {{ $data['absence_days_counter'] * 1 }}  --}}
                                @if ($data['absence_days_counter'] > 0)
                                    {{ $data['absence_days_total'] * 1 }} رس <br />
                                    عدد الايام : ({{ $data['absence_days_counter'] * 1 }})
                                @else
                                    0 رس
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"> الجزاءات </td>
                            <td>
                                @if ($data['sanctions_days_counter'] > 0)
                                    {{ $data['sanctions_days_total'] * 1 }} رس <br />
                                    عدد الايام : ({{ $data['sanctions_days_counter'] * 1 }})
                                @else
                                    0 رس
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="2"> السلف </td>
                            <td> سلف شهرية </td>
                            <td>{{ $data['monthly_loan'] * 1 }} رس</td>
                        </tr>
                        <tr>
                            <td> سلف مستديمة </td>
                            <td>{{ $data['permanent_loan'] * 1 }} رس</td>
                        </tr>
                        <tr>
                            <td rowspan="2"> الخصومات </td>
                            <td> الخصومات المالية </td>
                            <td>{{ $data['discount'] * 1 }} رس</td>
                        </tr>
                        <tr>
                            <td> فواتير التليفونات </td>
                            <td>{{ $data['phones'] * 1 }} رس</td>
                        </tr>
                        <tr style=" background-color: lightcoral !important;">
                            <td colspan="2">
                                اجمالي الاستقطاعات</td>
                            <td>{{ $data['total_deduction'] * 1 }} رس</td>
                        </tr>
                    </table>
                    <br>
                    <p class="underPrag mr-3">ثالثاً : صافي الراتب</p>
                    <table dir="rtl" cellspacing="1" cellpadding="3" border="2"
                        style="text-align:right;border-color: black; width: 97%;  margin: 0 auto;">
                        <tr style=" background-color: lightgray ;">
                            <td style="width: 50%;"> صافي الراتب </td>
                            <td>
                                <div class="p-2">
                                    @if ($data['final_the_net'] > 0)
                                        مبلغ مستحق للموظف بقيمة
                                    @elseif ($data['final_the_net'] < 0)
                                        مبلغ مستحق على الموظف بقيمة
                                    @else
                                        متزن
                                    @endif
                                    {{ abs($data['final_the_net'] * 1) }} رس
                                </div>
                            </td>
                        </tr>
                        @if ($data['is_archived'] == 1)
                        <tr style=" background-color: lightgray ;">
                            <td style="width: 50%;"> تمت ارشفة الراتب </td>
                            <td>
                                <div class="p-2">
                                    بتاريخ
                                    {{ $data['archived_date'] }}
                                </div>
                            </td>
                        </tr>
                        @endif
                    </table>
                    <p style="text-align: left; padding-left:20%; font-weight: bold; font-size: 14px; color: black;">
                        {{ date('d-m-Y') }}</p>
                @else
                    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
                @endif

            </div>
        </div>
    </div>

    <!-- .archiveSalaryModal -->
    <div class="modal fade" id="archiveSalaryModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h4 class="modal-title">أرشفة الراتب</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="archiveSalaryModalBody">

                </div>
                <div class="modal-footer justify-content-between bg-secondary">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.archiveSalaryModal -->
@endsection

@section('script')
    {{-- <script src="{{ url('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script> --}}
    <script src="{{ url('assets/admin/js/main_salary_employee/salary_details.js') }}"></script>

    <script>
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>
@endsection
