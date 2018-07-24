$(document).scroll(function () {
    // Sticky nav bar
    var y = $(this).scrollTop();
    var navWrap = $('#nav_wrap').offset().top;
    if (y > navWrap) {
        $('nav').addClass('sticky');
    } else {
        $('nav').removeClass('sticky');
    }

    // Carousel cards
    $(window).resize(function () {
        if ($(window).width() < 800) {
            slickInit();
        } else {
            $('.cards').slick('unslick');
        }
    });

    function slickInit() {
        $('.cards').not('.slick-initialized').slick({
            infinite: false,
            responsive: true,
            centerMode: true,
            variableWidth: false,
            focusOnSelect: true,
            arrows: false,
            mobileFirst: true,
            centerPadding: '15px',
            // slidesToShow: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            pauseOnFocus: true,
            responsive: [
                {
                    breakpoint: 500,
                    settings: {
                        centerPadding: '10%',
                    }
                }
            ]
        });
    };
    if ($(window).width() < 800) {
        slickInit();
    }
});