<h1>Edit user: {$userToEdit->get('given_name')|escape:'html'} {$userToEdit->get('surname')|escape:'html'}</h1>

<form method="post" enctype="multipart/form-data" class="form-horizontal" autocomplete="off">
	<fieldset>
		<legend>User attributes</legend>
		
		<div class="form-group">
			<label class="control-label col-lg-3">Given name:</label>
			<div class="form-controls col-lg-6">
				<input type="text" name="attrs[given_name]" class="form-control" value="{$userToEdit->get('given_name')|escape:'html'}" />
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-lg-3">Surname:</label>
			<div class="form-controls col-lg-6">
				<input type="text" name="attrs[surname]" class="form-control" value="{$userToEdit->get('surname')|escape:'html'}" />
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-lg-3">Nickname:</label>
			<div class="form-controls col-lg-6">
				<input type="text" name="attrs[nickname]" class="form-control" value="{if $userToEdit->get('nickname') != null}{$userToEdit->get('nickname')|escape:'html'}{/if}" placeholder="None" />
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-lg-3">User type:</label>
			<div class="form-controls col-lg-1 col-md-2 col-sm-2 col-xs-4">
				<select class="form-control" name="attrs[role]">
					{foreach array('student', 'parent', 'leader', 'administrator') as $role}
						<option value="{$role}"{if $role == $userToEdit->get('role')} selected="selected"{/if}>{ucfirst($role)}</option>
					{/foreach}
				</select>
			</div>
		</div>
		
		<div class="form-group require-role student {if $userToEdit->get('role') !== 'student'}hide{/if}">
			<label class="control-label col-lg-3">Grade:</label>
			<div class="form-controls col-lg-1 col-md-2 col-sm-2 col-xs-4">
				<input class="form-control" type="number" name="attrs[grade]" min="1" max="12" value="{$userToEdit->get('grade')}" />
			</div>
		</div>
		
		<div class="form-group require-role parent {if $userToEdit->get('role') !== 'parent'}hide{/if}">
			<label class="control-label col-lg-3">Children:</label>
			<div class="form-controls col-lg-1 col-md-2 col-sm-2 col-xs-4">
				{if $userToEdit->get('role') == 'parent'}
					{foreach $userToEdit->getChildren() as $child}
						<p>
							<span class="btn btn-default accounts-child">
								<a class="close">
									<span class="sr-only">Remove</span>
									&times;
								</a>
								<i class="fa fa-child"></i>
								{$child->get('given_name')|escape:'html'}
								{$child->get('surname')|escape:'html'}
							</span>
							<input type="hidden" name="children[]" value="{$child->getID()}" />
						</p>
					{/foreach}
				{/if}
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-lg-3">Login allowed:</label>
			<div class="form-controls col-lg-6 checkbox">
				<label>
					<input type="checkbox" name="password[login_ok]" {if $userToEdit->get('password') !== null}checked="checked"{/if} />
				</label>
			</div>
		</div>
		
		<div class="form-group {if $userToEdit->get('password') === null}hide{/if}">
			<label class="control-label col-lg-3">Reset password:</label>
			<div class="form-controls col-lg-6">
				<p><input {if $userToEdit->getID() === $user->getID()}disabled="disabled"{/if} type="password" class="form-control" name="password[new]" placeholder="New password" /></p>
				<p><input {if $userToEdit->getID() === $user->getID()}disabled="disabled"{/if} type="password" class="form-control" name="password[confirm]" placeholder="Confirm password" /></p>
				{if $userToEdit->getID() === $user->getID()}
					<p class="help-block">To change your own password, use the <a href="{$app_root}/Session/ManageAccount">Manage Account</a> page.</p>
				{/if}
			</div>
		</div>
		
	</fieldset>
	
	<div class="text-center">
		<input class="btn-lg btn btn-primary" type="submit" value="Submit changes" />
		<a class="btn btn-lg btn-default" href="{$app_root}/Accounts">Cancel</a>
	</div>
</form>
