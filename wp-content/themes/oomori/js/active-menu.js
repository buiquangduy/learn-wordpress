jQuery(document).ready(function () {
  jQuery('.headerGlobalContent li a').each(function() {
    var itemUrl = jQuery(this).attr('href');
    if( window.location.href ===  itemUrl || window.location.pathname ===  itemUrl || window.location.pathname ===  itemUrl + '/') {
      jQuery(this).addClass('active');
    }
  })
});