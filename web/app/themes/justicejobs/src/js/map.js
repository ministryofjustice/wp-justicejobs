/*
The Map Functionality
*/

(function ($) {

    var map,
        infoWindow,
        markers = [],
        $markers = [];

    // Toggle map appearing
    if ($('.search_contain__map-wrap').length) {
        _initMap();
    }

    // Pop-up map entry
    $('.search_contain__item').on('click', function (e) {
        var search_id = $(this).children('.marker').data('id');
        $('.search_contain__item.active').removeClass('active');
        $(this).addClass('active');

        $.each(markers, function () {
            if (this.id == search_id) {
                var content = '<h3 style="width: 250px; font-size: 18px;">' + this.title + '<br /></h3>',
                    ariaLabel = '';

                if (this.url) {
                    ariaLabel = 'View the job description for ' + this.title.split(' – ')[0].trim();
                    content = content + '<br/><a href="' + this.url + '"  class="btn btn--blue btn--small btn--job-open" style="font-size: 14px; min-height:30px; min-width: 120px;" aria-label="' + ariaLabel + '">View Job</a>';
                }
                if (infoWindow) {
                    infoWindow.close();
                }
                infoWindow = new google.maps.InfoWindow();
                infoWindow.setContent(content);
                infoWindow.open(map, this);
            }
        });
    });

    function _initMap() {
        $markers = $('.search_contain__item').find('.marker');
        var bounds = new google.maps.LatLngBounds();

        map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(54.00366, -2.547855),
            zoom: 6
        });

        $($markers).each(function (i, v) {
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng($(this).data('lat'), $(this).data('lng')),
                map: map,
                title: $(this).data('title'),
                id: $(this).data('id'),
                url: $(this).data('url'),
                animation: google.maps.Animation.DROP,
                icon: new google.maps.MarkerImage(
                    'https://justicejobs.dev.wp.dsd.io/app/themes/justicejobs/dist/img/icons/marker.png',
                    null, // size
                    null, // origin
                    new google.maps.Point(9, 19), // anchor (move to center of marker)
                    new google.maps.Size(27, 38) // scaled size (required for Retina display icon)
                )
            });

            //extend the bounds to include each marker's position
            bounds.extend(marker.position);

            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    var content = '<h3 style="width: 250px; font-size: 18px;">' + marker.title + '<br /></h3>',
                        ariaLabel = '';

                    if (marker.url) {
                        ariaLabel = 'View the job description for ' + marker.title.split(' – ')[0].trim();
                        content = content + '<br/><a href="' + marker.url + '"  class="btn btn--blue btn--small btn--job-open" style="font-size: 14px; min-height:30px; min-width: 120px;" aria-label="' + ariaLabel + '">View Job</a>';
                    }
                    //marker.setIcon('http://178.128.172.31/wp-content/themes/justicejobs/img/icons/marker.png');
                    if (infoWindow) {
                        infoWindow.close();
                    }

                    infoWindow = new google.maps.InfoWindow();
                    infoWindow.setContent(content);
                    infoWindow.open(map, marker);

                    $('.search_contain__item.active').removeClass('active');
                    $('.search_contain__item .marker[data-id="' + marker.id + '"]').parent('.search_contain__item').addClass('active');
                }
            })(marker, i));

            markers.push(marker);

            if ($('.search_contain__item').length === 1) {
                setTimeout(function () {
                    $('.search_contain__item').eq(0).click();
                }, 900);
            }
        });

        var zoomChangeBoundsListener =
            google.maps.event.addListener(map, 'bounds_changed', function (event) {
                google.maps.event.removeListener(zoomChangeBoundsListener);
                map.setZoom(Math.min(15, map.getZoom()));
            });
        //now fit the map to the newly inclusive bounds
        map.fitBounds(bounds);
    }
})(jQuery);
