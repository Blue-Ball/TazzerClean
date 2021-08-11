<?php 
	
?>
<div class="breadcrumb-bar">
	 <div class="container">
		 <div class="row">
			 <div class="col">
				 <div class="breadcrumb-title">
					<h2>Blog</h2>
				</div>
			</div>
			 <div class="col-auto float-right ml-auto breadcrumb-menu"> 
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href=" <?php echo base_url();?>"> <?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page">Blog</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>

<main class="v-main blog" data-booted="true" style="padding: 0px;">
    <div class="v-main__wrap">
        <div class="container container--fluid" style="padding: 0px !important;">
            <div class="container mx-auto container--fluid" style="padding: 0px !important;">
                <div class="container container--fluid" style="width: 80%; background: rgb(231, 231, 231); margin-top: 20px;">
                    <div class="row row--dense">
						<?php
							if (count($blogList) > 0) {
								foreach ($blogList as $key => $blog) {
									# code...
								?>
						<div class="col-sm-6 col-md-6 col-lg-6 col-12">
					        <div class="v-card v-sheet theme--light elevation-0">
					            <div class="v-image v-responsive theme--light" reverse-transition="fade-transition" style="height: 250px;">
					                <div class="v-responsive__sizer" style="padding-bottom: 73.9612%;"></div>
					                <div class="v-image__image v-image__image--cover" style="background-image: url(&quot;<?=base_url().$blog['image']?>&quot;); background-position: center center;"></div>
					                <div class="v-responsive__content" style="width: 361px;"></div>
					            </div>
					            <div tabindex="-1" class="v-list-item theme--light">
					                <div class="v-list-item__content">
					                    <div class="v-list-item__title" style="font-size: 18px;"><i aria-hidden="true" class="v-icon notranslate fa fa-user theme--light" style="font-size: 24px; color: rgb(37, 203, 242); caret-color: rgb(37, 203, 242); margin-right: 20px;"></i><?=$blog['author']?></div>
					                </div>
					                <div class="v-list-item__content" style="margin-left: -40%;">
					                    <div class="v-list-item__title" style="font-size: 18px;"><i aria-hidden="true" class="v-icon notranslate fa fa-clock-o theme--light" style="font-size: 24px; color: rgb(37, 203, 242); caret-color: rgb(37, 203, 242); margin-right: 20px;"></i><?=date("M d, Y", strtotime($blog['created_at']))?></div>
					                </div>
					            </div>
					            <div class="v-card__title" style="font-size: 24px;"><?=$blog['title']?></div>
					            <div class="v-card__text">
					                <div class="row mx-0 align-center">
					                    <div class="v-rating v-rating--readonly v-rating--dense"><button type="button" tabindex="-1" class="v-icon notranslate v-icon--link  fa fa-star theme--light amber--text" style="font-size: 18px;"></button><button type="button" tabindex="-1" class="v-icon notranslate v-icon--link  fa fa-star theme--light amber--text" style="font-size: 18px;"></button><button type="button" tabindex="-1" class="v-icon notranslate v-icon--link  fa fa-star theme--light amber--text" style="font-size: 18px;"></button><button type="button" tabindex="-1" class="v-icon notranslate v-icon--link  fa fa-star theme--light amber--text" style="font-size: 18px;"></button><button type="button" tabindex="-1" class="v-icon notranslate v-icon--link  fa fa-star theme--light amber--text" style="font-size: 18px;"></button></div>
					                </div>
					                <div style="margin-top: 12px;"><?=substr(strip_tags($blog['content']), 0, 150).(strlen($blog['content']) > 150?"...":"")?></div>
					            </div>
					            <div class="v-card__actions">
									<a href="<?=base_url()."blog-detail/".$blog['id']?>" type="button" class="v-btn theme--light elevation-0 v-size--default"><span class="v-btn__content">Read More </span></a>
								</div>
					        </div>
					    </div>
								<?php
								}
							}
						?>
					    
					</div>
                </div>
                <!-- <div class="text-center">
                    <nav role="navigation" aria-label="Pagination Navigation">
                        <ul class="v-pagination theme--light">
                            <li><button type="button" aria-label="Previous page" class="v-pagination__navigation v-pagination__navigation--disabled"><i aria-hidden="true" class="v-icon notranslate fa fa-chevron-left theme--light"></i></button></li>
                            <li><button type="button" aria-current="true" aria-label="Current Page, Page 1" class="v-pagination__item v-pagination__item--active primary">1</button></li>
                            <li><button type="button" aria-label="Goto Page 2" class="v-pagination__item">2</button></li>
                            <li><button type="button" aria-label="Goto Page 3" class="v-pagination__item">3</button></li>
                            <li><button type="button" aria-label="Next page" class="v-pagination__navigation"><i aria-hidden="true" class="v-icon notranslate fa fa-chevron-right theme--light"></i></button></li>
                        </ul>
                    </nav>
                </div><br> -->
            </div>
        </div>
    </div>
</main>