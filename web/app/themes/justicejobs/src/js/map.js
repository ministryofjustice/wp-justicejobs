/*
The Map Functionality
*/

(function($) {

	var map;
	var centreUK = {lat: 54.00366, lng: -2.547855};
	var geocoder;
	var infowindow;
	var marker;
	var i;
	var service;
	var marker;
	var markers = [];
	var $markers = [];
	var markerCluster;
	var locations = [
     ];


	if ($('.search_contain__map-wrap').length) {
		initMap();
	}

	// Pop-up map entry
	$('.search_contain__item').on('click', function( e ){
		//e.preventDefault();
		var search_id = $(this).children('.marker').data('id');

		$('.search_contain__item.active').removeClass('active');
		$(this).addClass('active');

		$.each(markers, function(){
			if ( this.id == search_id ) {
				var content =  '<h3 style="width: 250px; font-size: 18px;">' + this.title + '<br /></h3>';

				if ( this.url ) {
					content = content + '<br/><a href="' + this.url + '"  class="btn btn--blue btn--small btn--job-open" style="font-size: 16px; min-height:40px; min-width: 120px;">View Job</a>';
				}

				if (infowindow) {
	          infowindow.close();
	      }
	      infowindow = new google.maps.InfoWindow();

				infowindow.setContent(content );
				infowindow.open( map, this);
			}
		});

	});


	function initMap() {

		$markers 	= $('.search_contain__item').find('.marker');

		map = new google.maps.Map(document.getElementById('map'), {
			center: centreUK,
			zoom: 6
		});

		infowindow = new google.maps.InfoWindow({
			content: ''
		});
		geocoder = new google.maps.Geocoder();

		$($markers).each(function(){
			var latlng = new google.maps.LatLng($(this).data('lat'), $(this).data('lng'));
			var marker = new google.maps.Marker({
				position: latlng,
				map: map,
				title: $(this).data('title'),
				id: $(this).data('id'),
				url: $(this).data('url'),
				animation: google.maps.Animation.DROP,
				icon : new google.maps.MarkerImage(
					'http://178.128.172.31/wp-content/themes/justicejobs/img/icons/marker.png',
					null, // size
					null, // origin
					new google.maps.Point( 9, 19 ), // anchor (move to center of marker)
					new google.maps.Size( 27, 38 ) // scaled size (required for Retina display icon)
				)
			});



			marker.addListener('click', function( e ) {
				var content =  '<h3 style="width: 250px; font-size: 18px;">' + marker.title + '<br /></h3>';

				if ( marker.url ) {
					content = content + '<br/><a href="' + marker.url + '"  class="btn btn--blue btn--small btn--job-open" style="font-size: 16px; min-height:40px; min-width: 120px;">View Job</a>';
				}

				infowindow.setContent(content );
				infowindow.open( map, marker);

				console.log(marker.id);

				$('.search_contain__item.active').removeClass('active');
				$('.search_contain__item .marker[data-id="' + marker.id + '"]').parent('.search_contain__item').addClass('active');

			});


			markers.push( marker );

		});

/*
		var markerCluster = new MarkerClusterer(map, markers,
				{imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});




		google.maps.event.addListener( map, 'idle', function() {


			$(markers).each(function(key, value) {
				console.log($(this));
				if ( map.getBounds().contains(value.getPosition()) && value.getVisible() ) {
					$('.marker[data-id="'+ value.id  +'"]').parent().show();
				} else {
					$('.marker[data-id="'+ value.id  +'"]').parent().hide();
				}

			 $('.marker[data-id="'+ value.id  +'"]').parent().data('distance', value.distance );
				//theme.scrollbar.recalculate();

			});

			$(window).resize();

		});


    for (i = 0; i < locations.length; i++) {
			if (locations[i].length > 1) {
				for (var j = 0; j < locations[i].length; j++) {
					console.log(locations[i]);
					//geocodeAddressString(locations[i][j]);
					createMarker(locations[i]);
				}
			} else {
				if (locations[i] == 'NATIONAL') {
					console.log('Nationwide');
				} else {
					console.log(locations[i]);
					createMarker(locations[i]);
				}

			}

    }
		*/


		function geocodeAddress(location) {
		  geocoder.geocode( { 'address': location[0]}, function(results, status) {
		  //alert(status);
		    if (status == google.maps.GeocoderStatus.OK) {

		      //createMarker(results[0].geometry.location,location[0]+"<br>"+location[1]);
					createMarker(results[0].geometry.location,location[0]);
		    }
		    else
		    {
		      alert("some problem in geocode" + status);
		    }
		  });
		}

		function geocodeAddressString(location) {
		  geocoder.geocode( { 'address': location}, function(results, status) {
				console.log(location);
		  //alert(status);
		    if (status == google.maps.GeocoderStatus.OK) {

		      createMarker(results[0].geometry.location,location);

		    }
		    else
		    {
		      alert("some problem in geocode" + status);
		    }
		  });
		}


/*


		var request = {
	    location: {lat: 51.8491751, lng: -1.0964753},
	    radius: '5000',
	    type: ['restaurant']
	  };

	  service = new google.maps.places.PlacesService(map);
	  service.nearbySearch(request, callback);

		function callback(results, status) {
		  if (status == google.maps.places.PlacesServiceStatus.OK) {
		    for (var i = 0; i < results.length; i++) {
					console.log(results[i]);
		      var placeLocation = results[i].geometry.location;
					var placeName = results[i].name;
		      createMarker(placeLocation, placeName);
		    }
		  } else {
				alert("some problem in places" + status);
			}
		}
		*/

	}



})(jQuery);
