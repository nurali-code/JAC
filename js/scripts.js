$(document).ready(function () {
    // AOS.init({
    //     duration: 1000,
    //     offset: 100,
    // });
    /*---------------------------------------------------end*/

    $('.btn-menu').on('click', function () {
        $(this).toggleClass('active');
        $('header, body').toggleClass('active');
    })

    /*---------------------------------------------------end*/

    function hideModals() {
        $('.modal').fadeOut();
        $('.modal, body, [data-modal]').removeClass('active');
    };
    $(function () {
        function showModal(id) {
            if ($(id).hasClass('active')) {
                $(id).fadeOut(300)
                $(id).removeClass('active');
                $('body').removeClass('active');
            } else {
                $(id).addClass('active')
                $('body').addClass('active');
                $(id).fadeIn(300);
            }
        }

        $('[data-modal]').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('active')
            showModal('#' + $(this).attr("data-modal"));
        });

        $('.modal-close').on('click', () => {
            hideModals();
        });

        $(document).on('click', function (e) {
            if (!(
                ($(e.target).parents('.modal-content').length) ||
                ($(e.target).parents('.nav').length) ||
                ($(e.target).parents('.btn-menu').length) ||
                ($(e.target).hasClass('nav')) ||
                ($(e.target).hasClass('btn-menu')) ||
                ($(e.target).hasClass('btn')) ||
                ($(e.target).hasClass('modal-content'))
            )) {
                hideModals();
            }
        });
    });

    /*---------------------------------------------------end*/

    $('a[href*="#"]').on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: $($(this).attr('href')).offset().top - 40, }, 300,)
    })
    /*---------------------------------------------------end*/
    $('.slider').slick({
        dots: false,
        arrows: true,
        infinite: true,
        speed: 300,
        slidesToShow: 4,
        swipeToSlide: true,
        centerPadding: '0',
        centerMode: true,
        slidesToScroll: 1,
        variableWidth: false,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    centerMode: true,
                    slidesToScroll: 1,
                    variableWidth: false,
                }
            },
            {
                breakpoint: 700,
                settings: {
                    slidesToShow: 1,
                    centerMode: true,
                    centerPadding: '15px',
                    slidesToScroll: 1,
                    variableWidth: false,
                }
            },
        ]
    });

    $('.guarantee-slider').slick({
        dots: false,
        arrows: true,
        infinite: true,
        speed: 300,
        slidesToShow: 3,
        swipeToSlide: true,
        centerMode: false,
        slidesToScroll: 1,
        variableWidth: true,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 1,
                    centerMode: true,
                    slidesToScroll: 1,
                    variableWidth: false,
                }
            },
        ]
    });

    /*---------------------------------------------------end*/

    $(window).scroll(function () {
        if ($(window).scrollTop() >= 500) {
            $(".scroll-up").fadeIn(300);
        } else {
            $(".scroll-up").fadeOut(300);
        }
    });
    /*---------------------------------------------------end*/

    $('input[type="tel"]').inputmask({ "mask": "8-999-999-99-99" });

    /*---------------------------------------------------end*/
    $('.dropdown-btn').click(function () {
        $(this).next('.dropdown-content').slideDown();
        $(this).remove();
    });
    /*---------------------------------------------------end*/

    $("form").submit(function () {
        $('form .btn').addClass('loading');
        $.ajax({
            type: "post",
            method: 'post',
            url: "../sendmail.php",
            data: $(this).serialize()
        }).done(function () {
            $('form .btn').removeClass('loading');
            $('form').trigger('reset');
            alert('Спасибо за заявку. Ожидайте с вами свяжется специалист!');
        }); return false;
    });
});

