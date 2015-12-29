$(function () {
  function resetForm() {
    $('#add-message').html('');
    $('#add-form').find('input').val('');
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
    $('#add-label').html('Add News');
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
          $('#add-message').html('News saved.');
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
      $('#add-header').val(data.header);
      if (lang === 'en') {
        $('#add-content').val(data.content_en);
        $('#add-translated').val(data.content_id);
      } else {
        $('#add-content').val(data.content_id);
        $('#add-translated').val(data.content_en);
      }

      $('#add-modal').modal('show');
    }
  }

  function editNews(id) {
    $('#add-label').html('Update News');
    $.get(baseUrl + 'news/view/' + id, prepEdit, 'json');
  }

  function deleteNews(id) {
    var conf = confirm('Are you sure, you want to delete this news?');
    if (conf === true) {
      $.post(baseUrl + 'news/delete', {"id": id}, refresh, 'json');
    }
  }

  var table = $("#browse").DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": $("#browse").data('url'),
    "columns": [
      {"data": "header"},
      {"data": "img"},
      {"data": "content"},
      {"data": "created"},
      {"data": "modified"},
      {"data": "actions"}
    ],
    "ordering": false,
    "drawCallback": function (settings) {
      $('.edit-btn').click(function () {
        editNews($(this).data('id'));
      });
      $('.delete-btn').click(function () {
        deleteNews($(this).data('id'));
      });
    }
  });
});
