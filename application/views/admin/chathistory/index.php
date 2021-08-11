<?php
	// print_r($list); exit;
	$params['from'] = !empty($params['from'])?date("d-m-Y",strtotime($params['from'])):"";
	$params['to'] = !empty($params['to'])?date("d-m-Y",strtotime($params['to'])):"";
?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">User Chat History</h3>
				</div>
				<div class="col-auto text-right">
					<a class="btn btn-white filter-btn mr-3" href="javascript:void(0);" id="filter_search">
						<i class="fa fa-filter"></i>
					</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<form action="<?php echo base_url()?>user-chat-history" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    
			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>User Name</label>
								<input class="form-control" name="name" value='<?php echo $params['search_name']; ?>'/>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Limit</label>
								<input class="form-control" name="limit" value='<?php echo $params['limit']; ?>'/>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>From Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker" type="text" name="from" value="<?php echo $params['from']; ?>">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>To Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker" type="text" name="to" value="<?php echo $params['to']; ?>">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<button class="btn btn-primary btn-block" name="form_submit" value="submit" type="submit">Submit</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</form>
		<!-- /Search Filter -->

		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0 filter-data-table" >
								<thead>
									<tr>
										<th>#</th>
										<th>Sender</th>
										<th>message</th>
										<th>Receiver</th>
										<th>time</th>
									</tr>
								</thead>
								<tbody>
								<?php
								if(!empty($list)) {
									$i=1;
									foreach ($list as $rows) {

										if(!empty($rows['created_at'])){
											$time=date('Y-m-d H:i',strtotime($rows['created_at']));
										}else{
											$time='-';
										}
										 
										echo'<tr>
										<td>'.$i++.'</td>
										<td><img class="avatar-sm rounded mr-1" src="'.base_url().$rows['sender_profile_img'].'"> '.$rows['sender_name'].($rows['sender_type']==1?' (provider)':'').'</td>
										<td>'.$rows['message'].'</td>
										<td><img class="avatar-sm rounded mr-1" src="'.base_url().$rows['receiver_profile_img'].'"> '.$rows['receiver_name'].($rows['receiver_type']==1?' (provider)':'').'</td>
										<td>'.$time.'</td>
										</tr>';
								
									}
								}
								else
								{
									echo '<tr><td colspan="5"><div class="text-center text-muted">No records found</div></td></tr>';
								}
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>