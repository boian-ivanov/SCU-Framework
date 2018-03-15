$(document).scroll(function () {
    var y = $(this).scrollTop();
    var navWrap = $('#nav_wrap').offset().top;
    if (y > navWrap) {
        $('nav').addClass('sticky');
    } else {
        $('nav').removeClass('sticky');
    }
});