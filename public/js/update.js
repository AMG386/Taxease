
//submit form
$(document).on('click', '.btnUpdate', function (e) {
    // $(document).on('submit', '#fmCreate', function (e) {
    e.preventDefault();
    // alert('submit');
   
    var form = $(this).closest("form");
    var submiturl = form.find('#submiturl').val();
    $.ajax({
        url: window.location.origin + '/' + submiturl,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: new FormData(form[0]),
        dataType: 'json',
        cache: false,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function (resp) {
            console.log(resp);
            if (resp.Status == 'Success') {
                toastr.success(resp.Msg, resp.Status);
                setTimeout(function() {
                    window.location = resp.RedirectUrl;
                }, 1000);
            }
            else {
                toastr.error(resp.Msg, resp.Status);
            }
        },
        error: function (reject) {
            // console.log(reject);
            if (reject.status === 422) {
                var err = $.parseJSON(reject.responseText);
                console.log(err.errors);

                // swalalert();


                $.each(err.errors, function (key, val) {
                    form.find("#" + key + "_error").html(val[0]);
                    form.find("#" + key + "_error").removeClass('d-none');
                    // $("#" + key).addClass('is-invalid');
                });
            }
        }
    });

});