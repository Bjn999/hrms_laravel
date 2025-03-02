@extends('layouts.admin')

@section('title')
    أنواع الوظائف
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('jobs_categories.index') }}">أنواع الوظائف</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> 
                    بيانات أنواع وظائف الموظفين 
                    <a href=" {{ route('jobs_categories.create') }} " class="btn btn-sm btn-success">إضافة جديد</a>
                </h3>
            </div>
            <div class="card-body">
                @if (@isset($data) and !@empty($data) and count($data) > 0)
                <table id="example2" class="table table-bordered table-hover text-center">
                    <thead class="custom_thead">
                        <tr>
                            <th style="vertical-align: middle"> اسم الوظيفة </th>
                            <th style="vertical-align: middle"> حالة التفعيل </th>
                            <th style="vertical-align: middle"> الإضافة بواسطة </th>
                            <th style="vertical-align: middle"> التحديث بواسطة </th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $data as $info )
                        <tr>
                            <td style="vertical-align: middle"> {{ $info->name }} </td>
                            <td style="vertical-align: middle" @if ($info->active==1) class="bg-success" @else class="bg-danger" @endif > @if ($info->active==1) مفعل @else معطل @endif </td>
                            
                            <td style="vertical-align: middle"> 
                                @php
                                    $dt = new DateTime($info->created_at);
                                    $date = $dt->format("Y-m-d");
                                    $time = $dt->format("h-i");
                                    $newDateTime = date("A", strtotime($time));
                                    //$newDateTime = $dt->format("AM");
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
                                        $newDateTime = date("A", strtotime($time));
                                        //$newDateTime = $dt->format("AM");
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

                                <a href="{{ route('jobs_categories.edit', $info->id) }}" class="btn btn-success">تعديل</a>
                                <a href="{{ route('jobs_categories.destroy', $info->id) }}" class="btn btn-danger r_u_sure">حذف</a>

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



