<link rel="stylesheet" type="text/css" href="{$app_root}/res/XLR8/signin.css" />

<div class="signin">
	<div class="page initial">
		<h1>Welcome!</h1>
		<h2>Please fill in your info.</h2>
		
		<form method="post" enctype="multipart/form-data" class="form-horizontal" class="new-user">
		
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-3 col-xs-3">First name:</label>
				<div class="form-controls col-lg-9 col-md-9 col-sm-9 col-xs-9">
					<input type="text" class="form-control" name="given_name" value="" />
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-3 col-xs-3">Last name:</label>
				<div class="form-controls col-lg-9 col-md-9 col-sm-9 col-xs-9">
					<input type="text" class="form-control" name="surname" value="" />
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-3 col-xs-3">Grade:</label>
				<div class="form-controls col-lg-1 col-md-1 col-sm-2 col-xs-2">
					<input type="number" class="form-control" name="grade" min="1" max="12" value="1" />
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-lg-6 col-md-6 col-sm-6 col-xs-6">Do you go to PrimeTime?</label>
				<div class="form-controls text-left col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<div class="radio">
						<label>
							<input type="radio" name="primetime" value="yes" />
							Yes
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="primetime" value="no" checked="checked" />
							No
						</label>
					</div>
				</div>
			</div>
			
		</form>
	</div>
</div>

