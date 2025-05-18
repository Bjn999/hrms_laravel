@extends('layouts.admin')

@section('title')
شؤون الموظفين
@endsection

@section('css')
<!-- Select2 -->
<link rel="stylesheet" href="{{ url('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<style>
    .emp_logo {
        border-radius: 50%;
        width: 80px;
        height: 80px;
    }

</style>
@endsection

@section('contentheader')
شؤون الموظفين
@endsection

@section('contentheaderactivelink')
<a href="{{ route('employees.index') }}">بيانات الموظفين</a>
@endsection

@section('contentheaderactive')
عرض
@endsection

@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                بيانات الموظفين
                <a href=" {{ route('employees.create') }} " class="btn btn-sm btn-success">إضافة جديد</a>
            </h3>
        </div>
        <div class="row mx-2 mt-2">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="searchBy_code_search">
                        <input type="radio" name="radio_code_search" checked value="employee_code"> رقم الموظف
                        <input type="radio" name="radio_code_search" value="zketo_code"> كود بصمة 
                    </label>
                    <input type="text" name="searchBy_code_search" id="searchBy_code_search" class="form-control" value="">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="emp_name_search">بحث بالاسم:</label>
                    <input type="text" name="emp_name_search" id="emp_name_search" class="form-control" value="">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="branch_id_search">بحث بالفرع:</label>
                    <select name="branch_id_search" id="branch_id_search" class="form-control select2">
                        <option selected value="all">الكل</option>
                        @if (isset($other['branches']) && !empty($other['branches']))
                        @foreach ($other['branches'] as $info)
                        <option value="{{ $info->id }}">{{ $info->name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="emp_departments_id_search">بحث بالإدارة:</label>
                    <select name="emp_departments_id_search" id="emp_departments_id_search" class="form-control select2">
                        <option selected value="all">الكل</option>
                        @if (isset($other['departments']) and !empty($other['departments']))
                        @foreach ($other['departments'] as $info)
                        <option value="{{ $info->id }}">{{ $info->name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="emp_job_id_search">بحث بالوظيفة:</label>
                    <select name="emp_job_id_search" id="emp_job_id_search" class="form-control select2">
                        <option selected value="all">الكل</option>
                        @if (isset($other['jobs']) and !empty($other['jobs']))
                        @foreach ($other['jobs'] as $info)
                        <option value="{{ $info->id }}">{{ $info->name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="functional_status_search">بحث بالحالة الوظيفية:</label>
                    <select name="functional_status_search" id="functional_status_search" class="form-control">
                        <option selected value="all"> الكل</option>
                        <option value="1">في الخدمة</option>
                        <option value="0">خارج الخدمة</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="sal_cash_or_visa_search">بحث بطريقة صرف الراتب:</label>
                    <select name="sal_cash_or_visa_search" id="sal_cash_or_visa_search" class="form-control">
                        <option selected value="all">الكل</option>
                        <option value="1">نقداً</option>
                        <option value="2">بنك/فيزا</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="emp_gender_search">بحث بنوع الجنس:</label>
                    <select name="emp_gender_search" id="emp_gender_search" class="form-control">
                        <option selected value="all">الكل</option>
                        <option value="1">ذكر</option>
                        <option value="2">أنثى</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body" id="ajax_response_searchDiv">
            @if (isset($data) and !empty($data) and count($data) > 0)
            <table id="example2" class="table table-bordered table-hover text-center">
                <thead class="custom_thead">
                    <tr>
                        <th style="vertical-align: middle"> الكود </th>
                        <th style="vertical-align: middle"> الاسم </th>
                        <th style="vertical-align: middle"> الفرع </th>
                        <th style="vertical-align: middle"> الإدارة </th>
                        <th style="vertical-align: middle"> الوظيفة </th>
                        <th style="vertical-align: middle"> حالة الوظيفية </th>
                        <th style="vertical-align: middle"> الصورة </th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $data as $info )
                    <tr>
                        <td style="vertical-align: middle"> {{ $info->employee_code }} </td>
                        <td style="vertical-align: middle"> {{ $info->emp_name }} </td>
                        <td style="vertical-align: middle"> {{ $info->branch->name }} </td>
                        <td style="vertical-align: middle"> {{ $info->department->name }} </td>
                        <td style="vertical-align: middle"> {{ $info->job->name }} </td>
                        <td style="vertical-align: middle" @if ($info->functional_status == 1) class="bg-success" @else class="bg-danger" @endif> @if ($info->functional_status == 1) في الخدمة @else خارج الخدمة @endif </td>

                        <td style="vertical-align: middle">
                            @if (!empty($info->emp_photo))
                            <img class="emp_logo rounded-circle" src="{{ url('assets/admin/uploads').'/'.$info->emp_photo }}">
                            @else
                            @if ($info->emp_gender == 1)
                            <img class="emp_logo rounded-circle" src="{{ url('assets/admin/imgs/default_m.png') }}">
                            @else
                            <img class="emp_logo rounded-circle" src="{{ url('assets/admin/imgs/default_f.png') }}">
                            @endif
                            @endif
                        </td>

                        <td style="vertical-align: middle">

                            <a href="{{ route('employees.edit', $info->id) }}" class="btn btn-success">تعديل</a>
                            <a href="{{ route('employees.destroy', $info->id) }}" class="btn btn-danger r_u_sure">حذف</a>
                            <a href="{{ route('employees.show', $info->id) }}" class="btn btn-info">المزيد</a>

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

<script src="{{ url('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>

<script>
    //Initialize Select2 Elements
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Searching in employees 
    $(document).ready(function() {

        $(document).on("input", "#searchBy_code_search", function(e) {
            ajax_search();
        });
        $(document).on("input", "#emp_name_search", function(e) {
            ajax_search();
        });
        $(document).on("change", "#branch_id_search", function(e) {
            ajax_search();
        });
        $(document).on("change", "#emp_departments_id_search", function(e) {
            ajax_search();
        });
        $(document).on("change", "#emp_job_id_search", function(e) {
            ajax_search();
        });
        $(document).on("change", "#functional_status_search", function(e) {
            ajax_search();
        });
        $(document).on("change", "#sal_cash_or_visa_search", function(e) {
            ajax_search();
        });
        $(document).on("change", "#emp_gender_search", function(e) {
            ajax_search();
        });
        $('input[type=radio][name=radio_code_search]').change(function(e) {
            ajax_search();
        });

        // Ajax Search 
        function ajax_search() {
            var radio_code_search = $('input[type=radio][name=radio_code_search]:checked').val();
            var searchBy_code = $('#searchBy_code_search').val();
            var emp_name = $('#emp_name_search').val();
            var branch_id = $('#branch_id_search').val();
            var emp_departments_id = $('#emp_departments_id_search').val();
            var emp_job_id = $('#emp_job_id_search').val();
            var functional_status = $('#functional_status_search').val();
            var sal_cash_or_visa = $('#sal_cash_or_visa_search').val();
            var emp_gender = $('#emp_gender_search').val();

            jQuery.ajax({
                url: "{{ route('employees.ajax_search') }}"
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , radio_code_search: radio_code_search
                    , searchBy_code: searchBy_code
                    , emp_name: emp_name
                    , branch_id: branch_id
                    , emp_departments_id: emp_departments_id
                    , emp_job_id: emp_job_id
                    , functional_status: functional_status
                    , sal_cash_or_visa: sal_cash_or_visa
                    , emp_gender: emp_gender
                }
                , success: function(response) {
                    $("#ajax_response_searchDiv").html(response);
                }
                , error: function() {
                    alert("عفواً حدث خطأ ما");
                }
            });
        }

        // Ajax Pagination 
        $(document).on("click", "#ajax_pagination_search a", function(e) {
            e.preventDefault();
            var radio_code_search = $('input[type=radio][name=radio_code_search]:checked').val();
            var searchBy_code = $('#searchBy_code_search').val();
            var emp_name = $('#emp_name_search').val();
            var branch_id = $('#branch_id_search').val();
            var emp_departments_id = $('#emp_departments_id_search').val();
            var emp_job_id = $('#emp_job_id_search').val();
            var functional_status = $('#functional_status_search').val();
            var sal_cash_or_visa = $('#sal_cash_or_visa_search').val();
            var emp_gender = $('#emp_gender_search').val();

            var linkUrl = $(this).attr('href');

            jQuery.ajax({
                url: linkUrl
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , radio_code_search: radio_code_search
                    , searchBy_code: searchBy_code
                    , emp_name: emp_name
                    , branch_id: branch_id
                    , emp_departments_id: emp_departments_id
                    , emp_job_id: emp_job_id
                    , functional_status: functional_status
                    , sal_cash_or_visa: sal_cash_or_visa
                    , emp_gender: emp_gender
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
