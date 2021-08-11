<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="container">
			<div class="row">
				<div class="col-xl-8 offset-xl-2">
					<h4 class="page-title m-b-20 m-t-0">Edit Partner Department</h4>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-8 offset-xl-2">
					<div class="card">
						<div class="card-body">
							<form class="form-horizontal" id="edit_department" method="POST" enctype="multipart/form-data">
								<div class="form-group">
									<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

									<label class=" control-label">Department Name</label>
									<input type="text" class="form-control" name="department_name" value="<?= isset($department['name']) ? $department['name'] : '' ?>">
								</div>
								
								<div class="mt-4">
									<?php if ($user_role == 1) { ?>
										<button class="btn btn-primary " name="form_submit" value="submit" type="submit">Submit</button>
									<?php } ?>
									<a href="<?php echo $base_url; ?>admin/partner_department" class="btn btn-link">Cancel</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {	

    $('#edit_department').bootstrapValidator({
        fields: {
          	department_name: {
            	validators: {
              		notEmpty: {
                		message: 'Please insert Department Name'
              		}
            	}
          	},
        }
    }).on('success.form.bv', function(e) {
        return true;
    });
});
</script>