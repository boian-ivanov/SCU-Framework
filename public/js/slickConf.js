$(document).ready(function () {
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
            responsive: [
                {
                    breakpoint: 410,
                    settings: {
                        arrows: true,
                        centerPadding: '5%',
                        slidesToShow: 1,
                        autoplay: true,
                        autoplaySpeed: 3000,
                        pauseOnFocus: true
                    }
                }
            ]
        });
    };
    slickInit();
});