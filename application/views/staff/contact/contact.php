 <div class="breadcrumb-bar">
	 <div class="container">
		 <div class="row">
			 <div class="col">
				 <div class="breadcrumb-title">
					<h2> CONTACT <?php echo ($toFlag=='staff_to_admin') ? 'ADMIN' : 'CUSTOMER';?> </h2>
				</div>
			</div>
			 <div class="col-auto float-right ml-auto breadcrumb-menu">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href=" <?php echo base_url();?>"> <?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"> CONTACT  <?php echo ($toFlag=='staff_to_admin') ? 'ADMIN' : 'CUSTOMER';?> </li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>

 <div class="content">
	 <div class="container">
		 <div class="row">
			 <div class="col-8">
				 <div class="contact-blk-content">
				<form method="post" enctype="multipart/form-data" id="contact_form" >
          			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value=" <?php echo $this->security->get_csrf_hash(); ?>" />
					
					
					<?php #--> add by maksimU ?>
    				<input type='hidden' id='contactto' name='to' value='<?php echo $toFlag ?>'>
					<?php if(!empty($toFlag) && $toFlag=='staff_to_user' ) { ?>
					<div class="row" style='padding-bottom:1rem'>						
						<div class="col-lg-6">
							<label>Client</label>
							<select class="form-control" type="text" name="userId" id="userId" >
								<option value="0">select</option>
								<?php foreach($users as $user) {?>
									<option value='<?php echo $user['id'] ?>'><?php echo $user['name'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div >
					<?php } ?>
					<?php #<-- add end ?>


					<div class="row">						
						 <div class="col-lg-6">
							 <div class="form-group">
								<label>Name</label>
								<input class="form-control" type="text" name="name" id="name" >
							</div>
						</div>	
						 <div class="col-lg-6">
							 <div class="form-group">
								<label>Email</label>
								<input class="form-control" type="text" name="email" id="email">
							</div>
						</div>					

						 <div class="col-lg-12">
							 <div class="form-group">
								 <div class="text-center">
									 <div id="load_div"></div>
								</div>
								<label>Message</label>
								<textarea class="form-control" name="message" id="message" rows="5"></textarea>
							</div>
						</div>
					</div>
					 <div class="submit-section">
						<button class="btn btn-primary submit-btn submit_service_book"  type="submit" id="submit">Submit</button>
					</div>
				</form>					
				</div>
			</div>
			<div class="col-4">
				<div class="contact-details">
					<div class="contact-info">
						<i class="fa fa-map-marker-alt"></i>
						<div class="contact-data">
							<h4>Address</h4>
							<p>South Yorkishre, S66 7AW</p>
						</div>
					</div>
					<hr>
					<div class="contact-info">
						<i class="fa fa-phone-alt"></i>
						<div class="contact-data">
							<h4>Phone</h4>
							<p>07961242587</p>
							
						</div>
					</div>
					<hr>
					
					<div class="contact-info">
						<i class="fa fa-envelope"></i>
						<div class="contact-data">
							<h4>Email</h4>
							<p> Support@Tazzergroup.com </p>
						</div>
					</div>
				</div>
			</div>			
		</div>
	</div>
</div>

