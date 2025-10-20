function populateSelect(url, data, target, selectall=0) {
    $.ajax({
        url: window.location.origin + url,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: data,
        dataType: 'json',
        type: 'GET',
        success: function (resp) {
            // console.log(resp.returndata);
            if (resp.Status == 'Success') {
                var v = resp.returndata;
                // var len = v.length;
                target.empty();
                if(selectall==0)
                target.append("<option value=''>Select</option>");
                else
                target.append("<option value='All'>All</option>");

                for (let x in v) {
                    target.append("<option value='" + x + "'>" + v[x] + "</option>");
                }


                // for (var i = 0; i < len; i++) {
                //     var idval = v[i][id];
                //     var nameval = v[i][name];
                //     target.append("<option value='" + idval + "'>" + nameval + "</option>");
                // }
            }
            else {
                toastr.error(resp.Msg, resp.Status);
            }
        }
    });
}