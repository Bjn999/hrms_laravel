@extends('layouts.admin')

@section('title')
الأجور والرواتب
@endsection

@section('contentheader')
قائمة الأجور والرواتب
@endsection

@section('contentheaderactivelink')
<a href="{{ route('mainsalaryrecord.index') }}">الأجور والرواتب</a>
@endsection

@section('contentheaderactive')
عرض
@endsection

@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                بيانات الأجور والرواتب
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

            <div class="col-md-12 mt-3">
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

        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {

        $(document).on("change", "#finance_yr", function(e) {
            ajax_search();
        });

        function ajax_search() {
            var finance_yr = $('#finance_yr').val();

            jQuery.ajax({
                url: "{{ route('mainsalaryrecord.ajaxSearch') }}"
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , finance_yr: finance_yr
                }
                , success: function(response) {
                    $("#ajax_response_searchDiv").html(response);
                }
                , error: function() {
                    alert("عفواً حدث خطأ ما");
                }
            });
        }

        $(document).on("click", "#ajax_pagination_search a", function(e) {
            e.preventDefault();
            var finance_yr = $('#finance_yr').val();
            var linkUrl = $(this).attr('href');

            jQuery.ajax({
                url: linkUrl
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , finance_yr: finance_yr
                }
                , success: function(response) {
                    $("#ajax_response_searchDiv").html(response);
                }
                , error: function() {
                    alert("عفواً حدث خطأ ما");
                }
            , });
        });

        //// bring load_open_monthModal view and show in Popup modal to determine the pasma start and end date 
        $(document).on("click", ".load_the_open_modal", function(e) {
            var id = $(this).data('id');

            jQuery.ajax({
                url: "{{ route('mainsalaryrecord.load_open_month') }}"
                , method: 'post' //////////////////////////////////////////////////
                , datatype: 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , id: id
                , }
                , success: function(data) {
                    $("#load_open_monthModalBody").html(data);
                    $("#load_open_monthModal").modal("show");
                }
                , error: function() {
                    alert("عفواً حدث خطأ ما");
                }
            })
        });
    });

</script>
@endsection
