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
.card-header {
    border-bottom: solid 3px #f3f3f3 !important;
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
.round-border {
    text-align: center;
    border-radius: 10rem;
    display: inline-block;
    border: 1px solid #b1b1b1;
    padding: .25rem .75rem;
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
</style>
<div class="content">
    <div class="container">
        <div class="row">
            <?php $this->load->view($theme.'/home/'.$theme.'_sidemenu');?>
            <div class="col-xl-9 col-md-8">
                <h4 class="widget-title">Project</h4>
                <ul class="nav nav-tabs menu-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$theme ?>-project-detail/1">Details</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo base_url().$theme ?>-project-proposals/1">Proposals</a>
                    </li>
                </ul>
                <div class="row page-data"> 
                    <!-- ============================= Project Info =============================== -->
                    <div class="col-md-9 mt-4 mb-4">
                        <!-- ----------------------------- project detail ----------------------------- -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <img class="avatar-sm rounded mr-1" src="<?php echo base_url().'assets/img/user.jpg'; ?>">
                                        <span>@name</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="rating">
                                            <i class="fa fa-star filled"></i>
                                            <i class="fa fa-star filled"></i>
                                            <i class="fa fa-star filled"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <span class="d-inline-block average-rating">3.0(351 reviews)</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <span><i class="fa fa-circle-o-notch" aria-hidden="true"></i> 98%</span>
                                    </div>
                                    <div class="col-md-4 content-right">
                                        <span>Replies with in a few hours</span>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                        
                    </div>
                    <!-- ============================= Client Info =============================== -->
                    <div class="col-md-3 mt-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="title-3">Budget</p>
                                    </div>
                                </div>                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>$300</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="title-3">Bids</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>6</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                            
                </div>
            </div>
        </div>
    </div>
</div>


