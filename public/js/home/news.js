$(function () {
  function newsItemBuilder(data) {
    var timePosted = new Date(data.created);
    var str = '<article class="news bg-info">';
    str += '<header class="news-header"><h5>' + data.header + '</h5></header>';
    str += '<div class="news-content">';
    str += (data.img ? '<img src="public/img/' + data.img + '" class="img-rounded img-responsive">' : '<p>' + data.content + '</p>');
    str += '</div>';
    str += '<footer class="news-footer"><small>';
    str += timePosted.toLocaleString();
    str += ' <a href="#" data-news-id="' + data.id + '" class="news-read label label-primary">Read more...</a>';
    str += '</small></footer>';
    str += '</article>';

    return str;
  }

  function showNews(data) {
    console.log(data);

    var timePosted = new Date(data.created);
    var timeModified = new Date(data.modified);
    var str = '<article>';
    str += '<header class="news-header"><h3>' + data.header + '</h3></header>';
    str += '<div class="news-content">';
    str += (data.img ? '<img src="public/img/' + data.img + '" class="pull-left img-rounded img-responsive" alt="' + data.img + '">' : '');
    if (lang === 'en') {
      str += (data.content_en ? data.content_en : '');
      str += (data.content_en && data.content_id ? '<hr>' : '');
      str += (data.content_id ? data.content_id : '');
    } else {
      str += (data.content_id ? data.content_id : '');
      str += (data.content_en && data.content_id ? '<hr>' : '');
      str += (data.content_en ? data.content_en : '');
    }
    str += '</div>';
    str += '<footer class="news-footer">';
    str += timePosted.toLocaleString();
    str += '</footer>';
    str += '</article>';

    $('#mainContent').html(str);
  }

  // ==================================================== ==============================================================
  function populateNews(data) {
    var btn = $('#usa-news-btn');
    if (data[0].region === 'id') {
      btn = $('#indo-news-btn');
    }

    if (data[0].error === undefined && data[0].news.length >= 10) {
      btn.removeAttr('disabled');
    } else {
      btn.attr('disabled', true);
    }

    var news = data[0].news;
    for (var i = 0; i < news.length; i++) {
      btn.before(newsItemBuilder(news[i]));
    }
    btn.data('newsId', news[i - 1].id);

    $(".news-read").on("click", function () {
      $.get('news/view/' + $(this).data('newsId'), showNews, 'json');
    });
  }

  $('#usa-news-btn').click(function () {
    $(this).attr('disabled', true);
    $.get($(this).data('url') + ($(this).data('newsId') > 0 ? '/' + $(this).data('newsId') : ''), populateNews, 'json');
  });

  $('#indo-news-btn').click(function () {
    $(this).attr('disabled', true);
    $.get($(this).data('url') + ($(this).data('newsId') > 0 ? '/' + $(this).data('newsId') : ''), populateNews, 'json');
  });

  $.get($('#usa-news-btn').data('url'), populateNews, 'json');
  $.get($('#indo-news-btn').data('url'), populateNews, 'json');

  // ==================================================== ==============================================================
});