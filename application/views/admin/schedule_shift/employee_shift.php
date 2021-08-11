<style type="text/css">
.table thead tr th {
    border: 1px solid rgba(0, 0, 0, 0.05) !important;
}

.table-bordered th, .table-bordered td {
    text-align: center;
}
.table-bordered th {
    width: 10%;
    background-color: #f1f1f1;
}

.date-input {
    text-align: center;
    border-radius: 10rem;
    border: 1px solid #ddd;
    box-shadow: none;
    color: #333;
    font-size: 15px;
    height: 40px;
}
</style>
<div class="page-wrapper">
	<div class="content container-fluid">
            <div class="row mb-4">
                <div class="col-sm-8">
                    <h4 class="page-title m-b-20 m-t-0">Employee Shift</h4>
                </div>
            </div>
            <?php
            if ($this->session->userdata('message')) {
                echo $this->session->userdata('message');
            }
            ?>
            <div class="card pt-4">
                <div class="card-body">
                    <?php $attributes = array('id' => 'formDate');
                    echo form_open('admin/schedule_shift/employee_shift/'.$user_id, $attributes); ?>
                        <div class="row mb-4">
                            <div class="col-md-2"> 
                                <label>From:</label>                                   
                                <input type="text" class="date-input" id="from_date" name="from_date">
                            </div>
                            <div class="col-md-2"> 
                                <label>To:</label>                                     
                                <input type="text" class="date-input" id="to_date" name="to_date">
                            </div>
                            <div class="col-md-7">                                
                            </div>
                            <div class="col-md-1">
                                <button name="form_submit" type="submit" id="search_btn" class="btn btn-primary center-block" value="true" style="display:none;">Search</button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered categories_table">
                            <thead>
                                <tr>
                                    <th style="width:3%;">#</th>
                                    <th>Day</th>
                                    <th>Job</th>
                                    <th>Clock in</th>
                                    <th>Clock out</th>
                                    <th>Total hours</th>
                                    <th>Break hours</th>
                                    <th>Daily Total</th>
                                    <!-- <th>Regular hours</th>
                                    <th>Over time</th> -->
                                </tr>
                            </thead>
                                <?php 
                                    $sno = 1;
                                    foreach ($list as $row) {
                                ?>
                                        <tr>
                                            <td><?php echo $sno ?></td>
                                            <td><?php echo $row['shift_date']; ?></td>
                                            <td><?php echo $row['job']; ?></td>
                                            <td><?php echo $row['clock_in']; ?></td>
                                            <td><?php echo $row['clock_out']; ?></td>
                                            <td><?php echo $row['total_hours']; ?></td>
                                            <td><?php echo $row['break_hours']; ?></td>
                                            <td><?php echo $row['daily_total']; ?></td>
                                        </tr>
                                <?php
                                        $sno ++;
                                    }
                                ?>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
$(document).ready(function(){
    $("#from_date").datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
        todayBtn: 'linked',
        orientation: 'bottom auto',
        onSelect: function(dateText) {
            $(this).change();
        }        
    }).datepicker("setDate", new Date('<?php echo $start_date; ?>')).on("change", function() {
        $('#search_btn').trigger('click');
    });
    $("#to_date").datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
        todayBtn: 'linked',
        orientation: 'bottom auto',
        onSelect: function(dateText) {
            $(this).change();
        }        
    }).datepicker("setDate", new Date('<?php echo $end_date; ?>')).on("change", function() {
        $('#search_btn').trigger('click');
    });
});
</script>

