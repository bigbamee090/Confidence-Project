<?php
use yii\helpers\Url;
?>
<div class="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<ul>
					<li><a href="<?= Url::home() ?>">Home</a></li>
					<li class="active">Treatment</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<section class="section bg-gray  training">
	<div class="container">

		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-12 text-left">
					<h2 class="title">Training Course Sign Up</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="firstname">First Name <span class="text-danger">*</span></label>
						<input id="firstname" type="text" placeholder="First Name"
							name="firstname" class="form-control input-sm required">
					</div>
					<!-- end form-group -->
					<div class="form-group">
						<label for="lastname">Last Name <span class="text-danger">*</span></label>
						<input id="lastname" type="text" placeholder="Last Name"
							name="lastname" class="form-control input-sm required">
					</div>
					<!-- end form-group -->
					<div class="form-group">
						<label for="email">Email Address <span class="text-danger">*</span></label>
						<input id="email" type="email" placeholder="Email Address"
							name="email" class="form-control input-sm required email">
					</div>
					<!-- end form-group -->
					<label>Qualification</label>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<select class="form-control" name="select">
									<option value="1">BA</option>
									<option value="2">BCA</option>
									<option value="3">B.tech</option>

								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="Postal Code"> Postal Code <span class="text-danger">*</span></label>
						<input id="Postal Code" type="text" placeholder="Postal Code"
							name="Postal Code" class="form-control input-sm required">
					</div>
					<div class="form-group">
						<label for="Account Reference">Account Reference</label> <input
							id="Account Reference" type="text"
							placeholder="Account Reference" name="Account Reference"
							class="form-control input-sm required">
					</div>
					<div class="form-group">
						<label for="Phone">Phone</label> <input id="Phone" type="text"
							placeholder="Phone Number" name="Phone Number"
							class="form-control input-sm required">
					</div>
					<div class="form-group">
						<label for="Other Qualification">Other Qualification</label> <input
							id="Other Qualification" type="password"
							placeholder="Other Qualification" name="confirmPassword"
							class="form-control input-sm required">
					</div>
				</div>
			</div>
			<div class="form-group">
				<a href="javascript:void(0);" class="btn btn-default round btn-md"><i
					class="fa fa-save mr-5"></i> Save</a>
			</div>
		</div>

</section>