<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> طباعة راتب </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ url('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css') }}">
    <style>
        /*@media print {
            .hidden-print {
                display: none;
            }
        }*/

        @media print {
            #printButton {
                display: none;
            }

            .footer_bar {
                position: absolute;
                border-top: 1px solid black;
            }

            .header_bar {
                border-bottom: 1px solid black;
            }
        }

        td {
            font-size: 15px !important;
            text-align: center;
        }

        th {
            text-align: center;
        }
    </style>

<body style="padding-top: 20px; font-family: tahoma;">


    <div class="header_bar" style="width: 100%; margin: 0 auto;">
        <table style="width: 70%; float: right;;" dir="rtl">
            <tr>
                <td style="text-align: center;padding: 5px;font-weight: bold;"> <span
                        style=" display: inline-block;
               width: 500px;
               height: 30px;
               text-align: center;
               color: red;
                  border-bottom: 2px solid black; 
                  border-radius: 10px">
                        طباعة راتب موظف للشهر المالي ({{ $financeMonth_data->month->name }} لسنة
                        {{ $financeMonth_data->finance_yr }})
                    </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding: 5px;font-weight: bold;">
                    <span
                        style=" display: inline-block;
                  width: 500px;
                  height: 30px;
                  text-align: center;
                  color: blue;
                  border-bottom: 2px solid black; 
                  border-radius: 10px">
                        طبع بتاريخ @php echo date('Y-m-d'); @endphp
                    </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding: 5px;font-weight: bold;">
                    <span
                        style=" display: inline-block;
                  width: 500px;
                  height: 30px;
                  text-align: center;
                  color: blue;
                  border-bottom: 2px solid black; 
                  border-radius: 10px">
                        طبع بواسطة {{ auth()->user()->name }}
                    </span>
                </td>
            </tr>
        </table>
        <table style="width: 25%;margin-left: 5px;" dir="rtl">
            <tr>
                <td style="text-align:left !important; padding: 5px;">
                    <div style="text-align: center;">
                        <img style="width: 90px; height: 90px; border-radius: 10px;"
                            src="{{ url('assets/admin/uploads') . '/' . $systemData['image'] }}" alt="company_logo">
                        <br />
                        <br />
                        <p>{{ $systemData['company_name'] }}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <br>
    <div class="card-body">
        @if (@isset($data) and !@empty($data))
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
                <tr>
                    <td colspan="3" class="p-3">
                        حالة الراتب :
                        (@if ($data->is_stoped == 0)
                            مفعل
                        @else
                            موقوف
                        @endif)
                        {{-- @if ($data->is_stoped == 0)
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <span class="text-success">هذا الراتب مفعل </span>
                            </div>
                        @else
                            <div class="d-flex justify-content-center align-items-center mb-3 mx-3 py-3 rounded bg-danger">
                                <span class="text-white">هذا الراتب موقوف</span>
                            </div>
                        @endif --}}
                    </td>
                </tr>
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
    <script>
        window.print();
    </script>
</body>

</html>
