
let token = $("meta[name='csrf-token']").attr('content');

//Initialize Select2 Elements
$('.select2').select2({
    theme: 'bootstrap4'
});

// show.blade.php
$(document).on("click", "#add_fixed_allowance", function (e) {
    var allowance_id = $('#add_allowance_id').val();
    if (allowance_id == "") {
        Swal.fire({
            title: "من فضلك اختر بدل"
            , text: ""
            , icon: "error"
        }).then((res) => {
            if (res.isConfirmed) {
                setTimeout(() => {
                    $('#add_allowance_id').focus();
                }, 500);
            }
        });
        return false;
    }

    var value = $('#add_value').val();
    if (value == "" || value == 0) {
        Swal.fire({
            title: "من فضلك ادخل قيمة البدل"
            , text: ""
            , icon: "error"
        }).then((res) => {
            if (res.isConfirmed) {
                setTimeout(() => {
                    $('#add_value').focus();
                    value == 0 ? $('#add_value').select() : 0;
                }, 300);
            }
        });
        return false;
    }
});


$(document).on("click", "#update_fixed_allowance", function (e) {
    var value = $('#edit_value').val();
    if (value == "" || value == 0) {
        e.preventDefault();
        Swal.fire({
            title: "من فضلك ادخل قيمة البدل"
            , text: ""
            , icon: "error"
        }).then((res) => {
            if (res.isConfirmed) {
                setTimeout(() => {
                    $('#edit_value').focus();
                    value == 0 ? $('#edit_value').select() : $('#edit_value').val(0);
                }, 300);
            }
        });
        return false;
    }


});


$(document).on("click", ".load_edit_fixedAllowance", function(e) {
    let id = $(this).data('id');

    jQuery.ajax({
        url: edit_fixedAllowanceLink
        , type: 'post'
        , 'dataType': 'html'
        , cache: false
        , data: {
            // "_token": '{{ csrf_token() }}'
            "_token": token
            , id: id
        }
        , success: function (data) {
            $("#edit_fixed_allowance_modal_body").html(data);
            $("#edit_fixed_allowance_modal").modal("show");
        }
        , error: function () {
            alert("عفواً حدث خطأ ما : Ajax One ");
        }
        ,
    });
});

