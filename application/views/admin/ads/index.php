<style type="text/css">
    .detail-link:hover{
        background-color: #c5c5c5;
    }
    .table td{
        vertical-align: middle;
    }
</style>
<div class="page-wrapper">
	<div class="content container-fluid">
            <div class="row mb-4">
                <div class="col-sm-8">
                    <h4 class="page-title m-b-20 m-t-0">Requested Google Ads</h4>
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
                        <table class="table categories_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category Name</th>
                                    <th>Subcategory Name</th>
                                    <th>Users Request Info</th>
                                    <th>Total User</th>
                                    <th>Requested User</th>
                                    <th>Campain</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sno = 1;
                                    if(!empty($list)){
                                        foreach ($list as $sub_category) {
                                ?>
                                            <tr>
                                                <td><?php echo $sno ?></td>
                                                <td><?php echo $sub_category['category_name'] ?></td>
                                                <td><?php echo $sub_category['subcategory_name'] ?></td>
                                                <td>
                                                    <?php 
                                                    foreach($sub_category['child_list'] as $row){
                                                        $profile_img = 'assets/img/user.jpg';
                                                        if (!empty($row['user_profile_img'])){        
                                                            $profile_img = $row['user_profile_img'];
                                                        }
                                                        $created_at = '-';
                                                        if (isset($row['created_at'])) {
                                                            if (!empty($row['created_at']) && $row['created_at'] != "0000-00-00 00:00:00") {
                                                                $created_at = '<span >' . date('d-m-Y', strtotime($row['created_at'])) . '</span>';
                                                            }
                                                        }
                                                    ?>
                                                        <div class="row mt-2">
                                                            <div class="col-md-3 detail-link"><a href="<?php echo base_url().'admin/ads/detail/' . $row['id']; ?>">
                                                                <img class="avatar-sm rounded mr-1" src="<?php echo base_url().$profile_img; ?>"><?php echo $row['user_name']; ?></a>
                                                            </div>
                                                            <?php
                                                                $color = "dark";
                                                                $color2 = "info";
                                                                $status_str = "Pending";
                                                                $status_str2 = "Inprogress";
                                                                if ($row['status']==ADS_INPROGRESS) {
                                                                    $color= 'info';
                                                                    $color2 = "success";
                                                                    $status_str = "Inprogress";
                                                                    $status_str2 = "Complete";
                                                                }                                                  
                                                                if ($row['status']==ADS_COMPLETED) {
                                                                    $color='success';
                                                                    $status_str = "Completed";
                                                                }
                                                                if ($row['status']==ADS_REJECTED) {
                                                                    $color='warning';
                                                                    $status_str = "Rejected";
                                                                } 
                                                            ?>                                                        
                                                            <div class="col-md-2">
                                                                <label class="badge badge-<?=$color?>"><?php echo $status_str; ?></label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <?php if($row['status'] == ADS_PENDING){ ?>
                                                                    <a href="javascript:void(0)" class="btn btn-sm bg-warning mr-2 update_status" data-id="<?php echo $row['id']; ?>" data-status="<?=ADS_INPROGRESS?>"><i class="fa fa-edit mr-1" ></i>Inprogress</a>
                                                                    <a onclick="initRejectData(this)" class="btn btn-sm bg-danger mr-2 myReject" data-toggle="modal" data-target="#myReject" data-id="<?php echo $row['id'] ?>"><i class="fa fa-edit mr-1"></i>Reject</a>
                                                                <?php }else if($row['status']==ADS_INPROGRESS){ ?>
                                                                    <a href="javascript:void(0)" class="btn btn-sm bg-success mr-2 update_status" data-status="<?=ADS_COMPLETED?>" data-id="<?php echo $row['id']; ?>"><i class="fa fa-edit mr-1"></i>Complete</a>
                                                                    <a href="javascript:void(0)" class="btn btn-sm bg-danger mr-2"><i class="fa fa-edit mr-1"></i>Reject</a>
                                                                <?php } ?>
                                                                <a href="javascript:void(0)" class="on-default remove-row btn btn-sm bg-danger-light mr-2 delete_ads" id="Onremove_<?php echo $row['id']; ?>" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash-alt mr-1"></i> Delete</a>
                                                            </div>                                                                
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $sub_category['total_cnt'] ?></td>
                                                <td><?php echo $sub_category['request_cnt'] ?></td>
                                                <td>
                                                    <?php if($sub_category['total_cnt'] >= 10){ ?>
                                                        <a href="#" class="btn btn-sm bg-success mr-2"><i class="fa fa-edit mr-1"></i>Campain</a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                <?php
                                            $sno++;
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="myReject">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reason for Reject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="reject_ads_id">
                
                <div class="form-group">
                    <label>Reason</label>
                    <textarea class="form-control" rows="5" id="reject_reason"></textarea>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-primary py-2 px-4 text-white mx-auto" id="reject_ads" >sumit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Reject Modal -->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
<script type="text/javascript">

var BASE_URL = $('#base_url').val();

function initRejectData(obj){
    $('#reject_reason').val('');
    $('#reject_ads_id').val('');
    var ads_id = $(obj).attr("data-id");

    $("#reject_ads_id").val(function() {
        return this.value + ads_id;
    });
}

$('#reject_ads').on('click', function() {
    reject_ads();
});   

function reject_ads() {    
    id = $("#reject_ads_id").val();
    status = '<?php echo ADS_REJECTED; ?>';
    reason = $("#reject_reason").val();
    update_status(id,status,reason);
}

$('.update_status').on('click', function() {
    var id = $(this).attr('data-id');
    var status = $(this).attr('data-status');
    var reason = "";
    update_status(id,status,reason);
});

function update_status(id,status,reason) {
    bootbox.confirm("Are you sure want to update status? ", function(result) {
        if (result == true) {
            var BASE_URL = $('#base_url').val();
            var url = BASE_URL + 'admin/ads/update_status';
            var keyname = "<?php echo $this->security->get_csrf_token_name(); ?>";
            var keyvalue = "<?php echo $this->security->get_csrf_hash(); ?>";
            var data = {
                ads_id: id,
                ads_status:status,
                ads_reason:reason
            };
            data[keyname] = keyvalue;
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                success: function(res) {
                  var data = JSON.parse(res)
                  if (data.sec == 1) {
                    window.location = BASE_URL + 'admin/ads';
                  } else {
                    alert('Something wrong, Please try again')
                  }
                }
            });
        }
    });
}

$('.delete_ads').on('click', function() {
    var id = $(this).attr('data-id');
    delete_ads(id);
});

function delete_ads(val) {
    bootbox.confirm("Are you sure want to delete this ads ? ", function(result) {
        if (result == true) {
            var BASE_URL = $('#base_url').val();
            var url = BASE_URL + 'admin/ads/delete_ads';
            var keyname = "<?php echo $this->security->get_csrf_token_name(); ?>";
            var keyvalue = "<?php echo $this->security->get_csrf_hash(); ?>";
            var data = {
                ads_id: val
            };
            data[keyname] = keyvalue;
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                success: function(res) {
                  var data = JSON.parse(res)
                  if (data.sec == 1) {
                    window.location = BASE_URL + 'admin/ads';
                  } else {
                    alert('Something wrong, Please try again')
                  }
                }
            });
        }
    });
}
</script>