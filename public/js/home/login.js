$(function () {
  function loginSuccess(data) {
    if (data.success === 1) {
      var str = '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">';
      str += data.email + '<span class="caret"></span>';
      str += '</a>';
      str += '<ul class="dropdown-menu">';
      str += '<li><a href="#" data-toggle="modal" data-target="#password-modal">Change Password</a></li>';

      if (data.is_adm !== 0) {
        str += '<li>';
        str += '<a href="' + data.is_adm + '">Admin Page</a>';
        str += '</li>';
      }

      str += '<li role="separator" class="divider"></li>';
      str += '<li><a href="' + data.logout + '">Logout</a></li>';
      str += '</ul>';

      $('#user-nav').html(str);
      $('#login-modal').modal('hide');
    } else {
      var str = '<div class="alert alert-danger alert-dismissible" role="alert">';
      str += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
      str += '<strong>Error!</strong> <p>Username or password doesn\'t match.</p>';
      str += '</div>';
      $('#login-message').html(str);
    }
  }

  $('#login-form').submit(function (evt) {
    evt.preventDefault();
    var url = $(this).attr('action');
    var postData = $(this).serialize();

    $.post(url, postData, loginSuccess, 'json');
  });

  function registerSuccess(data) {
    if (data.success === 1) {
      $('#register-message').html('<p></p>');
      var str = '<div class="alert alert-success alert-dismissible" role="alert">';
      str += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
      str += '<strong>Success!</strong> Your account has been created.';
      str += '</div>';
      $('#register-message').html(str);
      setTimeout(function () {
        loginSuccess(data);
      }, 500);
    } else {
      var str = '<div class="alert alert-danger alert-dismissible" role="alert">';
      str += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
      str += '<strong>Error!</strong> ' + data.error;
      str += '</div>';
      $('#register-message').html(str);
    }
  }

  $('#register-form').submit(function (evt) {
    evt.preventDefault();
    var url = $(this).attr('action');
    var postData = $(this).serialize();

    $.post(url, postData, registerSuccess, 'json');
  });
});