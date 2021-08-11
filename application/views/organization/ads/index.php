
<style>
.two-line-ellipsis {
    text-overflow: ellipsis;
    overflow: hidden;
    width: 200px;
    /*line-height: 25px;*/
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    white-space: pre-line;
}
</style>
<div class="content">
    <div class="container">
        <div class="row">
            <?php $this->load->view('organization/home/organization_sidemenu'); ?>
            <div class="col-xl-9 col-md-8"> 
                <h4 class="mb-4">Advertisement</h4>                
                <div class="card transaction-table mb-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mt-4">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#add_modal">Request Google Ads</button> 
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="order-summary" class="table table-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Subcategory Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(!empty($ads_list)){
                                            foreach ($ads_list as $row) {
                                    ?>
                                                <tr>
                                                    <td><lable><?php echo $row['subcategory_name']; ?></lable></td>
                                                    <td><div><span class="two-line-ellipsis"><?php echo $row['description']; ?>
                                                    </span></div></td>
                                                    <td>
                                                        <?php
                                                            $color = "dark";
                                                            $status_str = "Pending";
                                                            if ($row['status']==ADS_INPROGRESS) {
                                                                $color= 'info';
                                                                $status_str = "Inprogress";
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
                                                        <label class="badge badge-<?=$color?>"><?php echo $status_str; ?></label>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            if ($row['status']==ADS_PENDING){
                                                        ?>
                                                        <a class="btn btn-sm bg-success-light mr-2 edit_ads" data-target="#add_modal" ads_id='<?php echo $row['id'] ?>'>
                                                            <i class="fa fa-edit mr-1"></i> Edit
                                                        </a>
                                                        <a class="on-default remove-row btn btn-sm bg-danger-light mr-2 delete_skill" onclick='remove_ads_info(<?php echo $row["id"] ?>)'>
                                                            <i class="fa fa-trash-alt mr-1"></i> Delete
                                                        </a>      
                                                        <?php } ?>                 
                                                    </td>
                                                </tr>
                                    <?php
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
    </div>
</div>
<!-- =========================================== Add Ads Modal Start ====================================== -->
<div class="modal add_modal fade" id="add_modal" data-keyboard="false" data-backdrop="static">
    <div class=" modal-lg modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-0 border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="account-content">
                    <div class="account-box">
                        <div class="login-right">
                            <div class="login-header">
                                <h3>Request Google Ads</h3>
                            </div> 

                            <form method='post' id="add_ads_form">                                
                                <div class="row">
                                    <div class="form-group col-md-6 mt-3">
                                        <label>Category</label>
                                        <select name="category_id" id="category_id" class="form-control cborder-dark">
                                            <?php foreach($category as $row){                                            
                                                if($selected_category_id == $row['id']){
                                            ?>
                                                        <option value="<?= $row['id'] ?>" selected><?= $row['category_name'] ?> </option>
                                            <?php
                                                    }else{
                                            ?>
                                                        <option value="<?= $row['id'] ?>"><?= $row['category_name'] ?> </option>
                                            <?php
                                                    }                                                                                   
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 mt-3">
                                        <label>Sub Category</label>
                                        <select name="subcategory_id" id="subcategory_id" class="form-control cborder-dark">
                                            <?php foreach($subcategory as $row){                                            
                                                if($selected_subcategory_id == $row['id']){
                                            ?>
                                                        <option value="<?= $row['id'] ?>" selected><?= $row['subcategory_name'] ?> </option>
                                            <?php
                                                    }else{
                                            ?>
                                                        <option value="<?= $row['id'] ?>"><?= $row['subcategory_name'] ?> </option>
                                            <?php
                                                    }                                                                                   
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12 mt-3">
                                        <label>Description</label>
                                        <textarea class="form-control border-dark" name="description" id="description">
                                        </textarea>
                                    </div>                                
                                    
                                    <div class="form-group col-md-12 mt-3" style="display: grid;justify-content: end;">
                                        <input type="hidden" name="ads_id" id="ads_id" value="0" >
                                        <input type="hidden" name="ads_subcategory_id" id="ads_subcategory_id" value="0" >
                                        <button type="submit" class="btn btn-primary">Register</button>
                                    </div>
                                </div>                            
                            </form> 
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>
<!-- =========================================== Add Ads Modal End ======================================== -->
    
<script type="text/javascript">

function remove_ads_info(ads_id)
{
    var csrf_token = $('#csrf_token').val();
    if( !confirm('Are you sure?'))  return false;
    $.ajax({
            type: "POST",
            url: base_url + "/organization/ads/ajax_remove_ads",
            data: {
                csrf_token_name: csrf_token,
                ads_id : ads_id
            },
            success: function(data) {
                data = JSON.parse(data);
                if(data.result=='ok'){
                    alert("Ads has been removed successfully.");
                    window.location.reload();
                }else{
                    alert("Failed. Ads has not been removed.");
                    window.location.reload();
                }
            }
    });
}

var keep_stsff_information_flag = 0;
$(document).ready(function(){   
    $('#category_id').on('change', function(){
        var csrf_token = $('#csrf_token').val();
        var url = base_url + "/organization/ads/change_subcategory";
        var category_id = $(this).val();
        var selected_subcategory = $('#ads_subcategory_id').val();

        var data = {
          category_id: category_id,
          selected_subcategory : selected_subcategory,
          csrf_token_name : csrf_token,
        };
        
        $.ajax({
            url: url,
            data: data,
            type: "POST",
            success: function(res) {
                $('#subcategory_id').html(res);
            }
        });
    });

    $('#add_modal').on('show.bs.modal', function (e) {
        if(keep_stsff_information_flag==0)
        {
            $('.login-header h3').html('Request Google Ads');
            $('#add_ads_form')[0].reset();
            $('#category_id').trigger('change');
        }
        else
        {
            $('.login-header h3').html('Edit Google Ads');
        }            
        keep_stsff_information_flag = 0;
    });
    
    $('.edit_ads').click( function(e) {
        var ads_id = $(this).attr('ads_id');
       
       $.ajax({
            type: "POST",
            url: base_url + "organization/ads/ajax_ads_info",
            data: {
                ads_id : ads_id,
                csrf_token_name: csrf_token,
            },
            beforeSend: function() {
            },
            success: function(data) {
                try{
                    data = JSON.parse(data);                    
                    if(data.result=='ok'){
                        $('#ads_id').val(data.ads.id);
                        $('#description').val(data.ads.description);
                        $('#category_id>option[value="'+data.ads.category_id+'"]').prop('selected', true);
                        $('#subcategory_id>option[value="'+data.ads.subcategory_id+'"]').prop('selected', true);

                        $('#ads_subcategory_id').val(data.ads.subcategory_id);
                        $('#category_id').trigger('change');
                    }else{
                        alert("The ads information could not get.");
                        return;
                    }
                }
                catch(ee)
                {
                    console.log(ee);
                    return;
                }
                keep_stsff_information_flag = 1;
                $('#add_modal').modal('show');
            },
            error: function(obj, error, description) {

            }
        });
        
    });
    

    var base_url = $('#base_url').val();
    var csrf_token = $('#csrf_token').val();

    $('#add_ads_form').bootstrapValidator({
        excluded: ':disabled',
        fields: {
            category_id: {
                validators: {
                    notEmpty: {
                        message: 'Please select your Category'
                    }
                }
            },
            subcategory_id: {
                validators: {
                    notEmpty: {
                        message: 'Please select your Sub Category'
                    }
                }
            },            
        }
    }).on('success.form.bv', function(e) {
        
        var subcategory_id = $('#subcategory_id').val();
        var description = $('#description').val();
        var ads_id = $('#ads_id').val();
        $.ajax({
            type: "POST",
            url: base_url + "organization/ads/add_ads",
            dataType:'json',
            data: {
                'subcategory_id': subcategory_id,
                'description': description,
                'ads_id': ads_id,
                'csrf_token_name': csrf_token
            },
            beforeSend: function() {
            },
            success: function(data) {
                if(data.result){
                    alert("Regist Success.");
                    window.location.reload();
                }else{
                    alert("Failed.");
                    window.location.reload();
                }
            }
        });
        
        return false;
    });
});
</script>                

