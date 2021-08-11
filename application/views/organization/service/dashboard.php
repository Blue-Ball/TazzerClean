<?php
//$data = $this->session->userdata('data_employee');
?>
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
    .card-body-bg{
        background: #daefff;
    }
    .card-header-bg{
        background: #219af4;
    }

    .border-radius-top{
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        -moz-border-radius-topleft: 5px;
        -moz-border-radius-topright: 5px;
    }
    .border-radius-bottom{
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        -moz-border-radius-bottomleft: 5px;
        -moz-border-radius-bottomright: 5px;
    }
    .card-body{
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
    .custom-card-header{
        display: flex;
        justify-content: space-between;
        padding: 20px;
        align-items: center;
        margin-bottom: 30px;

    }
    .border-bottom-white{
        border-bottom: solid 1px white;
    }
</style>
<div class="content">
	<div class="container">
		<div class="row">
			 <?php $this->load->view('organization/home/organization_sidemenu');?>
			<div class="col-xl-9 col-md-8">
                <div class="row">
                    <div class="col-lg-4">
                        <div class=" widget-card">
                            <a href="<?php echo base_url()?>organization-orders/view" class=" custom-card-header border-radius-top card-header-bg">
                                <span class="dash-widget-icon"><?php echo $total?></span>
                                <div class="dash-widget-info">
                                    <span>Orders</span>
                                </div>
                            </a>
                            <div class="card-body border-radius-bottom card-body-bg">
                            <a href='<?php echo base_url()."organization-orders/missing" ?>'>
                                <?php if($reject_in_process>0) { ?>
                                <div>
                                    <span class="font-small" style='color:red' >Missing Orders </span>
                                    <span class="font-small" style='color:red;' ><?php echo $reject_in_process?></span>
                                </div></a>
                                <?php } ?>

                                <a href='<?php echo base_url()."organization-orders/1" ?>'>
                                <div>
                                    <span class="font-small">Pending Orders </span>
                                    <span class="font-small"><?php echo $pending?></span>
                                </div></a>
                                <a href='<?php echo base_url()."organization-orders/2" ?>'>
                                <div>
                                    <span class="font-small">In Progress Orders</span>
                                    <span class="font-small"><?php echo $progress?></span>
                                </div></a>
                                <a href='<?php echo base_url()."organization-orders/3" ?>'>
                                <div>
                                    <span class="font-small">Complete Request Orders</span>
                                    <span class="font-small"><?php echo $complete_request?></span>
                                </div></a>
                                <a href='<?php echo base_url()."organization-orders/4" ?>'>
                                <div>
                                    <span class="font-small">Accepted Orders</span>
                                    <span class="font-small"><?php echo $accepted?></span>
                                </div></a>
                                <a href='<?php echo base_url()."organization-orders/5" ?>'>
                                <div>
                                    <span class="font-small">Reject Orders</span>
                                    <span class="font-small"><?php echo $reject?></span>
                                </div></a>
                                <a href='<?php echo base_url()."organization-orders/6" ?>'>
                                <div>
                                    <span class="font-small">Cancelled Orders</span>
                                    <span class="font-small"><?php echo $cancelled?></span>
                                </div></a>
                                <a href='<?php echo base_url()."organization-orders/7" ?>'>
                                <div>
                                    <span class="font-small">Complete Orders</span>
                                    <span class="font-small"><?php echo $complete?></span>
                                </div></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class=" widget-card">
                            <a href="<?php echo base_url()?>user-reviews" class=" custom-card-header border-radius-top dash-bg-2">
                                <span class="dash-widget-icon"><?php echo $reviews_count?></span>
                                <div class="dash-widget-info">
                                    <span>Reviews</span>
                                </div>
                            </a>
                            <div class="card-body border-radius-bottom card-body-bg">
                                <?php
                                if($reviews_count > 0){
                                    $count = 0;
                                    foreach($reviews as $value){
                                        if($count > 6){
                                            echo "..."; continue;
                                        }
                                        else{
                                            ?>
                                            <div>
                                                <span class="font-small"><?php echo $value['review']?></span>
                                                <!-- <span class="font-small">--><?php //echo $value['reviewer_name']?><!--</span>-->
                                            </div>
                                        <?php }
                                        $count ++;
                                    } }?>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class=" widget-card">
                            <a href="<?php echo base_url()?>notification-list" class="custom-card-header border-radius-top card-header-bg">
                                <span class="dash-widget-icon"><?php echo $n_count?></span>
                                <div class="dash-widget-info">
                                    <span>Notification</span>
                                </div>
                            </a>
                            <div class="card-body border-radius-bottom card-body-bg">
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

                <div class=" widget-card p-3">
                    <div class="card text-white bg-info border-radius-top border-radius-bottom">
                        <div class="p-3">
                            <button type="button" class="btn btn-transparent float-right">
                                <img src="<?=base_url('assets/img/dollar-solid.svg')?>" width="36px">
                            </button>
                            <h4 class="amount">0</h4>
                            <p>Total Amount</p>
                        </div>
                        <div class="chart-wrapper p-3 card-body" style="min-height:210px;">
                            <canvas id="chartAmount" class="chart"></canvas>
                        </div>
                    </div>
                </div>


                <form id='ststuschange' >
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="selStatus"><span class="form-control font-small"
                                    style="cursor:pointer; border:none; padding-top:0.8rem"
                                    >Do you change the Status?</span></label>
                        </div>
                        <div class="col-lg-6">
                            <select class="form-control" id="selStatus" name="ChanegStatus">
                                <option value=0>No</option>
                                <option value=1>Sign out</option>
                                <option value=3>Temporarily Remove</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <button id="submit_status" type="submit" class="login-btn btn">Submit</button>
                        </div>
                    </div>
                </form>

			</div>
		</div>
	</div>
</div>
<script src="<?=base_url('assets/plugins/chartjs/Chart.bundle.js');?>"></script>
<script src="<?=base_url('assets/plugins/chartjs/utils.js');?>"></script>
<script src="<?=base_url('assets/plugins/numeral/numeral.min.js');?>"></script>
<script>
    $('#ststuschange').submit( function(e) {
        event.preventDefault();
        $.confirm({
            title: 'Confirmations..!',
            content: 'Do you want continue on this proccess..',
            buttons: {
            confirm: function() {
                $.ajax({
                url: base_url + "update_status_organization",
                data: {
                    'booking_id': bookid,
                    'status': status,
                    'review': review,
                    'csrf_token_name': csrf_token
                },
                type: 'POST',
                dataType: 'JSON',
                success: function(response) {

                    if (response == '3') { // session expiry
                    swal({
                        title: "Session was Expired... !",
                        text: "Session Was Expired ..",
                        icon: "error",
                        button: "okay",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                    }).then(function() {
                        window.location.reload();
                    });
                    }

                    if (response == '2') { //not updated
                    swal({
                        title: "Somethings wrong !",
                        text: "Somethings wents to wrongs",
                        icon: "error",
                        button: "okay",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                    }).then(function() {
                        window.location.reload();
                    });
                    }

                    if (response == '1') { //not updated
                    swal({
                        title: "Updated the booking status !",
                        text: "Service is Updated successfully...",
                        icon: "success",
                        button: "okay",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                    }).then(function() {
                        $('#update_div' + rowid).hide();
                        window.location.reload();
                    });
                    }


                }
                })
            },
            cancel: function() {

            },
            }
        });
        });
    $(document).ready(function() {
        setFemaleEmp(<?php echo $revenue?>);
    });
    var cardOpt = {
        maintainAspectRatio: false,
        legend: {
            display: false
        },
        scales: {
            xAxes: [{
                gridLines: {
                    color: 'transparent',
                    zeroLineColor: 'transparent'
                },
                ticks: {
                    fontSize: 1,
                    fontColor: 'transparent'
                }

            }],
            yAxes: [{
                display: false,
                ticks: {
                    display: false
                }
            }]
        },
        elements: {
            line: {
                tension: 0.00001,
                borderWidth: 1
            },
            point: {
                radius: 4,
                hitRadius: 10,
                hoverRadius: 4
            }
        }
    };

    function setFemaleEmp(obj){
        console.log(obj);
        var Data = [];
        var Labels = [];
        var Total = 0;
        $.each(obj, function(key, data){
            Data.push(data.amount);
            Labels.push(data.re_date);
            Total = Number(Total) + Number(data.amount);
        });

        $(".amount").html(numeral(Total).format('0,0'));
        // Prepare Json data End

        // Prepare Chart Start
        var config = {
            type: 'bar',
            data: {
                datasets: [
                    {
                        type: 'line',
                        label: 'Amount/Month',
                        backgroundColor: $.brandDanger,
                        borderColor: 'rgba(255,255,255,.55)',
                        pointBackgroundColor: $.brandDanger,
                        data: Data,
                        fill: false,
                        borderWidth: 1
                    }
                ],
                labels:Labels
            },
            options: cardOpt
        };
        var chartAmount = $('#chartAmount').get(0).getContext('2d');
//        if(typeof chartAmount != 'undefined' ){
//            chartAmount.destroy();
//        }
        chartAmount = new Chart(chartAmount, config);
        // Prepare Chart End
    }
</script>