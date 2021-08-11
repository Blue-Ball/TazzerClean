$(document).ready(function(){
	init();
});

function init() {

	if ($('.home-banner-carousel').length > 0) {
        $('.home-banner-carousel').owlCarousel({
            // rtl: true,
            loop: true,
            center: true,
            margin: 0,
            responsiveClass: true
        })
    }

    if ($('.customer-carousel').length > 0) {
        $('.customer-carousel').owlCarousel({
          loop: true,
          autoplay: true,
          // center: true,
          margin: 50,
          animateOut: 'fadeOut',
          animateIn: 'fadeIn',
          nav: false,
          dots: true,
          autoplayHoverPause: false,
          items: 1,
          navText : ["<span class='ion-ios-arrow-back'></span>","<span class='ion-ios-arrow-forward'></span>"],
          responsiveClass: true,
          responsive:{
            0:{
                items:1,
                margin:30
            },
            1020:{
              items:2
            },
            1600:{
              items:3
            },
            2040:{
              items:4
            },
            2550:{
              items:5
            },
          }
        });
    }

    if ($('.blog-carousel').length > 0) {
        $('.blog-carousel').owlCarousel({
          loop: true,
          autoplay: true,
          // center: true,
          margin: 40,
          animateOut: 'fadeOut',
          animateIn: 'fadeIn',
          nav: false,
          dots: true,
          autoplayHoverPause: false,
          items: 1,
          navText : ["<span class='ion-ios-arrow-back'></span>","<span class='ion-ios-arrow-forward'></span>"],
          responsiveClass: true,
          responsive:{
            0:{
                items:1,
                margin:30
            },
            780:{
              items:2
            },
            1180:{
              items:3
            },
            1540:{
              items:4
            },
            1850:{
              items:5
            },
          }
        });
    }

    var key = $("#map_key").val();
    // console.log(key);
    var formattedAddress = "35high street, Briathwell Rotherham, 566 7AW";
    $.get('https://maps.googleapis.com/maps/api/geocode/json',{address:formattedAddress,key:key},function(data, status){
      // console.log(data,status);
      var location = data.results[0].geometry.location;
      // console.log(location)
      initMap(location);
    });

}

function initMap(location) {
    location = location===undefined?{lat:0,lng:0}:location;
    const map = new google.maps.Map(document.getElementById("our-location"), {
        zoom: 10,
        center: location,
    });
    // The marker
    const marker = new google.maps.Marker({
        position: location,
        map: map,
    });
}