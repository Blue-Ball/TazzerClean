<div class="page-wrapper">
	<div class="content container-fluid">
        <div class="container">
            <div class="row mb-4">
                <div class="col-sm-8">
                    <h4 class="page-title m-b-20 m-t-0">Fees</h4>
                </div>
                <div class="col-sm-4 text-right m-b-20">
                    <a href="<?php echo base_url($theme . '/' . $model . '/create'); ?>" class="btn btn-primary ">Add</a>
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
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Value (%) </th>
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
                                ?>
                                            <tr>
                                                <td> <?php echo $sno ?></td>
                                                <td> <?php echo $row['name']; ?></td>
                                                <td> <?php echo $row['type']; ?></td>
                                                <td> <?php echo $row['fee']; ?></td>
                                                <td class="text-right">
                                                <a href="<?php echo base_url().'admin/fees/edit/' . $_id; ?>" class="btn btn-sm bg-success-light mr-2"><i class="fa fa-edit mr-1"></i> Edit</a>&nbsp;
													<a href="javascript:void(0)" class="on-default remove-row btn btn-sm bg-danger-light mr-2 delete_fee" id="Onremove_<?php echo $_id; ?>" data-id="<?php echo $_id; ?>"><i class="fa fa-trash-alt mr-1"></i> Delete</a>
                                                   
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
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
<script type="text/javascript">
var BASE_URL = $('#base_url').val();
$('.delete_fee').on('click', function() {
    var id = $(this).attr('data-id');;
    delete_fee(id);
});

function delete_fee(val) {
    bootbox.confirm("Are you sure want to delete this fee ? ", function(result) {
        if (result == true) {
            var BASE_URL = $('#base_url').val();
            var url = BASE_URL + 'admin/fees/delete_fee';
            var keyname = "<?php echo $this->security->get_csrf_token_name(); ?>";
            var keyvalue = "<?php echo $this->security->get_csrf_hash(); ?>";
            var data = {
                fee_id: val
            };
            data[keyname] = keyvalue;
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                success: function(res) {
                  var data = JSON.parse(res)
                  if (data.sec == 1) {
                    window.location = BASE_URL + 'admin/fees';
                  } else {
                    alert('Something wrong, Please try again')
                  }
                }
            });
        }
    });
}
</script>