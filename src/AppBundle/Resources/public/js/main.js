$(document).ready(function () {
    'use strict';
    /* ========================================================================
     Fullscreen burger menu
     ========================================================================== */
    $(".menu-trigger, .mobilenav").click(function () {
        $(".mobilenav").fadeToggle(500);
    });
    $(".menu-trigger, .mobilenav").click(function () {
        $(".top-menu").toggleClass("top-animate");
        $(".mid-menu").toggleClass("mid-animate");
        $(".bottom-menu").toggleClass("bottom-animate");
    });

    /* ========================================================================
     On click menu item animate to the section
     ========================================================================== */
    $(".mobilenav li, .back-to-top").on('click', function () {
        var target = $(this).data('rel');
        var $target = $(target);
        $('html, body').stop().animate({
            'scrollTop': $target.offset().top
        }, 900, 'swing');
    });

    /* Header Height Control
     ========================================================================== */
    var height = $(window).height();
    if (height < 600) {
        height = 600;
    }
    $('header').css({
        'minHeight': 0,
        'maxHeight': 'none',
        'height': height
    });
    /* ========================================================================
     Header carousel
     ========================================================================== */
    $('.carousel').carousel({
        interval: 50000
    });

    /* ========================================================================
     Wow Animation
     ========================================================================== */
    var wow = new WOW({
        mobile: false
    });
    wow.init();

    /* ========================================================================
     Collapse event
     ========================================================================== */
    $('.panel-collapse').on('shown.bs.collapse', function () {
        $(this).parent().find(".state").html('<strong>-</strong>');
    });

    $('.panel-collapse').on('hidden.bs.collapse', function () {
        $(this).parent().find(".state").html('<strong>+</strong>');
    });

    /* ========================================================================
     Animated Skill Bar
     ========================================================================== */
    $('.skill-bar li').each(function (i) {
        $(this).appear(function () {
            $(this).animate({opacity: 1, left: "0px"}, 1200);
            var b = $(this).find(".wrapper span").attr("data-width");
            $(this).find("span").animate({
                width: b + "%"
            }, 1700, "swing");
        });
    });

    /* ========================================================================
     Member Skill chart
     ========================================================================== */
    for (var i = 1; i <= 16; i++) { // 16 for 4 members | 4 x 4 = 16
        $('#skill-' + i).circliful();
    }

    /* ========================================================================
     Testimonial Carousel
     ========================================================================== */
    var testimonialCarousel = $("#testimonial-carousel");
    testimonialCarousel.owlCarousel({
        autoPlay: 5000,
        stopOnHover: true,
        slideSpeed: 1000,
        paginationSpeed: 500,
        goToFirstSpeed: 2000,
        singleItem: true,
        responsive: true,
        addClassActive: true,
        navigation: false
    });

    /* ========================================================================
     Nivo Lightbox
     ========================================================================== */
    $('.portfolio a').nivoLightbox({
        effect: 'fall'
    });

    var container = $('.portfolio-wrapper'); // portfoolio container

    container.isotope({
        itemSelector: '.portfolio-item',
        animationEngine: 'best-available',
        animationOptions: {
            duration: 200,
            queue: false
        },
        layoutMode: 'fitRows'
    });

    // sort items on button click
    $('.filters a').on('click', function () {
        $('.filters a').removeClass('active');
        $(this).addClass('active');
        var filterValue = $(this).attr('data-filter');
        container.isotope({
            filter: filterValue
        });
        initIsotope();
        return false;
    });

    // Split columns for different size layout
    function splitColumns() {
        var windowWidth = $(window).width(),
            columnNumber = 1; //  default column number
        if (windowWidth > 1200) {
            columnNumber = 4;
        } else if (windowWidth > 767) {
            columnNumber = 3;
        } else if (windowWidth > 600) {
            columnNumber = 2;
        }
        return columnNumber;
    }

    // Set width for portfolio item
    function setColumns() {
        var windowWidth = $(window).width(),
            columnNumber = splitColumns(),
            postWidth = Math.floor(windowWidth / columnNumber);

        container.find('.portfolio-item').each(function () {
            $(this).css({
                width: postWidth + 'px'
            });
        });
    }

    // initialize isotope
    function initIsotope() {
        setColumns();
        container.isotope('layout');
    }

    container.imagesLoaded(function () {
        setColumns();
    });
    $(window).bind('resize', function () {
        initIsotope();
    });
    initIsotope();
});