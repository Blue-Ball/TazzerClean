<?php
    // print_r($category);
?>

<section class="top-section">
    <div class="layer">
        <div class="top-banner"></div>  
        <div class="top-mask"></div>  
        <div class="top-content">
            <div class="row">
                <div class="col-12">
                    <h2>Services</h2>
                </div>
            </div>
            <div class="row align-center justify-center"></div>
        </div>

        <div class="container search-block">
            <div class="row">          
                <div class="col-lg-10 con">
                    <div class="section-search">
                        <div class="search-box">
                            <form action="<?php echo base_url(); ?>search" id="search_service" method="post">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="search-input line"  style="width:100%;">
                                            <i class="fa fa-tv bficon"></i>
                                            <div class="form-group mb-0">
                                                <input type="text" class="form-control common_search" name="common_search" id="search-blk" placeholder="Please type what you want here" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="search-input"  style="width:100%;">
                                            <i class="fa fa-location-arrow bficon"></i>
                                            <div class="form-group mb-0">
                                                <input type="text" class="form-control" value="" name="user_address" id="user_address" placeholder="Postcode / Zipcode">
                                                <input type="hidden" value="" name="user_latitude" id="user_latitude">
                                                <input type="hidden" value="" name="user_longitude" id="user_longitude">
                                                <a class="current-loc-icon current_location" data-id="1" href="javascript:void(0);"><i class="fa fa-crosshairs"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="search-btn">
                                            <button class="btn search_service" name="search" value="search"  type="button"><?php echo (!empty($user_language[$user_selected]['lg_search'])) ? $user_language[$user_selected]['lg_search'] : $default_language['en']['lg_search']; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="search-cat">
                            <i class="fa fa-circle"></i>
                            <span><?php echo (!empty($user_language[$user_selected]['lg_popular_search'])) ? $user_language[$user_selected]['lg_popular_search'] : $default_language['en']['lg_popular_search']; ?></span>
                            <?php foreach ($popular as $popular_services) { ?>
                                <a href="<?php echo base_url() . 'service-preview/' . str_replace($GLOBALS['specials']['src'], $GLOBALS['specials']['des'], $popular_services['service_title']) . '?sid=' . md5($popular_services['id']); ?>">
                                    <?php echo $popular_services['service_title'] ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="service-list-section">
    <div class="header-title">
        <h3>Services Detail</h3>
    </div>

    <div class="service-block row">
        <?php 
        foreach($category as $cate) {
            $categoryName = $cate['category_name'];
            $categoryImage = $cate['category_image'];
            $icon = $cate['icon'];
            $description = $cate['description'];
            $id = $cate['id'];
            ?>
            <div link="<?php echo base_url().'service-category-detail/'.$id;?>" data-id="<?=$id?>" class="service-box">
                <div>
                    <img class="service-img" alt="<?=$categoryName?>" src="<?php echo base_url().$icon;?>" transition="scale-transition">
                </div>
                <div class="service-title">
                    <h3><?=$categoryName?></h3>
                </div>
                <div class="service-description">
                    <span><?=$description?></span>
                </div>
            </div>
            <?php
        }
        ?>
        <!-- <div link="<?php echo base_url();?>" class="service-box">
            <div>
                <img class="service-img light-blue" alt="Cleaning Services" src="<?php echo base_url();?>assets/img/services/1.png" transition="scale-transition">
            </div>
            <div class="service-title">
                <h3>Cleaning Services</h3>
            </div>
            <div class="service-description">
                <span>The objective of cleaning is not just to clean, but to feel happiness living within that environment.</span>
            </div>
        </div>
        <div link="<?php echo base_url();?>" class="service-box">
            <div>
                <img class="service-img purple" alt="Clearance And Rubbish Removal Services" src="<?php echo base_url();?>assets/img/services/2.png" transition="scale-transition">
            </div>
            <div class="service-title">
                <h3>Clearance And Rubbish Removal Services</h3>
            </div>
            <div class="service-description">
                <span>Put your office, home, and garage clearance in your hands. We also do removal of rubbish & goods as well as transfers of all types.</span>
            </div>
        </div>
        <div link="<?php echo base_url();?>" class="service-box">
            <div>
                <img class="service-img light-green" alt="Domestic Helpers Services" src="<?php echo base_url();?>assets/img/services/3.png" transition="scale-transition">
            </div>
            <div class="service-title">
                <h3>Domestic Helpers Services</h3>
            </div>
            <div class="service-description">
                <span>This service would support you with lots of things around your home such as shopping, completing paperwork & many others.</span>
            </div>
        </div>
        <div link="<?php echo base_url();?>" class="service-box">
            <div>
                <img class="service-img blue" alt="Property And Facilities Management Services" src="<?php echo base_url();?>assets/img/services/4.png" transition="scale-transition">
            </div>
            <div class="service-title">
                <h3>Property And Facilities Management Services</h3>
            </div>
            <div class="service-description">
                <span>Every Day we help many different people with many different things.</span>
            </div>
        </div>
        <div link="<?php echo base_url();?>" class="service-box">
            <div>
                <img class="service-img pink" alt="Gardening And Landscaping Services" src="<?php echo base_url();?>assets/img/services/5.png" transition="scale-transition">
            </div>
            <div class="service-title">
                <h3>Gardening And Landscaping Services</h3>
            </div>
            <div class="service-description">
                <span>Maintenance comes with the job of gardening.</span>
            </div>
        </div>
        <div link="<?php echo base_url();?>" class="service-box">
            <div>
                <img class="service-img light-blue" alt="Handyman Services" src="<?php echo base_url();?>assets/img/services/6.png" transition="scale-transition">
            </div>
            <div class="service-title">
                <h3>Handyman Services</h3>
            </div>
            <div class="service-description">
                <span>Anything broke we can fix it, No job is too big.</span>
            </div>
        </div>
        <div link="<?php echo base_url();?>" class="service-box">
            <div>
                <img class="service-img purple" alt="Dog Walking And Pet Services" src="<?php echo base_url();?>assets/img/services/7.png" transition="scale-transition">
            </div>
            <div class="service-title">
                <h3>Dog Walking And Pet Services</h3>
            </div>
            <div class="service-description">
                <span>You don’t need to strain or worry about your pets anymore just get to our platform & look for someone to help you with it.</span>
            </div>
        </div>
        <div link="<?php echo base_url();?>" class="service-box">
            <div>
                <img class="service-img light-green" alt="Scaffolding And Netting Services" src="<?php echo base_url();?>assets/img/services/8.png" transition="scale-transition">
            </div>
            <div class="service-title">
                <h3>Scaffolding And Netting Services</h3>
            </div>
            <div class="service-description">
                <span>We offer both commercial & Private services therefore do not hesitate to give us a call or book through our platform.</span>
            </div>
        </div>
        <div link="<?php echo base_url();?>" class="service-box">
            <div>
                <img class="service-img blue" alt="Security Services" src="<?php echo base_url();?>assets/img/services/9.png" transition="scale-transition">
            </div>
            <div class="service-title">
                <h3>Security Services</h3>
            </div>
            <div class="service-description">
                <span>Our security men and women are well trained & know our customer expectations as well. we also cover Locksmith & Fire Safety.</span>
            </div>
        </div>
        <div link="<?php echo base_url();?>" class="service-box">
            <div>
                <img class="service-img pink" alt="Construction And Builders Services" src="<?php echo base_url();?>assets/img/services/10.png" transition="scale-transition">
            </div>
            <div class="service-title">
                <h3>Construction And Builders Services</h3>
            </div>
            <div class="service-description">
                <span>Put any of your construction work into the hand of our tradesmen and women and you won't be disappointed.</span>
            </div>
        </div> -->
    </div>
</section>

<section class="bottom-section">
    <div class="layer">
        <div class="bottom-banner"></div>  
        <div class="bottom-mask"></div>  
        <div class="bottom-content">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <h3>Over 200+ companies are already using Tazzer</h3>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <a href="<?php echo base_url(); ?>all-services" class="bottom-button">Book Now</a>
                </div>
            </div>
            <div class="row align-center justify-center"></div>
        </div>
    </div>
</section>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/service_categories/index.css?v1.04">
<script src="<?php echo base_url(); ?>assets/js/service_categories/index.js"></script>