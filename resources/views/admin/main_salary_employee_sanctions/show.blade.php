@extends('layouts.admin')

@section('title')
الأجور والرواتب
@endsection

@section('css')
<!-- Select2 -->
<link rel="stylesheet" href="{{ url('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<style>
    .modal-2xl {
        min-width: 100%;
    }

</style>
@endsection

@section('contentheader')
قائمة الأجور والرواتب
@endsection

@section('contentheaderactivelink')
<a href="{{ route('mainsalarysanction.index') }}">جزاءات الأيام</a>
@endsection

@section('contentheaderactive')
عرض
@endsection

@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                بيانات جزاءات الأيام برواتب الشهر المالي ({{ $financeMonth_data['month']['name'] }} لسنة {{ $financeMonth_data['finance_yr'] }})
            </h3>
        </div>
        @if($financeMonth_data['is_open'] == 1)
        <button class="btn btn-md btn-success col-md-2 m-1" data-toggle="modal" data-target="#add_sanctionModal">
            إضافة جديد
            <i class="fas fa-plus ml-3"></i>
        </button>
        @endif
        <form method="POST" action="{{ route('mainsalarysanction.printSearch') }}" target="_blank">
            @csrf
            <input type="hidden" id="finance_month_period_id" name="finance_month_period_id" value="{{ $financeMonth_data['id'] }}" />
            <div class="row" style="padding: 0 5px">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="employee_code_search_sanction">بحث برقم الموظف:</label>
                        <select class="form-control select2" id="employee_code_search_sanction" name="employee_code_search_sanction">
                            <option selected value="all">غير محدد</option>
                            @if (isset($employees_for_search) and !empty($employees_for_search))
                            @foreach ($employees_for_search as $info)
                            <option value="{{ $info->employee_code }}" data-salary="{{ $info->emp_sal }}">({{ $info->employee_code }}) {{ $info->emp_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="sanctions_type_search_sanction">بحث بنوع الجزاء:</label>
                        <select class="form-control" id="sanctions_type_search_sanction" name="sanctions_type_search_sanction">
                            <option selected value="all">غير محدد</option>
                            <option value="1">جزاء أيام</option>
                            <option value="2">جزاء بصمة</option>
                            <option value="3">جزاء تحقيق</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="is_archived_search_sanction">بحث بالحالة:</label>
                        <select class="form-control" id="is_archived_search_sanction" name="is_archived_search_sanction">
                            <option selected value="all">غير محدد</option>
                            <option value="1">مؤرشف</option>
                            <option value="0">غير مؤرشف (مفتوح)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group text-center">
                        <button type="submet" class="btn btn-lg btn-primary">
                            طباعة البحث
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="card-body" id="ajax_response_searchDiv" style="padding: 0 5px">
            @if (@isset($data) and !@empty($data) and count($data) > 0)
            <table id="example2" class="table table-bordered table-hover text-center">
                <thead class="custom_thead">
                    <th style="vertical-align: middle"> اسم الموظف </th>
                    <th style="vertical-align: middle"> نوع الجزاء </th>
                    <th style="vertical-align: middle"> عدد الأيام </th>
                    <th style="vertical-align: middle"> اجمالي قيمة الجزاء </th>
                    <th style="vertical-align: middle"> تاريخ الاضافة </th>
                    <th style="vertical-align: middle"> تاريخ التحديث </th>
                    <th style="vertical-align: middle"> الحالة </th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr>
                        <td style="vertical-align: middle"> 
                        {{ $info->employee->emp_name }} 
                        @if(!empty($info->notes))
                            <br/>
                            <br/>
                            <span style="color: brown"> 
                            <i class="fa-solid fa-note-sticky"></i>
                            ملاحظة: 
                            </span> 
                            {{ $info->notes }}
                        @else
                            <br/>
                            <br/>
                            <span style="color: brown"> 
                            <i class="fa-solid fa-note-sticky"></i>
                            لا توجد ملاحظة 
                            </span> 
                        @endif
                        </td>
                        <td style="vertical-align: middle">
                            @if ($info->sanctions_type == 1)
                            جزاء أيام
                            @elseif ($info->sanctions_type == 2)
                            جزاء بصمة
                            @elseif ($info->sanctions_type == 3)
                            جزاء تحقيق
                            @endif
                        </td>
                        <td style="vertical-align: middle">
                            @if($info->value == 1)
                            يوم واحد
                            @elseif ($info->value == 2)
                            يومان
                            @elseif ($info->value > 2 and $info->value < 11) {{ $info->value * 1 }} أيام @elseif ($info->value > 10)
                                {{ $info->value * 1 }} يوم
                                @endif
                                {{-- ({{ $info->value * 1 }}) --}}
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
                            {{ $newTime }} <br />
                            {{ $info->added->name }}
                        </td>
                        <td style="vertical-align: middle">
                            @if($info->updated_by > 0)
                            @php
                            $dt = new DateTime($info->updated_at);
                            $date = $dt->format("Y-m-d");
                            $time = $dt->format("h:i");
                            $newTime = $dt->format("A") == 'AM' ? 'صباحاً' : 'مساءاً';
                            @endphp
                            {{ $date }} <br />
                            {{ $time }}
                            {{ $newTime }} <br />
                            {{ $info->updatedby->name }}
                            @else
                            لا يوجد
                            @endif
                        </td>
                        <td class="@if ($info->is_approved == 1) bg-success @else bg-info @endif" style="vertical-align: middle">
                            @if ($info->is_archived == 1)
                            مؤرشف
                            @else
                            غير مؤرشف
                            @endif
                        </td>
                        <td style="vertical-align: middle">

                            <button data-id="{{ $info->id }}" data-main_salary_employee_id="{{ $info->main_salary_employee_id  }}" class="btn btn-success edit_sanction">تعديل</button>
                            <button data-id="{{ $info->id }}" data-main_salary_employee_id="{{ $info->main_salary_employee_id  }}" class="btn btn-danger delete_sanction">حذف</button>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @else
            <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
            @endif

        </div>
    </div>
</div>

<!-- .addModal -->
<div class="modal fade" id="add_sanctionModal">
    <div class="modal-dialog modal-2xl">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">اضافة جزاءات للموظفين</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="add_sanctionModalBody">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="employee_code_add_sanction">الموظف: <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="employee_code_add_sanction" name="employee_code_add_sanction">
                            <option selected value="">غير محدد</option>
                            @if (isset($employees) and !empty($employees))
                            @foreach ($employees as $info)
                            <option value="{{ $info->employee_code }}" data-salary="{{ $info->employeeData['emp_sal'] }}" data-day_price="{{ $info->employeeData['day_price'] }}">({{ $info->employee_code }}) {{ $info->employeeData['emp_name'] }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-3 related_employee_add" style="display:none">
                    <div class="form-group">
                        <label for="emp_sal_add_sanction">راتب الموظف:</label>
                        <input readonly type="text" class="form-control" name="emp_sal_add_sanction" id="emp_sal_add_sanction" value="">
                    </div>
                </div>
                <div class="col-md-3 related_employee_add" style="display:none">
                    <div class="form-group">
                        <label for="day_price_add_sanction">راتب اليوم للموظف:</label>
                        <input readonly type="text" class="form-control" name="day_price_add_sanction" id="day_price_add_sanction" value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="sanctions_type_add_sanction">نوع الجزاء: <span class="text-danger">*</span></label>
                        <select class="form-control" id="sanctions_type_add_sanction" name="sanctions_type_add_sanction">
                            <option selected value="">غير محدد</option>
                            <option value="1">جزاء أيام</option>
                            <option value="2">جزاء بصمة</option>
                            <option value="3">جزاء تحقيق</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="value_add_sanction">عدد ايام الجزاء: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="value_add_sanction" name="value_add_sanction" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="total_add_sanction">اجمالي قيمة الجزاء:</label>
                        <input readonly type="text" class="form-control" id="total_add_sanction" name="total_add_sanction" value="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="notes_add_sanction">ملاحظات:</label>
                        <input type="text" class="form-control" id="notes_add_sanction" name="notes_add_sanction" value="">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mx-auto col-md-2">
                        <button type="submit" class="form-control btn-primary" id="do_add_sanction" value="">إضافة</button>
                    </div>
                </div>

            </div>
            <div class="modal-footer justify-content-between bg-secondary">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
                {{-- <button type="button" class="btn btn-outline-light">Save changes</button> --}}
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.addModal -->

<!-- .editModal -->
<div class="modal fade" id="edit_sanctionModal">
    <div class="modal-dialog modal-2xl">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">تعديل جزاء الموظف</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="edit_sanctionModalBody">

            </div>
            <div class="modal-footer justify-content-between bg-secondary">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.editModal -->

@endsection

@section('script')
<script src="{{ url('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>

<script>
    $(document).ready(function() {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // For addSanction Bring and display the salary and day praice for selected employee 
        $(document).on("change", "#employee_code_add_sanction", function(e) {
            if ($(this).val() == "") {
                $('.related_employee_add').hide();
                $('#emp_sal_add_sanction').val(0);
                $('#day_price_add_sanction').val(0);
            } else {
                var sal = $('#employee_code_add_sanction option:selected').data('salary');
                var d_price = $('#employee_code_add_sanction option:selected').data('day_price');
                $('.related_employee_add').show();
                $('#emp_sal_add_sanction').val(sal * 1);
                $('#day_price_add_sanction').val(d_price * 1);
            }
            var value_add_sanction = $('#value_add_sanction').val();
            if (value_add_sanction == '') {
                value_add_sanction = 0;
            }
            var day_price_add_sanction = $('#day_price_add_sanction').val();

            $('#total_add_sanction').val(value_add_sanction * day_price_add_sanction);
        });

        // For addSanction Set and write the total value after write sanctions days number 
        $(document).on("input", "#value_add_sanction", function(e) {
            var value_add_sanction = $(this).val();
            if (value_add_sanction == '') {
                value_add_sanction = 0;
            }
            var day_price_add_sanction = $('#day_price_add_sanction').val();

            $('#total_add_sanction').val(value_add_sanction * day_price_add_sanction);
        });

        // Check the fields values and add the Sanction for the employee 
        $(document).on("click", "#do_add_sanction", function(e) {
            var finance_month_period_id = $('#finance_month_period_id').val();
            var employee_code_addSanction = $('#employee_code_add_sanction').val();
            var sal_addSanction = $('#emp_sal_add_sanction').val();
            var d_price_addSanction = $('#day_price_add_sanction').val();
            var sanctions_type_addSanction = $('#sanctions_type_add_sanction').val();
            var value_addSanction = $('#value_add_sanction').val();
            var total_addSanction = $('#total_add_sanction').val();
            var notes_addSanction = $('#notes_add_sanction').val();

            if (employee_code_addSanction == "") {
                Swal.fire({
                    title: "من فضلك اختر الموظف"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#employee_code_edit_sanction').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (sanctions_type_addSanction == "") {
                Swal.fire({
                    title: "من فضلك اختر نوع الجزاء"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#sanctions_type_add_sanction').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (value_addSanction == "") {
                Swal.fire({
                    title: "من فضلك ادخل عدد ايام الجزاء"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#value_add_sanction').focus();
                        }, 500);
                    }
                });
                return 0;
            }

            jQuery.ajax({
                url: "{{ route('mainsalarysanction.checkExist') }}"
                , type: 'post'
                , 'dataType': 'json'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , employee_code: employee_code_addSanction
                    , finance_month_period_id: finance_month_period_id
                }
                , success: function(data) {
                    if (data == "exists") {
                        var res = confirm('يوجد جزاء مسجل من قبل لهذا الموظف هل تريد الاستمرار؟');
                        if (res == true) {
                            var resFlag = true;
                        } else {
                            var resFlag = false;
                        }
                    } else {
                        var resFlag = true;
                    }

                    if (resFlag) {
                        $('#loadingModal').modal('show');
                        jQuery.ajax({
                            url: "{{ route('mainsalarysanction.sanctionStore') }}"
                            , type: 'post'
                            , 'dataType': 'json'
                            , cache: false
                            , data: {
                                "_token": '{{ csrf_token() }}'
                                , employee_code: employee_code_addSanction
                                , finance_month_period_id: finance_month_period_id
                                , salary: sal_addSanction
                                , day_price: d_price_addSanction
                                , sanctions_type: sanctions_type_addSanction
                                , value: value_addSanction
                                , total: total_addSanction
                                , notes: notes_addSanction
                            }
                            , success: function(data) {
                                if (data == 'success') {
                                    // Hide Add Sanction Modal 
                                    $('#add_sanctionModal').modal('hide');
                                    // Show Add Sanction Success Toast 
                                    const Toast = Swal.mixin({
                                        toast: true
                                        , position: "top-end"
                                        , showConfirmButton: false
                                        , timer: 3000
                                        , timerProgressBar: true
                                        , didOpen: (toast) => {
                                            toast.onmouseenter = Swal.stopTimer;
                                            toast.onmouseleave = Swal.resumeTimer;
                                        }
                                    });
                                    Toast.fire({
                                        icon: "success"
                                        , title: "تم اضافة الجزاء بنجاح"
                                    });

                                    setTimeout(function() {
                                        $('#loadingModal').modal('hide');
                                    }, 1000);
                                    // Update Sanctions List 
                                    ajax_search();
                                } else {
                                    setTimeout(function() {
                                        $('#loadingModal').modal('hide');
                                    }, 1000);
                                    Swal.fire("عفواً توجد مشكلة في الاضافة \n\n" + data, "", "error");
                                }
                            }
                            , error: function() {
                                alert("عفواً حدث خطأ ما : Ajax Two");
                                //Swal.fire("عفواً توجد مشكلة ما!!", "", "error");
                            }
                        });
                    }
                }
                , error: function() {
                    alert("عفواً حدث خطأ ما : Ajax One ");
                }
            , });
        });

        // Delete Sanction Button  
        $(document).on("click", ".delete_sanction", function(e) {
            var res = confirm('هل انت متأكد من حذف هذا الجزاء؟');
            if (!res) {
                return false;
            }

            var id = $(this).data('id');
            var main_salary_employee_id = $(this).data('main_salary_employee_id');
            var finance_month_period_id = $('#finance_month_period_id').val();

            $('#loadingModal').modal('show');

            jQuery.ajax({
                url: "{{ route('mainsalarysanction.sanctionDelete') }}"
                , type: 'post'
                , 'dataType': 'json'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , id: id
                    , main_salary_employee_id: main_salary_employee_id
                    , finance_month_period_id: finance_month_period_id
                }
                , success: function(data) {
                    ajax_search();
                    setTimeout(function() {
                        $('#loadingModal').modal('hide');
                    }, 1000);
                }
                , error: function(err) {
                    setTimeout(function() {
                        $('#loadingModal').modal('hide');
                    }, 1000);
                    alert("عفواً حدث خطأ ما : Ajax_sanctionDelete");
                }
            });
        });

        // For editSanction Bring and display the salary and day praice for selected employee 
        $(document).on("change", "#employee_code_edit_sanction", function(e) {
            if ($(this).val() == "") {
                $('.related_employee_edit').hide();
                $('#emp_sal_edit_sanction').val(0);
                $('#day_price_edit_sanction').val(0);
            } else {
                var sal = $('#employee_code_edit_sanction option:selected').data('salary');
                var d_price = $('#employee_code_edit_sanction option:selected').data('day_price');
                $('.related_employee_edit').show();
                $('#emp_sal_edit_sanction').val(sal * 1);
                $('#day_price_edit_sanction').val(d_price * 1);
            }
            var value_edit_sanction = $('#value_edit_sanction').val();
            if (value_edit_sanction == '') {
                value_edit_sanction = 0;
            }
            var day_price_edit_sanction = $('#day_price_edit_sanction').val();

            $('#total_edit_sanction').val(value_edit_sanction * day_price_edit_sanction);
        });

        // For editSanction Set and write the total value after write sanctions days number 
        $(document).on("input", "#value_edit_sanction", function(e) {
            var value_edit_sanction = $(this).val();
            if (value_edit_sanction == '') {
                value_edit_sanction = 0;
            }
            var day_price_edit_sanction = $('#day_price_edit_sanction').val();

            $('#total_edit_sanction').val(value_edit_sanction * day_price_edit_sanction);
        });

        // Edit Sanction Button 
        $(document).on("click", ".edit_sanction", function(e) {
            var id = $(this).data('id');
            var main_salary_employee_id = $(this).data('main_salary_employee_id');
            var finance_month_period_id = $('#finance_month_period_id').val();

            $('#loadingModal').modal('show');
            jQuery.ajax({
                url: "{{ route('mainsalarysanction.sanctionEdit') }}"
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , id: id
                    , main_salary_employee_id: main_salary_employee_id
                    , finance_month_period_id: finance_month_period_id
                }
                , success: function(data) {
                    $('#loadingModal').modal('hide');
                    $("#edit_sanctionModalBody").html(data);
                    $("#edit_sanctionModal").modal('show');
                    //$('.select2').select2();
                    $('.select2').select2({
                        theme: 'bootstrap4'
                    });
                }
                , error: function(err) {
                    alert("عفواً حدث خطأ ما : Ajax_sanctionEdit");
                }
            });
        });

        // Edit the Sanction for the employee 
        $(document).on("click", "#do_edit_sanction", function(e) {
            var id = $(this).data('id');
            var main_salary_employee_id = $(this).data('main_salary_employee_id');
            var finance_month_period_id = $('#finance_month_period_id').val();
            var employee_code_editSanction = $('#employee_code_edit_sanction').val();
            var sal_editSanction = $('#emp_sal_edit_sanction').val();
            var d_price_editSanction = $('#day_price_edit_sanction').val();
            var sanctions_type_editSanction = $('#sanctions_type_edit_sanction').val();
            var value_editSanction = $('#value_edit_sanction').val();
            var total_editSanction = $('#total_edit_sanction').val();
            var notes_editSanction = $('#notes_edit_sanction').val();

            if (employee_code_editSanction == "") {
                Swal.fire({
                    title: "من فضلك اختر الموظف"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#employee_code_edit_sanction').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (sanctions_type_editSanction == "") {
                Swal.fire({
                    title: "من فضلك اختر نوع الجزاء"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#sanctions_type_edit_sanction').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (value_editSanction == "") {
                Swal.fire({
                    title: "من فضلك ادخل عدد ايام الجزاء"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#value_edit_sanction').focus();
                        }, 500);
                    }
                });
                return 0;
            }

            $('#loadingModal').modal('show');
            jQuery.ajax({
                url: "{{ route('mainsalarysanction.sanctionUpdate') }}"
                , type: 'post'
                , 'dataType': 'json'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , id: id
                    , main_salary_employee_id: main_salary_employee_id
                    , finance_month_period_id: finance_month_period_id
                    , employee_code: employee_code_editSanction
                    , day_price: d_price_editSanction
                    , sanctions_type: sanctions_type_editSanction
                    , value: value_editSanction
                    , total: total_editSanction
                    , notes: notes_editSanction
                }
                , success: function(data) {
                    if (data == 'success') {
                        // Hide Add Sanction Modal 
                        $('#edit_sanctionModal').modal('hide');
                        // Show Add Sanction Success Toast 
                        const Toast = Swal.mixin({
                            toast: true
                            , position: "top-end"
                            , showConfirmButton: false
                            , timer: 4000
                            , timerProgressBar: true
                            , didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success"
                            , title: "تم تعديل الجزاء بنجاح"
                        });

                        setTimeout(function() {
                            $('#loadingModal').modal('hide');
                        }, 1000);
                        // Update Sanctions List on the screen 
                        ajax_search();
                    } else {
                        setTimeout(function() {
                            $('#loadingModal').modal('hide');
                        }, 1000);
                        Swal.fire("عفواً توجد مشكلة في الاضافة \n\n" + data, "", "error");
                    }
                }
                , error: function() {
                    alert("عفواً حدث خطأ ما : Ajax Two");
                    //Swal.fire("عفواً توجد مشكلة ما!!", "", "error");
                }
            });
        });

        $(document).on("change", "#employee_code_search_sanction", function(e) {
            ajax_search();
        });
        $(document).on("change", "#sanctions_type_search_sanction", function(e) {
            ajax_search();
        });
        $(document).on("change", "#is_archived_search_sanction", function(e) {
            ajax_search();
        });

        function ajax_search() {
            var employee_code = $('#employee_code_search_sanction').val();
            var sanctions_type = $('#sanctions_type_search_sanction').val();
            var is_archived = $('#is_archived_search_sanction').val();
            var finance_month_period_id = $('#finance_month_period_id').val();

            jQuery.ajax({
                url: "{{ route('mainsalarysanction.showAjaxSearch') }}"
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , employee_code: employee_code
                    , sanctions_type: sanctions_type
                    , is_archived: is_archived
                    , finance_month_period_id: finance_month_period_id
                }
                , success: function(data) {
                    $("#ajax_response_searchDiv").html(data);
                    //$("#add_sanctionModal").destroy();
                }
                , error: function(err) {
                    alert("عفواً حدث خطأ ما : Ajax_showSearch");
                }
            });
        }

    });

</script>
@endsection
