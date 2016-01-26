//= require mdl/material.min.js
//= require core/jquery-2.1.4.min.js

// tr.data-href
$(function() {
  path = location.pathname + "/";
  $(".mdl-navigation__link").each(function() {
    if (path.indexOf($(this).attr("href")) === 0) {
      return $(this).addClass("is-active");
    }
  });
  
  $('.mdl-data-table tbody tr').each(function() {
    var href = $(this).find('a:first').attr('href');
    if (!href) return;
    $(this).closest('tr').addClass('clickable').css('cursor', 'pointer').click(function(e) {
      if (!$(e.target).is('a') && $(e.target).closest('label').length == 0) {
        window.location = href;
      }
    });
  });
  
  //$('tr[data-href]').addClass('clickable').css('cursor', 'pointer').click(function(e) {
  //  if (!$(e.target).is('a') && $(e.target).closest('label').length == 0) {
  //    window.location = $(e.target).closest('tr').data('href');
  //  }
  //});
});

// tr.data-id
$(document).on('click', '.mdl-data-table--selectable .mdl-checkbox__ripple-container', function() {
  $(this).closest('table').find('tbody tr').each(function() {
    var tr = $(this);
    tr.find('label:first').each(function() {
      var chk = $(this).hasClass('is-checked');
      $(this).find('input').attr('name', 'id[]').val(tr.data('id')).prop('checked', chk);
    });
  });
});
