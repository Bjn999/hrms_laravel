
$(document).ready(function () {
    let token = $("meta[name='csrf-token']").attr('content');

    /////////////////////////////////////
    ///////////// Show view /////////////
    /////////////////////////////////////

    // $(document).on("click", "#ajax_pagination_search a", function (e) {
    //     e.preventDefault();
    //     var finance_yr = $('#finance_yr').val();
    //     var linkUrl = $(this).attr('href');

    //     jQuery.ajax({
    //         url: linkUrl
    //         , type: 'post'
    //         , 'dataType': 'html'
    //         , cache: false
    //         , data: {
    //             "_token": '{{ csrf_token() }}'
    //             , finance_yr: finance_yr
    //         }
    //         , success: function (response) {
    //             $("#ajax_response_searchDiv").html(response);
    //         }
    //         , error: function () {
    //             alert("عفواً حدث خطأ ما");
    //         }
    //         ,
    //     });
    // });

    //////////////////////////////////////
    // Show 

    $(document).on("change", "#employee_code_search", function (e) {
        showAjaxSearch();
    });
    $(document).on("change", "#branch_search", function (e) {
        showAjaxSearch();
    });
    $(document).on("change", "#department_search", function (e) {
        showAjaxSearch();
    });
    $(document).on("change", "#job_type_search", function (e) {
        showAjaxSearch();
    });
    $(document).on("change", "#functional_status_search", function (e) {
        showAjaxSearch();
    });
    $(document).on("change", "#sal_cash_or_visa_search", function (e) {
        showAjaxSearch();
    });
    $(document).on("change", "#is_stoped_search", function (e) {
        showAjaxSearch();
    });
    $(document).on("change", "#is_archived_search", function (e) {
        showAjaxSearch();
    });

    /**
     * Serach Between Salary Records 
     */
    function showAjaxSearch() {
        // alert("Ajax called");
        var employee_code = $('#employee_code_search').val();
        var branch = $('#branch_search').val();
        var department = $('#department_search').val();
        var job_type = $('#job_type_search').val();
        var functional_status = $('#functional_status_search').val();
        var sal_cash_or_visa = $('#sal_cash_or_visa_search').val();
        var is_stoped = $('#is_stoped_search').val();
        var is_archived = $('#is_archived_search').val();
        var finance_month_period_id = $('#finance_month_period_id').val();

        jQuery.ajax({
            url: showAjaxSearchUrl
            , type: 'post'
            , 'dataType': 'html'
            , cache: false
            , data: {
                "_token": token
                , employee_code: employee_code
                , branch: branch
                , department: department
                , job_type: job_type
                , functional_status: functional_status
                , sal_cash_or_visa: sal_cash_or_visa
                , is_stoped: is_stoped
                , is_archived: is_archived
                , finance_month_period_id: finance_month_period_id
            }
            , success: function (data) {
                $("#ajax_response_searchDiv").html(data);
                //$("#add_sanctionModal").destroy();
            }
            , error: function (err) {
                alert("عفواً حدث خطأ ما : Ajax_showSearch");
            }
        });
    }

    // For addsalary Bring and display the salary for selected employee 
    $(document).on("change", "#employee_code_add_salary", function (e) {
        if ($(this).val() == "") {
            $('.related_employee_add').hide();
            $('#emp_sal_add_salary').val(0);
        } else {
            var sal = $('#employee_code_add_salary option:selected').data('salary');
            $('.related_employee_add').show();
            $('#emp_sal_add_salary').val(sal * 1);
        }
    });

    // For addsalary 
    $(document).on("submit", "#add_salaryForm", async function (e) {
        // Prevent default form acts to Reloding the page 
        e.preventDefault();

        var employee_code_addsalary = $('#employee_code_add_salary').val();

        if (employee_code_addsalary == "") {
            Swal.fire({
                title: "من فضلك اختر الموظف",
                text: "",
                icon: "error"
            }).then((res) => {
                if (res.isConfirmed) {
                    setTimeout(() => {
                        $('#employee_code_add_salary').focus();
                    }, 500);
                }
            });
            return 0;
        }

        const formData = new FormData(this);

        // alert("Data = " + formData.get("emp_sal_add_salary"));

        try {
            const response = await fetch(addSalaryUrl, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": token,
                },
                body: formData,
            });

            const res = await response.json();

            if (res.status == "success") {
                Swal.fire({
                    title: res.message,
                    text: "",
                    icon: "success"
                }).then((res) => {
                    if (res.isConfirmed) {
                        setTimeout(() => {
                            $('#add_salaryModal').modal('hide');
                            $('#add_salaryForm')[0].reset();
                            showAjaxSearch();
                        }, 500);
                    }
                });
            } else {
                Swal.fire({
                    title: res.message,
                    text: "",
                    icon: "error"
                });
            }
        } catch (error) {
            console.error("Error:", error);
            Swal.fire({
                title: res.message,
                text: "",
                icon: "error"
            });
        }
    });

    // For delete_salary
    $(document).on("click", "#delete_salary", async function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var finance_month_id = $(this).data('finance_month_id');
        Swal.fire({
            title: "هل انت متأكد من حذف راتب الموظف",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "نعم",
            cancelButtonText: "الغاء"
        }).then((res) => {
            if (res.isConfirmed) {
                jQuery.ajax({
                    url: deleteSalaryUrl
                    , type: 'post'
                    , 'dataType': 'json'
                    , cache: false
                    , data: {
                        "_token": token
                        , id: id
                        , finance_month_id: finance_month_id
                    }
                    , success: function (data) {
                        if (data.status == "success") {
                            Swal.fire({
                                title: data.message,
                                text: "",
                                icon: "success"
                            }).then((res) => {
                                // if (res.isConfirmed) {}

                                setTimeout(() => {
                                    showAjaxSearch();
                                }, 500);
                            });
                        } else {
                            Swal.fire({
                                title: data.message,
                                text: "",
                                icon: "error",
                                confirmButtonText: "الغاء",
                            });
                        }
                    }
                    , error: function () {
                        alert("عفواً حدث خطأ ما : Ajax One ");
                    }
                    ,
                });
                setTimeout(() => {
                    showAjaxSearch();
                }, 500);
            }
        });
    });

});
