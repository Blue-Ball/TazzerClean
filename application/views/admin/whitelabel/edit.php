<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="container">
			<div class="row">
				<div class="col-xl-8 offset-xl-2">
					<h4 class="page-title m-b-20 m-t-0">Edit Whitelabel</h4>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-8 offset-xl-2">
					<div class="card">
						<div class="card-body">
							<form class="form-horizontal" id="eidt_whitelabel" method="POST" enctype="multipart/form-data">
								<div class="form-group">
									<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

									<label class=" control-label">Label</label>
									<input type="text" require class="form-control" name="name" value="<?= isset($detail['name']) ? $detail['name'] : '' ?>">
								</div>
								<div class="form-group">
									<label class=" control-label">Brand Name</label>
									<input type="text" class="form-control" name="brandname" value="<?= isset($detail['brandname']) ? $detail['brandname'] : '' ?>">
								</div>
								<div class="form-group">
									<label class="control-label">Category </label>
									<select class="form-control select2" id="category_select" name="category[]" multiple="multiple">
										<?= $category_options ?>
									</select>
								</div>
								<div class="form-group">
									<label class="control-label">Subcategory </label>
									<select class="form-control select2" id="subcategory_select" name="subcategory[]" multiple="multiple">
										<?= $subcate_options ?>
									</select>
								</div>
								<div class="row p-3">
									<div class="form-group col-sm-6">
										<div class="media align-items-center mb-3">
											<?php if (!empty($detail['logofile'])) { ?>
												<img class="site-logo" src="<?php echo base_url() ?>assets/wll_logos/<?= $detail['logofile'] ?>" alt="">
											<?php } else { ?>
												<img class="site-logo" src="<?php echo base_url() ?>assets/wll_logos/no_image.jpg" alt="">
											<?php } ?>
											<div class="media-body ml-3">
												<label>Logo</label>
												<div class="jstinput"><a id="logo_openfile" href="javascript:void(0);" class="btn-primary btn py-0 px-3">Browse</a></div>
												<input type="file" accept="image/*" hidden class="logofile_upload" id="logo_file">
												<input type="hidden" id="crop_logo_img" name="logofile" value="<?= $detail['logofile'] ?>">
											</div>
										</div>
									</div>

									<div class="form-group col-sm-6">
										<div class="media align-items-center mb-3">
											<?php if (!empty($detail['favicon'])) { ?>
												<img class="fav-icon" src="<?php echo base_url() ?>assets/wll_logos/<?= $detail['favicon'] ?>" alt="">
											<?php } else { ?>
												<img class="fav-icon" src="<?php echo base_url() ?>assets/wll_logos/no_image.jpg" alt="">
											<?php } ?>
											<div class="media-body ml-3">
												<label>Favicon</label>
												<div class="jstinput"><a id="fav_openfile" href="javascript:void(0);" class="btn-primary btn py-0 px-3">Browse</a></div>
												<input type="file" hidden accept="image/*" class="logofile_upload" id="favicon_file">
												<input type="hidden" id="crop_favicon_img" name="favicon" value="<?= $detail['favicon'] ?>">
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class=" control-label">Color</label>
									<input type="color" class="form-control" name="color" value="<?= isset($detail['color']) ? $detail['color'] : '' ?>">
								</div>

								<div class="form-group">
									<label class=" control-label">Country</label>
									<input type="text" class="form-control" name="country" value="<?= isset($detail['country']) ? $detail['country'] : '' ?>">
								</div>

								<div class="form-group">
									<label class=" control-label">Host Address</label>
									<input type="text" class="form-control" name="hostaddress" value="<?= isset($detail['hostaddress']) ? $detail['hostaddress'] : '' ?>">
								</div>
								<div class="form-group">
									<label class=" control-label">Status</label>
									<div class="radio radio-primary radio-inline">
										<input type="radio" id="academy_status1" value="1" name="status" <?php echo isset($detail['status']) && $detail['status'] ? "checked" : ""; ?>>
										<label for="academy_status1">Active</label>
									</div>
									<div class="radio radio-danger radio-inline">
										<input type="radio" id="academy_status2" value="0" name="status" <?php echo isset($detail['status']) && !$detail['status'] ? "checked" : ""; ?>>
										<label for="academy_status2">Inactive</label>
									</div>
								</div>
								<div class="mt-4">
									<?php if ($user_role == 1) { ?>
										<button class="btn btn-primary " name="form_submit" value="submit" type="submit">Submit</button>
									<?php } ?>

									<a href="<?php echo $base_url; ?>admin/whitelabel" class="btn btn-link">Cancel</a>
									<?php
									$colorparam = '';
									if (!empty($detail['color']))
										$colorparam = '&color=' . substr($detail['color'], 1);

									if (!empty($detail['logofile'])) {
										$logofileparam = '&logofile=' . $detail['logofile'];
										// if (file_exists(base_url() . 'assets/wll_logos/temp/' . $detail['logofile'])) {
										// 	$logofileparam = '&logofile=temp/' . $detail['logofile'];
										// }
									}
									$faviconparam = '';
									if (!empty($detail['favicon'])) {
										$faviconparam = '&favicon=' . $detail['favicon'];
										// if (file_exists(base_url() . 'assets/wll_logos/temp/' . $detail['favicon'])) {
										// 	$faviconparam = '&favicon=temp/' . $detail['favicon'];
										// }
									}

									$brandnameparam = '';
									if (!empty($detail['brandname']))
										$brandnameparam = '&brandname=' . $detail['brandname'];

									?>
									<a href="<?php echo $base_url; ?>?web_preview=true<?php echo $colorparam . $brandnameparam . $faviconparam . $logofileparam ?>" class="btn btn-link btn-secondary right" target="_blank" style="color: white;">preview</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>