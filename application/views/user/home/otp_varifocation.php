<style type="text/css">
	
.card {
    width: 350px;
    padding: 10px;
    border-radius: 20px;
    background: #eadfdf;
    border: none;
    height:  462px;
    position: relative
}


.form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #ff8880;
    outline: 0;
    box-shadow: none
}

.cursor {
    cursor: pointer
}
</style>
<?php 
    // print_r($send_success); exit;
?>
<div class="d-flex justify-content-center align-items-center container">
    <div class="card py-5 px-3">
    	<form method="POST" action="<?php echo base_url()?>otp-verification-check" enctype='multipart/form-data'>

        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        <?php if($_GET['msg']=='not'){ ?>
            <div class="alert alert-danger">
              <strong>Entered Otp is Incorrect !</strong>
            </div>
        <?php } ?>
        <h5 class="m-0">Email verification</h5>
       <!--  <span class="mobile-text">Enter the code we just send on your mobile phone <?php echo $service_phone; ?> </span></br><span>E-mail :<?php echo $service_email; ?></span> -->
        <input name="phone" type="hidden" value='<?php echo $service_phone; ?>' disabled>  
        <input name="email" type="hidden" value='<?php echo $service_email; ?>' disabled><b class="text-danger">
        <input name="service_data" type="hidden" value='<?php echo $service_data; ?>' >
        <input name="last_id" type="hidden" value='<?php echo $service_email; ?>' >
        <?php 
        
//echo $sess_mobil=$this->session->userdata('mobile_otp',$mobile_otp);
   // echo    '==='.$sess_email=$this->session->userdata('email_otp',$email_otp);
        ?>
        <input type="hidden" value='<?php echo $this->session->userdata('mobile_otp'); ?>' >  
            </b>
        <br class="mt-2">
        <p>We have sent you code. Please check your email.</p>
        
        <label class="mt-4">Enter Email Otp</label></br>
        <div class="d-flex ">
        	
        	<input name="mobile_otp" type="text" class="form-control" 
            placeholder="Enter Verfication Code" autofocus="">
        	            
        </div>
       <!--  <label class="mt-3">Enter E-mail Otp</label></br>
         <div class="d-flex flex-row ">
            
            <input name="email_otp" type="text" class="form-control" 
            placeholder="Enter E-mail Otp"
            autofocus="">
        </div> -->
         <div class="col-md-12 mt-3">
            <div class="text-center">
           <button class="btn btn-primary" type="Submit">Submit</button>
            </div>
        </div>
    </form>
        <!-- <div class="text-center mt-5"><span class="d-block mobile-text">Don't receive the code?</span><span class="font-weight-bold text-danger cursor">Resend</span></div> -->
    </div>
</div>

<script type="text/javascript">
    var send_success = <?=json_encode($send_success)?>;
    // console.log(send_success)
    $(document).ready(function() {
        if (send_success) {
            toaster_msg("info","We have sent you verification code. please check your email.");
        }
    });

    function toaster_msg(status, msg) {

      setTimeout(function() {
        Command: toastr[status](msg);

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
    }
</script>
