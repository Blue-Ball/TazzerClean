<style type="text/css">
.table-bordered {
    border: 4px solid rgba(0, 0, 0, 0.05) !important;
}
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

.week {
  text-decoration: none;
  display: inline-block;
  padding: 7px 16px;
}

.user-link:hover {
    background-color: #f1f1f1;
    cursor:pointer;
}

.week:hover {
    background-color: #ddd;
    color: black;
}

.previous {
    background-color: #2acaf5;
    color: black;
    border-top-left-radius: 10rem;
    border-bottom-left-radius: 10rem;
}

.next {
    background-color: #4CAF50;
    color: white;
    border-top-right-radius: 10rem;
    border-bottom-right-radius: 10rem;
}

.date-input {
    text-align: center;
    border-radius: 10rem;
}


</style>
<div class="page-wrapper">
	<div class="content container-fluid">
            <div class="row mb-4">
                <div class="col-sm-8">
                    <h4 class="page-title m-b-20 m-t-0">Employee Schedule</h4>
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
                    echo form_open('admin/schedule_shift/index', $attributes); ?>
                        <div class="row mb-4">
                            <div class="col-md-2">                                    
                                <input type="text" class="form-control date-input" id="selected_date" name="selected_date">
                            </div>
                            <div class="col-md-4">
                                <a href="#" class="week previous" onclick="changed_week('prev');">Prev Week</a>
                                <a href="#" class="week next" onclick="changed_week('next');">Next Week</a>
                                <a class="btn btn-primary date-input" style="color:#fff;" onclick="showClockedToday()">Clocked in Today</a>
                            </div>
                            <div class="col-md-1">
                                <button name="form_submit" type="submit" id="search_btn" class="btn btn-primary center-block" value="true" style="display:none;">Search</button>
                            </div>
                            <div class="col-md-5" style="display:block;text-align:end;">
                                <div class="mt-2">
                                    <label class="badge badge-dark">Pending</label>
                                    <label class="badge badge-info">Inprogress</label>
                                    <label class="badge badge-primary">Provider Completed</label>
                                    <label class="badge badge-muted">Accepted</label>
                                    <label class="badge badge-warning">Rejected</label>
                                    <label class="badge badge-success">Completed</label>
                                    <label class="badge badge-danger">Cancelled</label>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                    <div class="row ml-2">
                                
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Employees</th>
                                    <th>Mon <?php echo $week_days[0]; ?></th>
                                    <th>Tue <?php echo $week_days[1]; ?></th>
                                    <th>Wed <?php echo $week_days[2]; ?></th>
                                    <th>Thu <?php echo $week_days[3]; ?></th>
                                    <th>Fri <?php echo $week_days[4]; ?></th>
                                    <th>Sat <?php echo $week_days[5]; ?></th>
                                    <th>Sun <?php echo $week_days[6]; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($list as $employee) {
                                        $profile_img = 'assets/img/user.jpg';
                                        if (!empty($employee['profile_img'])){                                            
                                            $profile_img = $employee['profile_img'];
                                        }
                                ?>
                                        <tr>
                                            <td class="user-link" style="text-align:left;" onclick="showEmployeeShift('<?php echo $employee['id'];?>');"><div class="mt-3" ><img class="avatar-sm rounded mr-1" src="<?php echo base_url().$profile_img; ?>"><?php echo $employee['name']; ?></div></td>                                        
                                            <?php
                                                for($i=0;$i<7;$i++){
                                            ?>
                                                    <td>
                                                        <?php 
                                                            if(!empty($employee['date_ary'][$i])){
                                                                
                                                                $cnt = 0;
                                                                foreach($employee['date_ary'][$i] as $day_row){
                                                                    if ($day_row['bs_status']==BS_PENDING) {
                                                                        $color= 'dark';
                                                                    }
                                                                    if ($day_row['bs_status']==BS_INPROGRESS) {
                                                                        $color= 'info';
                                                                    }
                                                                    if ($day_row['bs_status']==BS_COMPLETED_PROVIDER) {
                                                                        $color= 'primary';
                                                                    }
                                                                    if ($day_row['bs_status']==BS_ACCEPTED) {
                                                                        $color='muted';
                                                                    }
                                                                    if ($day_row['bs_status']==BS_REJECTED) {
                                                                        $color='warning';
                                                                    } 
                                                                    if ($day_row['bs_status']==BS_COMPLETED) {
                                                                        $color='success';
                                                                    }
                                                                    if ($day_row['bs_status']==BS_CANCELLED) {
                                                                        $color='danger';
                                                                    }
                                                        ?>
                                                                    <div class="mt-2 pl-2 pr-2" style="cursor:pointer;background-color:#f1f1f1;" onclick="showDetail('<?=$day_row['book_service_id'] ?>');">
                                                                    <label class="mt-2 badge badge-pill badge-<?=$color?>" data-background="<?=$color?>" style="cursor:pointer;"><?php echo $day_row['service_time']; ?></label>
                                                                    </br> <label style="cursor:pointer;"><?php echo $day_row['category_name']; ?></label>
                                                                    </div>
                                                        <?php
                                                                    $cnt++;
                                                                }
                                                            }                                                            
                                                        ?>                                                        
                                                    </td>
                                            <?php
                                                }
                                            ?>
                                        </tr>
                                <?php
                                    }
                                ?>
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
    $("#selected_date").datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
        todayBtn: 'linked',
        orientation: 'bottom auto',
        onSelect: function(dateText) {
            // console.log("Selected date: " + dateText + "; input's current value: " + this.value);
            $(this).change();
        }        
    }).datepicker("setDate", new Date('<?php echo $selected_date; ?>')).on("change", function() {
        $('#search_btn').trigger('click');
    });
});

function changed_week(type) { 
    if(type == 'prev'){
        var myprev_date = new Date($('#selected_date').val());
        myprev_date.setDate(myprev_date.getDate() - 7);
        $('#selected_date').datepicker("setDate",new Date(myprev_date));
    }else{
        var myprev_date = new Date($('#selected_date').val());
        myprev_date.setDate(myprev_date.getDate() + 7);
        $('#selected_date').datepicker("setDate",new Date(myprev_date));
    }
    $('#search_btn').trigger('click');
}

function showEmployeeShift(user_id){
    var BASE_URL = $('#base_url').val();
    var url = BASE_URL + 'admin/schedule_shift/employee_shift/'+user_id;
    window.location.href = url;
}

function showClockedToday(){
    var BASE_URL = $('#base_url').val();
    var url = BASE_URL + 'admin/schedule_shift/clocked_today';
    window.location.href = url;
}

function showDetail(book_service_id){
    alert(book_service_id);
}

</script>

