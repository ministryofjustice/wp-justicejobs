jQuery(document).ready(function ($) {
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
      if (e.keyCode === 27) {
        window.close();
      }
    }
  });

  // close popup: escape key
  $(document).on('keydown', function (event) {
    if (event.key == "Escape") {
      $('.popup .btn-close').click();
    }
  });

  // close popup: background clicked
  $(".popup").click(function (event) {
    if ($(event.target).hasClass('is-opened')) {
      $('.popup').removeClass('is-opened');
    }
  });

  //agency.php / Bottom block carousel
  $('.carousel-popup-open').on('click', e => {
    e.preventDefault();
    var i = $(this).data('index');
    $('.popup--carousel').eq(i).addClass('is-opened');
  });

  //page-campaign.php / People Stories section / pop-up carousel inside section carousel
  $('.campaign__carousel').on('click', '.campaign-carousel-popup-open', function (e) {
    e.preventDefault();
    var i = $(this).data('index');
    $('.popup--carousel').eq(i).addClass('is-opened');

    maybe_show_close_btn($('.popup--carousel').eq(i));
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
});
