$(document).ready(function() {
    var base_url = $('#base_url').val();
    var BASE_URL = $('#base_url').val();
    var csrf_token = $('#csrf_token').val();

    init();

    $(".service-mode").on('click', function() {
        $(".service-mode.active").removeClass("active");
        $(".service-mode input[checked]").attr("checked", false);
        $(this).addClass("active");
        $(this).find("input[type='checkbox']").attr("checked", true);

    });

    $(".duration-box").on('click', function() {
        $(".duration-box.active").removeClass("active");
        $(this).addClass("active");
    });

    $("#address-form").on("submit", function(event) {
        var form = $(this).get(0);
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    $("#booking-complete").on("click", function() {
        // $("#address-form").submit();
        var addressForm = $("#address-form").get(0);
        if (!addressForm.checkValidity()) {
            // addressForm.classList.add("was-validated");
            $(addressForm).addClass("was-validated");
            return;
        }
        // var bookingDate = bookingData['booking_date'];
        // var bookingTime = bookingData['booking_time'];
        var params = {
            service_id: service.id,
            provider_id: service.user_id,
            service_date: bookingData['booking_date'],
            service_time: bookingData['booking_time'],
            amount: serviceAmount,
            currency_code: userCurrencyCode,
            location: bookingData['booking_user_address'],
            latitude: bookingData['booking_user_latitude'],
            longitude: bookingData['booking_user_longitude'],
            notes: bookingData['booking_description'],
            first_name: bookingData['booking_first_name'],
            last_name: bookingData['booking_last_name'],
            country: $("[name='country_region']").val(),
            town: $("[name='town_city']").val(),
            street_addr_1: $("[name='street_address_1']").val(),
            street_addr_2: $("[name='street_address_2']").val(),
            phone: $("[name='phone']").val(),
            email: $("[name='email']").val(),
            csrf_token_name: csrf_token
        }

        $.ajax({
            url: base_url + 'user/booking/booking_complete',
            data: params,
            type: 'POST',
            dataType: 'JSON',
            beforeSend: function() {
                booking_button_loading();
            },
            success: function(response) {
            	// console.log("success");
            	booking_button_unloading();
            	if (response.success) {
	                swal({
	                  title: "Booking Confirmation...",
	                  text: "Your booking was booked Successfully ...!",
	                  icon: "success",
	                  button: "okay",
	                  closeOnEsc: false,
	                  closeOnClickOutside: false
	                }).then(function() {
	                  window.location.href = base_url + 'user-bookings';
	                });
            	}
                else {
                	switch(response.result) {
                		case "ADD_WALLET":
                			setTimeout(function () {
					            Command: toastr['error'](response.msg);
					            toastr.options = {
					              "closeButton": false,
					              "debug": false,
					              "newestOnTop": false,
					              "progressBar": false,
					              "positionClass": "toast-top-right",
					              "preventDuplicates": false,
					              "onclick": null,
					              "showDuration": "3000",
					              "hideDuration": "5000",
					              "timeOut": "6000",
					              "extendedTimeOut": "1000",
					              "showEasing": "swing",
					              "hideEasing": "linear",
					              "showMethod": "fadeIn",
					              "hideMethod": "fadeOut"
					            }   
					        }, 300);
                		break;
                	}
                }
            },
            error: function(error) {
            	// console.log("error")
                booking_button_unloading();
                swal({
                  title: "Booking Confirmation...",
                  text: "Somethings went to wrong so try later ...!",
                  icon: "error",
                  button: "okay",
                  closeOnEsc: false,
                  closeOnClickOutside: false
                }).then(function() {
                  window.location.reload();
                });
            }
        });
    });
});

function init() {
    var csrf_token = $('#csrf_token').val();
    $(".promo-code-apply").on("click", function() {
        var promoCode = $("input[name='promo_code']").val();
        if (promoCode.trim() == "") {
            toastrAlert("Please input promotion code!");
            $("input[name='promo_code']").focus();
            return;
        }
        var postData = {csrf_token_name:csrf_token,code:promoCode};
        $.post(base_url+"promo-code-check", postData, function(response) {
            var response = JSON.parse(response);
            switch(response.result) {
                case "NONE":
                    toastrAlert("This Promotion Code doesn't exist!");
                    $("input[name='promo_code']").focus();
                    break;
                case "OK":
                    var promotionData = response.data;
                    
                    break;
            }
        });
    });
}

function booking_button_loading() {
    var $this = $('#booking-complete');
    var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
    if ($this.html() !== loadingText) {
        $this.data('original-text', $this.html());
        $this.html(loadingText).prop('disabled', true).bind('click', false);
    }
}

function booking_button_unloading() {
    var $this = $('#booking-complete');
    $this.html($this.data('original-text')).prop('disabled', false);
    // $this.prop("disabled", false);
}

function toastrAlert(msg) {
    setTimeout(function () {
        Command: toastr['error'](msg);
        toastr.options = {
          "closeButton": false,
          "debug": false,
          "newestOnTop": false,
          "progressBar": false,
          "positionClass": "toast-top-left",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "2000",
          "hideDuration": "3000",
          "timeOut": "3000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }   
    }, 100);
}