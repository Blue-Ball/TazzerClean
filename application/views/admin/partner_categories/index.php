<div class="page-wrapper">
	<div class="content container-fluid">
            <div class="row mb-4">
                <div class="col-sm-8">
                    <h4 class="page-title m-b-20 m-t-0">Partner Categories</h4>
                </div>
                <div class="col-sm-4 text-right m-b-20">
                    <a href="<?php echo base_url($theme . '/' . $model . '/create'); ?>" class="btn btn-primary add_whitelabel">Add</a>
                </div>
            </div>
            <?php
            if ($this->session->userdata('message')) {
                echo $this->session->userdata('message');
            }
            ?>
            <div class="card pt-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-actions-bar categories_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Partner Name</th>
                                    <th>Email</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($lists)) {
                                    $sno = 1;
                                    foreach ($lists as $row) {
                                        $_id = isset($row['id']) ? $row['id'] : '';
                                        if (!empty($_id)) {
                                            $user_name = $row['user_name'];
                                            $user_email = $row['user_email'];
                                            $category_name = $row['category_name'];
                                            $subcategory_name = $row['subcategory_name'];
                                            $status = 'Inactive';
                                            if (isset($row['status']) && $row['status'] == 1) {
                                                $status = 'Active';
                                            }
                                            
                                            /*$created_at = '-';
                                            if (isset($row['created_at'])) {
                                                if (!empty($row['created_at']) && $row['created_at'] != "0000-00-00 00:00:00") {
                                                    $created_at = '<span >' . date('d-m-Y', strtotime($row['created_at'])) . '</span>';
                                                }
                                            }*/
                                ?>
                                            <tr>
                                                <td> <?php echo $sno ?></td>
                                                <td> <?php echo $user_name; ?></td>
                                                <td> <?php echo $user_email; ?></td>
                                                <td> <?php echo $category_name; ?></td>
                                                <td> <?php echo $subcategory_name; ?> </td>
                                                <td> <?php echo $status; ?></td>
                                                <td class="text-right">
                                                <a href="<?php echo base_url().'admin/partner_categories/edit/' . $_id; ?>" class="btn btn-sm bg-success-light mr-2"><i class="fa fa-edit mr-1"></i> Edit</a>&nbsp;
													<a href="javascript:void(0)" class="on-default remove-row btn btn-sm bg-danger-light mr-2 delete_partner_category" id="Onremove_<?php echo $_id; ?>" data-id="<?php echo $_id; ?>"><i class="fa fa-trash-alt mr-1"></i> Delete</a>
                                                   
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                        $sno = $sno + 1;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="11">
                                            <p class="text-danger text-center m-b-0">No Records Found</p>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
<script type="text/javascript">

var BASE_URL = $('#base_url').val();
$('.delete_partner_category').on('click', function() {
    var id = $(this).attr('data-id');
    delete_partner_category(id);
});

function delete_partner_category(val) {
    bootbox.confirm("Are you sure want to delete this partner category ? ", function(result) {
        if (result == true) {
            var BASE_URL = $('#base_url').val();
            var url = BASE_URL + 'admin/partner_categories/delete_partner_category';
            var keyname = "<?php echo $this->security->get_csrf_token_name(); ?>";
            var keyvalue = "<?php echo $this->security->get_csrf_hash(); ?>";
            var data = {
                partner_category_id: val
            };
            data[keyname] = keyvalue;
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                success: function(res) {
                  var data = JSON.parse(res)
                  if (data.sec == 1) {
                    window.location = BASE_URL + 'admin/partner_categories';
                  } else {
                    alert('Something wrong, Please try again')
                  }
                }
            });
        }
    });
}
</script>