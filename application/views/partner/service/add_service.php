<?php
$type = $this->session->userdata('usertype');
if ($type == 'user') {
$user_currency = get_user_currency();
} else if ($type == 'provider') {
$user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code'];
?>

<style type="text/css">
.page-title {
    font-size: 36px;
    padding-bottom: 20px; 
    letter-spacing: 1px;
    color: #242133;
}
input[type="file"] {
    display: block;
}
.imageThumb {
    max-height: 75px;
    border: 2px solid;
    padding: 1px;
    cursor: pointer;
}
.pip {
    display: inline-block;
    margin: 10px 10px 0 0;
}
.remove {
    display: block;
    background: #444;
    border: 1px solid black;
    color: white;
    text-align: center;
    cursor: pointer;
}
.remove:hover {
    background: white;
    color: black;
}
/*span.remove {
    margin-top: -85px;
}*/
</style>

<div class="content">
    <div class="container">
        <div class="row">
            <?php $this->load->view('partner/home/partner_sidemenu');?>
            <div class="col-xl-9 col-md-8">
                <div class="page-title">
                    <h2 class="widget-title mb-0">Add Services</h2>
                </div>

                <form method="post" enctype="multipart/form-data" autocomplete="off" id="partner_add_service">
                    <input type="hidden" id="frm_csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input class="form-control" type="hidden" name="currency_code" value="<?php echo $user_currency_code; ?>">
                    <div class="service-fields mb-3">
                        <h3 class="heading-2">Service Information</h3>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Service Title <span class="text-danger">*</span></label>
                                    <input type="hidden" class="form-control" id="map_key" value="<?=$map_key?>" >
                                    <input class="form-control" type="text" name="service_title" id="service_title" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Service Amount Type <span class="text-danger">*</span></label>
                                      <select class="form-control serviceamounttype" title="Select Service Amount Type" name="serviceamounttype" id="serviceamounttype"  required>
                                            <option value="Fixed">Fixed</option>
                                            <option value="Hourly">Hourly</option>
                                            <option value="Per sqft">Per sqft</option>
                                      </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Service Amount <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="service_amount" id="service_amount" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Service Location <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="service_location" id="service_location" required>
                                    <input type="hidden" name="service_latitude" id="service_latitude">
                                    <input type="hidden" name="service_longitude" id="service_longitude">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="service-fields mb-3">
                        <h3 class="heading-2">Service Category</h3>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Category <span class="text-danger">*</span></label>
                                    <select class="form-control select" title="Category" name="category" id="partner_category"   required></select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Services<span class="text-danger">*</span></label>
                                    <select class="form-control select" title="Select Services" name="subcategory" id="subcategory"  required></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="service-fields mb-3">
                        <h3 class="heading-2">Add Sub Category</h3>

                        <div class="membership-info">
                            <div class="row form-row membership-cont">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Service Offered <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="service_offered[]" id="field1" class="" required="">
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="add-more form-group">
                            <a href="javascript:void(0);" class="add-membership"><i class="fa fa-plus-circle"></i> Add More</a>
                        </div>
                    </div>
                    <div class="service-fields mb-3">
                        <h3 class="heading-2">Details Information</h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Descriptions <span class="text-danger">*</span></label>
                                    <textarea id="about" class="form-control service-desc" name="about" required></textarea>
                                    <script>
                                    // Replace the <textarea id="editor1"> with a CKEditor
                                    // instance, using default configuration.
                                    CKEDITOR.replace( 'about' );
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="service-fields mb-3">
                        <h3 class="heading-2">Meta Tag </h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Meta Tag Details <span class="text-danger">*</span></label>
                                    <textarea id="metatagdetails" class="form-control metatagdetails" name="metatagdetails" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="service-fields mb-3">
                        <h3 class="heading-2">Service Gallery</h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="service-upload" id="remove-after">
                                    <i class="fa fa-cloud-upload-alt"></i>
                                    <span>Upload Service Images *</span>
                                    
                                    <input type="file" name="images[]" id="files" multiple accept="image/jpeg, image/png, image/gif,">
                                
                                </div>
                                <div id="uploadPreview"></div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var max_service_post_num = <?php echo json_encode($max_service_post_num); ?>;
var total_service_num = <?php echo json_encode($total_service_num); ?>;
$(document).ready(function() {
    if (Number(max_service_post_num) !== 0 && Number(total_service_num) >= Number(max_service_post_num)) {
        swal({
            title: "Service Post Warning!",
            text: "You can post "+max_service_post_num+" services at the most. You posted "+total_service_num+" services already.",
            icon: "warning",
            button: "okay",
            closeOnEsc: false,
            closeOnClickOutside: false
        }).then(function(){
            window.history.back();
        });
    }
    if (window.File && window.FileList && window.FileReader) {
        $("#files").on("change", function(e) {
          var files = e.target.files, filesLength = files.length;
          for (var i = 0; i < filesLength; i++) {
            var f = files[i]
            var fileReader = new FileReader();
            fileReader.onload = (function(e) {
              var file = e.target;
              $("<span class=\"pip\">" +
                "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                "<br/><span class=\"remove\" style=\"color:red\">RemoveX</span>" +
                "</span>").insertAfter("#remove-after");
              $(".remove").click(function(){
                $(this).parent(".pip").remove();
              });  
            });
            fileReader.readAsDataURL(f);
          }
          // console.log(files);
        });
    } else {
        alert("Your browser doesn't support to File API")
    }
    // ---------------------------------------------------------------------------
    var base_url = $('#base_url').val();
    var csrf_token = $('#csrf_token').val();
    $.ajax({
        type: "GET",
        url: base_url + "partner/service/get_partner_category",
        data: {
            id: $(this).val(),
            'csrf_token_name': csrf_token
        },
        beforeSend: function() {
            $("#partner_category option:gt(0)").remove();
            $('#partner_category').selectpicker('refresh');
            $("#partner_category").selectpicker();
            $('#partner_category').find("option:eq(0)").html("Please wait..");
            $('#partner_category').selectpicker('refresh');
            $("#partner_category").selectpicker();
        },
        success: function(data) {
            $('#partner_category').selectpicker('refresh');
            $("#partner_category").selectpicker();
            $('#partner_category').find("option:eq(0)").html("Select Category");
            $('#partner_category').selectpicker('refresh');
            $("#partner_category").selectpicker();
            var obj = jQuery.parseJSON(data);
            $('#partner_category').selectpicker('refresh');
            $("#partner_category").selectpicker();
            $(obj).each(function() {
                var option = $('<option />');
                option.attr('value', this.value).text(this.label);
                $('#partner_category').append(option);
            });
            $('#partner_category').selectpicker('refresh');
            $("#partner_category").selectpicker();
        }
    });


    $('#partner_category').change(function() {
        $("#subcategory").val('default');
        $("#subcategory").selectpicker("refresh");
        $.ajax({
                type: "POST",
                url: base_url + "partner/service/get_partner_subcategory",
                data: {
                  id: $(this).val(),
                  csrf_token_name: csrf_token
                },
            beforeSend: function() {
                $("#subcategory option:gt(0)").remove();
                $('#subcategory').selectpicker('refresh');
                $("#subcategory").selectpicker();
                $('#subcategory').find("option:eq(0)").html("Please wait..");
                $('#subcategory').selectpicker('refresh');
                $("#subcategory").selectpicker();
            },
            success: function(data) {
                $('#subcategory').selectpicker('refresh');
                $("#subcategory").selectpicker();
                $('#subcategory').find("option:eq(0)").html("Select SubCategory");
                $('#subcategory').selectpicker('refresh');
                var obj = jQuery.parseJSON(data);
                $('#subcategory').selectpicker('refresh');
                $("#subcategory").selectpicker();
                $(obj).each(function() {
                    var option = $('<option />');
                    option.attr('value', this.value).text(this.label);
                    $('#subcategory').append(option);
                });
                $('#subcategory').selectpicker('refresh');
                $("#subcategory").selectpicker();
            }
        });
    });

    // ----------------------------------------------------------------------------
    $('#partner_add_service').bootstrapValidator({
        fields: {
            service_title: {
                validators: {
                    remote: {
                        url: base_url + 'partner/service/check_service_title',
                        data: function(validator) {
                            return {
                                service_title: validator.getFieldElements('service_title').val(),
                                'csrf_token_name':$('#frm_csrf').val()
                            };
                        },
                        message: 'This Service is already exist',
                        type: 'POST'
                    },
                    notEmpty: {
                        message: 'Please Enter your service title'
                    }
                }
            },
            service_sub_title: {
                validators: {
                    notEmpty: {
                        message: 'Please Enter service sub title'
                    }
                }
            },
            category: {
                validators: {
                    notEmpty: {
                        message: 'Please select category...'
                    }
                }
            },
            subcategory: {
                validators: {
                    notEmpty: {
                        message: 'Please select subcategory...'
                    }
                }
            },
            service_location: {
                validators: {
                    notEmpty: {
                        message: 'Please Enter service location...'
                    }
                }
            },
            service_amount: {
                validators: {
                    digits: {
                        message: 'Please Enter valid service amount and not user in special characters...'
                    },
                    notEmpty: {
                        message: 'Please Enter service amount...'
                    }
                }
            },
            'service_offered[]': {
                validators: {
                    notEmpty: {
                        message: 'Please Enter service offered'
                    }
                }
            }, 
            about: {
                validators: {
                    notEmpty: {
                        message: 'Please Enter About Informations...'
                    }
                }
            },
            'images[]': {
                validators: {
                    file: {
                        extension: 'jpeg,png,jpg',
                        type: 'image/jpeg,image/png,image/jpg',
                        message: 'The selected file is not valid. Only allowed jpeg,png files'
                    },
                    notEmpty:               {
                        message: 'Please upload category image...'
                    }
                }
            }                    
        }
    }).on('success.form.bv', function(e) {return true;}); 
});
</script>