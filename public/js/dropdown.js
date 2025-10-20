function populateDropdown(url, data, target, id, name) {

    $.ajax({
        url: url,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: data,
        dataType: 'json',
        type: 'GET',
        success: function (resp) {
            if (resp.Status == 'Success') {
                var v = resp.returndata;
                var len = v.length;
                target.empty();
                target.append("<option value=''>Select</option>");

                for (var i = 0; i < len; i++) {
                    var idval = v[i][id];
                    var nameval = v[i][name];
                    target.append("<option value='" + idval + "'>" + nameval + "</option>");
                }
            }
            else {
                toastr.error(resp.Msg, resp.Status);
            }
        }
    });


}