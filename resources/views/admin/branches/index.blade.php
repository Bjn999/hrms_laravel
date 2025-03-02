@extends('layouts.admin')

@section('title')
    الفروع
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('branches.index') }}">الفروع</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> 
                    بيانات الفروع 
                    <a href=" {{ route('branches.create') }} " class="btn btn-sm btn-success">إضافة جديد</a>
                </h3>
            </div>
            <div class="card-body">
                @if (@isset($data) and !@empty($data) and count($data) > 0)
                <table id="example2" class="table table-bordered table-hover text-center">
                    <thead class="custom_thead">
                        <tr>
                            <th style="vertical-align: middle"> كود الفرع </th>
                            <th style="vertical-align: middle"> اسم الفرع </th>
                            <th style="vertical-align: middle"> العنوان </th>
                            <th style="vertical-align: middle"> الهاتف </th>
                            <th style="vertical-align: middle"> البريد الإلكتروني </th>
                            <th style="vertical-align: middle"> حالة التفعيل </th>
                            <th style="vertical-align: middle"> الإضافة بواسطة </th>
                            <th style="vertical-align: middle"> التحديث بواسطة </th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $data as $info )
                        <tr>
                            <td style="vertical-align: middle"> {{ $info->id }} </td>
                            <td style="vertical-align: middle"> {{ $info->name }} </td>
                            <td style="vertical-align: middle"> {{ $info->address }} </td>
                            <td style="vertical-align: middle"> {{ $info->phones }} </td>
                            <td style="vertical-align: middle"> {{ $info->email }} </td>
                            <td style="vertical-align: middle" @if ($info->active==1) class="bg-success" @else class="bg-danger" @endif > @if ($info->active==1) مفعل @else معطل @endif </td>
                            
                            <td style="vertical-align: middle"> {{ $info->added->name }} </td>
                            <td style="vertical-align: middle">
                                @if ($info->updated_by>0)
                                    {{ $info->updatedby->name }}
                                @else
                                    لا يوجد
                                @endif
                            </td>
                            <td style="vertical-align: middle">

                                <a href="{{ route('branches.edit', $info->id) }}" class="btn btn-success">تعديل</a>
                                <a href="{{ route('branches.destroy', $info->id) }}" class="btn btn-danger r_u_sure">حذف</a>

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
        
        $(document).ready(function () {
            $(document).on('click', '.show_year_monthes', function () {
                var id = $(this).data('id');
                // alert("Ali")
                jQuery.ajax({
                    url: "{{ route('finance_calenders.show_year_monthes') }}",
                    type: 'post',
                    'dataType': 'html',
                    cache: false,
                    data: { '_token': '{{ csrf_token() }}', 'id': id },
                    success: function (data) {
                        $("#show_year_monthesModalBody").html(data);
                        $("#show_year_monthesModal").modal("show");
                    },
                    error:function () {

                    }
                    
                });
            })
        });

    </script>

@endsection


