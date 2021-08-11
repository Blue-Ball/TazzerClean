<div class="content">
	 <div class="container">
		 <div class="row">
       <?php $this->load->view($theme.'/home/'.$theme.'_sidemenu'); ?>
       <div class="col-3" style="padding-top:4.5rem; float:right;">
          <div class="row">
            <label class='form-control'>Select Oppontnt</label>
          </div>
          <div class="row" style="padding-top:0.1rem" >
            <select id='chat-opponent' class='form-control' size=10 style="width:100%;">
              <?php 
                print($this->session->userdata('id').'<br />');
                print_r($users);
              ?>
              <?php foreach($users as $user) { ?>
                <?php if(($user['user_type']==1 || empty($user['user_type'])) && $user['id']==$this->session->userdata('id')) continue;  ?>
                <option user_type='<?php echo $user['user_type'] ?>' value="<?php echo $user['id'] ?>"><?php echo $user['name'] ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="row" >
            <div class="col-6" >
              <label for='videoType' class='form-control' style="padding:0px;">
                <input type='radio' id='videoType' style='margin-top:2rem;' value="1" name='callType' checked />
              Video Call</label>
              </div><div class="col-6" >
              <label for='audioType' class='form-control' style='padding:0px;' >
                <input type='radio' id='audioType' class='' value="2" name='callType' style='margin-top:2rem;' />
                Voice Call</label>
              </div>
          </div>
       </div>

			 <div class="col-5" style="padding-top:4.0rem">
          <div class="row">
            <label class='form-control'>Video Chat</label>
          </div>
          <div class="row">
        <?php // print_r($config); ?>
<iframe id='vchat_view' src='<?php echo $config["VIDEOSERVER_PROTOCAL"]."://".$config["VIDEOSERVER_ADDRESS"].":".$config["VIDEOSERVER_PORT"] ."/". (!empty($roomId) ? "?roomId=".$roomId : ""); ?>' 
        class=""
        frameborder="0"
        style="width:480px; height:260px;"
        ></iframe>
          </div>

          <div class='row'>
            <div clas='col-3'></div>
            <div clas='col-3'>
              <button  id='btnCallChat' type="button" class="btn btn-primary submit-btn" data-dismiss="" aria-label="CALL">
                CALL
              </button>
            </div>
            <div clas='col-3'>&nbsp;</div>
            <div clas='col-3'>
              <button  id='btnStopChat' type="button" class="btn btn-secondary disabled" data-dismiss="" aria-label="STOP">
                STOP
              </button>
            </div>
          </div>
       </div>
     </div>
     
   </div>
</div>

<style>
  label.form-control
  {
    border: none;
  }
</style>
<script>
  
  function video_chat_polling(roomId)
  {
    console.log(roomId);
          var code = '';
          var csrf_token = $('#csrf_token').val();
          $.ajax({
              type:'POST',
              url: '<?php echo base_url(); ?>ajax/video_chat_polling',
              data :  {
                      code:code,
                      csrf_token_name: csrf_token,
                      roomId : roomId
                      },
                  //dataType:'json',
                  success:function(response)
              {  
                response = JSON.parse(response);
                if(response.result!='updated')
                {
                  swal({
                    title: "Video chating is closed!",
                    text: "Hello! Your opponent closed the Video channel.",
                    icon: "warning",
                    button: "okay",
                    closeOnEsc: true,
                    closeOnClickOutside: true
                  }).then(function() {
                    if(intervalObject!=null) clearInterval(intervalObject);
                    window.location.href = base_url ; 
                  });
                }
              },
              error:function(obj, code, descripton)
              {  
                
                console.log("failed => " + code + " : " + descripton);
                //console.log("response : " + status);
              }
            }); 
  }
  var intervalObject = null;
  $(document).ready( function() {
    var roomId = '';
    <?php
      if( !empty($this->input->get('roomId') ) )
      {
        print('roomId = "'.$this->input->get('roomId').'";');
        ?>
        $('#btnStopChat').removeClass('disabled');$('#btnStopChat').removeAttr('disabled');
        $('#btnCallChat').addClass('disabled');$('#btnCallChat').attr('disabled', 'disabled');
        
        <?php 
        print('intervalObject = setInterval(video_chat_polling, 10000, "'. $this->input->get('roomId') .'");');
      }
    ?>
    
    
    function Call_Chat(userId, userType)
    {
      if(!userType) userType = 1;
      var callType= $('#videoType').prop("checked") ? 1 : 2;
      var code = '';
      var csrf_token = $('#csrf_token').val();
      $.ajax({
	           type:'POST',
	           url: '<?php echo base_url(); ?>ajax/call_opponent_user',
	           data :  {
                      code:code,
                      csrf_token_name: csrf_token,
                      userId : userId,
                      userType : userType,
                      callType : callType
                      },
	           dataType:'json',
	           success:function(response)
	           {  
                //console.log(response) ; return;
	             	if(response.success)
	             	{   
                    if(response.room_id)
                    {
                      $('#btnStopChat').removeClass('disabled');$('#btnStopChat').removeAttr('disabled');
                      $('#btnCallChat').addClass('disabled');$('#btnCallChat').attr('disabled', 'disabled');
                      setTimeout(function() {
                        intervalObject = setInterval(video_chat_polling, 10000, response.room_id);
                      }, 5000);
                      
                    }
                    $('#vchat_view').attr('src', '<?php echo $config["VIDEOSERVER_PROTOCAL"]."://".$config["VIDEOSERVER_ADDRESS"].":".$config["VIDEOSERVER_PORT"] ."/?roomId=" ?>' + response.roomId);
	           		}
	           		else {    
	            		alert('Video calling failed');
	        		}
	    		}
			});
    }
    $('#chat-opponent').dblclick(function() {
      var userId = $(this).val();
      var userType = $(this).attr('user_type');
      var userType = $('#chat-opponent>option:selected').attr('user_type');
      Call_Chat(userId, userType);
    });

    $('#btnStopChat').click(function() {
      if(roomId) 
      {
        var code = '';
        var csrf_token = $('#csrf_token').val();
        $.ajax({
              type:'POST',
              url: '<?php echo base_url(); ?>ajax/stop_video_chat',
              data :  {
                        code:code,
                        csrf_token_name: csrf_token,
                        roomId : roomId
                        },
              dataType:'json',
              success:function(response)
              {  
                  if(response.success)
                  {   
                    $('#btnStopChat').addClass('disabled'); $('#btnStopChat').attr('disabled', 'disabled');
                    $('#btnCallChat').removeClass('disabled');$('#btnCallChat').removeAttr('disabled');
                    swal({
                        title: "Hello",
                        text: "Your video chatting has been closed.",
                        icon: "success",
                        button: "okay",
                        closeOnEsc: true,
                        closeOnClickOutside: true
                      }).then(function() {
                        if(intervalObject!=null) clearInterval(intervalObject);
                        window.location.href = base_url ; 
                      });
                  }
                  else {    
                    alert('Video chat stoping has been failed');
                }
            }
        });        
      }
      
    });

    $('#btnCallChat').click(function() {
      var userId = $('#chat-opponent').val();
      var userType = $('#chat-opponent>option:selected').attr('user_type');
      if(userId==null) 
      {
        alert('Please, select calling user.');
        return;
      }
      Call_Chat(userId, userType);
    });
  });
  </script>