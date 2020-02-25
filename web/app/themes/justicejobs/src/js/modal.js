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

  $('.agency__featured').click(function (e) {
    var link = $(this).closest('.agency__featured').find('a').eq(0),
      resolveLink = null;

    // check for popup...
    if (link.attr('href')[0] === '#') {
      resolveLink = $(this).find('a.carousel-popup-open');
      if (resolveLink.length > 0) {
        // open the popup
        $('.popup--carousel').eq(resolveLink.data('index')).addClass('is-opened');
        maybe_show_close_btn($('.popup.is-opened'));
      }
      return false;
    }
    window.location = link.prop('href');
  });

  $(window).resize(function () {
    maybe_show_close_btn($('.popup.is-opened'));
  });
});

function maybe_show_close_btn(ele) {
  if (ele instanceof jQuery && ele.length > 0) {
    var closeBtn = ele.find('.btn-close'),
      position = closeBtn.position(),
      btnHeight = closeBtn.outerHeight(),
      ii = 100,
      cnt = 0;

    if (!position.top) {
      return false;
    }

    if (closeBtn.is(':offscreen') || (position.top - 5) < btnHeight) {
      var blockToMove = ele.find('.popup__block'),
        distance = 10;

      for (ii; cnt < ii; ii++) {
        distance = distance + 20;
        blockToMove.css({
          top: distance
        });

        if (!closeBtn.is(':offscreen')) {
          blockToMove.css({
            top: distance + btnHeight
          });
          break;
        }
        cnt++;
      }
    }
  }
}

/**
 * Create a new jquery selector - :offscreen
 * obj.is(':offscreen')
 * @param el
 * @returns {boolean}
 */

jQuery.expr.filters.offscreen = function (el) {
  var rect = el.getBoundingClientRect();
  return (
    (rect.x + rect.width) < 0
    || (rect.y + rect.height) < 0
    || (rect.x > window.innerWidth || rect.y > window.innerHeight)
  );
};
