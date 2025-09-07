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
<a href="{{ route('mainsalarydiscount.index') }}">الخصومات</a>
@endsection

@section('contentheaderactive')
عرض
@endsection

@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                بيانات خصومات الرواتب للشهر المالي ({{ $financeMonth_data['month']['name'] }} لسنة {{ $financeMonth_data['finance_yr'] }})
            </h3>
        </div>
        @if($financeMonth_data['is_open'] == 1)
        <button class="btn btn-md btn-success col-md-2 m-1" data-toggle="modal" data-target="#add_discountModal">
            إضافة جديد
            <i class="fas fa-plus ml-3"></i>
        </button>
        @endif
        <form method="POST" action="{{ route('mainsalarydiscount.printSearch') }}" target="_blank">
            @csrf
            <input type="hidden" id="finance_month_period_id" name="finance_month_period_id" value="{{ $financeMonth_data['id'] }}" />
            <div class="row" style="padding: 0 5px">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="employee_code_search_discount">بحث برقم الموظف:</label>
                        <select class="form-control select2" id="employee_code_search_discount" name="employee_code_search_discount">
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
                        <label for="discounts_type_search_discount">بحث بنوع الخصم:</label>
                        <select class="form-control" id="discounts_type_search_discount" name="discounts_type_search_discount">
                            <option selected value="all">غير محدد</option>
                            @if (isset($discountal_types) and !empty($discountal_types))
                            @foreach ($discountal_types as $info)
                            <option value="{{ $info->id }}">{{ $info->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="is_archived_search_discount">بحث بالحالة:</label>
                        <select class="form-control" id="is_archived_search_discount" name="is_archived_search_discount">
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
                    <th style="vertical-align: middle"> نوع الخصم </th>
                    <th style="vertical-align: middle"> قيمة الخصم </th>
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
                            {{ $info->discount_type->name }}
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

                            <button data-id="{{ $info->id }}" data-main_salary_employee_id="{{ $info->main_salary_employee_id  }}" class="btn btn-success edit_discount">تعديل</button>
                            <button data-id="{{ $info->id }}" data-main_salary_employee_id="{{ $info->main_salary_employee_id  }}" class="btn btn-danger delete_discount">حذف</button>

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
<div class="modal fade" id="add_discountModal">
    <div class="modal-dialog modal-2xl">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">اضافة إضافي للموظفين</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="add_discountModalBody">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="employee_code_add_discount">الموظف: <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="employee_code_add_discount" name="employee_code_add_discount">
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
                        <label for="emp_sal_add_discount">راتب الموظف:</label>
                        <input readonly type="text" class="form-control" name="emp_sal_add_discount" id="emp_sal_add_discount" value="">
                    </div>
                </div>
                <div class="col-md-3 related_employee_add" style="display:none">
                    <div class="form-group">
                        <label for="day_price_add_discount">راتب اليوم للموظف:</label>
                        <input readonly type="text" class="form-control" name="day_price_add_discount" id="day_price_add_discount" value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="discounts_type_add_discount">نوع الخصم: <span class="text-danger">*</span></label>
                        <select class="form-control" id="discounts_type_add_discount" name="discounts_type_add_discount">
                            <option selected value="">غير محدد</option>
                            @if (isset($discountal_types) and !empty($discountal_types))
                            @foreach ($discountal_types as $info)
                            <option value="{{ $info->id }}">{{ $info->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="total_add_discount">اجمالي قيمة الخصم: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="total_add_discount" name="total_add_discount">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="notes_add_discount">ملاحظات:</label>
                        <input type="text" class="form-control" id="notes_add_discount" name="notes_add_discount" value="">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mx-auto col-md-2">
                        <button type="submit" class="form-control btn-primary" id="do_add_discount" value="">إضافة</button>
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
<div class="modal fade" id="edit_discountModal">
    <div class="modal-dialog modal-2xl">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">تعديل إضافي الموظف</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="edit_discountModalBody">

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

        // For adddiscount Bring and display the salary and day praice for selected employee 
        $(document).on("change", "#employee_code_add_discount", function(e) {
            if ($(this).val() == "") {
                $('.related_employee_add').hide();
                $('#emp_sal_add_discount').val(0);
                $('#day_price_add_discount').val(0);
            } else {
                var sal = $('#employee_code_add_discount option:selected').data('salary');
                var d_price = $('#employee_code_add_discount option:selected').data('day_price');
                $('.related_employee_add').show();
                $('#emp_sal_add_discount').val(sal * 1);
                $('#day_price_add_discount').val(d_price * 1);
            }
        });

        // Check the fields values and add the discount for the employee 
        $(document).on("click", "#do_add_discount", function(e) {
            var finance_month_period_id = $('#finance_month_period_id').val();
            var employee_code_add_discount = $('#employee_code_add_discount').val();
            var sal_add_discount = $('#emp_sal_add_discount').val();
            var d_price_add_discount = $('#day_price_add_discount').val();
            var discounts_type_add_discount = $('#discounts_type_add_discount').val();
            var total_add_discount = $('#total_add_discount').val();
            var notes_add_discount = $('#notes_add_discount').val();

            if (employee_code_add_discount == "") {
                Swal.fire({
                    title: "من فضلك اختر الموظف"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#employee_code_edit_discount').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (discounts_type_add_discount == "") {
                Swal.fire({
                    title: "من فضلك اختر نوع الخصم"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#discounts_type_add_discount').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (total_add_discount == "" || total_add_discount == 0) {
                Swal.fire({
                    title: "من فضلك ادخل قيمة الخصم"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#total_add_discount').focus();
                        }, 500);
                    }
                });
                return 0;
            }

            jQuery.ajax({
                url: "{{ route('mainsalarydiscount.checkExist') }}"
                , type: 'post'
                , 'dataType': 'json'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , employee_code: employee_code_add_discount
                    , finance_month_period_id: finance_month_period_id
                }
                , success: function(data) {
                    if (data == "exists") {
                        var res = confirm('يوجد خصم مسجل من قبل لهذا الموظف هل تريد الاستمرار؟');
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
                            url: "{{ route('mainsalarydiscount.discountStore') }}"
                            , type: 'post'
                            , 'dataType': 'json'
                            , cache: false
                            , data: {
                                "_token": '{{ csrf_token() }}'
                                , employee_code: employee_code_add_discount
                                , finance_month_period_id: finance_month_period_id
                                , salary: sal_add_discount
                                , day_price: d_price_add_discount
                                , discounts_type: discounts_type_add_discount
                                , total: total_add_discount
                                , notes: notes_add_discount
                            }
                            , success: function(data) {
                                if (data == 'success') {
                                    // Hide Add discount Modal 
                                    $('#add_discountModal').modal('hide');
                                    // Show Add discount Success Toast 
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
                                        , title: "تم اضافة الخصم بنجاح"
                                    });

                                    setTimeout(function() {
                                        $('#loadingModal').modal('hide');
                                    }, 1000);
                                    // Update discounts List 
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

        // Delete discount Button  
        $(document).on("click", ".delete_discount", function(e) {
            //var res = confirm('هل انت متأكد من حذف هذا الخصم؟');
            //if (!res) {
            //    return false;
            //}
            Swal.fire({
                title: "هل انت متأكد من حذف هذا الخصم"
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
                        url: "{{ route('mainsalarydiscount.discountDelete') }}"
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
                            alert("عفواً حدث خطأ ما : Ajax_discountDelete");
                        }
                    });
                }
            });
        });

        // For editdiscount Bring and display the salary and day praice for selected employee 
        $(document).on("change", "#employee_code_edit_discount", function(e) {
            if ($(this).val() == "") {
                $('.related_employee_edit').hide();
                $('#emp_sal_edit_discount').val(0);
                $('#day_price_edit_discount').val(0);
            } else {
                var sal = $('#employee_code_edit_discount option:selected').data('salary');
                var d_price = $('#employee_code_edit_discount option:selected').data('day_price');
                $('.related_employee_edit').show();
                $('#emp_sal_edit_discount').val(sal * 1);
                $('#day_price_edit_discount').val(d_price * 1);
            }
            var value_edit_discount = $('#value_edit_discount').val();
            if (value_edit_discount == '') {
                value_edit_discount = 0;
            }
            var day_price_edit_discount = $('#day_price_edit_discount').val();

            $('#total_edit_discount').val(value_edit_discount * day_price_edit_discount);
        });

        // For editdiscount Set and write the total value after write discounts days number 
        $(document).on("input", "#value_edit_discount", function(e) {
            var value_edit_discount = $(this).val();
            if (value_edit_discount == '') {
                value_edit_discount = 0;
            }
            var day_price_edit_discount = $('#day_price_edit_discount').val();

            $('#total_edit_discount').val(value_edit_discount * day_price_edit_discount);
        });

        // Edit discount Button 
        $(document).on("click", ".edit_discount", function(e) {
            var id = $(this).data('id');
            var main_salary_employee_id = $(this).data('main_salary_employee_id');
            var finance_month_period_id = $('#finance_month_period_id').val();

            $('#loadingModal').modal('show');
            jQuery.ajax({
                url: "{{ route('mainsalarydiscount.discountEdit') }}"
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
                    $("#edit_discountModalBody").html(data);
                    $("#edit_discountModal").modal('show');
                    //$('.select2').select2();
                    $('.select2').select2({
                        theme: 'bootstrap4'
                    });
                }
                , error: function(err) {
                    alert("عفواً حدث خطأ ما : Ajax_discountEdit");
                }
            });
        });

        // Edit the discount for the employee 
        $(document).on("click", "#do_edit_discount", function(e) {
            var id = $(this).data('id');
            var main_salary_employee_id = $(this).data('main_salary_employee_id');
            var finance_month_period_id = $('#finance_month_period_id').val();
            var employee_code_edit_discount = $('#employee_code_edit_discount').val();
            var sal_edit_discount = $('#emp_sal_edit_discount').val();
            var d_price_edit_discount = $('#day_price_edit_discount').val();
            var discounts_type_edit_discount = $('#discounts_type_edit_discount').val();
            var value_edit_discount = $('#value_edit_discount').val();
            var total_edit_discount = $('#total_edit_discount').val();
            var notes_edit_discount = $('#notes_edit_discount').val();

            if (employee_code_edit_discount == "") {
                Swal.fire({
                    title: "من فضلك اختر الموظف"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#employee_code_edit_discount').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (discounts_type_edit_discount == "") {
                Swal.fire({
                    title: "من فضلك اختر نوع الخصم"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#discounts_type_edit_discount').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (total_edit_discount == "" || total_edit_discount == 0) {
                Swal.fire({
                    title: "من فضلك ادخل قيمة الخصم"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#total_add_discount').focus();
                        }, 500);
                    }
                });
                return 0;
            }

            $('#loadingModal').modal('show');
            jQuery.ajax({
                url: "{{ route('mainsalarydiscount.discountUpdate') }}"
                , type: 'post'
                , 'dataType': 'json'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , id: id
                    , main_salary_employee_id: main_salary_employee_id
                    , finance_month_period_id: finance_month_period_id
                    , employee_code: employee_code_edit_discount
                    , day_price: d_price_edit_discount
                    , discounts_type: discounts_type_edit_discount
                    , value: value_edit_discount
                    , total: total_edit_discount
                    , notes: notes_edit_discount
                }
                , success: function(data) {
                    if (data == 'success') {
                        // Hide Add discount Modal 
                        $('#edit_discountModal').modal('hide');
                        // Show Add discount Success Toast 
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
                            , title: "تم تعديل الخصم بنجاح"
                        });

                        setTimeout(function() {
                            $('#loadingModal').modal('hide');
                        }, 1000);
                        // Update discounts List on the screen 
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

        $(document).on("change", "#employee_code_search_discount", function(e) {
            ajax_search();
        });
        $(document).on("change", "#discounts_type_search_discount", function(e) {
            ajax_search();
        });
        $(document).on("change", "#is_archived_search_discount", function(e) {
            ajax_search();
        });

        function ajax_search() {
            var employee_code = $('#employee_code_search_discount').val();
            var discounts_type = $('#discounts_type_search_discount').val();
            var is_archived = $('#is_archived_search_discount').val();
            var finance_month_period_id = $('#finance_month_period_id').val();

            jQuery.ajax({
                url: "{{ route('mainsalarydiscount.showAjaxSearch') }}"
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , employee_code: employee_code
                    , discounts_type: discounts_type
                    , is_archived: is_archived
                    , finance_month_period_id: finance_month_period_id
                }
                , success: function(data) {
                    $("#ajax_response_searchDiv").html(data);
                    //$("#add_discountModal").destroy();
                }
                , error: function(err) {
                    alert("عفواً حدث خطأ ما : Ajax_showSearch");
                }
            });
        }

    });

</script>
@endsection
