@extends('layouts.admin')

@section('title')
السنوات المالية
@endsection

@section('contentheader')
قائمة الضبط
@endsection

@section('contentheaderactivelink')
<a href="{{ route('finance_calenders.index') }}">السنوات المالية</a>
@endsection

@section('contentheaderactive')
عرض
@endsection

@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                بيانات السنوات المالية
                <a href=" {{ route('finance_calenders.create') }} " class="btn btn-sm btn-success">إضافة جديد</a>
            </h3>
        </div>

        <div class="card-body">
            @if (@isset($data) and !@empty($data) and count($data) > 0)
            <table id="example2" class="table table-bordered table-hover text-center">
                <thead class="custom_thead">
                    <th style="vertical-align: middle"> كود السنة </th>
                    <th style="vertical-align: middle"> وصف السنة </th>
                    <th style="vertical-align: middle"> تاريخ البداية </th>
                    <th style="vertical-align: middle"> تاريخ النهاية </th>
                    <th style="vertical-align: middle"> الاضافة بواسطة </th>
                    <th style="vertical-align: middle"> التحديث بواسطة </th>
                    <th> </th>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr>
                        <td style="vertical-align: middle"> {{ $info->finance_yr }} </td>
                        <td style="vertical-align: middle"> {{ $info->finance_yr_desc }} </td>
                        <td style="vertical-align: middle"> {{ $info->start_date }} </td>
                        <td style="vertical-align: middle"> {{ $info->end_date }} </td>
                        <td style="vertical-align: middle"> {{ $info->added->name }} </td>
                        <td style="vertical-align: middle">
                            @if ($info->updated_by > 0)
                            {{ $info->updatedby->name }}
                            @else
                            لا يوجد
                            @endif
                        </td>
                        <td style="vertical-align: middle">
                            {{-- check if the finance year is open or not --}}
                            @if ($info->is_open == 0)
                            {{-- check if there are another finance years is opened --}}
                            @if ($checkdataopen == 0)
                            <a href="{{ route('finance_calenders.do_open', $info->id) }}" class="btn btn-primary">فتح</a>
                            @endif
                            <a href="{{ route('finance_calenders.edit', $info->id) }}" class="btn btn-success">تعديل</a>
                            <a href="{{ route('finance_calenders.delete', $info->id) }}" class="btn r_u_sure btn-danger">حذف</a>
                            {{-- <button class="btn btn-info show_year_monthes" data-id="{{ $info->id }}">عرض الشهور</button> --}}
                            @else
                            سنة مالية مفتوحة
                            @endif
                            <button class="btn btn-info show_year_monthes" data-id="{{ $info->id }}">عرض الشهور</button>
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

<!-- .modal -->
<div class="modal fade" id="show_year_monthesModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-info">
            <div class="modal-header">
                <h4 class="modal-title">عرض الشهور للسنة المالية</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="show_year_monthesModalBody">

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-outline-light">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


@endsection

@section('script')

<script>
    $(document).ready(function() {
        $(document).on('click', '.show_year_monthes', function() {
            var id = $(this).data('id');

            jQuery.ajax({
                url: "{{ route('finance_calenders.show_year_monthes') }}"
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    '_token': '{{ csrf_token() }}'
                    , 'id': id
                }
                , success: function(data) {
                    $("#show_year_monthesModalBody").html(data);
                    $("#show_year_monthesModal").modal("show");
                }
                , error: function() {

                }

            });
        })
    });

</script>

@endsection
