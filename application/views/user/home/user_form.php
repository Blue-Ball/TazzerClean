<div class="page-wrapper">
	<div class="content container">
		<!-- Page Header -->
		<!-- <div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title"></h3>
				</div>
				
			</div>
		</div> -->
		<!-- /Page Header -->
		<div class="row pricing-box">
			<form id="update_user" action="<?php echo base_url() ?>second_user_form" method="POST" enctype="multipart/form-data" novalidate class="multisteps-form__form">
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
				<!--single form panel-->
				<div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="scaleIn">
					<div class="multisteps-form__content">

						<?php
						$id = $this->session->userdata('user_last_insert_id');
						$this->db->where('id', $id);
						$query = $this->db->get('users');
						$result = $query->result();
						$row = $result['0'];

						$checkList = [
							C_YOUARE_FREELANCER,
							C_YOUARE_SHOP,
							C_YOUARE_LOCALJOB,
							C_YOUARE_SOLELYVENDER,
							C_YOUARE_SUBCONTRACTOR,
							C_YOUARE_SOLETRADER,
							C_YOUARE_BUSINESS
						];

						if (in_array($row->you_are_appling_as, $checkList)) { ?>

							<h3 class="col-md-9 mt-5 mb-2 mx-auto"><strong>Need to know of about you. </strong></h3>
							<!-- <div class="form-row"> -->
							<div class="col-md-9 col-12 mb-5 mx-auto">

								<div class="row">
									<h3 class="col-xl-12 mt-5 form-title">How many years of paid experience do you have? </h3>
									<div class="col-xl-7 mt-3">
										<select required="true" name="how_many_years_of_paid_experience_do_you_have" class="form-control ">
											<option value="">Select</option>
											<option value="1">None </option>
											<option value="2">3 months</option>
											<option value="3">6 months </option>
											<option value="4">9 months </option>
											<option value="5">12 months </option>
											<option value="6">1 year & above</option>
										</select>
									</div>
								</div>

								<div class="row">
									<h3 class="col-xl-12 mt-5 form-title">Provide proof of qualification obtained choose to do your job and upload.</h3>
									<div class="col-xl-7 mt-3">
										<label>Proof method of qualification obtained choose to do your job</label>
										<select required="true" name="provide_proof_of_qualification_obtained_choose_to_do_your_job_an" class="form-control " id="colorselector2555">
											<option selected="selected" value="">Select</option>
											<option value="1">Self-taught </option>
											<option value="2">NVQ</option>
											<option value="3">GCE</option>
											<option value="4">College degree</option>
											<option value="5">University degree</option>
											<option value="6">HND</option>
											<option value="7">Vocational qualification</option>
											<option value="8">I don't have any qualifications.</option>
											<option value="9">Others</option>
										</select>
									</div>
									<div class="col-xl-7 mt-3 colors_bb2555" style="display:none" id="9">
										<label>Please add name:</label>
										<!-- <h5 class="form-title font-weight-bold"> Please add name:</h5> -->
										<input type="text" name="name_provide_proof_of_qualification_obtained_choose_to_do_your_j" value="" placeholder="Please add name" class="form-control ">

									</div>
									<div class="col-xl-5 mt-3">
										<label>Please add upload:</label>
										<!-- <h5 class="form-title font-weight-bold"> Please add upload:</h5> -->
										<input type="file" name="file_provide_proof_of_qualification_obtained_choose_to_do_your_j" value="" placeholder="Enter Address" class="form-control ">

									</div>
								</div>
								<h3 class="col-xl-12 mt-5 form-title">What supplies do you have? Check all that apply. </h3>
								<div class="row mt-3">
									<div class="col-xl-12">
										<div class="custom-control custom-checkbox checkbox-xl">
											<input type="checkbox" name="what_supplies_do_you_have_check_all_that_apply[]" class="custom-control-input" id="checkbox-202" value="1">
											<label class="custom-control-label" for="checkbox-202">Basic tools (drill, wrench, hammer, level, etc.)</label>
										</div>
									</div>
									<div class="col-xl-12">
										<div class="custom-control custom-checkbox checkbox-xl">
											<input type="checkbox" name="what_supplies_do_you_have_check_all_that_apply[]" class="custom-control-input" id="checkbox-20" value="2">
											<label class="custom-control-label" for="checkbox-20">Power tools (circular/table saw, nail gun, shop vac, etc.)</label>
										</div>
									</div>
									<div class="col-xl-12">
										<div class="custom-control custom-checkbox checkbox-xl">
											<input type="checkbox" name="what_supplies_do_you_have_check_all_that_apply[]" class="custom-control-input" id="checkbox-21" value="3">
											<label class="custom-control-label" for="checkbox-21">Painting supplies (roller, brush, drop cloth, tape, etc.)</label>
										</div>
									</div>
									<div class="col-xl-12">
										<div class="custom-control custom-checkbox checkbox-xl">
											<input type="checkbox" name="what_supplies_do_you_have_check_all_that_apply[]" class="custom-control-input" id="checkbox-22" value="4">
											<label class="custom-control-label" for="checkbox-22">Lawn care equipment (mower, leaf blower, string trimmer, hand tools, etc.)</label>
										</div>
									</div>
									<div class="col-xl-12">
										<div class="custom-control custom-checkbox checkbox-xl">
											<input type="checkbox" name="what_supplies_do_you_have_check_all_that_apply[]" class="custom-control-input" id="checkbox-23" value="5">
											<label class="custom-control-label" for="checkbox-23">Ladder</label>
										</div>
									</div>
								</div>
								<div class="row">
									<h3 class="col-xl-12 mt-5 form-title"><strong>Are you legally eligible to work in the county you are current in? </strong></h3>
								</div>
								<div class="row mt-3">
									<div class="col-xl-3">
										<div class="custom-control custom-checkbox checkbox-xl">
											<input type="radio" name="are_you_legally_eligible_to_work_in_the_current" class="custom-control-input" id="checkbox-24" value="Yes" checked="">
											<label class="custom-control-label" for="checkbox-24">Yes</label>
										</div>
									</div>
									<div class="col-xl-3">
										<div class="custom-control custom-checkbox checkbox-xl">
											<input type="radio" name="are_you_legally_eligible_to_work_in_the_current" class="custom-control-input" id="checkbox-25" value="No">
											<label class="custom-control-label" for="checkbox-25">No</label>
										</div>
									</div>

								</div>

							</div>
						<?php } ?>


						<h3 class="col-md-9 mt-5 mb-2 mx-auto"><strong>ID verification. </strong></h3>
						<!-- <div class="form-row"> -->
						<div class="col-md-9 col-12 mx-auto">

							<div class="row mb-3">
								<h3 class="col-xl-12 mt-5 form-title">Provide proof of photo ID you must choose at least one from the list and upload.</h3>
								<div class="col-xl-7 mt-3">
									<div class="form-group">
										<label> Proof method: </label>
										<select required="true" name="provide_proof_of_photo_id_you_must_choose_at_least_one_from" class="form-control " id="colorselector1">
											<option value="">Select</option>
											<option value="1">Passport </option>
											<option value="2">Driving licence</option>
											<option value="3">Biometric card</option>
											<option value="4">ID card</option>
											<option value="5">Resident permit.</option>
											<option value="6">Other</option>
										</select>
									</div>
								</div>

								<div class="col-xl-7 mt-3 colors_bb1 " style="display:none" id="6">
									<div class="form-group">
										<label> Please add name: </label>
										<!-- <h5 class="form-title font-weight-bold"> Please add name:</h5> -->
										<input type="text" name="name_provide_proof_of_photo_id_you_must_choose_at_least_one_from" value="" placeholder="Please add name" class="form-control ">
									</div>
								</div>
								<div class="col-xl-5 mt-3 ">
									<div class="form-group">
										<label> Please add upload: </label>
										<!-- <h5 class="form-title font-weight-bold"> Please add upload:</h5> -->
										<input type="file" name="file_provide_proof_of_photo_id_you_must_choose_at_least_one_from" value="" placeholder="Enter Address" class="form-control ">
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<h3 class="col-xl-12 mt-5 form-title"> Provide proof of right to work in your country you select a minimum of one and upload.</h3>
								<div class="col-xl-7 mt-3">
									<div class="form-group">
										<label> Proof method in country: </label>
										<select required="true" name="provide_proof_of_right_to_work_in_your_country_you_select_a_mini" class="form-control " id="colorselector27">
											<option value="">Select</option>
											<option value="1">National Insurance </option>
											<option value="2">Passport</option>
											<option value="3">Driving licence</option>
											<option value="4">Biometric card</option>
											<option value="5">ID card</option>
											<option value="6">Resident permit</option>
											<option value="7">Other</option>
										</select>
									</div>
								</div>

								<div class="col-xl-7 mt-3 colors_bb27" style="display:none" id="7">
									<div class="form-group">
										<label> Please add name: </label>
										<!-- <h5 class="form-title font-weight-bold"> Please add name:</h5> -->
										<input type="text" name="name_provide_proof_of_right_to_work_in_your_country_you_select_a" class="form-control ">
									</div>
								</div>
								<div class="col-xl-5 mt-3">
									<div class="form-group">
										<label>Please add upload:</label>
										<!-- <h5 class="form-title font-weight-bold"> Please add upload:</h5> -->
										<input type="file" name="file_provide_proof_of_right_to_work_in_your_country_you_select_a" value="" placeholder="Enter Address" class="form-control ">
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<h3 class="col-xl-12 mt-5 form-title">Provide proof of homes address Must be less than 3 months old from the date of issue</h3>
								<div class="col-xl-7 mt-3">
									<div class="form-group">
										<label> Address proof method:</label>
										<select required="true" name="provide_proof_of_homes_address_must_be_less_than_3_months_old_fr" class="form-control " id="colorselector2">
											<option value="">Select</option>
											<option value="1">Telephone Bill </option>
											<option value="2">Gas or electric bill</option>
											<option value="3">Bank statement</option>
											<option value="4">Letter from the government or school</option>
											<option value="5">Other</option>
										</select>
									</div>
								</div>
								<div class="col-xl-7 mt-3 colors_bb2" style="display:none" id="5">
									<div class="form-group">
										<label>Please add name: </label>
										<!-- <h5 class="form-title font-weight-bold"> Please add name:</h5> -->
										<input type="text" name="name_provide_proof_of_homes_address_must_be_less_than_3_months_o" value="" placeholder="Please add name" class="form-control ">
									</div>
								</div>
								<div class="col-xl-5 mt-3">
									<div class="form-group">
										<label>Please add upload:</label>
										<!-- <h5 class="form-title font-weight-bold"> Please add upload:</h5> -->
										<input type="file" name="file_provide_proof_of_homes_address_must_be_less_than_3_months_o" value="" placeholder="Enter Address" class="form-control ">
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<h3 class="col-xl-12 mt-5 form-title">Provide bank account information for payment.</h3>
								<div class="row">
									<div class="col-md-6 mt-3">
										<div class="form-group">
											<label>Bank name:</label>
											<input value="" type="text" name="bank_name" class="form-control " Placeholder="Enter Bank name">
										</div>
									</div>
									<div class="col-md-6 mt-3">
										<div class="form-group">
											<label>Account holder name:</label>
											<input value="" type="text" name="acc_holder_name" class="form-control " Placeholder="Enter Account holder name">
										</div>
									</div>
									<div class="col-md-6 mt-3">
										<div class="form-group">
											<label>Bank address:</label>
											<input value="" type="text" name="bank_address" class="form-control " Placeholder="Enter Bank address">
										</div>
									</div>
									<div class="col-md-6 mt-3">
										<div class="form-group">
											<label>Sort code :</label>
											<input value="" type="text" name="sort_code" class="form-control " Placeholder="Enter Sort code">
										</div>
									</div>
									<div class="col-md-6 mt-3">
										<div class="form-group">
											<label>Account Number :</label>
											<input value="" type="text" name="account_number" class="form-control " Placeholder="Enter Account Number">
										</div>
									</div>
									<div class="col-md-6 mt-3">
										<div class="form-group">
											<label>swost code :</label>
											<input value="" type="text" name="swost_code" class="form-control " Placeholder="Enter swost code">
										</div>
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<h3 class="col-xl-12 mt-5 form-title">For business only</h3>
								<div class="col-xl-7 mt-3">
									<div class="form-group">
										<label>Business proof method:</label>
										<select name="for_business_only" class="form-control " id="colorselector4">
											<option value="">Select</option>
											<option value="1">Company registration number </option>
											<option value="2">Company registration document</option>
											<option value="3">Business insurance</option>
											<option value="4">Method statement</option>
											<option value="5">Proof of trading address</option>
											<option value="6">The ID of the responsible individual</option>
											<option value="7">provide website link if any</option>
											<option value="8">Other</option>
										</select>
									</div>
								</div>
								<div class="col-xl-7 mt-3 colors_bb4" style="display:none" id="8">
									<div class="form-group"><label>Please add name:</label>
										<!-- <h5 class="form-title font-weight-bold"> Please add name:</h5> -->
										<input type="text" name="name_for_business_only" value="" placeholder="Please add name" class="form-control ">
									</div>
								</div>
								<div class="col-xl-5 mt-3">
									<div class="form-group">
										<label>Please add upload:</label>
										<!-- <h5 class="form-title font-weight-bold"> Please add upload:</h5> -->
										<input type="file" name="file_for_business_only" value="" placeholder="Enter Address" class="form-control ">
									</div>
								</div>
							</div>
							<div class="row">
								<h4 class="col-xl-12 mb-5"><strong></strong></h4>
								<h3 class="col-xl-12 mb-2 form-title">Upload the must current photo of you </h3>
								<p class="col-xl-12">Note: This photo must be very clear with all your face clearly showing it must be from your chest level upwards.</p>
								<div class="col-xl-6">
									<div class="form-group">
										<input type="file" name="upload_the_must_current_photo_of_you" value="" placeholder="Enter Address" class="form-control ">
									</div>
								</div>
							</div>
							<div class="row">
								<h4 class="col-xl-12 mb-5"><strong></strong></h4>
								<h3 class="col-xl-12 mb-2 form-title">Facial Video verification is a must. </h3>
								<p class="col-xl-12">Note: Use your phone to take a video & say verify me.</p>
								<div class="col-xl-6">
									<div class="form-group">
										<input type="file" name="facial_video_verification_is_a_must" value="" placeholder="Enter Address" class="form-control ">
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-9 col-12 mx-auto button-row d-flex mt-4 mb-4">
							<button class="btn btn-success ml-auto mr-3 px-5" name="form_submitss" type="submit" title="Send">Send</button>
						</div>
					</div>
					<!-- <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
						
					</div> -->
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() { // Makes sure the code contained doesn't run until
		//     all the DOM elements have loaded
		$('#colorselector').change(function() {
			$('.colors_bb').hide();
			if ($(this).val() != "") {
				$('#' + $(this).val()).show();
			}
		});
		$('#colorselector2555').change(function() {
			$('.colors_bb2555').hide();
			if ($(this).val() != "") {
				$('#' + $(this).val()).show();
			}
		});
	});
	$(function() { // Makes sure the code contained doesn't run until
		//     all the DOM elements have loaded
		$('#colorselector1').change(function() {
			$('.colors_bb1').hide();
			if ($(this).val() != "") {
				$('#' + $(this).val()).show();
			}
		});
		$('#colorselector2').change(function() {
			$('.colors_bb2').hide();
			if ($(this).val() != "") {
				$('#' + $(this).val()).show();
			}
		});
		$('#colorselector3').change(function() {
			$('.colors_bb3').hide();
			if ($(this).val() != "") {
				$('#' + $(this).val()).show();
			}
		});
		$('#colorselector4').change(function() {
			$('.colors_bb4').hide();
			if ($(this).val() != "") {
				$('#' + $(this).val()).show();
			}
		});
		$('#colorselector27').change(function() {
			/// $('.colors_bb27').hide();
			if ($(this).val() != "") {
				$('#' + $(this).val()).show();
			}
		});
	});
	$(document).ready(function() {
		$("input[name$='cars']").click(function() {
			var test = $(this).val();
			$("div.desc").hide();
			$("#Cars" + test).show();
		});
		$("input[name$='cars1']").click(function() {
			var test = $(this).val();
			$("div.desc1").hide();
			$("#Cars1" + test).show();
		});
	});
</script>