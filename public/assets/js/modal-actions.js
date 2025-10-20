// $(document).on('click', '.btnAction', function (e) {
//     e.preventDefault();

//     var url = $(this).data('url');
//     var callbackfn = $(this).data('callback');
//     // var form = $(this).closest("form");
//     // alert(url);

//     $.ajax({
//         url: window.location.origin + url,
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         dataType: 'html',
//         type: 'GET',
//         success: function (resp) {
//             $('#diti-modal-content').html(resp);
//             $('#ditimodal').modal('show');

//             if (callbackfn)
//                 eval(callbackfn);
//         }
//     });

// });

$(document).on('click', '.btnModalSave', function (e) {
    e.preventDefault();
    var form = $(this).closest("form");
    var url = $(this).data('url');
    var div = $(this).data('div');
    var action = $(this).data('action');
    var callbackfn = $(this).data('callback');
    // alert(callbackfn);

    $.ajax({
        url: window.location.origin + url,
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
                // toastr.success(resp.Msg, resp.Status);
                Toast.fire({
                    icon: 'success',
                    title: resp.Msg
                });

                if (action == 'edit')
                    $('#ditimodal').modal('hide');
                if (callbackfn) {
                    eval(callbackfn);
                    loadDiv(div + '_header');

                }
                else
                    loadDiv(div);

            }
            else {
                Toast.fire({
                    icon: 'error',
                    title: resp.Msg
                });
            }
        },
        error: function (reject) {
            // console.log(reject);
            if (reject.status === 422) {
                var err = $.parseJSON(reject.responseText);
                console.log(err.errors);
                $.each(err.errors, function (key, val) {
                    form.find("#" + key + "_error").html(val[0]);
                    form.find("#" + key + "_error").removeClass('d-none');
                    form.find("#" + key).addClass('is-invalid');
                });
            }
        }
    });
});

$(document).on('click', '.btndelete', function (e) {
    e.preventDefault();

    var url = $(this).data('url');
    var div = $(this).data('div');
    var actiontype = $(this).data('actiontype');
    var actionvalue = $(this).data('actionvalue');
    var callbackfn = $(this).data('callback');
    // alert(callbackfn);

    bootbox.confirm({
        title: "Confirm Delete",
        message: "Are you sure you want to delete?",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> No'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Yes'
            }
        },
        callback: function (result) {
            if (result) {
                $.ajax({
                    url: window.location.origin + url,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                    },
                    // dataType: 'json',
                    type: 'DELETE',
                    success: function (resp) {
                        console.log(resp);
                        if (resp.Status == 'Success') {
                            Toast.fire({
                                icon: 'success',
                                title: resp.Msg
                            });
                           
                            // loadDiv(div);
                            // if (callbackfn){
                            //     eval(callbackfn);
                            //     loadDiv(div + '_header');
                            // }
                            // else
                            //     loadDiv(div);
                        }
                        else {
                            Toast.fire({
                                icon: 'error',
                                title: resp.Msg
                            });
                        }
                    }
                });
            }
        }
    });
});

function loadDiv(div) {
    $("#" + div).load(window.location.href + " #" + div + "-content");


    // window.addEventListener('load', function () {
    //     alert("It's loaded!")
    //   })

    setTimeout(function () {
        feather.replace();
    }, 1000);
}