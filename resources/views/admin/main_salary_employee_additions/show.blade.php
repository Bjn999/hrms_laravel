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
<a href="{{ route('mainsalaryaddition.index') }}">إضافي الأيام</a>
@endsection

@section('contentheaderactive')
عرض
@endsection

@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                بيانات إضافي الأيام برواتب الشهر المالي ({{ $financeMonth_data['month']['name'] }} لسنة {{ $financeMonth_data['finance_yr'] }})
            </h3>
        </div>
        @if($financeMonth_data['is_open'] == 1)
        <button class="btn btn-md btn-success col-md-2 m-1" data-toggle="modal" data-target="#add_additionModal">
            إضافة جديد
            <i class="fas fa-plus ml-3"></i>
        </button>
        @endif
        <form method="POST" action="{{ route('mainsalaryaddition.printSearch') }}" target="_blank">
            @csrf
            <input type="hidden" id="finance_month_period_id" name="finance_month_period_id" value="{{ $financeMonth_data['id'] }}" />
            <div class="row" style="padding: 0 5px">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="employee_code_search_addition">بحث برقم الموظف:</label>
                        <select class="form-control select2" id="employee_code_search_addition" name="employee_code_search_addition">
                            <option selected value="all">غير محدد</option>
                            @if (isset($employees_for_search) and !empty($employees_for_search))
                            @foreach ($employees_for_search as $info)
                            <option value="{{ $info->employee_code }}" data-salary="{{ $info->emp_sal }}">({{ $info->employee_code }}) {{ $info->emp_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="is_archived_search_addition">بحث بالحالة:</label>
                        <select class="form-control" id="is_archived_search_addition" name="is_archived_search_addition">
                            <option selected value="all">غير محدد</option>
                            <option value="1">مؤرشف</option>
                            <option value="0">غير مؤرشف (مفتوح)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
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
                    <th style="vertical-align: middle"> عدد الأيام </th>
                    <th style="vertical-align: middle"> قيمة الإضافي </th>
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
                            <br />
                            <br />
                            <span style="color: brown">
                                <i class="fa-solid fa-note-sticky"></i>
                                ملاحظة:
                            </span>
                            {{ $info->notes }}
                            @else
                            <br />
                            <br />
                            <span style="color: brown">
                                <i class="fa-solid fa-note-sticky"></i>
                                لا توجد ملاحظة
                            </span>
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

                            <button data-id="{{ $info->id }}" data-main_salary_employee_id="{{ $info->main_salary_employee_id  }}" class="btn btn-success edit_addition">تعديل</button>
                            <button data-id="{{ $info->id }}" data-main_salary_employee_id="{{ $info->main_salary_employee_id  }}" class="btn btn-danger delete_addition">حذف</button>

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
<div class="modal fade" id="add_additionModal">
    <div class="modal-dialog modal-2xl">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">اضافة إضافي للموظفين</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="add_additionModalBody">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="employee_code_add_addition">الموظف: <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="employee_code_add_addition" name="employee_code_add_addition">
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
                        <label for="emp_sal_add_addition">راتب الموظف:</label>
                        <input readonly type="text" class="form-control" name="emp_sal_add_addition" id="emp_sal_add_addition" value="">
                    </div>
                </div>
                <div class="col-md-3 related_employee_add" style="display:none">
                    <div class="form-group">
                        <label for="day_price_add_addition">راتب اليوم للموظف:</label>
                        <input readonly type="text" class="form-control" name="day_price_add_addition" id="day_price_add_addition" value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="value_add_addition">عدد ايام الإضافي: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="value_add_addition" name="value_add_addition" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="total_add_addition">اجمالي قيمة الإضافي:</label>
                        <input readonly type="text" class="form-control" id="total_add_addition" name="total_add_addition">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="notes_add_addition">ملاحظات:</label>
                        <input type="text" class="form-control" id="notes_add_addition" name="notes_add_addition" value="">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mx-auto col-md-2">
                        <button type="submit" class="form-control btn-primary" id="do_add_addition" value="">إضافة</button>
                    </div>
                </div>

            </div>
            <div class="modal-footer justify-content-between bg-secondary">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.addModal -->

<!-- .editModal -->
<div class="modal fade" id="edit_additionModal">
    <div class="modal-dialog modal-2xl">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">تعديل إضافي الموظف</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="edit_additionModalBody">

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

        // For addaddition Bring and display the salary and day praice for selected employee 
        $(document).on("change", "#employee_code_add_addition", function(e) {
            if ($(this).val() == "") {
                $('.related_employee_add').hide();
                $('#emp_sal_add_addition').val(0);
                $('#day_price_add_addition').val(0);
            } else {
                var sal = $('#employee_code_add_addition option:selected').data('salary');
                var d_price = $('#employee_code_add_addition option:selected').data('day_price');
                $('.related_employee_add').show();
                $('#emp_sal_add_addition').val(sal * 1);
                $('#day_price_add_addition').val(d_price * 1);
            }
            var value_add_addition = $('#value_add_addition').val();
            if (value_add_addition == '') {
                value_add_addition = 0;
            }
            var day_price_add_addition = $('#day_price_add_addition').val();

            $('#total_add_addition').val(value_add_addition * day_price_add_addition);
        });

        // For addaddition Set and write the total value after write additions days number 
        $(document).on("input", "#value_add_addition", function(e) {
            var value_add_addition = $(this).val();
            if (value_add_addition == '') {
                value_add_addition = 0;
            }
            var day_price_add_addition = $('#day_price_add_addition').val();

            $('#total_add_addition').val(value_add_addition * day_price_add_addition);
        });

        // Check the fields values and add the addition for the employee 
        $(document).on("click", "#do_add_addition", function(e) {
            var finance_month_period_id = $('#finance_month_period_id').val();
            var employee_code_addaddition = $('#employee_code_add_addition').val();
            var sal_addaddition = $('#emp_sal_add_addition').val();
            var d_price_addaddition = $('#day_price_add_addition').val();
            var value_addaddition = $('#value_add_addition').val();
            var total_addaddition = $('#total_add_addition').val();
            var notes_addaddition = $('#notes_add_addition').val();

            if (employee_code_addaddition == "") {
                Swal.fire({
                    title: "من فضلك اختر الموظف"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#employee_code_edit_addition').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (value_addaddition == "" || value_addaddition == 0) {
                Swal.fire({
                    title: "من فضلك ادخل عدد أيام الإضافي"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#total_add_addition').focus();
                        }, 500);
                    }
                });
                return 0;
            }

            jQuery.ajax({
                url: "{{ route('mainsalaryaddition.checkExist') }}"
                , type: 'post'
                , 'dataType': 'json'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , employee_code: employee_code_addaddition
                    , finance_month_period_id: finance_month_period_id
                }
                , success: function(data) {
                    if (data == "exists") {
                        var res = confirm('يوجد إضافي مسجل من قبل لهذا الموظف هل تريد الاستمرار؟');
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
                            url: "{{ route('mainsalaryaddition.additionStore') }}"
                            , type: 'post'
                            , 'dataType': 'json'
                            , cache: false
                            , data: {
                                "_token": '{{ csrf_token() }}'
                                , employee_code: employee_code_addaddition
                                , finance_month_period_id: finance_month_period_id
                                , salary: sal_addaddition
                                , day_price: d_price_addaddition
                                , value: value_addaddition
                                , total: total_addaddition
                                , notes: notes_addaddition
                            }
                            , success: function(data) {
                                if (data == 'success') {
                                    // Hide Add addition Modal 
                                    $('#add_additionModal').modal('hide');
                                    // Show Add addition Success Toast 
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
                                        , title: "تم اضافة الإضافي بنجاح"
                                    });

                                    setTimeout(function() {
                                        $('#loadingModal').modal('hide');
                                    }, 1000);
                                    // Update additions List 
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

        // Delete addition Button  
        $(document).on("click", ".delete_addition", function(e) {
            //var res = confirm('هل انت متأكد من حذف هذا الإضافي؟');
            //if (!res) {
            //    return false;
            //}
            Swal.fire({
                title: "هل انت متأكد من حذف هذا الإضافي"
                , confirmButtonText: "نعم"
                , showCancelButton: true
                , cancelButtonText: "لا"
                , text: ""
                , icon: "question"
            }).then((res) => {
                if (res.isConfirmed) {
                    var id = $(this).data('id');
                    var main_salary_employee_id = $(this).data('main_salary_employee_id');
                    var finance_month_period_id = $('#finance_month_period_id').val();

                    $('#loadingModal').modal('show');

                    jQuery.ajax({
                        url: "{{ route('mainsalaryaddition.additionDelete') }}"
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
                            setTimeout(function() {
                                $('#loadingModal').modal('hide');
                            }, 1000);
                            ajax_search();
                        }
                        , error: function(err) {
                            setTimeout(function() {
                                $('#loadingModal').modal('hide');
                            }, 1000);
                            alert("عفواً حدث خطأ ما : Ajax_additionDelete");
                        }
                    });
                }
            });
        });

        // For editaddition Bring and display the salary and day praice for selected employee 
        $(document).on("change", "#employee_code_edit_addition", function(e) {
            if ($(this).val() == "") {
                $('.related_employee_edit').hide();
                $('#emp_sal_edit_addition').val(0);
                $('#day_price_edit_addition').val(0);
            } else {
                var sal = $('#employee_code_edit_addition option:selected').data('salary');
                var d_price = $('#employee_code_edit_addition option:selected').data('day_price');
                $('.related_employee_edit').show();
                $('#emp_sal_edit_addition').val(sal * 1);
                $('#day_price_edit_addition').val(d_price * 1);
            }
            var value_edit_addition = $('#value_edit_addition').val();
            if (value_edit_addition == '') {
                value_edit_addition = 0;
            }
            var day_price_edit_addition = $('#day_price_edit_addition').val();

            $('#total_edit_addition').val(value_edit_addition * day_price_edit_addition);
        });

        // For editaddition Set and write the total value after write additions days number 
        $(document).on("input", "#value_edit_addition", function(e) {
            var value_edit_addition = $(this).val();
            if (value_edit_addition == '') {
                value_edit_addition = 0;
            }
            var day_price_edit_addition = $('#day_price_edit_addition').val();

            $('#total_edit_addition').val(value_edit_addition * day_price_edit_addition);
        });

        // Edit addition Button 
        $(document).on("click", ".edit_addition", function(e) {
            var id = $(this).data('id');
            var main_salary_employee_id = $(this).data('main_salary_employee_id');
            var finance_month_period_id = $('#finance_month_period_id').val();

            $('#loadingModal').modal('show');
            jQuery.ajax({
                url: "{{ route('mainsalaryaddition.additionEdit') }}"
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
                    $("#edit_additionModalBody").html(data);
                    $("#edit_additionModal").modal('show');
                    //$('.select2').select2();
                    $('.select2').select2({
                        theme: 'bootstrap4'
                    });
                }
                , error: function(err) {
                    alert("عفواً حدث خطأ ما : Ajax_additionEdit");
                }
            });
        });

        // Edit the addition for the employee 
        $(document).on("click", "#do_edit_addition", function(e) {
            var id = $(this).data('id');
            var main_salary_employee_id = $(this).data('main_salary_employee_id');
            var finance_month_period_id = $('#finance_month_period_id').val();
            var employee_code_editaddition = $('#employee_code_edit_addition').val();
            var sal_editaddition = $('#emp_sal_edit_addition').val();
            var d_price_editaddition = $('#day_price_edit_addition').val();
            var value_editaddition = $('#value_edit_addition').val();
            var total_editaddition = $('#total_edit_addition').val();
            var notes_editaddition = $('#notes_edit_addition').val();

            if (employee_code_editaddition == "") {
                Swal.fire({
                    title: "من فضلك اختر الموظف"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#employee_code_edit_addition').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (value_editaddition == "" || value_editaddition == 0) {
                Swal.fire({
                    title: "من فضلك ادخل ادخل أيام الإضافي"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#total_edit_addition').focus();
                        }, 500);
                    }
                });
                return 0;
            }

            $('#loadingModal').modal('show');
            jQuery.ajax({
                url: "{{ route('mainsalaryaddition.additionUpdate') }}"
                , type: 'post'
                , 'dataType': 'json'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , id: id
                    , main_salary_employee_id: main_salary_employee_id
                    , finance_month_period_id: finance_month_period_id
                    , employee_code: employee_code_editaddition
                    , day_price: d_price_editaddition
                    , value: value_editaddition
                    , total: total_editaddition
                    , notes: notes_editaddition
                }
                , success: function(data) {
                    if (data == 'success') {
                        // Hide Add addition Modal 
                        $('#edit_additionModal').modal('hide');
                        // Show Add addition Success Toast 
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
                            , title: "تم تعديل الإضافي بنجاح"
                        });

                        setTimeout(function() {
                            $('#loadingModal').modal('hide');
                        }, 1000);
                        // Update additions List on the screen 
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

        $(document).on("change", "#employee_code_search_addition", function(e) {
            ajax_search();
        });
        $(document).on("change", "#additions_type_search_addition", function(e) {
            ajax_search();
        });
        $(document).on("change", "#is_archived_search_addition", function(e) {
            ajax_search();
        });

        function ajax_search() {
            var employee_code = $('#employee_code_search_addition').val();
            var is_archived = $('#is_archived_search_addition').val();
            var finance_month_period_id = $('#finance_month_period_id').val();

            jQuery.ajax({
                url: "{{ route('mainsalaryaddition.showAjaxSearch') }}"
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , employee_code: employee_code
                    , is_archived: is_archived
                    , finance_month_period_id: finance_month_period_id
                }
                , success: function(data) {
                    $("#ajax_response_searchDiv").html(data);
                }
                , error: function(err) {
                    alert("عفواً حدث خطأ ما : Ajax_showSearch");
                }
            });
        }

    });

</script>
@endsection
