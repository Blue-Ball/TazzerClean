(function($) {
  "use strict";

  var base_url = $('#base_url').val();
  var BASE_URL = $('#base_url').val();
  var csrf_token = $('#csrf_token').val();
  var csrfName = $('#csrfName').val();
  var csrfHash = $('#csrfHash').val();
  var service_id = '';
  var provider_id = '';
  var final_gig_amount1 = '';
  var booking_date = '';
  var booking_time = '';
  var service_location = '';
  var service_latitude = '';
  var service_longitude = '';
  var notes = '';

  function booking_proccess(e) {

    service_id = $(e).attr('data-id');
    provider_id = $(e).attr('data-provider');
    final_gig_amount1 = $(e).attr('data-amount');
    booking_date = $("#booking_date").val();
    booking_time = $("#from_time").val();
    service_location = $("#service_location").val();
    service_latitude = $("#service_latitude").val();
    service_longitude = $("#service_longitude").val();
    notes = $("#notes").val();

    if (parseInt(final_gig_amount1) == 0) {
      alert('Service amount cannot be empty');
    } else {
      var booking_date1 = $("#booking_date").val();
      var booking_time1 = $("#from_time").val();
      var service_location1 = $("#service_location").val();

      if (booking_date1 == '') {
        $('.error_date').show();
        return false;
      } else if (booking_time1 == '' || booking_time == null) {
        $('.error_time').show();
        return false;
      } else if (service_location1 == '') {
        $('.error_date').hide();
        $('.error_loc').show();
        return false;
      }
      $('#load_div').html('<img src="' + base_url + 'assets/img/loader.gif" alt="" />');
      $('#load_div').show();

      $('.submit_service_book').attr('disabled', true);

      /*book*/
      $.ajax({

        url: base_url + 'user/booking/book_service/',
        data: {
          service_id: service_id,
          final_gig_amount: final_gig_amount1,
          provider_id: provider_id,
          tokenid: 'old flow',
          booking_time: booking_time,
          service_location: service_location,
          service_latitude: service_latitude,
          service_longitude: service_longitude,
          notes: notes,
          booking_date: booking_date,
          csrf_token_name: csrf_token
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $('.page-loading').fadeIn();
        },
        success: function(response) {
          $('.page-loading').fadeOut();
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
        },
        error: function(error) {
          $('.page-loading').fadeOut();
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
          console.log(error);
        }
      });
    }
  }

  $('#book_services').bootstrapValidator({
    fields: {
      name: {
        validators: {
          notEmpty: {
            message: 'Please Enter Your Name'
          }
        }
      },
      email: {
        validators: {
          notEmpty: {
            message: 'Please Enter Email'
          }
        }
      },
      date: {
        validators: {
          notEmpty: {
            message: 'Please Enter Date'
          }
        }
      },
      time: {
        validators: {
          notEmpty: {
            message: 'Please select Time Slot...'
          }
        }
      },

      user_address: {
        validators: {
          notEmpty: {
            message: 'Please Enter location...'
          }
        }
      }
    }
  }).on('success.form.bv', function(e) {
    return true;
    // @Leo delete -------------------------
    // e.preventDefault();
    // service_id = $('.submit_service_book').attr('data-id');
    // provider_id = $('.submit_service_book').attr('data-provider');
    // final_gig_amount1 = $('.submit_service_book').attr('data-amount');
    // booking_date = $("#booking_date").val();
    // booking_time = $("#from_time").val();
    // service_location = $("#service_location").val();
    // service_latitude = $("#service_latitude").val();
    // service_longitude = $("#service_longitude").val();
    // notes = $("#notes").val();

    // $.ajax({
    //   url: base_url + 'user/booking/book_service/',
    //   data: {
    //     service_id: service_id,
    //     final_gig_amount: final_gig_amount1,
    //     provider_id: provider_id,
    //     tokenid: 'old flow',
    //     booking_time: booking_time,
    //     service_location: service_location,
    //     service_latitude: service_latitude,
    //     service_longitude: service_longitude,
    //     notes: notes,
    //     booking_date: booking_date,
    //     csrf_token_name: csrf_token
    //   },
    //   type: 'POST',
    //   dataType: 'JSON',
    //   beforeSend: function() {
    //     button_loading();
    //   },
    //   success: function(response) {
    //     button_unloading();
    //     swal({
    //       title: "Booking Confirmation...",
    //       text: "Your booking was booked Successfully ...!",
    //       icon: "success",
    //       button: "okay",
    //       closeOnEsc: false,
    //       closeOnClickOutside: false
    //     }).then(function() {
    //       window.location.href = base_url + 'user-bookings';

    //     });
    //   },
    //   error: function(error) {
    //     button_unloading();
    //     swal({
    //       title: "Booking Confirmation...",
    //       text: "Somethings went to wrong so try later ...!",
    //       icon: "error",
    //       button: "okay",
    //       closeOnEsc: false,
    //       closeOnClickOutside: false
    //     }).then(function() {
    //       window.location.reload();
    //     });
    //   }
    // });
  });

  function button_loading() {
    var $this = $('.btn');
    var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
    if ($this.html() !== loadingText) {
      $this.data('original-text', $this.html());
      $this.html(loadingText).prop('disabled', 'true').bind('click', false);
    }
  }

  function button_unloading() {
    var $this = $('.btn');
    $this.html($this.data('original-text')).prop('disabled', 'false');
  }
})(jQuery);

/* @Leo add ---------- */
$(document).ready(function() {
  init();
});

function init() {
  $('#booking_date').datepicker({
    dateFormat: 'dd-mm-yy',
    minDate: new Date(),
    icons: {
      up: "fa fa-angle-up",
      down: "fa fa-angle-down",
      next: 'fa fa-angle-right',
      previous: 'fa fa-angle-left'
    },
    onSelect: function(dateText) {
      var date = dateText;
      var dataString = "date=" + date;
      var provider_id = $("#provider_id").val();
      var service_id = $("#service_id").val();
      $('#from_time').empty();
      $('#book_services').bootstrapValidator('revalidateField', 'date');

      if (date != "" && date != undefined) {
        var csrf_token = $('#csrf_token').val();
        $.ajax({
          url: base_url + "user/service/service_availability/",
          data: {
            date: date,
            provider_id: provider_id,
            service_id: service_id,
            csrf_token_name: csrf_token
          },
          type: "POST",

          success: function(response) {
            // console.log(response)
            $('#from_time').find("option:eq(0)").html("Select time slot");
            if (response != '') {
              var obj = jQuery.parseJSON(response);

              if (obj != '') {
                $(obj).each(function() {
                  var option = $('<option />');
                  option.attr('value', this.start_time + '-' + this.end_time).text(this.start_time + '-' + this.end_time);
                  $('#from_time').append(option);
                });
              } else if (obj == '') {
                var msg = 'Availability not found';
                $('#from_time').append(msg);
              }

              $('#to_time').find("option:eq(0)").html("Select end time");
              var obj = jQuery.parseJSON(response);

              $(obj).each(function() {
                var option = $('<option />');
                option.attr('value', this.end_time).text(this.end_time);
                $('#to_time').append(option);
              });
            }
          }

        });
      }
    }
  });
}