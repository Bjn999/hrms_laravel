<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> بحث بجزاءات الأيام الرواتب</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ url('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')}}">
    <style>
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
                <td style="text-align: center;padding: 5px;font-weight: bold;"> <span style=" display: inline-block;
               width: 500px;
               height: 30px;
               text-align: center;
               color: red;
                  border-bottom: 2px solid black; 
                  border-radius: 10px">
                        بحث بجزاءات الأيام برواتب الشهر المالي ({{ $financeMonth_data['month']['name'] }} لسنة {{ $financeMonth_data['finance_yr'] }})
                    </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding: 5px;font-weight: bold;">
                    <span style=" display: inline-block;
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
                    <span style=" display: inline-block;
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
                        <img style="width: 90px; height: 90px; border-radius: 10px;" src="{{ url('assets/admin/uploads').'/'.$systemData['image'] }}" alt="company_logo">
                        <br />
                        <br />
                        <p>{{ $systemData['company_name'] }}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <br>

    @if (@isset($data) && !@empty($data) && count($data) > 0)
    <table dir="rtl" id="example2" class="table table-bordered table-hover" style="width: 98%;margin: 0 auto;">
        <thead style="background-color: lightblue">

            <th style="vertical-align: middle; width: 5%"> م </th>
            <th style="vertical-align: middle; width: 20%"> اسم الموظف </th>
            <th style="vertical-align: middle; width: 5%"> رقم الموظف </th>
            <th style="vertical-align: middle; width: 10%"> نوع الجزاء </th>
            <th style="vertical-align: middle; width: 10%"> عدد الأيام </th>
            <th style="vertical-align: middle; width: 10%"> اجمالي المبلغ </th>
            <th style="vertical-align: middle; width: 15%"> التاريخ </th>
            <th style="vertical-align: middle; width: 15%"> بواسطة </th>
            <th style="vertical-align: middle; width: 10%"> الحالة </th>

        </thead>


        {{-- </thead> --}}
        <tbody>
            @php $i = 1; @endphp

            @foreach ($data as $info )
            <tr>
                <td style="vertical-align: middle"> {{ $i }} </td>
                <td style="vertical-align: middle"> {{ $info->employee->emp_name }} </td>
                <td style="vertical-align: middle"> {{ $info->employee_code }} </td>
                <td style="vertical-align: middle">
                    @if ($info->sanctions_type == 1)
                    جزاء أيام
                    @elseif ($info->sanctions_type == 2)
                    جزاء بصمة
                    @elseif ($info->sanctions_type == 3)
                    جزاء تحقيق
                    @endif
                </td>
                {{-- <td style="vertical-align: middle"> {{ $info->value * 1 }} </td> --}}
                <td style="vertical-align: middle">
                    @if($info->value == 1)
                    يوم واحد
                    @elseif ($info->value == 2)
                    يومان
                    @elseif ($info->value > 2 and $info->value < 11) {{ $info->value * 1 }} أيام @elseif ($info->value > 10)
                        {{ $info->value * 1 }} يوم
                        @endif
                </td>
                <td style="vertical-align: middle"> {{ $info->total * 1 }} رس </td>
                <td style="vertical-align: middle">
                    @php
                    $dt = new DateTime($info->created_at);
                    $date = $dt->format("Y-m-d");
                    $time = $dt->format("h:i");
                    $newTime = $dt->format("A") == 'AM' ? 'صباحاً' : 'مساءاً';
                    @endphp
                    {{ $date }} <br />
                    {{ $time }}
                    {{ $newTime }}
                </td>
                <td style="vertical-align: middle">
                    {{-- @php
                    $dt = new DateTime($info->created_at);
                    $date = $dt->format("Y-m-d");
                    $time = $dt->format("h:i");
                    $newTime = $dt->format("A") == 'AM' ? 'صباحاً' : 'مساءاً';
                    @endphp
                    {{ $date }} <br />
                    {{ $time }}
                    {{ $newTime }} <br /> --}}
                    {{ $info->added->name }}
                </td>
                <td class="@if ($info->is_approved == 1) bg-success @else bg-info @endif" style="vertical-align: middle">
                    @if ($info->is_archived == 1)
                    مؤرشف
                    @else
                    غير مؤرشف
                    @endif
                </td>

            </tr>

            @php $i++; @endphp

            @endforeach
            <tr>
                <td style="background-color:lightsalmon; font-weight: bold" colspan="4"> الاجمالي

                </td>
                <td style="background-color: lightgreen;" colspan="1">
                    @if($totals['value_sum'] == 1)
                    يوم واحد
                    @elseif ($totals['value_sum'] == 2)
                    يومين
                    @elseif ($totals['value_sum'] > 2 and $totals['value_sum'] < 11) {{ $totals['value_sum'] * 1 }} أيام @elseif ($totals['value_sum']>= 11)
                        {{ $totals['value_sum'] * 1 }} يوم
                        @endif
                </td>
                <td style="background-color: lightgreen;" colspan="1">
                    {{ $totals['total_sum'] * 1 }} رس
                </td>
            </tr>
        </tbody>
    </table>
    <br>

    @else
    <div class="clearfix"></div>
    <p class="" style="text-align: center; font-size: 16px;font-weight: bold; color: brown">
        عفوا لاتوجد بيانات لعرضها !!
    </p>

    @endif


    <br>
    <p style="
         padding: 10px 10px 0px 10px;
         margin: 0 auto;
         bottom: 0;
         width: 100%;
         /* Height of the footer*/ 
         text-align: center; font-size: 16px; font-weight: bold;
         " class="footer_bar"> {{ $systemData['address'] }} - <a href="tel:{{ $systemData['phones'] }}"> {{ $systemData['phones'] }} </a> </p>
    <div class="clearfix"></div> <br>
    <p class="text-center">
        <button onclick="window.print()" class="btn btn-success btn-md" id="printButton">طباعة</button>
    </p>
</body>

</html>
