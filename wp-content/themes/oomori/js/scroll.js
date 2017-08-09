var $ = jQuery;
$(document).ready(function () {
  var screenWidth = window.innerWidth;
  //smooth scroll
  $('.scroll-link').on("click", function(){
    var linkAnchor = $(this).attr('href');
    var target = '#' + linkAnchor.split('#')[linkAnchor.split('#').length - 1];
    if(screenWidth > 768) {
      $('html, body').animate({
        scrollTop: $(target).offset().top
      }, 1000);
    } else {
      $('html, body').animate({
        scrollTop: $(target).offset().top - $('#headerInner').outerHeight()
      }, 1000);
    }
    return false;
  });
});