$(document).ready(function() {        

  //Parameter (url prefix) selected
  $('.select-prefix').live('change', function () {
    if ($(this).val() != '') {
      window.location = $(this).val();
    }
  });

});