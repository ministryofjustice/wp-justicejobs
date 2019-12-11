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

  var userLocationGeocode;
  var userLocationGeocodeLat;
  var userLocationGeocodeLng;
  var $markers = [];
  var locationsRelevant = '';
  var locationsRelevantLive = '';
  var str;
  var thisRadius;

  $('#search-form').on('submit', function(event){

    event.preventDefault();
    var keyword = $(this).find('#keyword').attr('value');
    var roleType = $(this).find('#role-type').attr('data-cur');
    var salaryRange = $(this).find('#salary-range').attr('data-cur');
    var workingPattern = $(this).find('#working-pattern').attr('data-cur');
    var thisLocation = $(this).find('#location').attr('value');
    thisRadius = parseInt($(this).find('#radius').attr('data-cur'));
    console.log(thisRadius);

    var keywordLive;
    if (keyword !== '') {
      keywordLive = keyword;
    }

    var roleTypeLive;
    if (roleType === '') {
      roleTypeLive = '';
    } else {
      if (roleType === 'all') {
        roleTypeLive = '';
      } else {
        roleTypeLive = '&role-type=' + roleType;
      }
    }

    var salaryRangeLive;
    if (salaryRange === '') {
      salaryRangeLive = '';
    } else {
      if (salaryRange === 'all') {
        salaryRangeLive = '';
      } else {
        salaryRangeLive = '&salary-range=' + salaryRange;
      }
    }

    var workingPatternLive;
    if (workingPattern === '') {
      workingPatternLive = '';
    } else {
      if (workingPattern === 'all') {
        workingPatternLive = '';
      } else {
        workingPatternLive = '&working-pattern=' + workingPattern;
      }
    }


    var locationLive;
    var radiusLive;
    if (thisLocation == '') {
      locationLive = '';
      radiusLive = '';
      console.log('woot');
      str = '?s=' + keywordLive + roleTypeLive + salaryRangeLive + workingPatternLive;
      localStorage.setItem("currentSearch", str);
      console.log(str + sessionStorage.getItem("currentSearch"));
      window.location = window.location.origin + str;
    } else {
      locationLive = '&location=' + thisLocation;
      radiusLive = '&radius=' + thisRadius;
      str = '?s=' + keywordLive + roleTypeLive + salaryRangeLive + workingPatternLive + locationLive + radiusLive;
      getJSON(thisLocation);
      testEachMarker();
    }


  });

  $('#mini-search-form').on('submit', function(e){
    e.preventDefault();
    var keyword = $(this).find('#keyword').attr('value');
    // var roleType = $(this).find('#role-type').attr('data-cur');
    var thisLocation = $(this).find('#location').attr('value');
    thisRadius = parseInt($(this).find('#radius').attr('data-cur'));

    console.log(keyword);
    if (keyword !== '') {
      var keywordLive = keyword;
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
    if (!thisLocation) {
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
      testEachMarker();
    }

  });

      function getJSON(userLocation){
        $.getJSON( "https://maps.googleapis.com/maps/api/geocode/json?address=" + userLocation + "&key=" + justice.map_key , function( data ) {
          userLocationGeocodeLat = parseFloat(data.results[0].geometry.location.lat);
          userLocationGeocodeLng = parseFloat(data.results[0].geometry.location.lng);
          var userMathstest = userLocationGeocodeLat - userLocationGeocodeLng;
          console.log(userLocationGeocodeLat + ' ' + userLocationGeocodeLng + ' = ' + userMathstest);
        });
      }

      function testEachMarker(){
        setTimeout(function(){

          $markers 	= $('#allLocations').find('li');
            console.log($markers);

          $($markers).each(function(k){
              var thisLAT = parseFloat($(this).data('lat'));
              var thisLNG = parseFloat($(this).data('lng'));
              var thisMathstest = thisLAT - thisLNG;
              console.log(thisLAT + ' test ' + thisLNG + ' = ' + thisMathstest);
              var p1 = new google.maps.LatLng(thisLAT, thisLNG);
              var p2 = new google.maps.LatLng(userLocationGeocodeLat, userLocationGeocodeLng);
              //console.log(p1 + p2);
              var currentDistance = calcDistance(p1, p2);
              console.log(parseInt(currentDistance));
              console.log(parseInt(thisRadius));
              var thisRadiusInMiles = parseInt(thisRadius) * 1.609344;
              console.log(thisRadiusInMiles);
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
