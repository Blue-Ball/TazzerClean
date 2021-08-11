<style type="text/css">
.page-data {
    background-color: #f3f3f3;
}
.card {
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
  margin-bottom: 0px;
}
.card:hover {
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}
.content-right {
    display: flex;
    justify-content: flex-end;
}
.pro-title-font {
    font-size: 1.17rem;
}
.three-line-ellipsis {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    /*white-space: pre-line;*/
}
.title-3 {
    font-weight: 600;
}

.form-label {
    padding: 0.7rem 1rem 0.4rem 1rem;
    font-size: 15px;
    min-height: 46px;
    border: 1px solid #ced4da;
    background-color: #f3f3f3;
}

.service-upload {
    height: 85px;
    padding-top: 0px;
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
.help-block {
    color: red;
}
</style>
<div class="content">
    <div class="container">
        <div class="row">
            <?php $this->load->view($theme.'/home/'.$theme.'_sidemenu');?>
            <div class="col-xl-9 col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="widget-title">Projects</h4>
                    </div>
                </div>
                
                <ul class="nav nav-tabs menu-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$theme ?>-project">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$theme ?>-project-bids">Bids</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$theme ?>-project-current/">Current Work</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$theme ?>-project-past/">Past Work</a>
                    </li>
                </ul>
                <div class="row page-data">                    
                    <div class="col-md-12 mt-4 mb-4">                        
                        
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Post a Project</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ================================= post data start ===================================== -->
                        <form method="post" enctype="multipart/form-data" autocomplete="off" id="add_post">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <h6>Name for your project</h6>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="project_name" id="project_name">
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <h6>Tell us more about your project</h6>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <textarea class="form-control" rows="5" name="description" id="description"></textarea>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <h6>Upload Files</h6>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <div class="service-upload" id="remove-after">
                                                        <i class="fa fa-cloud-upload-alt"></i>
                                                        <span>Drag & drop any images or documents.</span>      
                                                        <input type="file" name="files[]" id="files" multiple />
                                                    </div>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <h6>What skills are required?</h6>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <select class="form-control select2" id="skills" name="skills[]" multiple="multiple">
                                                        <?php
                                                            foreach ($skill_list as $skill) {
                                                        ?>
                                                                <option value="<?=$skill['id']?>"><?php echo $skill['name']; ?></option>
                                                        <?php
                                                             } 
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h6>Price</h6>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-2">
                                                    <label class="form-label"><?php echo $user_currency['user_currency_sign']; ?></label>                                                   
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="number" class="form-control" name="price_from" id="price_from" placeholder="from" value="" >
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="number" class="form-control" name="price_to" id="price_to" placeholder="to" value="" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <button type="submit" name="form_submit" value="submit" class="btn login-btn">Submit</button>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- ==================================== post data End ===================================== -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    if (window.File && window.FileList && window.FileReader) {
        $("#files").on("change", function(e) {
            // $("#remove-after").html("");
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
        });
    } else {
        alert("Your browser doesn't support to File API")
    }

    $('#skills').select2({
        placeholder: "Select skills",
    });

    $('#add_post').bootstrapValidator({
        fields: {
            project_name: {
                validators: {
                    notEmpty: {
                        message: 'Please enter project name.'
                    }
                }
            },
            description: {
                validators: {
                    notEmpty: {
                        message: 'Please enter description.'
                    }
                }
            },
            price_from: {
                validators: {
                    digits: {
                        message: 'Please enter valid number'
                    },
                    notEmpty: {
                        message: 'Please enter price'
                    }
                }
            },
            price_to: {
                validators: {
                    digits: {
                        message: 'Please enter valid number'
                    },
                    notEmpty: {
                        message: 'Please enter price'
                    }
                }
            },            
            // 'files[]': {
            //     validators: {
            //         file: {
            //             extension: 'jpeg,png,jpg',
            //             type: 'image/jpeg,image/png,image/jpg',
            //             message: 'The selected file is not valid. Only allowed jpeg,png files'
            //         },
            //         notEmpty: {
            //             message: 'Please upload category image...'
            //         }
            //     }
            // }                    
        }
    }).on('success.form.bv', function(e) {return true;}); 
});
</script>
