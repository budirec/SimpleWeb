$(function () {
  function resetForm() {
    $('#add-message').html('');
    $('#add-form').find('input').val('');
  }

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
          $('#add-message').html('New content saved.');
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
      $('#add-content').val(data.content);
      $('#add-modal').modal('show');
    }
  }

  function edit(id) {
    $.get(baseUrl + 'basic_content/view/' + id, prepEdit, 'json');
  }

  function refresh(data) {
    if (data.error) {
      alert(data.error);
    } else {
      table.draw(false);
    }
  }

  var table = $("#browse").DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": $("#browse").data('url'),
    "columns": [
      {"data": "name"},
      {"data": "content"},
      {"data": "created"},
      {"data": "modified"},
      {"data": "actions"}
    ],
    "ordering": false,
    "drawCallback": function (settings) {
      $('.edit-btn').click(function () {
        edit($(this).data('id'));
      });
    }
  });
});
