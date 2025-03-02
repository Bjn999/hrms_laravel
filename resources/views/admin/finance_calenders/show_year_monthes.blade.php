

@if (@isset($finance_months_periods) and !@empty($finance_months_periods))
<table id="example2" class="table table-bordered table-hover text-center">
    <thead class="custom_thead">
        <th> اسم الشهر عربي </th>
        <th> اسم الشهر انجليزي </th>
        <th> تاريخ البداية </th>
        <th> تاريخ النهاية </th>
        <th> عدد الأيام </th>
        <th> حالة الشهر </th>
        <th> الاضافة بواسطة </th>
        <th> التحديث بواسطة </th>
    </thead>
    <tbody>
        @foreach ($finance_months_periods as $info)
            <tr>
                <td> {{ $info->month->name }} </td>
                <td> {{ $info->month->name_en }} </td>
                <td> {{ $info->start_date_m }} </td>
                <td> {{ $info->end_date_m }} </td>
                <td> {{ $info->number_of_days }} </td>
                <td> 
                    @if ($info->is_open == 1)
                        مفتوح
                    @elseif ($info->is_open == 2)
                        مؤرشف
                    @else
                        مغلق
                    @endif  
                </td>
                <td> {{ $info->added->name }} </td>
                <td>
                    @if ($info->updated_by>0)
                        {{ $info->updatedby->name }}
                    @else
                        لا يوجد
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@else
    <p class="text-danger text-center font-weight-bold">لا توجد بيانات لعرضها</p>
@endif

