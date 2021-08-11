
					<?php
                    $this->session->flashdata('success_message');
                    if (!empty($services)) {
                        foreach ($services as $srows) {                          
                    ?>
                            <div class="col-lg-4 col-md-6 inactive-service">
                                <div class="service-widget">
                                    <div class="service-img">
                                        <a href="<?php echo base_url() . 'service-preview/' . str_replace($GLOBALS['specials']['src'], $GLOBALS['specials']['des'], $srows['service_title']) . '?sid=' . md5($srows['id']); ?>">
                                            <img class="img-fluid serv-img" alt="Service Image" src="<?php echo base_url() . $srows['si_image']; ?>">
                                        </a>
                                    </div>
                                    <div class="service-content">
                                        <h3 class="title">
                                            <a href="<?php echo base_url() . 'service-preview/' . str_replace($GLOBALS['specials']['src'], $GLOBALS['specials']['des'], $srows['service_title']) . '?sid=' . md5($srows['id']); ?>"><?php echo $srows['service_title']; ?></a>
                                        </h3>
                                        <div class="rating">

                                            <?php
                                            for ($x = 1; $x <= $srows['rr_avg_rating']; $x++) {
                                                echo '<i class="fa fa-star filled"></i>';
                                            }
                                            if (strpos($srows['rr_avg_rating'], '.')) {
                                                echo '<i class="fa fa-star"></i>';
                                                $x++;
                                            }
                                            while ($x <= 5) {
                                                echo '<i class="fa fa-star"></i>';
                                                $x++;
                                            }
                                            ?>
                                            <span class="d-inline-block average-rating">(<?php echo $srows['rr_avg_rating'] ?>)</span>
                                        </div>                                        										
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
                    if (!empty($services)) {
                        echo $this->ajax_pagination->create_links();
                    }
                    ?>