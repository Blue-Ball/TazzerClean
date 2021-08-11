<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2> <?php echo (!empty($user_language[$user_selected]['lg_about'])) ? $user_language[$user_selected]['lg_about'] : $default_language['en']['lg_about']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-right ml-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href=" <?php echo base_url();?>">
                                <?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php echo (!empty($user_language[$user_selected]['lg_about'])) ? $user_language[$user_selected]['lg_about'] : $default_language['en']['lg_about']; ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
	.about-blk-content {
		text-align: justify;	
	}
	.about-blk-content img {

	}
	.how-work-section {
		margin-top: 50px;
		margin-bottom: 50px;
	}
	.howitworks {
		margin-bottom: 30px;
	}
	.howitworks .title {
		margin-top: 10px;
		margin-bottom: 20px;
		color: #5d2566;
	}
	.video-how-it-works {
		text-align: center;
	}
	.our-team-section {
		margin-top: 10px;
		margin-bottom: 50px;
	}
</style>

<section class="about-us">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-sm-5 col-12">
                    <div class="about-blk-image">
                        <img src="assets/img/aboutus1.8d9172eb.png" class="img-fluid" alt="">
                    </div>
                </div>
                <div class="col-xl-7 col-sm-7 col-12">
                    <div class="about-blk-content">
                        <h4>WE ARE YOUR PARTNER WHO CARES ABOUT YOUR NEEDS</h4>
                        <span> 
							<p class="pclass"> 
								<?php
									$about_us = settingValue("about_us");
									echo $about_us;
								?>
							</p>
						</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="dash-widget-header">

                                        <div class="dash-widget-info2">

                                            <h6 class="text-muted">We're Expertise</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="dash-widget-header">

                                        <div class="dash-widget-info2">
                                            <h6 class="text-muted">100% Satisfaction Guaranteed</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="dash-widget-header">

                                        <div class="dash-widget-info2">
                                            <h6 class="text-muted">Experienced & Reliable</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="dash-widget-header">

                                        <div class="dash-widget-info2">
                                            <h6 class="text-muted">Subscription</h6>
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
</section>

<section class="how-work-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="howitworks">
                    <h4 class="title">WE ARE QUALIFIED & EXPERIENCED INDUSRTY WITH CLEANING AND HANDYMAN SERVICES</h4>
                    <span style="color: #0a0a0a;">We can assist you in reaching your target audience and covering any demographics you desire. </br>
					As a result, we understand how to assist your business in succeeding. </br>
					Allow us to do all of the legwork for you, saving you time and money.
					</span>
                </div>

                <div class="video-how-it-works">
                    <iframe data-v-60e7013d="" style="margin-left: auto;" height="500" width="80%" src="https://www.youtube.com/embed/cF9BeGtxXGk" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="allowfullscreen" class="videoTag"></iframe>
                </div>

            </div>
        </div>
    </div>
</section>
<section class="call-us">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <span></span>
                <h4 class="pclass" style="text-align: justify;">Get your business on the map with our app & extend your business.</h4>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-1"></div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 call-us-btn">
                <a href="contact" class="btn btn-call-us">Join US</a>
            </div>
        </div>
    </div>
</section>
<section class="our-team-section">
    <div class="">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading howitworks container">
                    <h2 class="title">OUR TEAM</h2>
                    <span>Tazzer Group is a minority owned business with a large group of specially trained, dedicated employees who provide professional service with a personal touch.</span>
                </div>
                <!-- <div class="ml-5"> -->
                <div class="row">
                    <!-- <div class="col-lg-2">
						<div class="howwork">
							<div class="">
								<img width="225px" height="225px" src="assets/img/imgpsh_fullsize_animneww.png" style="border-radius: 50%;" alt="">
							</div>
							<h3 style="">Rose W.</h3>
						</div>
					</div> -->
                    <div class="col-lg-3 col-md-6">
                        <div class="howwork">
                            <div class="">
                                <img width="225px" height="225px" src="assets/img/imgpsh_fullsize_anim11.png" style="border-radius: 50%;" alt="">
                            </div>
                            <h3 style="">Aaron W.</h3>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="howwork">
                            <div class="">
                                <img width="225px" height="225px" src="assets/img/imgpsh_fullsize_anim (1)neww.png" style="border-radius: 50%;" alt="">
                            </div>
                            <h3 style="">Roselyn N.</h3>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="howwork">
                            <div class="">
                                <img width="225px" height="225px" src="assets/img/imgpsh_fullsize_anim (2)neww.png" style="border-radius: 50%;" alt="">
                            </div>
                            <h3 style="">Mahadi H.</h3>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="howwork">
                            <div class="">
                                <img width="225px" height="225px" src="assets/img/imgpsh_fullsize_anim (2).png" style="border-radius: 50%;" alt="">
                            </div>
                            <h3 style="">Duncan E.</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>