<div class="pull-right">
	<div class="btn-group">
		<a class="btn btn-default" href="{$app_root}/Attendance/Calendar/{$date|date_format:"%Y-%m"}">
			<i class="fa fa-calendar"></i>
			Calendar view
		</a>
		<a class="btn btn-default active" href="{$app_root}/Attendance/Ranks/{$date|date_format:"%Y-%m"}">
			<i class="fa fa-sort-amount-desc"></i>
			Rank view
		</a>
	</div>
</div>

<div class="container-fluid">
	<h1>
		Attendance ranks for {$date|date_format:"%B %Y"}
	</h1>
</div>

<div class="container-fluid padded-bottom">
	<div class="pull-left">
		<a class="btn btn-default" href="{$app_root}/Attendance/Ranks/{$prev_month|date_format:"%Y-%m"}">
			&laquo;
			{$prev_month|date_format:"%B %Y"}
		</a>
	</div>
	
	<div class="pull-right">
		<a class="btn btn-default" href="{$app_root}/Attendance/Ranks/{$next_month|date_format:"%Y-%m"}">
			{$next_month|date_format:"%B %Y"}
			&raquo;
		</a>
	</div>
</div>

<div class="container-fluid">
	<h3>{count($students)} students have attended XLR(8) this month</h3>
	
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Name</th>
				<th>Nights attended</th>
			</tr>
		</thead>
		<tbody>
			{foreach $nights_attended as $user_id => $count}
				<tr>
					<td>
						{$user = $students[$user_id]}
						<a href="{$app_root}/Accounts/Edit/{$user_id}">
							<i class="fa fa-child"></i>
							{$user->get('surname')|escape:'html'},
							{$user->get('given_name')|escape:'html'}
						</a>
						{if $count >= $perfect_threshold}
							<span class="label label-warning">
								<i class="fa fa-star"></i>
								Perfect!
							</span>
						{/if}
					</td>
					<td>
						{$count}
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>
