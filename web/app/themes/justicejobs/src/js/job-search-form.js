/*
The Job Form Search Functionality
*/
jQuery(document).ready(function($) {

    $('.dropdown__list').children('li').on('click', function(){
        var thisSelection = $(this).attr('data-slug');
        $(this).parent().siblings('.dropdown__wrap').children('input').attr('data-cur', thisSelection);

    });

    $('#reset').click(function() {
        $(':input','#search-form')
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected')
            .attr('data-cur', '');
        $('#radius').attr('data-cur',  10);
    });

    var userLocationGeocodeLat;
    var userLocationGeocodeLng;
    var $markers = [];
    var locationsRelevant = '';
    var locationsRelevantLive = '';
    var str;
    var thisRadius;

    $('#search-form').on('submit', function(event){

        event.preventDefault();
        var keyword = $(this).find('#keyword').val();
        var roleType = $(this).find('#role-type').attr('data-cur');
        var salaryRange = $(this).find('#salary-range').attr('data-cur');
        var workingPattern = $(this).find('#working-pattern').attr('data-cur');
        var thisLocation = $(this).find('#location').val();
        thisRadius = parseInt($(this).find('#radius').attr('data-cur'));

        var keywordLive;
        console.log(keyword);
        if (_hasString(keyword)) {
            keywordLive = keyword;
        }

        var roleTypeLive;
        if (!_hasString(roleType)) {
            roleTypeLive = '';
        } else {
            if (roleType === 'all') {
                roleTypeLive = '';
            } else {
                roleTypeLive = '&role-type=' + roleType;
            }
        }

        var salaryRangeLive;
        if (!_hasString(salaryRange)) {
            salaryRangeLive = '';
        } else {
            if (salaryRange === 'all') {
                salaryRangeLive = '';
            } else {
                salaryRangeLive = '&salary-range=' + salaryRange;
            }
        }

        var workingPatternLive;
        if (!_hasString(workingPattern)) {
            workingPatternLive = '';
        } else {
            if (workingPattern === 'all') {
                workingPatternLive = '';
            } else {
                workingPatternLive = '&working-pattern=' + workingPattern;
            }
        }


        var locationLive = '';
        var radiusLive = '';


        if (!_hasString(thisLocation)) {
            str = '?s=' + keywordLive + roleTypeLive + salaryRangeLive + workingPatternLive;
            localStorage.setItem("currentSearch", str);

            window.location = window.location.origin + str;

        } else {
            console.log('bb');
            locationLive = '&location=' + thisLocation;
            radiusLive = '&radius=' + thisRadius;
            str = '?s=' + keywordLive + roleTypeLive + salaryRangeLive + workingPatternLive + locationLive + radiusLive;
            getJSON(thisLocation);
            testEachMarker();
        }


    });

    $('#mini-search-form').on('submit', function(e){
        e.preventDefault();
        var keyword = $(this).find("#keyword").val();
        // var roleType = $(this).find('#role-type').attr('data-cur');
        var thisLocation = $(this).find('#location').val();
        thisRadius = parseInt($(this).find('#radius').val());

        var keywordLive;
        if (keyword !== '' || typeof keyword !== 'undefined') {
            keywordLive = keyword;
        }

        /*
        var roleTypeLive;
        if (roleType.length == '') {
          roleTypeLive = '';
        } else {
          if (roleType == 'all') {
            roleTypeLive = '';
          } else {
            roleTypeLive = '&role-type=' + roleType;
          }
        }
        */

        var locationLive;
        var radiusLive;
        if (thisLocation !== '' || typeof thisLocation !== 'undefined') {
            locationLive = '';
            radiusLive = '';
            str = '?s=' + keywordLive;
            console.log(str + sessionStorage.getItem("currentSearch"));
            window.location = window.location.origin + str;
        } else {
            locationLive = '&location=' + thisLocation;
            radiusLive = '&radius=' + thisRadius;
            str = '?s='+ keywordLive + locationLive + radiusLive;
            getJSON(thisLocation);
        }

    });

    function _hasString(string) {
        return (string !== '' || typeof string !== 'undefined')
    }

    function getJSON(userLocation){

        $.ajax({
            type : "post",
            dataType : "json",
            url : justice.ajaxurl,
            data : {action: "get_location_coordinates", location : userLocation },
            success: function(response) {
                if(response.error == false) {
                    userLocationGeocodeLat = parseFloat(response.lat);
                    userLocationGeocodeLng = parseFloat(response.lng);
                    var userMathstest = userLocationGeocodeLat - userLocationGeocodeLng;
                    testEachMarker();
                }
                else {
                    console.log('Error getting location');
                }
            }
        })
    }

    function testEachMarker(){
        setTimeout(function(){

            $markers 	= $('#allLocations').find('li');
            console.log($markers);

            $($markers).each(function(k){
                var thisLAT = parseFloat($(this).data('lat'));
                var thisLNG = parseFloat($(this).data('lng'));
                var thisMathstest = thisLAT - thisLNG;

                var p1 = new google.maps.LatLng(thisLAT, thisLNG);
                var p2 = new google.maps.LatLng(userLocationGeocodeLat, userLocationGeocodeLng);

                var currentDistance = calcDistance(p1, p2);

                var thisRadiusInMiles = parseInt(thisRadius) * 1.609344;

                if (parseInt(currentDistance) < thisRadiusInMiles ) {
                    console.log($(this).data('id'));
                    locationsRelevant += $(this).data('id') + ', ';
                }
                //calculates distance between two points in km's
                function calcDistance(p1, p2) {
                    return (google.maps.geometry.spherical.computeDistanceBetween(p1, p2) / 1000).toFixed(2);
                }
            });

            locationsRelevantLive = '&locations-relevant=' + locationsRelevant;

            str = str + locationsRelevantLive;

            window.location = window.location.origin + str;

        }, 1000);


    }






});
