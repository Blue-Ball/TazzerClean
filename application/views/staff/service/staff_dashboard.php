
<style>
	.font-small{
		font-size: small !important;
	}
    .widget-card{
        margin-bottom: 30px;
    }
    .widget-card a{
        margin-bottom: 0px !important;
    }
    .dash-bg-2{
        background: #292929 !important;
    }
    .card-body{
        background: #daefff;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        -moz-border-radius-bottomleft: 10px;
        -moz-border-radius-bottomright: 10px;
        border-top: solid 2px white;
        padding: 20px;
        min-height: 245px;
    }
    .card-body div{
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px;
    }
    .card-header{
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        -moz-border-radius-topleft: 10px;
        -moz-border-radius-topright: 10px;
        display: flex;
        justify-content: space-between;
        padding: 20px;
        align-items: center;
        margin-bottom: 30px;
        background: #219af4;
    }
    .border-bottom-white{
        border-bottom: solid 1px white;
    }
</style>
<div class="content">
	<div class="container">
		<div class="row">
		
			 <?php $this->load->view('staff/home/staff_sidemenu');?>
				
			<div class="col-xl-9 col-md-8">
				<div class="row">
					<div class="col-lg-4">
                        <div class=" widget-card">
                            <a href="<?php echo base_url()?>employee-orders/view" class=" card-header">
                                <span class="dash-widget-icon"><?php echo $total?></span>
                                <div class="dash-widget-info">
                                    <span>Orders</span>
                                </div>
                            </a>
                            <div class="card-body">
                                <div>
                                    <span class="font-small">Pending Orders </span>
                                    <span class="font-small"><?php echo $pending?></span>
                                </div>
                                <div>
                                    <span class="font-small">In Progress Orders</span>
                                    <span class="font-small"><?php echo $progress?></span>
                                </div>
                                <div>
                                    <span class="font-small">Accepted Orders</span>
                                    <span class="font-small"><?php echo $accepted?></span>
                                </div>
                                <div>
                                    <span class="font-small">Reject Orders</span>
                                    <span class="font-small"><?php echo $reject?></span>
                                </div>
                                <div>
                                    <span class="font-small">Cancelled Orders</span>
                                    <span class="font-small"><?php echo $cancelled?></span>
                                </div>
                                <div>
                                    <span class="font-small">Complete Orders</span>
                                    <span class="font-small"><?php echo $complete?></span>
                                </div>
                            </div>
                        </div>
					</div>
					
					<div class="col-lg-4">
                        <div class=" widget-card">
                            <a href="<?php echo base_url()?>notification-list" class=" card-header dash-bg-2">
                                <span class="dash-widget-icon"><?php echo $n_count?></span>
                                <div class="dash-widget-info">
                                    <span>Notification</span>
                                </div>
                            </a>
                            <div class="card-body">
                                <?php
                                if($n_count > 0){
                                    $count = 0;
                                    foreach ($notification as $value) {
                                        if ($count > 6) {
                                            echo "...";
                                            continue;
                                        } else {
                                            ?>
                                            <div>
                                                <span class="font-small"><?php echo $value['message'] ?></span>
                                            </div>
                                        <?php }
                                        $count++;
                                    }
                                }
                                else{
                                ?>
                                <div>
                                    <span class="font-small">No Notification</span>
                                </div>
                                <?php }?>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
