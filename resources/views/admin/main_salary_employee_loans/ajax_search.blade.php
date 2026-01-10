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
            <td style="vertical-align: middle" class="d-flex justify-content-around align-items-center">
                @if ($info->is_open == 1)
                مفتوح
                @elseif ($info->is_open == 2)
                مغلق ومؤرشف
                @else
                بانتظار الفتح
                @endif

                @if ($info->is_open != 0)
                <a href="{{ route('mainsalaryreward.show', $info->id) }}" class="btn btn-success btn-sm">عرض</a>
                @endif

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="col-md-12 mt-3" id="ajax_pagination_search">
    {{ $data->links('pagination::bootstrap-5') }}
</div>

@else
<p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
@endif

