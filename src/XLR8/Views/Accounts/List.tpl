<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Name</th>
			<th>Role</th>
			<th class="actions">
				<i class="fa fa-cog"></i>
			</th>
		</tr>
	</thead>
	<tbody>
		{foreach $users as $user}
		<tr>
			<td>
				{$user->get('surname')|escape:'html'}, {$user->get('given_name')|escape:'html'}
				{if $user->get('nickname') != null}
					({$user->get('nickname')|escape:'html'})
				{/if}
			</td>
			<td>{ucfirst($user->get('role'))}</td>
			<td class="actions">
				<a title="Edit" href="{$app_root}/Accounts/Edit/{$user->getID()}" class="btn btn-primary btn-xs">
					<i class="fa fa-pencil"></i>
				</a>
			</td>
		</tr>
		{/foreach}
	</tbody>
</table>
