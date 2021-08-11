

<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">FAQ</h3>
					<p class="m-t-5"></p>
				</div>
				<div class="col-auto text-right">
				
				
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<?php
			if ($this->session->userdata('message')) {
				echo $this->session->userdata('message');
			}
			?>
		<div class="panel">
				<div class="panel-body">
					<div class="table-responsive">
						<!-- Page Header -->
			
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
                       <form class="form-horizontal"  method="POST" enctype="multipart/form-data" >
					     <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							
						
							<div class="form-group">
								<?php
								
								if (!empty($edit_data->value)) {
									echo  "<textarea class='form-control' id='ck_editor_textarea_id' rows='6' name='template_content'>" . $edit_data->value ."</textarea>";
									echo display_ckeditor($ckeditor_editor1);
								}
								else {
									echo "<textarea class='form-control' id='ck_editor_textarea_id' rows='6' name='template_content'> </textarea>";
									echo display_ckeditor($ckeditor_editor1);
								}
								?>								
							</div>
							<div class="mt-4">
                                <button class="btn btn-primary" name="form_submit" value="submit" type="submit">Save Changes</button>

								<a href="<?php echo $base_url; ?>/emailtemplate"  class="btn btn-link">Cancel</a>

							</div>
						</form>                          
                    </div>
                </div>
 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	</div>
</div>