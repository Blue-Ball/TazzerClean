<div class="col-xl-3 col-md-4">
    <a href="<?php echo base_url()?>go_to_vendor" class="btn btn-success mb-2 text-light btn-outline-danger p-2">
        <i class="fa fa-hashtag"></i>
        <span>Go To Vendor Dashboard</span>
    </a>

    <div class="panel-style">
        <?php $user=$this->db->where('id',$this->session->userdata('id'))->get('users')->row();
			if(!empty($user->profile_img)){
				$profile_img=$user->profile_img;
			}else{
				$profile_img="assets/img/user.jpg";
			}
		?>
        <div class="mb-4">
            <div class="d-sm-flex flex-row flex-wrap text-center text-sm-left align-items-center">
                <img alt="profile image" src="<?php echo base_url().$profile_img; ?>" class="avatar-lg rounded-circle">
                <div class="ml-sm-3 ml-md-0 ml-lg-3 mt-2 mt-sm-0 mt-md-2 mt-lg-0 info-blk-style">
                    <h6 class="mb-0"><?php echo $this->session->userdata('name'); ?></h6>
                    <p class="text-muted mb-0">Member Since <?php echo date('M Y',strtotime($user->created_at));?></p>
                </div>
            </div>
        </div>
        <div class="widget settings-menu">
            <ul role="tablist" class="nav nav-tabs">
                <li class="nav-item current">
                    <a href="<?php echo base_url()?>user-dashboard" class="nav-link <?= ($this->uri->segment(1)=="user-dashboard")?'active':'';?>">
                        <i class="fa fa-line-chart"></i>
                        <span><?php echo (!empty($user_language[$user_selected]['lg_Dashboard'])) ? $user_language[$user_selected]['lg_Dashboard'] : $default_language['en']['lg_Dashboard']; ?></span>
                    </a>
                </li>
                <li class="nav-item current">
                    <a href="<?php echo base_url()?>user-bookings" class="nav-link <?= ($this->uri->segment(1)=="user-bookings")?'active':'';?>">
                        <i class="fa fa-calendar-check-o"></i>
                        <span><?php echo (!empty($user_language[$user_selected]['lg_Booking_List'])) ? $user_language[$user_selected]['lg_Booking_List'] : $default_language['en']['lg_Booking_List']; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>user-settings" class="nav-link <?= ($this->uri->segment(1)=="user-settings")?'active':'';?>">
                        <i class="fa fa-user"></i>
                        <span><?php echo (!empty($user_language[$user_selected]['lg_Profile_Settings'])) ? $user_language[$user_selected]['lg_Profile_Settings'] : $default_language['en']['lg_Profile_Settings']; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>user-wallet" class="nav-link <?= ($this->uri->segment(1)=="user-wallet")?'active':'';?>">
                        <i class="fa fa-money"></i>
                        <span><?php echo (!empty($user_language[$user_selected]['lg_wallet'])) ? $user_language[$user_selected]['lg_wallet'] : $default_language['en']['lg_wallet']; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>verify-payment-method" class="nav-link <?= ($this->uri->segment(1)=="verify-payment-method")?'active':'';?>">
                        <i class="fa fa-calendar"></i>
                        <span>Payment Verify</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>user-reviews" class="nav-link <?= ($this->uri->segment(1)=="user-reviews")?'active':'';?>">
                        <i class="fa fa-star"></i>
                        <span><?php echo (!empty($user_language[$user_selected]['lg_Reviews'])) ? $user_language[$user_selected]['lg_Reviews'] : $default_language['en']['lg_Reviews']; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>user-payment" class="nav-link <?= ($this->uri->segment(1)=="user-payment")?'active':'';?>">
                        <i class="fa fa-hashtag"></i>
                        <span><?php echo (!empty($user_language[$user_selected]['lg_Payment'])) ? $user_language[$user_selected]['lg_Payment'] : $default_language['en']['lg_Payment']; ?></span>
                    </a>
                </li>

                <?php 
					$login_type='';
					if (isset($settings['login_type'])) {
						$login_type = $settings['login_type'];
					}
					if($login_type=='email'){
					?>

                <li class="nav-item">
                    <a href="<?php echo base_url()?>change-password" class="nav-link <?= ($this->uri->segment(1)=="change-password")?'active':'';?>">
                        <i class="fa fa-key"></i>
                        <span>Change Password</span>
                    </a>
                </li>

                <?php } ?>
            </ul>
        </div>
    </div>
</div>