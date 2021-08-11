<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="container">
			<div class="row">
				<div class="col-xl-8 offset-xl-2">
					<h4 class="page-title m-b-20 m-t-0">Create Partner Category</h4>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-8 offset-xl-2">
					<div class="card">
						<div class="card-body">
							<form class="form-horizontal" id="eidt_partner_category" method="POST" enctype="multipart/form-data">
								<div class="form-group">
									<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

									<label class=" control-label">Partner</label>
									<select class="form-control select2" id="user_id" name="user_id">
										<?php foreach($partner_list as $id=>$name){ ?>
											<option value="<?= $id ?>"><?= $name ?> </option>
										<?php } ?>
									</select>
								</div>
								
								<div class="form-group">
									<label class="control-label">Category </label>
									<select class="form-control select2" id="main_category" name="category">
										<?php foreach($c_list as $id=>$name){ ?>
											<option value="<?= $id ?>"><?= $name ?> </option>
										<?php } ?>
									</select>
								</div>
								<div class="form-group">
									<label class="control-label">Subcategory </label>
									<select class="form-control select2" id="sub_category" name="subcategory">
									</select>
								</div>
								
								<div class="form-group">
									<label class=" control-label">Status</label>
									<div class="radio radio-primary radio-inline">
										<input type="radio" id="academy_status1" value="1" name="status" checked>
										<label for="academy_status1">Active</label>
									</div>
									<div class="radio radio-danger radio-inline">
										<input type="radio" id="academy_status2" value="0" name="status">
										<label for="academy_status2">Inactive</label>
									</div>
								</div>
								<div class="mt-4">
									<?php if ($user_role == 1) { ?>
										<button class="btn btn-primary " name="form_submit" value="submit" type="submit">Submit</button>
									<?php } ?>
									<a href="<?php echo $base_url; ?>admin/partner_categories" class="btn btn-link">Cancel</a>
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
	var BASE_URL = $('#base_url').val();
	$('#user_id').select2({
    	placeholder: "Select a Partner",
  	});
  	$('#main_category').select2({
    	placeholder: "Select a category",
  	});
  	$('#sub_category').select2({
    	placeholder: "Select a subcategory",
  	});
  	$('#main_category').on('change', function(){
        var url = BASE_URL + 'admin/partner_categories/change_subcategory';
        var keyname = "<?php echo $this->security->get_csrf_token_name(); ?>";
        var keyvalue = "<?php echo $this->security->get_csrf_hash(); ?>";
        var category_id = $(this).val();
        var selected_subcategory = $('#sub_category').val();

        var data = {
          category_id: category_id,
          selected_subcategory : selected_subcategory
        };
        data[keyname] = keyvalue;
        $.ajax({
          	url: url,
          	data: data,
          	type: "POST",
          	success: function(res) {
	            $('#sub_category').html(res);
	            $('#sub_category').trigger('change')
          	}
        });
    });

    $('#eidt_partner_category').bootstrapValidator({
        fields: {
          	user_id: {
            	validators: {
              		notEmpty: {
                		message: 'Please Select Partner'
              		}
            	}
          	},
          	subcategory: {
            	validators: {
              		notEmpty: {
                		message: 'Please Select Sub Category'
              		}
            	}
          	},
        }
    }).on('success.form.bv', function(e) {
        return true;
    });
});
</script>