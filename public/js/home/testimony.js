$(function () {
  function testimonyItemBuilder(data) {
    var timePosted = new Date(data.created);
    var str = '<article class="testimony bg-info">';
    str += '<header class="testimony-header"><h5>' + data.name + '</h5></header>';
    str += '<div class="testimony-content">';
    str += '<p>' + data.content + '</p>';
    str += '</div>';
    str += '<footer class="testimony-footer"><small>';
    str += timePosted.toLocaleString();
    str += '</small></footer>';
    str += '</article>';
    return str;
  }

  // ================================================= ======================================= =========================
  function testimonyAdd(data) {
    if (data[0]) {
      $('#testimony-container').prepend(testimonyItemBuilder(data[0]));
      $('#testimony-content').val('');
    } else {
      alert('Failed to add testimony.');
    }
  }

  $('#testimony-form').submit(function (evt) {
    evt.preventDefault();
    var url = $(this).attr('action');
    var postData = $(this).serialize();

    $.post(url, postData, testimonyAdd, 'json');
  });

  // ================================================= ======================================= =========================
  function populateTestimony(data) {
    var btn = $('#testimony-btn');
    if (data[0].error === undefined && data[0].testimonies.length >= 10) {
      btn.removeAttr('disabled');
    } else {
      btn.attr('disabled', true);
    }

    var testimony = data[0].testimonies;
    for (var i = 0; i < testimony.length; i++) {
      btn.before(testimonyItemBuilder(testimony[i]));
    }
    btn.data('testimonyId', testimony[i - 1].id);
  }

  $('#testimony-btn').click(function () {
    $(this).attr('disabled', true);
    $.get($(this).data('url') + ($(this).data('testimonyId') > 0 ? '/' + $(this).data('testimonyId') : ''),
            populateTestimony, 'json');
  });

  $.get($('#testimony-btn').data('url'), populateTestimony, 'json');

  // ================================================= ======================================= =========================

});