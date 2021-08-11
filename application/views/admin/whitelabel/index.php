<div class="page-wrapper">
	<div class="content container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h4 class="page-title m-b-20 m-t-0">Whitelabel</h4>
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
            <div class="panel pt-4">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-actions-bar categories_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Brand Name</th>
                                    <th>Country</th>
                                    <th>Logo</th>
                                    <th>Favicon</th>
                                    <th>Color</th>
                                    <th>Host Address</th>
                                    <th>Create Date</th>
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
                                            $name = $row['name'];
                                            $brand_name = $row['brandname'];
                                            $country = $row['country'];
                                            $logo_file = '-';
                                            if(!empty($row['logofile'])){
                                                $logo_file = '<img style="height:40px" class="mr-1" src="'.base_url().'assets/wll_logos/'.$row['logofile'].'">';
                                            }

                                            $favicon = '-';
                                            if(!empty($row['favicon'])){
                                                $favicon = '<img class="avatar-sm mr-1" src="'.base_url().'assets/wll_logos/'.$row['favicon'].'">';
                                            }

                                            $color = '-';
                                            if(!empty($row['color'])){
                                                $color = '<div class="p-1 mr-1" style="background:'.$row['color'].'">'.$row['color'].'</div>';
                                            }
                                           
                                            $host_address = $row['hostaddress'];
                                            $status = 'Inactive';
                                            if (isset($row['status']) && $row['status'] == 1) {
                                                $status = 'Active';
                                            }
                                            $created_on = '-';
                                            if (isset($row['created_at'])) {
                                                if (!empty($row['created_at']) && $row['created_at'] != "0000-00-00 00:00:00") {
                                                    $created_on = '<span >' . date('d-m-Y', strtotime($row['created_at'])) . '</span>';
                                                }
                                            }
                                ?>
                                            <tr>
                                                <td> <?php echo $sno ?></td>
                                                <td> <?php echo $name; ?></td>
                                                <td> <?php echo $brand_name; ?></td>
                                                <td> <?php echo $country; ?> </td>
                                                <td> <?php echo $logo_file; ?> </td>
                                                <td> <?php echo $favicon; ?> </td>
                                                <td> <?php echo $color; ?> </td>
                                                <td> <?php echo $host_address; ?> </td>
                                                <td> <?php echo $created_on ?></td>
                                                <td> <?php echo $status; ?></td>
                                                <td class="text-right">
                                                <a href="<?php echo base_url().'admin/whitelabel/edit/' . $_id; ?>" class="btn btn-sm bg-success-light mr-2"><i class="fa fa-edit mr-1"></i> Edit</a>&nbsp;
													<a href="javascript:void(0)" class="on-default remove-row btn btn-sm bg-danger-light mr-2 delete_whitelabel" id="Onremove_<?php echo $_id; ?>" data-id="<?php echo $_id; ?>"><i class="fa fa-trash-alt mr-1"></i> Delete</a>
                                                   
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