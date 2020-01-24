jQuery(document).ready(function ($) {
    // Injecting svg sprite in the beginning of document
    var ajax = new XMLHttpRequest();
    ajax.open('GET', justice.root_url + '/dist/img/sprite.svg', true);
    ajax.responseType = 'document';
    ajax.onload = function (e) {
        if (!ajax.responseXML) {
            // nothing for us here
        } else {
            document.body.insertBefore(
                ajax.responseXML.documentElement,
                document.body.childNodes[0]
            );
        }

    };
    ajax.send();

    $('.page-header__menu').click(function() {
        var target = $(this);
        console.log(target);
        if (target.hasClass('closed')) {
            $('body').addClass('disable-scrolling');
            target.removeClass('closed');
            target.addClass('open');
        } else {
            $('body').removeClass('disable-scrolling');
            target.removeClass('open');
            target.addClass('closed');
        }
    });

    $('.carousel').each((i, el) => {
        $(el)
            .find('.carousel__container')
            .slick({
                dots: true,
                speed: 700,
                cssEase: 'ease-in',
                infinite: true,
                appendDots: $(el).find('.carousel__dots'),
                prevArrow: $(el).find('.carousel__arrow--prev'),
                nextArrow: $(el).find('.carousel__arrow--next')
            });
    });

    // $('.video-popup').on('click', function(e) {
    //   console.log('in video popup');
    //   e.preventDefault();
    //   var i = $(this).data('index');
    //   // var i = e.target.dataset.index;
    //   console.log(i);
    //   $('.popup--video').eq(i).addClass('is-opened');
    // });

    //page-campaign.php / People Stories section / videos inside section carousel
    $('.campaign__carousel').on('click', '.video-campaign', function (e) {
        e.preventDefault();
        var videoSrc = $(this).data('video');
        $('.popup--video').find('iframe').prop('src', videoSrc);
        setTimeout(function () {
            $('.popup--video').addClass('is-opened');
        }, 500);
    });

    //page-campaign.php/Intro section & page-about.php/Overview section / single video
    $('.single-video').on('click', function (e) {
        e.preventDefault();
        var videoSrc = $(this).data('video');
        $('.popup--video').find('iframe').prop('src', videoSrc);
        setTimeout(function () {
            $('.popup--video').addClass('is-opened');
        }, 300);

        window.onkeyup = function (e) {
            if (e.keyCode == 27) {
                window.close();
            }
        }
    });

    $(document).on('keydown', function (event) {
        if (event.key == "Escape") {
            $('.popup .btn-close').click();
        }
    });

    //agency.php / Bottom block carousel
    $('.carousel-popup-open').on('click', e => {
        e.preventDefault();
        var i = e.target.dataset.index;
        $('.popup--carousel').eq(i).addClass('is-opened');
    });

    //page-campaign.php / People Stories section / pop-up carousel inside section carousel
    $('.campaign__carousel').on('click', '.campaign-carousel-popup-open', function (e) {
        e.preventDefault();
        var i = $(this).data('index');
        $('.popup--carousel').eq(i).addClass('is-opened');
    });

    $('.popup .btn-close').on('click', () => {
        $('.popup').removeClass('is-opened');
        var iframe = document.querySelector('iframe');
        if (iframe) {
            var iframeSrc = iframe.src;
            iframe.src = iframeSrc;
        }
    });

    if ($('.popup__carousel').length > 0) {
        $('.popup__carousel').each((i, el) => {
            // find a way to trap focus
        })
    }

    $('.dropdown__current').on('click', e => {
        e.stopPropagation();
        var $target = $(e.target);
        var dropdown = $target.closest('.dropdown');

        if (dropdown.hasClass('is-opened')) {
            $target.closest('.dropdown').removeClass('is-opened');
        } else {
            $('.dropdown').removeClass('is-opened');
            $target.closest('.dropdown').addClass('is-opened');
        }
    });

    $('.dropdown__list li').on('click', function (e) {
        e.stopPropagation();
        var target = $(e.target);
        var val = e.target.innerHTML;
        var dropdown = target.closest('.dropdown');

        dropdown.find('.dropdown__current').val(val);
        dropdown.removeClass('is-opened');
    });

    $(document).on('click', function () {
        $('.dropdown').removeClass('is-opened');
    });

    /* Accordion controls */
    $('.accordion__btn').on('click', function () {
        $('.inner_accordion__block').removeClass('inner_is-opened');
        $('.inner_accordion__content-wrap').slideUp();
        var btn = $(this);
        var block = btn.closest('.accordion__block');

        if (block.hasClass('is-opened')) {
            block
                .removeClass('is-opened')
                .attr("aria-expanded", "false")
                .find('.accordion__content-wrap')
                .slideUp();
            $('.accordion__btn')
                .attr("aria-expanded", "false")
        } else {
            $('.accordion__block')
                .removeClass('is-opened')
                .attr("aria-expanded", "false")
                .find('.accordion__content-wrap')
                .slideUp();
            $('.accordion__btn')
                .attr("aria-expanded", "true")
            block
                .addClass('is-opened')
                .attr("aria-expanded", "true")
                .find('.accordion__content-wrap')
                .slideDown();
        }
    });

    // Inner Accordion controls
    $('.inner_accordion__btn').on('click', function () {
        var btn = $(this);
        var block = btn.closest('.inner_accordion__block');
        if (block.hasClass('inner_is-opened')) {
            block
                .removeClass('inner_is-opened')
                .attr("aria-expanded", "false")
                .find('.inner_accordion__content-wrap')
                .slideUp();
        } else {
            // $('.accordion__block')
            //   .removeClass('is-opened')
            //   .find('.accordion__content-wrap')
            //   .slideUp();
            block
                .addClass('inner_is-opened')
                .attr("aria-expanded", "true")
                .find('.inner_accordion__content-wrap')
                .slideDown();
        }
    });

    $('.accordion__btn').on('keydown', function () {
        var target = event.target;
        var key = event.which.toString();

        // 33 = Page Up, 34 = Page Down
        var ctrlModifier = (event.ctrlKey && key.match(/33|34/));

        // Collects where current focus is in relation to other accordions
        var triggers = $('.accordion__btn');
        var current = triggers.filter(target);
        var position = triggers.index(current);

        // 38 = Up, 40 = Down
        if (key.match(/38|40/) || ctrlModifier) {
            var direction = (key.match(/34|40/)) ? 1 : -1;
            var length = triggers.length;
            var newIndex = (position + length + direction) % length;
            triggers[newIndex].focus();
            event.preventDefault();
        }
        else if (key.match(/35|36/)) {
            // 35 = End, 36 = Home keyboard operations
            switch (key) {
                case '36':
                    triggers[0].focus();
                    break;
                case '35':
                    triggers[triggers.length - 1].focus();
                    break;
            }
            event.preventDefault();
        }
    });


    if ($('.campaign__carousel').length > 0) {
        $('.campaign__carousel').slick({
            dots: true,
            infinite: true,
            slidesToShow: 3,
            appendDots: $('.campaign__carousel-wrap .carousel__dots'),
            prevArrow: $('.campaign__carousel-wrap .carousel__arrow--prev'),
            nextArrow: $('.campaign__carousel-wrap .carousel__arrow--next'),
            responsive: [{
                breakpoint: 1023,
                settings: {
                    slidesToShow: 2
                }
            },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    }

    // Remove default inline styles from MCE tables
    $('td,th,table').removeAttr('style');
    $('td,th,table').removeAttr('width');
    $('td,th,table').removeAttr('border');

    // Change background colour of nav menu when scrolling down
    var position = $(window).scrollTop();
    $(document).scroll(function () {
        var scroll = $(window).scrollTop();
        var nav = $('.page-header__nav');
        var page_header = $('.page-header');

        page_header.toggleClass('scroll-down', $(this).scrollTop() > 0.6 * nav.height());
        // if (scroll > 2*nav.height() && scroll>position){
        //   console.log('scrolldown');
        //   page_header.addClass('scroll-down');
        // } else if (scroll < 2*nav.height() && scroll<position) {
        //   console.log('scrollup');
        //   page_header.addClass('scroll-up');
        // }
        // position = scroll;

    });


    // Awards carousel on homepage
    $(window).on('load resize', function () {
        if ($(window).width() < 1024) {
            $('.awards-carousel').slick({
                infinite: true,
                arrows: false,
                slidesToScroll: 1,
                responsive: [{
                    breakpoint: 1023,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        } else {
            if ($('.awards-carousel').hasClass('slick-initialized')) {
                $('.awards-carousel').slick("unslick");
            }
        }
    });


    // Saves most recent search for use in Back button
    $('.back-to-search').each(function () {
        $(this).on('click', function () {
            history.back();
        });
    });


    // My video code
    // $('.agency__video').on('click', function(e) {
    //   var video = this.querySelector('video');
    //
    //   if ($(window).width() > 767) {
    //     //passing video src to the iframe
    //     var videoSrc = document.querySelector('video source');
    //     $('.popup--video').addClass('is-opened');
    //     var iframe = document.querySelector('iframe');
    //     if (iframe) {
    //       iframe.src = videoSrc.src;
    //     }
    //   } else {
    //     if (video.paused) {
    //       console.log('before playing...');
    //       const playPromise = video.play();
    //       if (playPromise !== null){
    //           playPromise.catch(() => { video.play(); })
    //       }
    //       video.play();
    //     } else {
    //       video.pause();
    //     }
    //   }
    // });
    //
    // $('.overview__video').on('click', function(e) {
    //   var video = this.querySelector('video');
    //
    //   if ($(window).width() > 767) {
    //     //passing video src to the iframe
    //     var videoSrc = document.querySelector('video source');
    //     $('.popup--video').addClass('is-opened');
    //     var iframe = document.querySelector('iframe');
    //     if (iframe) {
    //       iframe.src = videoSrc.src;
    //     }
    //   } else {
    //     if (video.paused) {
    //       video.play();
    //     } else {
    //       video.pause();
    //     }
    //   }
    // });

    // $('.popup .btn-close').on('click', () => {
    //   $('.popup').removeClass('is-opened');
    //   var iframe = document.querySelector('iframe');
    //   if (iframe) {
    //     // var iframeSrc = iframe.src;
    //     iframe.src = "";
    //   }
    // });

    if ($('.job__text').length) {
        console.log('woot');
        $('.job__text').children('p').each(function (e) {
            if (isEmpty($(this))) {
                $(this).remove();
            }
        });
    }

    function isEmpty(el) {
        return !$.trim(el.html())
    }

});
