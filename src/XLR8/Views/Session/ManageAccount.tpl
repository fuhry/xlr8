<h1>Your Account</h1>

<form method="post" class="form-horizontal" enctype="multipart/form-data">
	<fieldset>
		<legend>Current password</legend>
		
		<p>To change your e-mail address or password, enter your current password below.</p>
		<div class="form-group">
			<label class="control-label col-lg-3">Current password:</label>
			<div class="form-controls col-lg-6">
				<input type="password" name="password[old]" class="form-control" />
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Login information</legend>
		
		<div class="form-group">
			<label class="control-label col-lg-3">E-mail address:</label>
			<div class="form-controls col-lg-6">
				<input type="text" name="email" class="form-control" value="{$user->get('email')|escape:'html'}" />
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-lg-3">Change your password:</label>
			<div class="form-controls col-lg-6">
				<p><input type="password" name="password[new]" class="form-control" placeholder="New password" /></p>
				<p><input type="password" name="password[confirm]" class="form-control" placeholder="Confirm new password" /></p>
				<p class="help-block">If left blank, your password will be unchanged.</p>
			</div>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>User information</legend>
		
		<div class="form-group">
			<label class="control-label col-lg-3">User ID:</label>
			<div class="form-controls col-lg-1">
				<input type="text" class="form-control" size="5" readonly="readonly" value="{$user->getID()|escape:'html'}" />
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-lg-3">Account type:</label>
			<div class="form-controls col-lg-1">
				<p class="form-control-static">{$user->get('role')|escape:'html'|ucfirst}</p>
			</div>
		</div>
	</fieldset>
	
	<div class="text-center">
		<input class="input-lg btn btn-primary" type="submit" value="Submit changes" />
	</div>
</form>
