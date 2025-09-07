@extends('layouts.admin')

@section('title')
الشفتات
@endsection

@section('contentheader')
قائمة الضبط
@endsection

@section('contentheaderactivelink')
<a href="{{ route('shiftstypes.index') }}">الشفتات</a>
@endsection

@section('contentheaderactive')
عرض
@endsection

@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                بيانات أنواع شفتات الموظفين
                <a href=" {{ route('shiftstypes.create') }} " class="btn btn-sm btn-success">إضافة جديد</a>
            </h3>
        </div>

        <div class="row mx-2 mt-2">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="type">نوع الشفت:</label>
                    <select name="type_search" id="type_search" class="form-control">
                        <option value="all">بحث الكل</option>
                        <option value="1">صباحي</option>
                        <option value="2">مسائي</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="email">من عدد ساعات:</label>
                    <input type="text" name="hour_from_range" id="hour_from_range" class="form-control" value="" oninput="this.value = this.value.replace(/[^0-9]/g,'')">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="email">إلى عدد ساعات:</label>
                    <input type="text" name="hour_to_range" id="hour_to_range" class="form-control" value="" oninput="this.value = this.value.replace(/[^0-9]/g,'')">
                </div>
            </div>
        </div>
        <div class="card-body" id="ajax_response_searchDiv">
            @if (@isset($data) and !@empty($data) and count($data) > 0)
            <table id="example2" class="table table-bordered table-hover text-center">
                <thead class="custom_thead">
                    <tr>
                        <th style="vertical-align: middle"> نوع الشفت </th>
                        <th style="vertical-align: middle"> ساعة البداية </th>
                        <th style="vertical-align: middle"> ساعة النهاية </th>
                        <th style="vertical-align: middle"> عدد ساعات الدوام </th>
                        <th style="vertical-align: middle"> حالة التفعيل </th>
                        <th style="vertical-align: middle"> الإضافة بواسطة </th>
                        <th style="vertical-align: middle"> التحديث بواسطة </th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $data as $info )
                    <tr>
                        <td style="vertical-align: middle"> @if ($info->type==1) صباحي @else مسائي @endif </td>
                        <td style="vertical-align: middle">
                            @php
                            $dt = new DateTime($info->from_time);
                            $time = date("h-i", strtotime($info->from_time));
                            $newDateTime = date("A", strtotime($info->from_time));
                            $newDateTimeType = (($newDateTime == "AM") ? "صباحاً" : "مساءاً")
                            @endphp

                            {{ $time }}
                            {{ $newDateTimeType }}
                        </td>
                        <td style="vertical-align: middle">
                            @php
                            $dt = new DateTime($info->to_time);
                            $time = date("h-i", strtotime($info->to_time));
                            $newDateTime = date("A", strtotime($info->to_time));
                            $newDateTimeType = (($newDateTime == "AM") ? "صباحاً" : "مساءاً")
                            @endphp

                            {{ $time }}
                            {{ $newDateTimeType }}
                        </td>
                        <td style="vertical-align: middle"> {{ $info->total_hour*1 }} </td>
                        <td style="vertical-align: middle" @if ($info->active==1) class="bg-success" @else class="bg-danger" @endif > @if ($info->active==1) مفعل @else معطل @endif </td>

                        <td style="vertical-align: middle">
                            @php
                            $dt = new DateTime($info->created_at);
                            $date = $dt->format("Y-m-d");
                            $time = $dt->format("h-i");
                            $newDateTime = $dt->format("A");
                            //$newDateTime = date("A", strtotime($time));
                            $newDateTimeType = (($newDateTime == "AM") ? "صباحاً" : "مساءاً")
                            @endphp

                            {{ $date }} <br>
                            {{ $time }}
                            {{ $newDateTimeType }} <br>

                            بواسطة
                            {{ $info->added->name }}
                        </td>
                        <td style="vertical-align: middle">
                            @if ($info->updated_by>0)
                            @php
                            $dt = new DateTime($info->updated_at);
                            $date = $dt->format("Y-m-d");
                            $time = $dt->format("h-i");
                            $newDateTime = $dt->format("A");
                            //$newDateTime = date("A", strtotime($time));
                            $newDateTimeType = (($newDateTime == "AM") ? "صباحاً" : "مساءاً")
                            @endphp

                            {{ $date }} <br>
                            {{ $time }}
                            {{ $newDateTimeType }} <br>

                            بواسطة
                            {{ $info->updatedby->name }}
                            @else
                            لا يوجد
                            @endif
                        </td>
                        <td style="vertical-align: middle">

                            <a href="{{ route('shiftstypes.edit', $info->id) }}" class="btn btn-success">تعديل</a>
                            <a href="{{ route('shiftstypes.destroy', $info->id) }}" class="btn btn-danger r_u_sure">حذف</a>

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

<script>
    $(document).ready(function() {

        $(document).on("change", "#type_search", function(e) {
            ajax_search();
        });
        $(document).on("input", "#hour_from_range", function(e) {
            ajax_search();
        });
        $(document).on("input", "#hour_to_range", function(e) {
            ajax_search();
        });

        function ajax_search() {
            var type_search = $('#type_search').val();
            var hour_from_range = $('#hour_from_range').val();
            var hour_to_range = $('#hour_to_range').val();

            jQuery.ajax({
                url: "{{ route('shiftstypes.ajaxSearch') }}"
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , type_search: type_search
                    , hour_from_range: hour_from_range
                    , hour_to_range: hour_to_range
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
            var type_search = $('#type_search').val();
            var hour_from_range = $('#hour_from_range').val();
            var hour_to_range = $('#hour_to_range').val();
            var linkUrl = $(this).attr('href');

            jQuery.ajax({
                url: linkUrl
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , type_search: type_search
                    , hour_from_range: hour_from_range
                    , hour_to_range: hour_to_range
                }
                , success: function(response) {
                    $("#ajax_response_searchDiv").html(response);
                }
                , error: function() {
                    alert("عفواً حدث خطأ ما");
                }
            , });
        });

    });

</script>

@endsection
