


$(document).ready(function() { 
    $(document).on('click', '.r_u_sure', function (e) {
        var res = confirm('هل أنت متأكد؟');
        if (!res) {
            return false;
        }
    });
});




