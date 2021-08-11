<?php
   $user_details = $this->db->get('users')->result_array();
?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Users Subscription</h3>
				</div>
				<div class="col-auto text-right">
					<a class="btn btn-white filter-btn mr-3" href="javascript:void(0);" id="filter_search">
						<i class="fa fa-filter"></i>
					</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
                        <div class="table-responsive">
                            <table class="custom-table table table-hover table-center mb-0 w-100" id="users_subscription_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Email</th>
                                        <th>Created At</th>
										<th>Updated AT</th>
										<th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								</tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal account-modal fade" id="promo-code-modal" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header p-0 border-0">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="login_form_div">
					<div class="account-content">
						<div class="account-box">
							<div class="login-right">
								<div class="form-group">
									<label>Promo Code</label>
									<div class="row">
										<div class="col-12">
											<input type="hidden" name="csrf_token_name" value="<?php echo $this->security->get_csrf_hash(); ?>" id="promo-csrf">
											<input class="form-control promo-code" type="text" name="promo-offer" id="promo-code">
											<span id="mailid_error"></span>
											<input type="hidden" name="email"  id="email">
										</div>
									
									</div>
								</div>
									<div class="form-group">
						<label>Description</label>
									<div class="row">
										<div class="col-12">
					
						<input type="text" class="form-control form-control-lg" autocomplete="off" name="offer" id='offer'>
					</div>
					</div>
					</div>
					<span id="err_respwd"></span>
								<button class="btn btn-primary" id="submit-promo-offer" type="button">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>