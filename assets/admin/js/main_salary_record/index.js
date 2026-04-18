$(document).ready(function () {
    let token = $("meta[name='csrf-token']").attr('content');

    $(document).on("change", "#finance_yr", function (e) {
        ajax_search();
    });

    function ajax_search() {
        var finance_yr = $('#finance_yr').val();

        jQuery.ajax({
            url: ajaxSearchLink
            , type: 'post'
            , 'dataType': 'html'
            , cache: false
            , data: {
                "_token": token
                , finance_yr: finance_yr
            }
            , success: function (response) {
                $("#ajax_response_searchDiv").html(response);
            }
            , error: function () {
                alert("عفواً حدث خطأ ما");
            }
        });
    }

    $(document).on("click", "#ajax_pagination_search a", function (e) {
        e.preventDefault();
        var finance_yr = $('#finance_yr').val();
        var linkUrl = $(this).attr('href');

        jQuery.ajax({
            url: linkUrl
            , type: 'post'
            , 'dataType': 'html'
            , cache: false
            , data: {
                "_token": token
                , finance_yr: finance_yr
            }
            , success: function (response) {
                $("#ajax_response_searchDiv").html(response);
            }
            , error: function () {
                alert("عفواً حدث خطأ ما");
            }
            ,
        });
    });

    //// bring load_open_monthModal view and show in Popup modal to determine the pasma start and end date 
    $(document).on("click", ".load_the_open_modal", function (e) {
        var id = $(this).data('id');

        jQuery.ajax({
            url: loadOpenMonthLink
            , method: 'post'
            , datatype: 'html'
            , cache: false
            , data: {
                "_token": token
                , id: id
                ,
            }
            , success: function (data) {
                $("#load_open_monthModalBody").html(data);
                $("#load_open_monthModal").modal("show");
            }
            , error: function () {
                alert("عفواً حدث خطأ ما");
            }
        })
    });

    //// Close the opened month 
    $(document).on("click", "#do_close_month", function (e) {
        // var res = confirm('هل انت متأكد من إيقاف الشهر المالي؟');
        // if(!res){
        //     return false;
        // }
        e.preventDefault();
        var closeUrl = $(this).attr('href');

        Swal.fire({
            title: "هل انت متأكد من إيقاف الشهر المالي؟",
            text: "لن تتمكن من التعديل على الرواتب لاحقاً، إلا عن طريق التسويات",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "نعم",
            cancelButtonText: "الغاء"
        }).then((res) => {
            if (res.isConfirmed) {
                window.location.href = closeUrl;
            }
        });
    });
});