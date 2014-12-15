<form method="post">
	<h1>
		Delete
		{$userToDelete->get('given_name')|escape:'html'}
		{$userToDelete->get('surname')|escape:'html'}
		{if $userToDelete->get('nickname') != null}
			({$userToDelete->get('nickname')|escape:'html'})
		{/if}
		</h1>
	
	<p>Please confirm that you wish to delete {$userToDelete->get('given_name')|escape:'html'}
		{$userToDelete->get('surname')|escape:'html'}'s account.</p>
	
	<div class="alert alert-danger">
		<strong>Warning!</strong> This will irreversibly delete this user's account. If the user is a student, all of
		their attendance records and notes will be removed as well. Once deleted, a user's past attendance data <strong>cannot</strong>
		be restored.
	</div>
	
	<div class="text-center">
		<input type="submit" name="confirm" class="btn btn-lg btn-danger" value="Delete user" />
		<a class="btn btn-lg btn-default" href="{$app_root}/Accounts">Cancel</a>
	</div>
</form>
