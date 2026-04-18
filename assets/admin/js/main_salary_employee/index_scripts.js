$(document).ready(function () {
    let token = $("meta[name='csrf-token']").attr('content');

    ////////////////////////////////
    ////////// Index view //////////
    ////////////////////////////////

    /**
     * Finance Year for the salary 
     */
    $(document).on("change", "#finance_yr", function (e) {
        ajax_search();
    });

    function ajax_search() {
        var finance_yr = $('#finance_yr').val();
        jQuery.ajax({
            url: ajax_searchUrl
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

});