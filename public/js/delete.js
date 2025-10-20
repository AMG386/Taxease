$(document).on('click', '.btndelete', function (e) {
    e.preventDefault();

    var url = $(this).data('url');
    var actiontype = $(this).data('actiontype');
    // var actionvalue = $(this).data('actionvalue');

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
                    url: window.location.origin + '/' + url,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                    },
                    // dataType: 'json',
                    type: 'DELETE',
                    success: function (resp) {
                        console.log(resp);
                        if (resp.Status == 'Success') {
                            toastr.success(resp.Msg, resp.Status);
                            if (actiontype == 'redirect') {
                                setTimeout(function () {
                                    window.location = resp.RedirectUrl;
                                }, 1000);
                            }

                        }
                        else {
                            toastr.error(resp.Msg, resp.Status);
                        }
                    }
                });
            }
        }
    });
});

// function loadDiv(div) {
//     $("#" + div).load(window.location.href + " #" + div + "-content");


//     // window.addEventListener('load', function () {
//     //     alert("It's loaded!")
//     //   })

//     setTimeout(function () {
//         feather.replace();
//     }, 1000);
// }