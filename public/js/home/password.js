$(function () {
  function changeSuccess(data) {
    if (data.success === 1) {
      setTimeout(function(){
        $('#password-modal').modal('hide');
      }, 1000);
      var str = '<div class="alert alert-success alert-dismissible" role="alert">';
      str += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
      str += '<strong>Success!</strong> Password has been changed.';
      str += '</div>';
      $('#password-message').html(str);
    } else {
      var str = '<div class="alert alert-danger alert-dismissible" role="alert">';
      str += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
      str += '<strong>Error!</strong> ' + data.error;
      str += '</div>';
      $('#password-message').html(str);
    }
  }

  $('#password-form').submit(function (evt) {
    evt.preventDefault();
    var url = $(this).attr('action');
    var postData = $(this).serialize();

    $.post(url, postData, changeSuccess, 'json');
  });
});