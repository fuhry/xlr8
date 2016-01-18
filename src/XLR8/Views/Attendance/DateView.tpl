<h1>Attendance on {$date|date_format:"%A, %B %e, %Y"}</h1>

<p>
	<a class="btn btn-default" href="{$app_root}/Attendance/Calendar/{$date|date_format:"%Y-%m"}">
		<i class="fa fa-calendar"></i>
		Back to calendar
	</a>
</p>

<h2>
	{count($records)} students
</h2>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>Name</th>
			<th>Mood</th>
			<th>Behavior score</th>
			<th>Homework subjects</th>
			<th>Notes</th>
			<th class="actions">
				<i class="fa fa-cog"></i>
			</th>
		</tr>
	</thead>
	<tbody>
		{foreach $records as $record}
			<tr>
				{$user = $students[ $record->get('user_id') ]}
				<td>
					<a href="{$app_root}/Accounts/Edit/{$user->getID()}">
						<i class="fa fa-child"></i>
						{$user->get('surname')}, {$user->get('given_name')}
						{if $user->get('nickname') != null}
							({$user->get('nickname')|escape:'html'})
						{/if}
					</a>
				</td>
				<td>
					{if $record->get('mood')}
						{$moods[ $record->get('mood') ]['emoji']}
						{$moods[ $record->get('mood') ]['description']}
					{else}
						<em>Not recorded</em>
					{/if}
				</td>
				<td>
					{$record->get('behavior_score')}
				</td>
				<td>
					{assign var=i value=0}
					{foreach $homework[$user->getID()] as $hw}{if $i++ > 0}, {/if}{$hw->get('subject')}{foreachelse}
						<em>None</em>
					{/foreach}
				</td>
				<td>
					{if $record->get('notes') != ''}
						{$record->get('notes')|escape:'html'}
					{else}
						<em>None</em>
					{/if}
				</td>
				<td class="actions">
					
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>
