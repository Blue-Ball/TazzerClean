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
.card-body {
    padding-top: 0px;
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
                    <div class="col-md-6 content-right">
                        <a href="<?php echo base_url().$theme ?>-project-post" class="btn btn-primary" style="height:37px !important;">Post a Project</a>
                    </div>
                </div>
                
                <ul class="nav nav-tabs menu-tabs">
                    <li class="nav-item active">
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
                                    <div class="col-md-10">
                                        <label>Top Result. Showing 1~20 of 6410 results</label>
                                    </div>
                                    <div class="col-md-2 content-right">
                                        <select class="form-control-sm custom-select searchFilterOrder" id="sort_by" name="sort_by">
                                            <option value='1'>Latest</option>
                                            <option value='2'>Oldest</option>            
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="dataList">
                        <!-- ================================= dataList start ===================================== -->
                            <?php
                                $this->session->flashdata('success_message');
                                if (!empty($project_list)) {
                                    foreach ($project_list as $row) {                            
                            ?>
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <a href="<?php echo base_url().$theme ?>-project-detail/<?=$row['id']?>" class="pro-title-font"><?php echo $row['name']; ?></a>
                                                    </div>
                                                    <div class="col-md-3 ">
                                                        <div class="row content-right">
                                                            <span>
                                                                <?php echo $row['currency_sign']; ?>
                                                                <?php echo $row['price_from']." - ".$row['price_to']." "; ?>
                                                                <?php echo $row['currency_code']; ?>
                                                            </span>
                                                        </div>
                                                        <div class="row content-right">
                                                            <span>
                                                                <?php
                                                                    // $post_date = "";
                                                                    $created_at = '-';
                                                                    if (isset($row['created_at'])) {
                                                                        if (!empty($row['created_at']) && $row['created_at'] != "0000-00-00 00:00:00") {
                                                                            $created_at = '<span >' . date('Y-m-d h:i:s', strtotime($row['created_at'])) . '</span>';
                                                                        }
                                                                    }
                                                                    echo $created_at;
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <p class="three-line-ellipsis">
                                                            <?php echo $row['description']; ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <?php
                                                            $i=0;
                                                            $req_skill_list = explode(",", $row['skills']); 
                                                            for($i=0;$i<(count($req_skill_list)-1);$i++){
                                                        ?>
                                                                <label>
                                                                    <?php echo $skill_list[$req_skill_list[$i]]; ?>
                                                                </label> 
                                                                <i class="fa fa-ellipsis-v" style="margin:0 5px;"></i>
                                                        <?php
                                                            }
                                                        ?>
                                                        <label><?php echo $skill_list[$req_skill_list[$i]]; ?></label>
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="review-count">
                                                            <div class="rating">
                                                                <i class="fa fa-star filled"></i>
                                                                <i class="fa fa-star filled"></i>
                                                                <i class="fa fa-star filled"></i>
                                                                <i class="fa fa-star"></i>
                                                                <i class="fa fa-star"></i>
                                                                <span class="d-inline-block average-rating">(3)</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <i class="fa fa-list-ul" aria-hidden="true"></i>
                                                        5 Bids
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                            </div>
                                        </div>
                            <?php 
                                    }
                                } else {
                                    echo '<div class="col-xl-12 col-lg-12">No Services Found</div>';
                                }
                            ?>
                            <!-- Pagination Links -->
                            <?php
                                if (!empty($project_list)) {
                                    echo $this->ajax_pagination->create_links();
                                }
                            ?>
                        <!-- ==================================== dataList End ===================================== -->
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


