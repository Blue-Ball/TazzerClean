<?php
$country_list=$this->db->where('status',1)->order_by('country_name',"ASC")->get('country_table')->result_array();
?>
<div class="content">
    <div class="container">
        <div class="row">
            <?php $this->load->view('organization/home/organization_sidemenu'); ?>
            <div class="col-xl-9 col-md-8"> 
                <h4 class="mb-4">Staff List</h4>                
                <div class="card transaction-table mb-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mt-4">
                            <?php if(!empty($organization_id)) { ?>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#add_modal">Add Staff</button> 
                                <?php } else { ?>
                                <span><span style='color:red; padding:4px;'>*</span>You must complete Organization Setting.</span>    
                                <?php } ?>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="order-summary" class="table table-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile Number</th>
                                        <th>House name</th>
                                        <th>Street Address</th>
                                        <th>City</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(!empty($staffs)){
                                            foreach ($staffs as $staff) {
                                    ?>
                                                <tr>
                                                    <td><lable><?php echo $staff['name']; ?></lable></td>
                                                    <td><lable><?php echo $staff['email']; ?></lable></td>
                                                    <td><lable><?php echo $staff['mobileno']; ?></lable></td>
                                                    <td><lable><?php echo $staff['house_name']; ?></lable></td>
                                                    <td><lable><?php echo $staff['street_address']; ?></lable></td>
                                                    <td><lable><?php echo $staff['city']; ?></lable></td>
                                                    <td>
                                                        <div class="custom-control custom-switch">
                                                          <input id="staff_status_<?php echo $staff['id'] ?>" data-id="<?php echo $staff['id'] ?>" data-userid="<?= $staff['user_id'] ?>" type="checkbox" class="custom-control-input" <?php echo ($staff['status']==1?'checked':''); ?> onchange="update_status(this)">
                                                          <label class="custom-control-label" for="staff_status_<?php echo $staff['id'] ?>"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <!-- <a href="<?php echo base_url(); ?>edit-stuff/<?php echo $staff['id'] ?>" class="btn btn-sm bg-success-light mr-2" title="Edit"><i class="fa fa-edit mr-1"></i> Edit</a> -->
                                                        <!-- btn-icon -->
                                                        <button class="btn btn-sm" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="bx bx-dots-horizontal-rounded" data-toggle="tooltip" data-placement="top" title="" data-original-title="Actions"></i>
                                                            Action
                                                        </button>   <!-- href="<?php echo base_url(); ?>edit-stuff/<?php echo $staff['id'] ?>" -->
                                                                    <!-- data-toggle="modal"  -->
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2" style="">
                                                            <a class="dropdown-item edit-staff" data-target="#add_modal" staff_id='<?php echo $staff['id'] ?>' ><i class="bx bxs-pencil mr-2"></i> Edit Profile</a>
                                                            <a class="dropdown-item text-danger" onclick='remove_staff_info(<?php echo $staff["id"] ?>)'><i class="bx bxs-trash mr-2"></i> Remove</a>
                                                        </div>                        
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
<!-- =========================================== Add Staff Modal Start ====================================== -->
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
                                <h3>Add Staff</h3>
                            </div> 

                            <form method='post' id="add_staff_form">
                                <h3><strong>Personal Details</strong></h3>
                                <div class="row">
                                    <div class="form-group col-md-6 mt-3">
                                        <label>First Name</label>
                                        <input type="text" class="form-control border-dark" name="userName" id='userName' minlength="3">
                                    </div>
                                    <div class="form-group col-md-6 mt-3">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control border-dark" name="userLastName" id='userLastName' minlength="3">
                                    </div>
                                    <div class="form-group col-md-6 mt-3">
                                        <label>Email</label>
                                        <input type="email" class="form-control border-dark" name="userEmail" id='userEmail'>
                                    </div>
                                    <div class="form-group col-md-6 mt-3">
                                        <label>Password</label>
                                        <input type="password" class="form-control border-dark" name="userPassword" id='userPassword'>
                                    </div>

                                    <div class="form-group col-md-6 mt-3">
                                        <label>Postal Code/zipcode</label>
                                        <input type="number" name="postal_code" id="postal_code" class="form-control border-dark" Placeholder="Enter Postal Code">
                                    </div>

                                    <input type="hidden" class="form-control" name="you_are_appling_as" id="you_are_appling_as" value="11" >

                                    <h3 class="col-md-12 mt-3"><strong>Mailing Address</strong></h3>
                                    <div class="form-group col-md-4 mt-3">
                                        <label>House name</label>
                                        <input type="text" name="house_name" id="house_name" value="" placeholder="Enter Street Address" class="form-control border-dark">
                                    </div>
                                    <div class="form-group col-md-4 mt-3">
                                        <label>House or flat number</label>
                                        <input type="number" name="house_number" id="house_number" value="" placeholder="House or flat number" class="form-control border-dark">
                                    </div>
                                    <div class="form-group col-md-4 mt-3">
                                        <label>Street Address</label>
                                        <input type="text" name="street_address" id="street_address" value="" placeholder="Enter Street Address" class="form-control border-dark">
                                    </div>

                                    <div class="form-group col-md-4 mt-3">
                                        <label>City</label>
                                         <input type="text" name="city" id="city" value="" placeholder="Enter City" class="form-control border-dark">
                                    </div>
                                    <div class="form-group col-md-4 mt-3">
                                        <label> County/ Province</label>
                                        <input type="text" name="province" id="province" value="" placeholder="Enter Province" class="form-control border-dark">
                                    </div>
                                    <div class="form-group col-md-4 mt-3">
                                        <label>Postal Code</label>
                                        <input type="number" name="postal_code2" id="postal_code2" value="" placeholder="Enteer Postal Code" class="form-control border-dark">
                                    </div>
                                    <div class="form-group col-md-10 mt-3">
                                        <label>Mobile Number</label>
                                        <div class="row">
                                            <div class="col-4 pr-0">
                                                <select name="countryCode" id="countryCode" class="form-control countryCode final_country_code">
                                                    <?php
                                                    foreach ($country_list as $key => $country) { 
                                                        ?>
                                                        <option data-countryCode="<?=$country['country_code'];?>" value="<?=$country['country_id'];?>"><?=$country['country_name'];?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-8">
                                                <input type="text" class="form-control user_final_no user_mobile border-dark" placeholder="Enter Mobile No" name="userMobile" id='userMobile'>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="staff_id" id="hStaff_id" value="0" >
                                    <input type="hidden" name="user_id" id="hUser_id" value="0" >
                                    <button id="registration_submit_staff" type="submit" class="login-btn btn">Register</button>
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
<!-- =========================================== Add Staff Modal End ======================================== -->
    
<script type="text/javascript">

    //remove_staff_info
    function remove_staff_info(staff_id)
    {
        var csrf_token = $('#csrf_token').val();
        if( !confirm('Are you sure?'))  return false;
        $.ajax({
                type: "POST",
                url: base_url + "ajax-remove-staff",
                data: {
                    csrf_token_name: csrf_token,
                    staff_id : staff_id
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if(data.result=='ok'){
                        alert("Staff has been removed successfully.");
                        window.location.reload();
                    }else{
                        alert("Failed. Staff has not been removed.");
                        window.location.reload();
                    }
                }
        });
    }


function update_status(obj){
    var status = 1;
    if(obj.checked) {
        status = 1;
    } else {
        status = 2;
    }
    var staff_id = $(obj).attr('data-id');
    var user_id = $(obj).attr('data-userid');

    var csrf_token = $('#csrf_token').val();
    $.ajax({
        type:'POST',
        url: '<?php echo base_url(); ?>organization/staff/update_staff_status',
        data :  {
                staff_id:staff_id,
                user_id:user_id,
                status:status,
                csrf_token_name: csrf_token,
            },
        dataType:'json',
        success:function(response)
        {                  
            if(response.success) { 
                alert("Updated Success.");
            } else {    
                alert('Update Status Failed');
            }
        }
    }); 
}

var keep_stsff_information_flag = 0;
$(document).ready(function(){   

    $('#add_modal').on('show.bs.modal', function (e) {
        //console.log('Showing Staff\'s Information Modal');

        if(keep_stsff_information_flag==0)
        {
            $('.login-header h3').html('Add Staff');
            $('#add_staff_form')[0].reset();
            $('#add_staff_form').bootstrapValidator('enableFieldValidators',  'userPassword', 'notEmpty');
        }
        else
        {
            $('.login-header h3').html('Edit Staff');
            $('#add_staff_form').bootstrapValidator('enableFieldValidators',  'userPassword', false, 'notEmpty');
        }
            
        keep_stsff_information_flag = 0;
    });


    $('.edit-staff').click( function(e) {
        var staff_id = $(this).attr('staff_id');
       // return e.preventDefault() // stops modal from being shown
       
       $.ajax({
            type: "POST",
            url: base_url + "organization-staff-info",
            //dataType:'json',
            data: {
                staff_id : staff_id,
                csrf_token_name: csrf_token,
            },
            beforeSend: function() {
            },
            success: function(data) {
                try{
                    data = JSON.parse(data);
                    
                    if(data.result=='ok'){
                        console.log(data.data);
                        $('#hStaff_id').val(data.data.id);
                        $('#hUser_id').val(data.data.user_id);
                        $('#userName').val(data.data.name);
                        $('#userLastName').val(data.data.l_name);
                        $('#userEmail').val(data.data.email);
                        $('#house_name').val(data.data.house_name);
                        $('#street_address').val(data.data.street_address);
                        $('#city').val(data.data.city);
                        $('#userMobile').val(data.data.mobileno);
                        $('#province').val(data.data.province);
                        $('#postal_code').val(data.data.postal_code);
                        $('#postal_code2').val(data.data.postal_code2);
                        $('#house_number').val(data.data.house_number);
                        $('#userPassword').val('');
                        $('#countryCode  option[value="'+data.data.countryCode+'"]').prop('selected', true);
                        // -- you_are_appling_as
                    }else{
                        alert("The staff information could not get.");
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

    var checked = ''; 
    var base_url = $('#base_url').val();
    var csrf_token = $('#csrf_token').val();
    var csrfName = $('#csrfName').val();
    var csrfHash = $('#csrfHash').val();
    
    $('#add_staff_form').bootstrapValidator({
        excluded: ':disabled',
        fields: {
            userName: {
                validators: {
                    notEmpty: {
                        message: 'Please Enter your name ..'
                    }
                }
            },
            userEmail: {
                validators: {
                    remote: {
                        url: base_url + 'user/login/email_chk_user',
                        data: function(validator) {
                            return {
                                userEmail: validator.getFieldElements('userEmail').val(),
                                user_id : $('#hUser_id').val(),
                                csrf_token_name: csrf_token
                            };
                        },
                        message: 'This email is already exist...',
                        type: 'POST'
                    },
                    notEmpty: {
                        message: 'Please Enter Email Address...'
                    },
                    regexp: {
                        regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                        message: 'The value is not a valid email address'
                    },
                }
            },
            userPassword: {
                validators: {
                    notEmpty: {
                        message: 'Please Enter the Staff\'s password ..'
                    }
                }
            },
            userMobile: {
                validators: {
                    remote: {
                        url: base_url + 'user/login/mobileno_chk_user',
                        data: function(validator) {
                            return {
                                user_id : $('#hUser_id').val(),
                                userMobile: validator.getFieldElements('userMobile').val(),
                                countryCode: validator.getFieldElements('countryCode').val(),
                                csrf_token_name: csrf_token
                            };
                        },
                        message: 'This mobile number is already exist or invalid..',
                        type: 'POST'
                    },
                    notEmpty: {
                        message: 'Please Enter Mobile Number'
                    },
                    regexp: {
                        regexp: /^[1-9][0-9]{5,15}$/,
                        message: "Invalid Mobile Number"
                    },
                }
            },
        }
    }).on('success.form.bv', function(e) {
        
        var userName = $('#userName').val();
        var last_name = $('#userLastName').val();
        var userEmail = $('#userEmail').val();
        var userPassword = $('#userPassword').val();
        var postal_code = $('#postal_code').val();
        var you_are_appling_as = $('#you_are_appling_as').val();
        var house_name = $('#house_name').val();
        var house_number = $('#house_number').val();
        var street_address = $('#street_address').val();
        var city = $('#city').val();
        var province = $('#province').val();
        var postal_code2 = $('#postal_code2').val();
        var userMobile = $('#userMobile').val();
        var countryCode = $('#countryCode').val();

        var staff_id = $('#hStaff_id').val();
        var user_id = $('#hUser_id').val();
        $.ajax({
            type: "POST",
            url: base_url + "organization/staff/add_staff",
            dataType:'json',
            data: {
                'name': userName,
                'l_name': last_name,
                'email': userEmail,
                'password': userPassword,
                'postal_code': postal_code,
                'you_are_appling_as': you_are_appling_as,
                'house_name': house_name,
                'house_number': house_number,
                'street_address': street_address,
                'city': city,
                'province': province,
                'postal_code2': postal_code2,
                'country_code': countryCode,
                'csrf_token_name': csrf_token,
                'mobileno': userMobile
                
                ,"staff_id" : staff_id
                ,"user_id" : user_id

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

