<style>
.muilti-line-ellipsis {
    text-overflow: ellipsis;
    overflow: hidden;
    display: -webkit-box;
    white-space: pre-line;
}
</style>
<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="container">
			<div class="row">
				<div class="col-xl-8 offset-xl-2">
					<h4 class="page-title m-b-20 m-t-0">Detail Ads</h4>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-8 offset-xl-2">
					<div class="card">
						<div class="card-body">
							<div class="row form-group">
								<div class="col-md-3">
									<label class="control-label">User Name:</label>
								</div>
								<div class="col-md-9">
									<label><?php echo $ads_ary['user_name']; ?></label>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-3">
									<label class="control-label">User Photo:</label>
								</div>
								<div class="col-md-9">
									<?php 
										$profile_img = 'assets/img/user.jpg';
                                        if (!empty($ads_ary['user_profile_img'])){                                            
                                            $profile_img = $ads_ary['user_profile_img'];
                                        }
                                    ?>
									<label><img class="avatar-sm rounded-circle mr-1" src="<?php echo base_url().$profile_img; ?>"></label>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-3">
									<label class="control-label">Category:</label>
								</div>
								<div class="col-md-9">
									<label><?php echo $ads_ary['category_name']; ?></label>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-3">
									<label class="control-label">Sub Category:</label>
								</div>
								<div class="col-md-9">
									<label><?php echo $ads_ary['subcategory_name']; ?></label>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-3">
									<label class="control-label">Description:</label>
								</div>
								<div class="col-md-9">
									<label class="muilti-line-ellipsis"><?php echo $ads_ary['description']; ?>
									</label>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-3">
									<label class="control-label">Create Date:</label>
								</div>
								<div class="col-md-9">
									<?php 
									$created_at = '-';
                                        if (isset($ads_ary['created_at'])) {
                                            if (!empty($ads_ary['created_at']) && $ads_ary['created_at'] != "0000-00-00 00:00:00") {
                                                $created_at = '<span >' . date('d-m-Y', strtotime($ads_ary['created_at'])) . '</span>';
                                            }
                                        }  
                                    ?>
									<label><?php echo $created_at; ?></label>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-3">
									<label class="control-label">Status:</label>
								</div>
								<div class="col-md-9">
									<?php 
                                        $status_str = "Pending";
                                        if ($ads_ary['status']==ADS_INPROGRESS) {
                                            $status_str = "Inprogress";
                                        }                                                           
                                        if ($ads_ary['status']==ADS_COMPLETED) {
                                            $status_str = "Completed";
                                        }
                                        if ($ads_ary['status']==ADS_REJECTED) {
                                            $status_str = "Rejected";
                                        } 
                                    ?>
									<label><?php echo $status_str; ?></label>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-3">
									<label class="control-label">Reason:</label>
								</div>
								<div class="col-md-9">
									<label class="muilti-line-ellipsis"><?php echo $ads_ary['reason']; ?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
