$(document).ready(function() {
	init();
	
	// var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
	// Array.prototype.slice.call(forms).forEach((form) => {
	// 	form.addEventListener('submit', (event) => {
	// 	  if (!form.checkValidity()) {
	// 		event.preventDefault();
	// 		event.stopPropagation();
	// 	  }
	// 	  form.classList.add('was-validated');
	// 	}, false);
	// });

	$("select[name='sub_category']").on('change', function() {
		// console.log($(this).val())
		var subCate = "subcate_"+$(this).val();
		$serviceObj = $("select[name='service']");
		$serviceObj.empty();
		$serviceObj.append("<option value=''>Select Service</option>");
		var services = serviceList[subCate];
		if(services) {
			for(var i in services) {
				$serviceObj.append("<option value='"+services[i].id+"'>"+services[i].service_title+"</option>");
			}
		}
	});

	$("form#get_service").on("submit", function(event) {
		var form = $(this).get(0);
		if (!form.checkValidity()) {
			event.preventDefault();
			event.stopPropagation();
		}
		form.classList.add('was-validated');
	});

	$(".get-price").on("click", function() {
		$("form#get_service").submit();
	});

	if ($('.customer-carousel').length > 0) {
      $('.customer-carousel').owlCarousel({
          loop: false,
          autoplay: true,
          center: true,
          // margin: 10,
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
            620:{
              items:2
            },
            930:{
              items:3
            },
            1240:{
              items:4
            },
            1550:{
              items:5
            },
          }
      });
  }

  if ($('.why-choose-carousel').length > 0) {
      $('.why-choose-carousel').owlCarousel({
          loop: false,
          autoplay: true,
          // center: true,
          // margin: 10,
          animateOut: 'fadeOut',
          animateIn: 'fadeIn',
          nav: false,
          dots: true,
          autoplayHoverPause: false,
          items: 1
      });
  }

    $(".faq-item-title").on("click", function () {
    	$(this).find("i.fa").toggleClass("fa-angle-right");
    	$(this).find("i.fa").toggleClass("fa-angle-down");
    	// $(this).next(".faq-item-content").fadeToggle();
    	$(this).next(".faq-item-content").slideToggle();
    });

});

function init() {
	var subCate = "subcate_"+$("select[name='sub_category']").val();
	$serviceObj = $("select[name='service']");
	$serviceObj.empty();
	$serviceObj.append("<option value=''>Select Service</option>");
	var services = serviceList[subCate];
	if(services) {
		for(var i in services) {
			$serviceObj.append("<option value='"+services[i].id+"'>"+services[i].service_title+"</option>");
		}
	}
}