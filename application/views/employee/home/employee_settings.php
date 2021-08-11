<div class="content">
  <div class="container">
    <div class="row">
      <?php
      if (!empty($_GET['tbs'])) {
        $val = $_GET['tbs'];
      } else {
        $val = 1;
      }
      ?>
      <input type="hidden" name="tab_ctrl" id="tab_ctrl" value="<?= $val; ?>">
      <?php $this->load->view('employee/home/employee_sidemenu'); ?>

      <div class="col-xl-9 col-md-8">
        <div class="tab-content pt-0">
          <div class="tab-pane show active" id="user_profile_settings">
            <div class="widget">
              <h4 class="widget-title"><?php echo (!empty($user_language[$user_selected]['lg_Profile_Settings'])) ? $user_language[$user_selected]['lg_Profile_Settings'] : $default_language['en']['lg_Profile_Settings']; ?></h4>
              <form id="update_user" action="<?php echo base_url() ?>employee/dashboard/update_user" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                <div class="row">
                  <div class="col-xl-12">
                    <h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Basic_Information'])) ? $user_language[$user_selected]['lg_Basic_Information'] : $default_language['en']['lg_Basic_Information']; ?></h5>
                  </div>
                  <div class="form-group col-xl-12">
                    <div class="media align-items-center mb-3">
                      <?php if ($details['profile_img'] != '') { ?>
                        <img class="user-image" src="<?php echo base_url() . $details['profile_img'] ?>" alt="">
                      <?php } elseif ($details['profile_img'] == '') { ?>
                        <img class="user-image" src="<?php echo base_url(); ?>assets/img/user.jpg" alt="">
                      <?php } ?>
                      <div class="media-body">
                        <h5 class="mb-0"><?php echo $details['name'] ?></h5>
                        <p>Max file size is 20mb</p>
                        <div class="jstinput"><a id="openfile_profileimg" href="javascript:void(0);" class="browsephoto openfile"><?php echo (!empty($user_language[$user_selected]['lg_Browse'])) ? $user_language[$user_selected]['lg_Browse'] : $default_language['en']['lg_Browse']; ?></a></div>
                        <input type="hidden" id="crop_prof_img" name="profile_img">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-xl-6">
                    <label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Name'])) ? $user_language[$user_selected]['lg_Name'] : $default_language['en']['lg_Name']; ?></label>
                    <input class="form-control" type="text" value="<?php echo $details['name'] ?>" readonly>
                  </div>
                  <div class="form-group col-xl-6">
                    <label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Email'])) ? $user_language[$user_selected]['lg_Email'] : $default_language['en']['lg_Email']; ?></label>
                    <input class="form-control" type="email" value="<?php echo $details['email'] ?>" readonly>
                  </div>
                  <div class="form-group col-xl-6">
                    <label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Country_Code'])) ? $user_language[$user_selected]['lg_Country_Code'] : $default_language['en']['lg_Country_Code']; ?></label>
                    <input class="form-control" type="text" value="<?php echo $details['country_code'] ?>" readonly>
                  </div>
                  <div class="form-group col-xl-6">
                    <label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Mobile_Number'])) ? $user_language[$user_selected]['lg_Mobile_Number'] : $default_language['en']['lg_Mobile_Number']; ?></label>
                    <input class="form-control no_only" type="text" value="<?php echo $details['mobileno'] ?>" name="mobileno" readonly required>
                  </div>
                  <div class="form-group col-xl-6">
                    <label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Date_birth'])) ? $user_language[$user_selected]['lg_Date_birth'] : $default_language['en']['lg_Date_birth']; ?></label>
                    <input type="text" class="form-control birthdaypicker" autocomplete="off" name="dob" value="<?php echo (!empty($details['dob'])) ? date('d-m-Y', strtotime($details['dob'])) : ''; ?>">
                  </div>

                  <div class="form-group col-xl-6">
                    <label class="mr-sm-2">User Skill</label>
                    <select class="form-control" id="skill_id" name="skill_id">
                      <option value='0'>Select Skill</option>
                      <?php foreach ($skill_list as $row) { ?>
                        <option value='<?php echo $row['id']; ?>' 
                          <?php if (!empty($selected_skill)) {
                              echo ($row['id'] == $selected_skill) ? 'selected' : '';
                          } ?>><?php echo $row['name']; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>                  

                  <div class="col-xl-12">
                    <h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Address'])) ? $user_language[$user_selected]['lg_Address'] : $default_language['en']['lg_Address']; ?></h5>
                  </div>
                  <div class="form-group col-xl-12">
                    <label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Address'])) ? $user_language[$user_selected]['lg_Address'] : $default_language['en']['lg_Address']; ?></label>
                    <input type="text" class="form-control" name="address" value="<?php if (!empty($user_address['address'])) {
                                                                                    echo $user_address['address'];
                                                                                  } ?>">
                  </div>
                  <div class="form-group col-xl-6">
                    <label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Country'])) ? $user_language[$user_selected]['lg_Country'] : $default_language['en']['lg_Country']; ?></label>
                    <select class="form-control" id="country_id" name="country_id">
                      <option value=''><?php echo (!empty($user_language[$user_selected]['lg_Select_Country'])) ? $user_language[$user_selected]['lg_Select_Country'] : $default_language['en']['lg_Select_Country']; ?></option>
                      <?php foreach ($country as $row) { ?>
                        <option value='<?php echo $row['id']; ?>' <?php if (!empty($user_address['country_id'])) {
                                                                    echo ($row['id'] == $user_address['country_id']) ? 'selected' : '';
                                                                  } ?>><?php echo $row['country_name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group col-xl-6">
                    <label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_State'])) ? $user_language[$user_selected]['lg_State'] : $default_language['en']['lg_State']; ?></label>
                    <select class="form-control" name="state_id" id="state_id">
                    </select>
                  </div>
                  <div class="form-group col-xl-6">
                    <label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_City'])) ? $user_language[$user_selected]['lg_City'] : $default_language['en']['lg_City']; ?></label>
                    <select class="form-control" name="city_id" id="city_id">
                    </select>
                  </div>
                  <div class="form-group col-xl-6">
                    <label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Postal_Code'])) ? $user_language[$user_selected]['lg_Postal_Code'] : $default_language['en']['lg_Postal_Code']; ?></label>
                    <input type="text" class="form-control" name="pincode" value="<?php if (!empty($user_address['pincode'])) {
                                                                                    echo $user_address['pincode'];
                                                                                  } ?>">
                  </div>

                </div>

                <div class="row">
                  <div class="col-xl-12">
                    <h5 class="form-title">Employee Infomation</h5>
                  </div>
                  <div class="form-group col-xl-12">
                    <div class="media align-items-center mb-3">
                      <?php if ($details['recognition_img'] != '') { ?>
                        <img class="user-image" src="<?php echo base_url() . $details['recognition_img'] ?>" alt="">
                      <?php } elseif ($details['recognition_img'] == '') { ?>
                        <img class="user-image" src="<?php echo base_url(); ?>assets/img/user.jpg" alt="">
                      <?php } ?>
                      <div class="media-body">
                        <h5 class="mb-0">Recognition photo.</h5>
                        <p> This photo must be very clear with all your face clearly showing it must be from your chest level upwards.<br>Max file size is 20mb</p>
                        <div class="jstinput"><a id="openfile_employeeimg" href="javascript:void(0);" class="browsephoto openfile"><?php echo (!empty($user_language[$user_selected]['lg_Browse'])) ? $user_language[$user_selected]['lg_Browse'] : $default_language['en']['lg_Browse']; ?></a></div>
                        <!-- <input type="hidden" id="crop_prof_img" name="profile_img"> -->
                      </div>
                    </div>
                  </div>

                  <div class="col-xl-4">Wroking Time:
                    <!-- <input type="text" name="name" class="form-control border-dark" Placeholder="Enter First Name" required> -->
                  </div>
                  <div class="col-xl-3 col-6">
                    <div class="custom-control custom-checkbox checkbox-xl">
                      <input type="radio" name="work_time" class="custom-control-input" id="full_time" value="Full-time" <?php if ($details['work_time'] == "Full-time") {
                                                                                                                            echo 'checked="checked"';
                                                                                                                          } ?>>
                      <label class="custom-control-label" for="full_time">Full-time</label>
                    </div>
                  </div>
                  <div class="col-xl-5 col-6">
                    <div class="custom-control custom-checkbox checkbox-xl">
                      <input type="radio" name="work_time" class="custom-control-input" id="part_time" value="Part-time" <?php if ($details['work_time'] == "Part-time") {
                                                                                                                            echo 'checked="checked"';
                                                                                                                          } ?>>
                      <label class="custom-control-label" for="part_time">Part-Time</label>
                    </div>
                  </div>
                  <div class="part_time_setting col-xl-12 row" <?php if($details['work_time']!="Part-time"){echo "style='display:none'";} ?> >
                    <div class="col-xl-12 row mt-5">Working days:
                      <div class="col-xl-12 mt-3">
                        <div class="custom-control custom-checkbox checkbox-xl">
                          <input type="checkbox" name="work_day[]" class="custom-control-input" id="monday" value="monday" <?php if (in_array('monday', $details['work_day'])) {
                                                                                                                              echo 'checked="checked"';
                                                                                                                            } ?>>
                          <label class="custom-control-label" for="monday">Monday</label>
                        </div>
                        <div class="custom-control custom-checkbox checkbox-xl">
                          <input type="checkbox" name="work_day[]" class="custom-control-input" id="tuesday" value="tuesday" <?php if (in_array('tuesday', $details['work_day'])) {
                                                                                                                                echo 'checked="checked"';
                                                                                                                              } ?>>
                          <label class="custom-control-label" for="tuesday">Tuesday</label>
                        </div>
                        <div class="custom-control custom-checkbox checkbox-xl">
                          <input type="checkbox" name="work_day[]" class="custom-control-input" id="wednesday" value="wednesday" <?php if (in_array('wednesday', $details['work_day'])) {
                                                                                                                                    echo 'checked="checked"';
                                                                                                                                  } ?>>
                          <label class="custom-control-label" for="wednesday">Wednesday</label>
                        </div>
                        <div class="custom-control custom-checkbox checkbox-xl">
                          <input type="checkbox" name="work_day[]" class="custom-control-input" id="thursday" value="thursday" <?php if (in_array('thursday', $details['work_day'])) {
                                                                                                                                  echo 'checked="checked"';
                                                                                                                                } ?>>
                          <label class="custom-control-label" for="thursday">Thursday</label>
                        </div>
                        <div class="custom-control custom-checkbox checkbox-xl">
                          <input type="checkbox" name="work_day[]" class="custom-control-input" id="friday" value="friday" <?php if (in_array('friday', $details['work_day'])) {
                                                                                                                              echo 'checked="checked"';
                                                                                                                            } ?>>
                          <label class="custom-control-label" for="friday">Friday</label>
                        </div>
                        <div class="custom-control custom-checkbox checkbox-xl">
                          <input type="checkbox" name="work_day[]" class="custom-control-input" id="saturday" value="saturday" <?php if (in_array('saturday', $details['work_day'])) {
                                                                                                                                  echo 'checked="checked"';
                                                                                                                                } ?>>
                          <label class="custom-control-label" for="saturday">Saturday</label>
                        </div>
                        <div class="custom-control custom-checkbox checkbox-xl">
                          <input type="checkbox" name="work_day[]" class="custom-control-input" id="sunday" value="sunday" <?php if (in_array('sunday', $details['work_day'])) {
                                                                                                                              echo 'checked="checked"';
                                                                                                                            } ?>>
                          <label class="custom-control-label" for="sunday">Sunday</label>
                        </div>
                      </div>
                    </div>

                    <div class="mt-5 col-md-12">Working ours:</div>
                    <div class="col-6 mt-3">
                      <label for="working_start">From:</label>
                      <input type="time" id="work_start" name="work_start" value="<?php echo $details['work_start']; ?>" class="form-control">
                    </div>
                    <div class="col-6 mt-3">
                      <label for="working_end">To:</label>
                      <input type="time" id="work_end  " name="work_end" value="<?php echo $details['work_end']; ?>" class="form-control">
                    </div>
                  </div>

                  <div class="form-group col-xl-12 mt-5">
                    <button name="form_submit" id="form_submit" class="btn btn-primary pl-5 pr-5" type="submit">Update</button>
                  </div>
                  <input type="hidden" id="country_id_value" value="<?= $user_address['country_id']; ?>">
                  <input type="hidden" id="state_id_value" value="<?= $user_address['state_id']; ?>">
                  <input type="hidden" id="city_id_value" value="<?= $user_address['city_id']; ?>">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="avatar-modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upload Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php $curprofile_img = (!empty($profile['profile_img'])) ? $profile['profile_img'] : ''; ?>
        <form class="avatar-form" action="<?php echo base_url() ?>user/dashboard/profile_cropping" enctype="multipart/form-data" method="post">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <div class="avatar-body">
            <div class="avatar-upload">
              <input class="avatar-src" name="avatar_src" type="hidden">
              <input class="avatar-data" name="avatar_data" type="hidden">
              <label for="avatarInput">Select Image</label>
              <input type="file" accept="image/*" class="avatar-input ad_pd_file" id="avatarInput" name="profile_img">

            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="avatar-wrapper"></div>
              </div>
            </div>
            <div class="row avatar-btns">
              <div class="col-md-12">
                <input type="hidden" id="table_name" name="table_name" value="users">
                <input type="hidden" id="redirect" name="redirect" value="user-settings">
                <button class="btn btn-primary avatar-save pull-right" type="submit">Save Changes</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $('#openfile_employeeimg').on('click', function() {
      openfile();
      $('.avatar-form').attr('action', "<?php echo base_url() ?>employee/dashboard/profile_cropping")
      $('#table_name').val('employee')
      $('#redirect').val('employee-settings')
    });
    $('#openfile_profileimg').on('click', function() {
      openfile();
      $('.avatar-form').attr('action', "<?php echo base_url() ?>user/dashboard/profile_cropping")
      $('#table_name').val('users')
      $('#redirect').val('user-settings')
    });
    $("[name='work_time']").on('change', function() {
      var val = $(this).val();
      if (val == "Full-time") {
        $('.part_time_setting').hide()
      } else {
        $('.part_time_setting').show()
      }
    });
  });
</script>