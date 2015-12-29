$(function () {
  function getView(evt) {
    evt.preventDefault();
    $.get($(this).attr('href'), function(data){
      $('#main-content').html(data);
    }, 'text');
  }
  $('#content-nav').on('click', getView);
  $('#user-nav').on('click', getView);
  $('#news-nav').on('click', getView);
  $('#project-nav').on('click', getView);
  
});