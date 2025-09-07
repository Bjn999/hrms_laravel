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
            <td class="d-flex justify-content-around align-items-center @if ($info->is_open == 1) bg-success @endif" style="vertical-align: middle">
                @if ($info->is_open == 1)
                مفتوح
                @elseif ($info->is_open == 2)
                مغلق ومؤرشف
                @else
                بانتظار الفتح
                @endif

                @if (!empty($info->currentYear))
                @if ($info->currentYear['is_open'] == 1)
                @if ($info->is_open == 0 and $info->counterOpenMonth == 0 and $info->counterPreviousMonthWaitingOpen == 0)
                <button class="btn btn-primary btn-sm load_the_open_modal" data-id="{{ $info->id }}">فتح الان</button>
                @endif
                @endif
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


<!-- .modal -->
<div class="modal fade" id="load_open_monthModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-secondary">
            <div class="modal-header">
                <h4 class="modal-title">فتح الشهر المالي</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="load_open_monthModalBody">

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
                {{-- <button type="button" class="btn btn-outline-light">Save changes</button> --}}
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
