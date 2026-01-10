
@extends('layouts.admin')

@section('title')
    HRM - Test
@endsection

@section('contentheader')
    صفحة الاختبار
@endsection

@section('contentheaderactivelink')
    لينك 1
@endsection

@section('contentheaderactive')
    <a href="/">عرض</a>
@endsection

@section('content')

    <h1>بسم لله الرحمن الرحيم</h1>

    <script>
        
        /////////////////////////////////////////////////////////////////////////////
        //////////////////////////// SweetAlert2 Example ////////////////////////////
        /////////////////////////////////////////////////////////////////////////////
        // document.getElementById('btnSweet').addEventListener('click', function () {
        //     res = Swal.fire({
        //         // title: "حذف",
        //         text: "هل أنت متأكد?",
        //         icon: "info",
        //         background: "aqua",
        //         color: "red",
        //         // toast: true,
        //         // position: 'top-right',
        //         timer: 5000,
        //         timerProgressBar: true,
        //         // footer: "It's me Ali",
        //         // confirmButtonText: 'نعم',
        //         // cancelButtonText: 'لا',
        //         // showConfirmButton: true,
        //         // showCancelButton: true,
        //     }).then((val) => {
        //         if (val.value) {
        //             document.getElementById('para').innerHTML = val.value;
        //         }
        //     });
        // });

        

        // Edit loan Button 
        //$(document).on("click", ".edit_permanent_loan", function(e) {
        //    var id = $(this).data('id');

        //    $('#loadingModal').modal('show');
        //    jQuery.ajax({
        //        url: "{{ route('mainsalarypermanent_loan.edit') }}"
        //        , type: 'post'
        //        , 'dataType': 'html'
        //        , cache: false
        //        , data: {
        //            "_token": '{{ csrf_token() }}'
        //            , id: id
        //        }
        //        , success: function(data) {
        //            setTimeout(function() {
        //                $('#loadingModal').modal('hide');
        //            }, 500);
        //            $("#edit_permanent_loanModalBody").html(data);
        //            $("#edit_permanent_loanModal").modal('show');
        //            $('.select2').select2({
        //                theme: 'bootstrap4'
        //            });
        //        }
        //        , error: function(err) {
        //            alert("عفواً حدث خطأ ما : Ajax_permanent_edit");
        //        }
        //    });
        //});

        // Edit the loan for the employee 
        //$(document).on("click", "#do_edit_permanent_loan", function(e) {
        //    var id = $(this).data('id');
        //    var employee_code_edit_permanent_loan = $('#employee_code_edit_permanent_loan').val();
        //    var sal_edit_permanent_loan = $('#emp_sal_edit_permanent_loan').val();
        //    var d_price_edit_permanent_loan = $('#day_price_edit_permanent_loan').val();
        //    var total_edit_permanent_loan = $('#total_edit_permanent_loan').val();
        //    var notes_edit_permanent_loan = $('#notes_edit_permanent_loan').val();

        //    if (employee_code_edit_permanent_loan == "") {
        //        Swal.fire({
        //            title: "من فضلك اختر الموظف"
        //            , text: ""
        //            , icon: "error"
        //        }).then((res) => {
        //            if (res.isConfirmed) {
        //                setTimeout(() => {
        //                    $('#employee_code_edit_permanent_loan').focus();
        //                }, 500);
        //            }
        //        });
        //        return 0;
        //    }
        //    if (total_edit_permanent_loan == "" || total_edit_permanent_loan == 0) {
        //        Swal.fire({
        //            title: "من فضلك ادخل قيمة السلفة"
        //            , text: ""
        //            , icon: "error"
        //        }).then((res) => {
        //            if (res.isConfirmed) {
        //                setTimeout(() => {
        //                    $('#total_add_permanent_loan').focus();
        //                }, 500);
        //            }
        //        });
        //        return 0;
        //    }

        //    $('#loadingModal').modal('show');
        //    jQuery.ajax({
        //        url: "{{ route('mainsalarypermanent_loan.update') }}"
        //        , type: 'post'
        //        , 'dataType': 'json'
        //        , cache: false
        //        , data: {
        //            "_token": '{{ csrf_token() }}'
        //            , id: id
        //            , main_salary_employee_id: main_salary_employee_id
        //                //, finance_month_period_id: finance_month_period_id
        //            , employee_code: employee_code_edit_permanent_loan
        //            , day_price: d_price_edit_permanent_loan
        //            , total: total_edit_permanent_loan
        //            , notes: notes_edit_permanent_loan
        //        }
        //        , success: function(data) {
        //            if (data == 'success') {
        //                // Hide Add loan Modal 
        //                $('#edit_permanent_loanModal').modal('hide');
        //                // Show Add loan Success Toast 
        //                const Toast = Swal.mixin({
        //                    toast: true
        //                    , position: "top-end"
        //                    , showConfirmButton: false
        //                    , timer: 4000
        //                    , timerProgressBar: true
        //                    , didOpen: (toast) => {
        //                        toast.onmouseenter = Swal.stopTimer;
        //                        toast.onmouseleave = Swal.resumeTimer;
        //                    }
        //                });
        //                Toast.fire({
        //                    icon: "success"
        //                    , title: "تم تعديل السلفة بنجاح"
        //                });

        //                setTimeout(function() {
        //                    $('#loadingModal').modal('hide');
        //                }, 1000);
        //                // Update loans List on the screen 
        //                ajax_search();
        //            } else {
        //                setTimeout(function() {
        //                    $('#loadingModal').modal('hide');
        //                }, 1000);
        //                Swal.fire("عفواً توجد مشكلة في الاضافة \n\n" + data, "", "error");
        //            }
        //        }
        //        , error: function() {
        //            alert("عفواً حدث خطأ ما : Ajax Two");
        //            //Swal.fire("عفواً توجد مشكلة ما!!", "", "error");
        //        }
        //    });
        //});

    </script>

@endsection
