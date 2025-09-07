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
<a href="{{ route('mainsalaryreward.index') }}">المكافئات المالية</a>
@endsection

@section('contentheaderactive')
عرض
@endsection

@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                بيانات مكافئات الرواتب للشهر المالي ({{ $financeMonth_data['month']['name'] }} لسنة {{ $financeMonth_data['finance_yr'] }})
            </h3>
        </div>
        @if($financeMonth_data['is_open'] == 1)
        <button class="btn btn-md btn-success col-md-2 m-1" data-toggle="modal" data-target="#add_rewardModal">
            إضافة جديد
            <i class="fas fa-plus ml-3"></i>
        </button>
        @endif
        <form method="POST" action="{{ route('mainsalaryreward.printSearch') }}" target="_blank">
            @csrf
            <input type="hidden" id="finance_month_period_id" name="finance_month_period_id" value="{{ $financeMonth_data['id'] }}" />
            <div class="row" style="padding: 0 5px">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="employee_code_search_reward">بحث برقم الموظف:</label>
                        <select class="form-control select2" id="employee_code_search_reward" name="employee_code_search_reward">
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
                        <label for="additions_type_search_reward">بحث بنوع المكافئة:</label>
                        <select class="form-control" id="additions_type_search_reward" name="additions_type_search_reward">
                            <option selected value="all">غير محدد</option>
                            @if (isset($additional_types) and !empty($additional_types))
                            @foreach ($additional_types as $info)
                            <option value="{{ $info->id }}">{{ $info->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="is_archived_search_reward">بحث بالحالة:</label>
                        <select class="form-control" id="is_archived_search_reward" name="is_archived_search_reward">
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
                    <th style="vertical-align: middle"> نوع المكافئة </th>
                    <th style="vertical-align: middle"> قيمة المكافئة </th>
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
                            {{ $info->addition_type->name }}
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

                            <button data-id="{{ $info->id }}" data-main_salary_employee_id="{{ $info->main_salary_employee_id  }}" class="btn btn-success edit_reward">تعديل</button>
                            <button data-id="{{ $info->id }}" data-main_salary_employee_id="{{ $info->main_salary_employee_id  }}" class="btn btn-danger delete_reward">حذف</button>

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
<div class="modal fade" id="add_rewardModal">
    <div class="modal-dialog modal-2xl">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">اضافة إضافي للموظفين</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="add_rewardModalBody">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="employee_code_add_reward">الموظف: <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="employee_code_add_reward" name="employee_code_add_reward">
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
                        <label for="emp_sal_add_reward">راتب الموظف:</label>
                        <input readonly type="text" class="form-control" name="emp_sal_add_reward" id="emp_sal_add_reward" value="">
                    </div>
                </div>
                <div class="col-md-3 related_employee_add" style="display:none">
                    <div class="form-group">
                        <label for="day_price_add_reward">راتب اليوم للموظف:</label>
                        <input readonly type="text" class="form-control" name="day_price_add_reward" id="day_price_add_reward" value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="additions_type_add_reward">نوع المكافئة: <span class="text-danger">*</span></label>
                        <select class="form-control" id="additions_type_add_reward" name="additions_type_add_reward">
                            <option selected value="">غير محدد</option>
                            @if (isset($additional_types) and !empty($additional_types))
                            @foreach ($additional_types as $info)
                            <option value="{{ $info->id }}">{{ $info->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="total_add_reward">اجمالي قيمة المكافئة: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="total_add_reward" name="total_add_reward">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="notes_add_reward">ملاحظات:</label>
                        <input type="text" class="form-control" id="notes_add_reward" name="notes_add_reward" value="">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mx-auto col-md-2">
                        <button type="submit" class="form-control btn-primary" id="do_add_reward" value="">إضافة</button>
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
<div class="modal fade" id="edit_rewardModal">
    <div class="modal-dialog modal-2xl">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">تعديل إضافي الموظف</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="edit_rewardModalBody">

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

        // For addreward Bring and display the salary and day praice for selected employee 
        $(document).on("change", "#employee_code_add_reward", function(e) {
            if ($(this).val() == "") {
                $('.related_employee_add').hide();
                $('#emp_sal_add_reward').val(0);
                $('#day_price_add_reward').val(0);
            } else {
                var sal = $('#employee_code_add_reward option:selected').data('salary');
                var d_price = $('#employee_code_add_reward option:selected').data('day_price');
                $('.related_employee_add').show();
                $('#emp_sal_add_reward').val(sal * 1);
                $('#day_price_add_reward').val(d_price * 1);
            }
        });

        // Check the fields values and add the reward for the employee 
        $(document).on("click", "#do_add_reward", function(e) {
            var finance_month_period_id = $('#finance_month_period_id').val();
            var employee_code_add_reward = $('#employee_code_add_reward').val();
            var sal_add_reward = $('#emp_sal_add_reward').val();
            var d_price_add_reward = $('#day_price_add_reward').val();
            var additions_type_add_reward = $('#additions_type_add_reward').val();
            var total_add_reward = $('#total_add_reward').val();
            var notes_add_reward = $('#notes_add_reward').val();

            if (employee_code_add_reward == "") {
                Swal.fire({
                    title: "من فضلك اختر الموظف"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#employee_code_edit_reward').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (additions_type_add_reward == "") {
                Swal.fire({
                    title: "من فضلك اختر نوع المكافئة"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#additions_type_add_reward').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (total_add_reward == "" || total_add_reward == 0) {
                Swal.fire({
                    title: "من فضلك ادخل قيمة المكافئة"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#total_add_reward').focus();
                        }, 500);
                    }
                });
                return 0;
            }

            jQuery.ajax({
                url: "{{ route('mainsalaryreward.checkExist') }}"
                , type: 'post'
                , 'dataType': 'json'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , employee_code: employee_code_add_reward
                    , finance_month_period_id: finance_month_period_id
                }
                , success: function(data) {
                    if (data == "exists") {
                        var res = confirm('يوجد مكافئة مسجل من قبل لهذا الموظف هل تريد الاستمرار؟');
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
                            url: "{{ route('mainsalaryreward.rewardStore') }}"
                            , type: 'post'
                            , 'dataType': 'json'
                            , cache: false
                            , data: {
                                "_token": '{{ csrf_token() }}'
                                , employee_code: employee_code_add_reward
                                , finance_month_period_id: finance_month_period_id
                                , salary: sal_add_reward
                                , day_price: d_price_add_reward
                                , additions_type: additions_type_add_reward
                                , total: total_add_reward
                                , notes: notes_add_reward
                            }
                            , success: function(data) {
                                if (data == 'success') {
                                    // Hide Add reward Modal 
                                    $('#add_rewardModal').modal('hide');
                                    // Show Add reward Success Toast 
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
                                        , title: "تم اضافة المكافئة بنجاح"
                                    });

                                    setTimeout(function() {
                                        $('#loadingModal').modal('hide');
                                    }, 1000);
                                    // Update rewards List 
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

        // Delete reward Button  
        $(document).on("click", ".delete_reward", function(e) {
            //var res = confirm('هل انت متأكد من حذف هذا المكافئة؟');
            //if (!res) {
            //    return false;
            //}
            Swal.fire({
                title: "هل انت متأكد من حذف هذا المكافئة"
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
                        url: "{{ route('mainsalaryreward.rewardDelete') }}"
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
                            alert("عفواً حدث خطأ ما : Ajax_rewardDelete");
                        }
                    });
                }
            });
        });

        // For editreward Bring and display the salary and day praice for selected employee 
        $(document).on("change", "#employee_code_edit_reward", function(e) {
            if ($(this).val() == "") {
                $('.related_employee_edit').hide();
                $('#emp_sal_edit_reward').val(0);
                $('#day_price_edit_reward').val(0);
            } else {
                var sal = $('#employee_code_edit_reward option:selected').data('salary');
                var d_price = $('#employee_code_edit_reward option:selected').data('day_price');
                $('.related_employee_edit').show();
                $('#emp_sal_edit_reward').val(sal * 1);
                $('#day_price_edit_reward').val(d_price * 1);
            }
            var value_edit_reward = $('#value_edit_reward').val();
            if (value_edit_reward == '') {
                value_edit_reward = 0;
            }
            var day_price_edit_reward = $('#day_price_edit_reward').val();

            $('#total_edit_reward').val(value_edit_reward * day_price_edit_reward);
        });

        // For editreward Set and write the total value after write rewards days number 
        $(document).on("input", "#value_edit_reward", function(e) {
            var value_edit_reward = $(this).val();
            if (value_edit_reward == '') {
                value_edit_reward = 0;
            }
            var day_price_edit_reward = $('#day_price_edit_reward').val();

            $('#total_edit_reward').val(value_edit_reward * day_price_edit_reward);
        });

        // Edit reward Button 
        $(document).on("click", ".edit_reward", function(e) {
            var id = $(this).data('id');
            var main_salary_employee_id = $(this).data('main_salary_employee_id');
            var finance_month_period_id = $('#finance_month_period_id').val();

            $('#loadingModal').modal('show');
            jQuery.ajax({
                url: "{{ route('mainsalaryreward.rewardEdit') }}"
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
                    $("#edit_rewardModalBody").html(data);
                    $("#edit_rewardModal").modal('show');
                    //$('.select2').select2();
                    $('.select2').select2({
                        theme: 'bootstrap4'
                    });
                }
                , error: function(err) {
                    alert("عفواً حدث خطأ ما : Ajax_rewardEdit");
                }
            });
        });

        // Edit the reward for the employee 
        $(document).on("click", "#do_edit_reward", function(e) {
            var id = $(this).data('id');
            var main_salary_employee_id = $(this).data('main_salary_employee_id');
            var finance_month_period_id = $('#finance_month_period_id').val();
            var employee_code_edit_reward = $('#employee_code_edit_reward').val();
            var sal_edit_reward = $('#emp_sal_edit_reward').val();
            var d_price_edit_reward = $('#day_price_edit_reward').val();
            var additions_type_edit_reward = $('#additions_type_edit_reward').val();
            var total_edit_reward = $('#total_edit_reward').val();
            var notes_edit_reward = $('#notes_edit_reward').val();

            if (employee_code_edit_reward == "") {
                Swal.fire({
                    title: "من فضلك اختر الموظف"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#employee_code_edit_reward').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (additions_type_edit_reward == "") {
                Swal.fire({
                    title: "من فضلك اختر نوع المكافئة"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#additions_type_edit_reward').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (total_edit_reward == "" || total_edit_reward == 0) {
                Swal.fire({
                    title: "من فضلك ادخل قيمة المكافئة"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#total_add_reward').focus();
                        }, 500);
                    }
                });
                return 0;
            }

            $('#loadingModal').modal('show');
            jQuery.ajax({
                url: "{{ route('mainsalaryreward.rewardUpdate') }}"
                , type: 'post'
                , 'dataType': 'json'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , id: id
                    , main_salary_employee_id: main_salary_employee_id
                    , finance_month_period_id: finance_month_period_id
                    , employee_code: employee_code_edit_reward
                    , day_price: d_price_edit_reward
                    , additions_type: additions_type_edit_reward
                    , total: total_edit_reward
                    , notes: notes_edit_reward
                }
                , success: function(data) {
                    if (data == 'success') {
                        // Hide Add reward Modal 
                        $('#edit_rewardModal').modal('hide');
                        // Show Add reward Success Toast 
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
                            , title: "تم تعديل المكافئة بنجاح"
                        });

                        setTimeout(function() {
                            $('#loadingModal').modal('hide');
                        }, 1000);
                        // Update rewards List on the screen 
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

        $(document).on("change", "#employee_code_search_reward", function(e) {
            ajax_search();
        });
        $(document).on("change", "#additions_type_search_reward", function(e) {
            ajax_search();
        });
        $(document).on("change", "#is_archived_search_reward", function(e) {
            ajax_search();
        });

        function ajax_search() {
            var employee_code = $('#employee_code_search_reward').val();
            var additions_type = $('#additions_type_search_reward').val();
            var is_archived = $('#is_archived_search_reward').val();
            var finance_month_period_id = $('#finance_month_period_id').val();

            jQuery.ajax({
                url: "{{ route('mainsalaryreward.showAjaxSearch') }}"
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , employee_code: employee_code
                    , additions_type: additions_type
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
