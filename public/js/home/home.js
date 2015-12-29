$(function(){
  function getContent(evt){
    evt.preventDefault();
    $.get($(this).data('url'), function(data){
      $('#mainContent').html(data);
    }, 'text');
  }
  
  $('#project-nav').on('click', getContent);
  $('#about-nav').on('click', getContent);
  $('#contact-nav').on('click', getContent);
});