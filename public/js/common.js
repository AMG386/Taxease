$(document).on('change', 'input, select', function (e) {
    e.preventDefault();

    // alert('hi');
    var name = $(this).attr('name');
    if (name.indexOf('[]') > -1) {
        name = name.replace("[]", "");
    }

    $('.' + name + '_error').addClass('d-none');
});