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
<a href="{{ route('mainsalarypermanent_loan.index') }}">السلف المستديمة</a>
@endsection

@section('contentheaderactive')
عرض
@endsection

@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                بيانات السلف المستديمة للموظفين
            </h3>
        </div>
        <button title="إضافة قسط جديد" class="btn btn-md btn-success col-md-2 m-1" data-toggle="modal" data-target="#add_permanent_loanModal">
            <i class="far fa-plus-square"></i>
            إضافة جديد
            {{-- <i class="fas fa-plus ml-3"></i> --}}
        </button>
        <form method="POST" action="{{ route('mainsalarypermanent_loan.printSearch') }}" target="_blank">
            @csrf
            <div class="row" style="padding: 0 5px">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="employee_code_search_permanent_loan">بحث برقم الموظف:</label>
                        <select class="form-control select2" id="employee_code_search_permanent_loan" name="employee_code_search_permanent_loan">
                            <option selected value="all">غير محدد</option>
                            @if (isset($other['employees']) and !empty($other['employees']))
                            @foreach ($other['employees'] as $info)
                            <option value="{{ $info->employee_code }}">({{ $info->employee_code }}) {{ $info->emp_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="is_dismissal_search_permanent_loan">بحث بحالة الصرف:</label>
                        <select class="form-control" id="is_dismissal_search_permanent_loan" name="is_dismissal_search_permanent_loan">
                            <option selected value="all">غير محدد</option>
                            <option value="1">تم الصرف</option>
                            <option value="0">بانتظار الصرف</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="is_archived_search_permanent_loan">بحث بحالة الارشفة:</label>
                        <select class="form-control" id="is_archived_search_permanent_loan" name="is_archived_search_permanent_loan">
                            <option selected value="all">غير محدد</option>
                            <option value="1">مؤرشف</option>
                            <option value="0">غير مؤرشف (مفتوح)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group text-center">
                        <button title="طباعة نتيجة البحث" type="submet" class="btn btn-lg btn-primary">
                            طباعة البحث
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="card-body" id="ajax_response_searchDiv" style="padding: 0 5px">
            @if (@isset($data) and !@empty($data) and count($data) > 0)
            <table id="example2" class="table table-bordered table-hover text-center table-striped">
                <thead class="custom_thead">
                    <th style="vertical-align: middle; width: 20%"> اسم الموظف </th>
                    <th style="vertical-align: middle; width: 10%"> قيمة السلفة </th>
                    <th style="vertical-align: middle; width: 10%"> عدد الأشهر </th>
                    <th style="vertical-align: middle; width: 10%"> القسط الشهري </th>
                    <th style="vertical-align: middle; width: 10%"> اجمالي المدفوع </th>
                    <th style="vertical-align: middle; width: 10%"> هل صرفت </th>
                    <th style="vertical-align: middle; width: 10%"> هل انتهت </th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr style="cursor: pointer">
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
                        <td style="vertical-align: middle"> {{ $info->total * 1 }} رس </td>
                        <td style="vertical-align: middle"> {{ $info->months_number }} </td>
                        <td style="vertical-align: middle"> {{ $info->monthly_installment_value * 1 }} رس </td>
                        <td style="vertical-align: middle"> {{ $info->total_paid * 1 }} رس </td>
                        <td style="vertical-align: middle">
                            @if ($info->is_dismissal == 1)
                            نعم
                            @else
                            لا
                            @endif
                            <br>
                            @if ($info->is_dismissal == 0 and $info->is_archived == 0)
                            {{-- <a href="{{ route('mainsalarypermanent_loan.dismiss_p_loan', $info->id) }}" title="صرف السلفة المستدية" class="btn btn-warning">صرف الان</a> --}}
                            <button data-id="{{ $info->id }}" title="صرف السلفة المستدية" class="btn dismiss_permanent_loan btn-warning">صرف</button>
                            @endif
                        </td>
                        <td style="vertical-align: middle">
                            @if ($info->is_archived == 1)
                            نعم
                            @else
                            لا
                            @endif
                        </td>
                        <td style="vertical-align: middle">

                            @if ($info->is_archived == 0 and $info->is_dismissal == 0)
                            <button @if ($info->is_archived != 0 or $info->is_dismissal != 0) title="لا يمكن التعديل لأن هذه السلفة قد تم ارشفتها او تم صرفها بالفعل" @else title="تعديل السلفة" @endif data-id="{{ $info->id }}" class="btn btn-success @if ($info->is_archived == 0 and $info->is_dismissal == 0) edit_permanent_loan @endif">
                                {{-- تعديل --}}
                                {{-- <i class="fa-solid fs fa-edit"></i> --}}
                                <i class="far fa-edit"></i>
                                {{-- <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M21.1213 2.70705C19.9497 1.53548 18.0503 1.53547 16.8787 2.70705L15.1989 4.38685L7.29289 12.2928C7.16473 12.421 7.07382 12.5816 7.02986 12.7574L6.02986 16.7574C5.94466 17.0982 6.04451 17.4587 6.29289 17.707C6.54127 17.9554 6.90176 18.0553 7.24254 17.9701L11.2425 16.9701C11.4184 16.9261 11.5789 16.8352 11.7071 16.707L19.5556 8.85857L21.2929 7.12126C22.4645 5.94969 22.4645 4.05019 21.2929 2.87862L21.1213 2.70705ZM18.2929 4.12126C18.6834 3.73074 19.3166 3.73074 19.7071 4.12126L19.8787 4.29283C20.2692 4.68336 20.2692 5.31653 19.8787 5.70705L18.8622 6.72357L17.3068 5.10738L18.2929 4.12126ZM15.8923 6.52185L17.4477 8.13804L10.4888 15.097L8.37437 15.6256L8.90296 13.5112L15.8923 6.52185ZM4 7.99994C4 7.44766 4.44772 6.99994 5 6.99994H10C10.5523 6.99994 11 6.55223 11 5.99994C11 5.44766 10.5523 4.99994 10 4.99994H5C3.34315 4.99994 2 6.34309 2 7.99994V18.9999C2 20.6568 3.34315 21.9999 5 21.9999H16C17.6569 21.9999 19 20.6568 19 18.9999V13.9999C19 13.4477 18.5523 12.9999 18 12.9999C17.4477 12.9999 17 13.4477 17 13.9999V18.9999C17 19.5522 16.5523 19.9999 16 19.9999H5C4.44772 19.9999 4 19.5522 4 18.9999V7.99994Z" fill="#000000" />
                                </svg> --}}
                            </button>
                            <button @if ($info->is_archived != 0 or $info->is_dismissal != 0) title="لا يمكن الحذف لأن هذه السلفة قد تم ارشفتها او تم صرفها بالفعل" @else title="حذف السلفة" @endif data-id="{{ $info->id }}" class="btn btn-danger @if ($info->is_archived == 0 and $info->is_dismissal == 0) delete_permanent_loan @endif">
                                {{-- حذف --}}
                                {{-- <i class="fa-solid fs fa-trash-can"></i> --}}
                                <i class="far fa-trash-alt"></i>
                                {{-- <svg width="20px" height="20px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="#000000" d="M160 256H96a32 32 0 0 1 0-64h256V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64h-64v672a32 32 0 0 1-32 32H192a32 32 0 0 1-32-32V256zm448-64v-64H416v64h192zM224 896h576V256H224v640zm192-128a32 32 0 0 1-32-32V416a32 32 0 0 1 64 0v320a32 32 0 0 1-32 32zm192 0a32 32 0 0 1-32-32V416a32 32 0 0 1 64 0v320a32 32 0 0 1-32 32z" /></svg> --}}
                            </button>
                            @endif
                            <button title="تفاصيل الأقساط" data-id="{{ $info->id }}" class="btn btn-info permanent_loan_installments">
                                {{-- الأقساط --}}
                                <i class="far fa-calendar-alt"></i>
                            </button>

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

{{-- <input type="hidden" value="{{ date('Y-m-d') }}" id="the_today_date"> --}}

<!-- .addModal -->
<div class="modal fade" id="add_permanent_loanModal">
    <div class="modal-dialog modal-2xl">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">اضافة سلفة مستدامة للموظفين</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="add_permanent_loanModalBody">

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="employee_code_add_permanent_loan">الموظف: <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="employee_code_add_permanent_loan" name="employee_code_add_permanent_loan">
                            <option selected value="">غير محدد</option>
                            @if (isset($other['employees']) and !empty($other['employees']))
                            @foreach ($other['employees'] as $info)
                            <option value="{{ $info->employee_code }}" data-salary="{{ $info->emp_sal }}" data-day_price="{{ $info->day_price }}">({{ $info->employee_code }}) {{ $info->emp_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-4 related_employee_add" style="display:none">
                    <div class="form-group">
                        <label for="emp_sal_add_permanent_loan">راتب الموظف:</label>
                        <input readonly type="text" class="form-control" name="emp_sal_add_permanent_loan" id="emp_sal_add_permanent_loan" value="">
                    </div>
                </div>
                <div class="col-md-4 related_employee_add" style="display:none">
                    <div class="form-group">
                        <label for="day_price_add_permanent_loan">راتب اليوم للموظف:</label>
                        <input readonly type="text" class="form-control" name="day_price_add_permanent_loan" id="day_price_add_permanent_loan" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="total_add_permanent_loan">اجمالي قيمة السلفة: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="total_add_permanent_loan" name="total_add_permanent_loan" oninput="this.value = this.value.replace(/[^0-9.]/g, '');">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="months_number_add_permanent_loan">عدد الأشهر للأقساط: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="months_number_add_permanent_loan" name="months_number_add_permanent_loan" min="1" max="12" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="monthly_installment_value_add_permanent_loan">قيمة القسط الشهري:</label>
                        <input readonly type="text" class="form-control" id="monthly_installment_value_add_permanent_loan" name="monthly_installment_value_add_permanent_loan" value="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="year_and_month_start_date_add_permanent_loan">يبدأ السداد من تاريخ: <span class="text-danger">*</span></label>
                        <input type="date" min="{{ date("Y-m-d") }}" class="form-control" id="year_and_month_start_date_add_permanent_loan" name="year_and_month_start_date_add_permanent_loan">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="notes_add_permanent_loan">ملاحظات:</label>
                        <input type="text" class="form-control" id="notes_add_permanent_loan" name="notes_add_permanent_loan" value="">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mx-auto col-md-2">
                        <button type="submit" class="form-control btn-primary" id="do_add_permanent_loan" value="">إضافة</button>
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
<div class="modal fade" id="edit_permanent_loanModal">
    <div class="modal-dialog modal-2xl">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">تعديل السلفة المستدامة للموظف</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="edit_permanent_loanModalBody">

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

<!-- .installmentsModal -->
<div class="modal fade" id="permanent_loan_installmentsModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">قائمة السلف الشهرية للقسط الدائم</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="permanent_loan_installmentsModalBody">

            </div>
            <div class="modal-footer justify-content-between bg-info">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.installmentsModal -->

@endsection

@section('script')
<script src="{{ url('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>

<script>
    $(document).ready(function() {
        //Initialize Select2 Elements 
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // For addloan Bring and display the salary and day praice for selected employee 
        $(document).on("change", "#employee_code_add_permanent_loan", function(e) {
            if ($(this).val() == "") {
                $('.related_employee_add').hide();
                $('#emp_sal_add_permanent_loan').val(0);
                $('#day_price_add_permanent_loan').val(0);
            } else {
                var sal = $('#employee_code_add_permanent_loan option:selected').data('salary');
                var d_price = $('#employee_code_add_permanent_loan option:selected').data('day_price');
                $('.related_employee_add').show();
                $('#emp_sal_add_permanent_loan').val(sal * 1);
                $('#day_price_add_permanent_loan').val(d_price * 1);
            }
        });

        function calculate_add_installment_value() {
            var total_add_permanent_loan = $('#total_add_permanent_loan').val();
            var months_number_add_permanent_loan = $('#months_number_add_permanent_loan').val();

            if (total_add_permanent_loan == "") {
                total_add_permanent_loan = 0
            }
            if (months_number_add_permanent_loan == "") {
                months_number_add_permanent_loan = 0
            }
            if (total_add_permanent_loan != 0 && months_number_add_permanent_loan != 0) {
                $monthly_value = parseFloat(total_add_permanent_loan) / parseFloat(months_number_add_permanent_loan);
                // Put the monthly installment value in decimal numer with 2 places (XX.00) 
                $('#monthly_installment_value_add_permanent_loan').val($monthly_value.toFixed(2) * 1);
            } else {
                $('#monthly_installment_value_add_permanent_loan').val(0);
            }
        }

        $(document).on("input", "#total_add_permanent_loan", function(e) {
            calculate_add_installment_value();
        });
        $(document).on("input", "#months_number_add_permanent_loan", function(e) {
            calculate_add_installment_value();
        });

        // Check the fields values and add the loan for the employee 
        $(document).on("click", "#do_add_permanent_loan", function(e) {
            var employee_code_add_permanent_loan = $('#employee_code_add_permanent_loan').val();
            var sal_add_permanent_loan = $('#emp_sal_add_permanent_loan').val();
            var total_add_permanent_loan = $('#total_add_permanent_loan').val();
            var months_number_add_permanent_loan = $('#months_number_add_permanent_loan').val();
            var monthly_installment_value_add_permanent_loan = $('#monthly_installment_value_add_permanent_loan').val();
            var year_and_month_start_date_add_permanent_loan = $('#year_and_month_start_date_add_permanent_loan').val();
            var notes_add_permanent_loan = $('#notes_add_permanent_loan').val();

            if (employee_code_add_permanent_loan == "") {
                Swal.fire({
                    title: "من فضلك اختر الموظف"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#employee_code_add_permanent_loan').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (total_add_permanent_loan == "" || total_add_permanent_loan == 0) {
                Swal.fire({
                    title: "من فضلك ادخل قيمة السلفة"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#total_add_permanent_loan').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (months_number_add_permanent_loan == "" || months_number_add_permanent_loan == 0) {
                Swal.fire({
                    title: "من فضلك ادخل عدد أشهر الأقساط"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#months_number_add_permanent_loan').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (year_and_month_start_date_add_permanent_loan == "") {
                Swal.fire({
                    title: "من فضلك ادخل تاريخ بدأ السداد"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#year_and_month_start_date_add_permanent_loan').focus();
                        }, 500);
                    }
                });
                return 0;
            }

            jQuery.ajax({
                url: "{{ route('mainsalarypermanent_loan.checkExist') }}"
                , type: 'post'
                , 'dataType': 'json'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , employee_code: employee_code_add_permanent_loan
                }
                , success: function(data) {
                    if (data == "exists") {
                        var res = confirm('يوجد سلفة مستديمة من قبل لهذا الموظف ولم تسدد بعد هل تريد الاستمرار؟');
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
                            url: "{{ route('mainsalarypermanent_loan.store') }}"
                            , type: 'post'
                            , 'dataType': 'json'
                            , cache: false
                            , data: {
                                "_token": '{{ csrf_token() }}'
                                , employee_code: employee_code_add_permanent_loan
                                , emp_sal: sal_add_permanent_loan
                                , months_number: months_number_add_permanent_loan
                                , monthly_installment_value: monthly_installment_value_add_permanent_loan
                                , year_and_month_start_date: year_and_month_start_date_add_permanent_loan
                                , total: total_add_permanent_loan
                                , notes: notes_add_permanent_loan
                            }
                            , success: function(data) {
                                if (data == 'success') {
                                    // Hide Add loan Modal 
                                    $('#add_permanent_loanModal').modal('hide');
                                    // Show Add loan Success Toast 
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
                                        , title: "تم اضافة السلفة بنجاح"
                                    });

                                    setTimeout(function() {
                                        $('#loadingModal').modal('hide');
                                    }, 1000);
                                    // Update loans List 
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

        // Delete loan Button  
        $(document).on("click", ".delete_permanent_loan", function(e) {
            Swal.fire({
                title: "هل انت متأكد من حذف هذا السلفة"
                , confirmButtonText: "نعم"
                , showCancelButton: true
                , cancelButtonText: "لا"
                , text: ""
                , icon: "question"
            }).then((res) => {
                if (res.isConfirmed) {
                    var id = $(this).data('id');

                    $('#loadingModal').modal('show');

                    jQuery.ajax({
                        url: "{{ route('mainsalarypermanent_loan.delete') }}"
                        , type: 'post'
                        , 'dataType': 'json'
                        , cache: false
                        , data: {
                            "_token": '{{ csrf_token() }}'
                            , id: id
                        }
                        , success: function(data) {
                            if (data === 'success') {
                                setTimeout(function() {
                                    $('#loadingModal').modal('hide');
                                }, 500);
                                // Show Add loan Success Toast 
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
                                    , title: "تم حذف السلفة بنجاح"
                                });
                                ajax_search();
                            } else {
                                setTimeout(function() {
                                    $('#loadingModal').modal('hide');
                                }, 500);
                                // Show Toast 
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
                                    icon: "error"
                                    , title: 'حدث خطأ : ' + data
                                });
                            }
                        }
                        , error: function(err) {
                            setTimeout(function() {
                                $('#loadingModal').modal('hide');
                            }, 1000);
                            alert("عفواً حدث خطأ ما : Ajax_permanent_delete");
                        }
                    });
                }
            });
        });

        // Dissmiss loan Button  
        $(document).on("click", ".dismiss_permanent_loan", function(e) {
            Swal.fire({
                title: "هل انت متأكد من صرف هذا السلفة"
                , confirmButtonText: "نعم"
                , showCancelButton: true
                , cancelButtonText: "لا"
                , text: ""
                , icon: "question"
            }).then((res) => {
                if (res.isConfirmed) {
                    var id = $(this).data('id');

                    $('#loadingModal').modal('show');

                    jQuery.ajax({
                        url: "{{ route('mainsalarypermanent_loan.dismiss_p_loan') }}"
                        , type: 'post'
                        , 'dataType': 'json'
                        , cache: false
                        , data: {
                            "_token": '{{ csrf_token() }}'
                            , id: id
                        }
                        , success: function(data) {
                            if (data === 'success') {
                                setTimeout(function() {
                                    $('#loadingModal').modal('hide');
                                }, 500);
                                // Show Add loan Success Toast 
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
                                    , title: "تم حذف السلفة بنجاح"
                                });
                                ajax_search();
                            } else {
                                setTimeout(function() {
                                    $('#loadingModal').modal('hide');
                                }, 500);
                                // Show Toast 
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
                                    icon: "error"
                                    , title: 'حدث خطأ : ' + data
                                });
                            }
                        }
                        , error: function(err) {
                            setTimeout(function() {
                                $('#loadingModal').modal('hide');
                            }, 1000);
                            alert("عفواً حدث خطأ ما : Ajax_permanent_delete");
                        }
                    });
                }
            });
        });

        // For editloan Bring and display the salary and day praice for selected employee 
        $(document).on("change", "#employee_code_edit_permanent_loan", function(e) {
            if ($(this).val() == "") {
                $('.related_employee_edit').hide();
                $('#emp_sal_edit_permanent_loan').val(0);
                $('#day_price_edit_permanent_loan').val(0);
            } else {
                var sal = $('#employee_code_edit_permanent_loan option:selected').data('salary');
                var d_price = $('#employee_code_edit_permanent_loan option:selected').data('day_price');
                $('.related_employee_edit').show();
                $('#emp_sal_edit_permanent_loan').val(sal * 1);
                $('#day_price_edit_permanent_loan').val(d_price * 1);
            }
        });

        function calculate_edit_installment_value() {
            var total = $('#total_edit_permanent_loan').val();
            var months_number = $('#months_number_edit_permanent_loan').val();

            if (total == "") {
                total = 0
            }
            if (months_number == "") {
                months_number = 0
            }
            if (total != 0 && months_number != 0) {
                $monthly_value = parseFloat(total) / parseFloat(months_number);
                // Put the monthly installment value in decimal numer with 2 places (XX.00) 
                $('#monthly_installment_value_edit_permanent_loan').val($monthly_value.toFixed(2) * 1);
            } else {
                $('#monthly_installment_value_edit_permanent_loan').val(0);
            }
        }

        $(document).on("input", "#total_edit_permanent_loan", function(e) {
            calculate_edit_installment_value();
        });
        $(document).on("input", "#months_number_edit_permanent_loan", function(e) {
            calculate_edit_installment_value();
        });

        // Edit loan Button 
        $(document).on("click", ".edit_permanent_loan", function(e) {
            var id = $(this).data('id');
            // var main_salary_employee_id = $(this).data('main_salary_employee_id');
            // var finance_month_period_id = $('#finance_month_period_id').val();

            $('#loadingModal').modal('show');
            jQuery.ajax({
                url: "{{ route('mainsalarypermanent_loan.edit') }}"
                , type: 'post'
                , dataType: 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , id: id
                    // , main_salary_employee_id: main_salary_employee_id
                    // , finance_month_period_id: finance_month_period_id
                }
                , success: function(data) {
                    setTimeout(function() {
                        $('#loadingModal').modal('hide');
                    }, 500);
                    // Alert
                    if (data === '\"none\"') {
                        // Show Toast 
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
                            icon: "error"
                            , title: 'حدث خطأ: هذه السلفة غير موجودة'
                        });
                        return 0;
                    } else if (data === '\"unable\"') {
                        // Show Toast 
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
                            icon: "error"
                            , title: 'حدث خطأ : هذه السلفة أما مؤرشفة أو تم صرفها بالفعل'
                        });
                        return 0;
                    }

                    $("#edit_permanent_loanModalBody").html(data);
                    $("#edit_permanent_loanModal").modal('show');
                    $('.select2').select2({
                        theme: 'bootstrap4'
                    });
                }
                , error: function(err) {
                    alert("عفواً حدث خطأ ما : Ajax_edit");
                }
            });
        });

        // Check the fields values and Update the loan for the employee 
        $(document).on("click", "#do_edit_permanent_loan", function(e) {
            var id = $(this).data('id');
            var employee_code_edit_permanent_loan = $('#employee_code_edit_permanent_loan').val();
            var sal_edit_permanent_loan = $('#emp_sal_edit_permanent_loan').val();
            var total_edit_permanent_loan = $('#total_edit_permanent_loan').val();
            var months_number_edit_permanent_loan = $('#months_number_edit_permanent_loan').val();
            var monthly_installment_value_edit_permanent_loan = $('#monthly_installment_value_edit_permanent_loan').val();
            var year_and_month_start_date_edit_permanent_loan = $('#year_and_month_start_date_edit_permanent_loan').val();
            var notes_edit_permanent_loan = $('#notes_edit_permanent_loan').val();

            if (employee_code_edit_permanent_loan == "") {
                Swal.fire({
                    title: "من فضلك اختر الموظف"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#employee_code_edit_permanent_loan').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (total_edit_permanent_loan == "" || total_edit_permanent_loan == 0) {
                Swal.fire({
                    title: "من فضلك ادخل قيمة السلفة"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#total_edit_permanent_loan').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (months_number_edit_permanent_loan == "" || months_number_edit_permanent_loan == 0) {
                Swal.fire({
                    title: "من فضلك ادخل عدد أشهر الأقساط"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#months_number_edit_permanent_loan').focus();
                        }, 500);
                    }
                });
                return 0;
            }
            if (year_and_month_start_date_edit_permanent_loan == "") {
                Swal.fire({
                    title: "من فضلك ادخل تاريخ بدأ السداد"
                    , text: ""
                    , icon: "error"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#year_and_month_start_date_edit_permanent_loan').focus();
                        }, 500);
                    }
                });
                return 0;
            }

            $('#loadingModal').modal('show');
            jQuery.ajax({
                url: "{{ route('mainsalarypermanent_loan.update') }}"
                , type: 'post'
                , 'dataType': 'json'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , id: id
                    , employee_code: employee_code_edit_permanent_loan
                    , emp_sal: sal_edit_permanent_loan
                    , months_number: months_number_edit_permanent_loan
                    , monthly_installment_value: monthly_installment_value_edit_permanent_loan
                    , year_and_month_start_date: year_and_month_start_date_edit_permanent_loan
                    , total: total_edit_permanent_loan
                    , notes: notes_edit_permanent_loan
                }
                , success: function(data) {
                    if (data == 'success') {
                        // Hide Edit loan Modal 
                        $('#edit_permanent_loanModal').modal('hide');
                        // Show Edit loan Success Toast 
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
                            , title: "تم تحديث السلفة بنجاح"
                        });

                        setTimeout(function() {
                            $('#loadingModal').modal('hide');
                        }, 1000);
                        // Update loans List 
                        ajax_search();
                    } else {
                        setTimeout(function() {
                            $('#loadingModal').modal('hide');
                        }, 1000);
                        Swal.fire("عفواً توجد مشكلة في التحديث \n\n" + data, "", "error");
                    }
                }
                , error: function() {
                    alert("عفواً حدث خطأ ما : Ajax Two");
                    //Swal.fire("عفواً توجد مشكلة ما!!", "", "error");
                }
            });

        });

        // Show Permanent loan installments 
        $(document).on("click", ".permanent_loan_installments", function(e) {
            var id = $(this).data('id');

            $('#loadingModal').modal('show');
            jQuery.ajax({
                url: "{{ route('mainsalarypermanent_loan.p_loan_installments') }}"
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , id: id
                }
                , success: function(data) {
                    setTimeout(function() {
                        $('#loadingModal').modal('hide');
                    }, 500);
                    $("#permanent_loan_installmentsModalBody").html(data);
                    $("#permanent_loan_installmentsModal").modal('show');
                    $('.select2').select2({
                        theme: 'bootstrap4'
                    });
                }
                , error: function(err) {
                    alert("عفواً حدث خطأ ما : Ajax_permanent_loan_installments");
                }
            });
        });

        $(document).on("change", "#employee_code_search_permanent_loan", function(e) {
            ajax_search();
        });
        $(document).on("change", "#is_dismissal_search_permanent_loan", function(e) {
            ajax_search();
        });
        $(document).on("change", "#is_archived_search_permanent_loan", function(e) {
            ajax_search();
        });

        function ajax_search() {
            var employee_code = $('#employee_code_search_permanent_loan').val();
            var is_dismissal = $('#is_dismissal_search_permanent_loan').val();
            var is_archived = $('#is_archived_search_permanent_loan').val();
            var search_key = $('#search_key').val();

            jQuery.ajax({
                url: "{{ route('mainsalarypermanent_loan.ajaxSearch') }}"
                , type: 'post'
                , 'dataType': 'html'
                , cache: false
                , data: {
                    "_token": '{{ csrf_token() }}'
                    , employee_code: employee_code
                    , is_dismissal: is_dismissal
                    , is_archived: is_archived
                    , search_key: search_key
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
