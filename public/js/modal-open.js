$(document).on('click', '.btnOpenModal', function (e) {
  e.preventDefault();
  var url = $(this).data('url');
  $.ajax({
    url: url,
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    dataType: 'html',
    success: function (html) {
      $('#vitsmodal').remove();
      $('body').append(html);
      var el = document.getElementById('vitsmodal');
      if (el) new bootstrap.Modal(el).show();
    }
  });
});
$(document).on('hidden.bs.modal', '#vitsmodal', function(){ $(this).remove(); });
