<?php // echo "for employee";
    //$query = $this->db->query("select * from system_settings WHERE status = 1");
  //   $result = $query->result_array();
  //   $this->website_name = '';
  //   $website_logo_front ='assets/img/logo.png';
    
  //   if(!empty($result)) {
		// foreach($result as $data){
		// 	if($data['key'] == 'website_name'){
		// 		$this->website_name = $data['value'];
		// 	}
		// 	if($data['key'] == 'logo_front'){
		// 		$website_logo_front =  $data['value'];
		// 	}
		// }
  //   }
?> 

<div class="login-page">
	<div class="login-body container">

		<div class="loginbox">
			<?php
//if ($_GET['msg']=='wrong') {
	if ($this->input->get('msg')=='wrong') {
?>
  <div class="alert alert-danger">
    <strong>Warning!</strong> Wrong Password.
  </div>
<?php } ?>
<?php
if ($this->input->get('msg')=='login') {
?>
  <div class="alert alert-warning">
    <strong>Warning!</strong> Login First.
  </div>
  <?php } ?>
			<div class="login-right-wrap">
				<div class="account-header">
					<div class="account-logo text-center mb-4">
						<a href="<?php echo $base_url."employee"; ?>">
							<img src="<?php echo $base_url; ?>assets/img/logo-icon.png" alt="" class="img-fluid">
						</a>
					</div>
				</div>
				

				<?php if($this->session->flashdata('error_message')) {  ?>
				<div class="alert alert-danger text-center " id="flash_error_message"><?php echo $this->session->flashdata('error_message');?></div>
				<?php $this->session->unset_userdata('error_message');
				} ?>
				<?php if($this->session->flashdata('success_message')) {  ?>
				<div class="alert alert-success text-center" id="flash_succ_message"><?php echo $this->session->flashdata('success_message');?></div>
				<?php $this->session->unset_userdata('success_message');
				} ?>
				<form action="<?php echo $base_url; ?>employee-login" method="POST">
                   <input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>">
					<div class="form-group">
						<label class="control-label">Email</label>
						<input class="form-control" type="email" name="email" placeholder="Enter your email">
					</div>
					<div class="form-group mb-4">
						<label class="control-label">Password</label>
						<input class="form-control" type="password" name="password" id="password" placeholder="Enter your password">
					</div>
					
					<div class="form-group mb-4">
						<a href="<?php echo $base_url; ?>forgot_password" class="btn btn-info btn-sm" >Forgot Password?</a>
					</div>
					<div class="text-center">
						<input class="btn btn-primary btn-block account-btn" type="submit" name="" value="Login">
						<!-- <button type="submit">Login</button> -->
					</div>
				</form>
			</div>
		</div>
	</div>
</div>