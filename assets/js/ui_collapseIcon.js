
$('body').on('click', '.panel-group>.panel>.panel-heading>.panel-title>a', function(event) {
  var closeIcon = 'fa fa-chevron-right';
  var openIcon = 'fa fa-chevron-down';

  var elm = $(this);
  var elmGroup = elm.parent('.panel-title').parent('.panel-heading').parent('.panel').parent('.panel-group');

  var elmPanel = elm.parent('.panel-title').parent('.panel-heading').parent('.panel').children('div');

  var elmIcon  = elm.children('i');

  elmGroup.children('.panel').children('.panel-heading').children('.panel-title').children('a').children('i').attr('class', closeIcon);
  event.preventDefault();

  if (elmPanel.hasClass('in')) {
    elmIcon.attr('class', closeIcon);
  } else {
    elmIcon.attr('class', openIcon);
  }

});
