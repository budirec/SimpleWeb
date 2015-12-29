$(function () {
  function resetForm() {
    $('#add-message').html('');
    $('#add-form').find('input').val('');
    $('#add-admin').removeProp('checked');
  }

  function refresh(data) {
    if (data.error) {
      alert(data.error);
    } else {
      table.draw(false);
    }
  }

  $('#new-btn').click(function () {
    resetForm();
    $('#add-label').html('Add User');
  });

  $('#add-form').submit(function (evt) {
    evt.preventDefault();
    var url = $(this).attr('action');
    var formData = new FormData($(this)[0]);

    $.ajax({
      'url': url,
      'type': 'POST',
      'data': formData,
      'async': false,
      'cache': false,
      'contentType': false,
      'processData': false,
      'success': function (data) {
        if (data.error) {
          $('#add-message').html(data.error);
        } else {
          $('#add-message').html('User saved.');
          setTimeout(function () {
            resetForm();
            refresh(true);
            $('#add-modal').modal('hide');
          }, 500);
        }
      },
      'error': function () {
        alert("error in ajax form submission");
      },
      'dataType': 'json'
    });
  });

  function prepEdit(data) {
    resetForm();

    if (data.error) {
      $('#add-message').html(data.error);
    } else {
      $('#add-id').val(data.id);
      $('#add-name').val(data.name);
      $('#add-email').val(data.email);
      if(data.adm == 1){
        $('#add-admin').prop('checked', true);
      }

      $('#add-modal').modal('show');
    }
  }

  function edit(id) {
    $('#add-label').html('Update User');
    $.get(baseUrl + 'user/view/' + id, prepEdit, 'json');
  }

  function deleteUser(id) {
    var conf = confirm('Are you sure, you want to delete this user?');
    if (conf === true) {
      $.post(baseUrl + 'user/delete', {"id": id}, refresh, 'json');
    }
  }

  var table = $("#browse").DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": $("#browse").data('url'),
    "columns": [
      {"data": "name"},
      {"data": "email"},
      {"data": "last_login"},
      {"data": "created"},
      {"data": "modified"},
      {"data": "actions"}
    ],
    "ordering": false,
    "drawCallback": function (settings) {
      $('.edit-btn').click(function () {
        edit($(this).data('id'));
      });
      $('.delete-btn').click(function () {
        deleteUser($(this).data('id'));
      });
    }
  });
});
