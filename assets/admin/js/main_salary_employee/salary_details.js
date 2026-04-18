
$(document).ready(function () {
    let token = $("meta[name='csrf-token']").attr('content');

    //////////////////////////////////////////////
    ///////////// SalaryDetails view /////////////
    //////////////////////////////////////////////

    // For stopSalary
    $(document).on("click", "#stopSalary", async function (e) {
        e.preventDefault();
        let link = $(this).attr("href");
        Swal.fire({
            title: "هل انت متأكد من ايقاف راتب الموظف؟",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "نعم",
            cancelButtonText: "الغاء"
        }).then((res) => {
            if (res.isConfirmed) {
                window.location.href = link;
            }
        });
    });

    // For resumeSalary
    $(document).on("click", "#resumeSalary", async function (e) {
        e.preventDefault();
        let link = $(this).attr("href");
        Swal.fire({
            title: "هل انت متأكد من الغاء ايقاف راتب الموظف؟",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "نعم",
            cancelButtonText: "الغاء"
        }).then((res) => {
            if (res.isConfirmed) {
                window.location.href = link;
            }
        });
    });

    // For detailsDeleteSalary
    $(document).on("click", "#detailsDeleteSalary", async function (e) {
        e.preventDefault();
        let link = $(this).attr("href");
        Swal.fire({
            title: "هل انت متأكد من حذف راتب الموظف؟",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "نعم",
            cancelButtonText: "الغاء"
        }).then((res) => {
            if (res.isConfirmed) {
                window.location.href = link;
            }
        });
    });

    // For detailsArchiveSalary
    $(document).on("click", "#detailsArchiveSalary", async function (e) {
        e.preventDefault();
        let link = $(this).attr("href");
        Swal.fire({
            title: "هل انت متأكد من ارشفة راتب الموظف؟",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "نعم",
            cancelButtonText: "الغاء"
        }).then((res) => {
            if (res.isConfirmed) {
                window.location.href = link;
            }
        });
    });

    // For detailsShowArchiveSalary
    $(document).on("click", "#detailsShowArchiveSalary", async function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var link = $(this).data('url');
        jQuery.ajax({
            url: link
            , type: 'post'
            , 'dataType': 'html'
            , cache: false
            , data: {
                "_token": token
                , id: id
            }
            , success: function (data) {
                $("#archiveSalaryModalBody").html(data);
                $("#archiveSalaryModal").modal("show");
            }
            , error: function () {
                alert("عفواً حدث خطأ ما : Ajax One ");
            }
            ,
        });
    });

    // For printSalaryDetails
    // $(document).on("click", "#printSalaryDetails", async function (e) {
    //     e.preventDefault();
    //     let link = $(this).attr("href");
    //     Swal.fire({
    //         title: "هل انت متأكد من طباعة راتب الموظف؟",
    //         text: "",
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#d33",
    //         cancelButtonColor: "#3085d6",
    //         confirmButtonText: "نعم",
    //         cancelButtonText: "الغاء"
    //     }).then((res) => {
    //         if (res.isConfirmed) {
    //             window.location.href = link;
    //         }
    //     });
    // });
});