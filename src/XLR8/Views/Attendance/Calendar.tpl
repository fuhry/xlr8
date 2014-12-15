<div class="pull-right">
	<div class="btn-group">
		<a class="btn btn-default active" href="{$app_root}/Attendance/Calendar/{$date|date_format:"%Y-%m"}">
			<i class="fa fa-calendar"></i>
			Calendar view
		</a>
		<a class="btn btn-default" href="{$app_root}/Attendance/Ranks/{$date|date_format:"%Y-%m"}">
			<i class="fa fa-sort-amount-desc"></i>
			Rank view
		</a>
	</div>
</div>

<div class="container-fluid">
	<h1>
		Attendance for {$date|date_format:"%B %Y"}
	</h1>
</div>

<div class="container-fluid padded-bottom">
	<div class="pull-left">
		<a class="btn btn-default" href="{$app_root}/Attendance/Calendar/{$prev_month|date_format:"%Y-%m"}">
			&laquo;
			{$prev_month|date_format:"%B %Y"}
		</a>
	</div>
	
	<div class="pull-right">
		<a class="btn btn-default" href="{$app_root}/Attendance/Calendar/{$next_month|date_format:"%Y-%m"}">
			{$next_month|date_format:"%B %Y"}
			&raquo;
		</a>
	</div>
</div>

<div class="container-fluid">
	<table class="table table-bordered calendar">
		<thead>
			<tr>
				<th>Sunday</th>
				<th>Monday</th>
				<th>Tuesday</th>
				<th>Wednesday</th>
				<th>Thursday</th>
				<th>Friday</th>
				<th>Saturday</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				{if $month_start_dow > 0}
					<td colspan="{$month_start_dow}"></td>
				{/if}
				
				{$dom=1}
				{$dow=$month_start_dow}
				
				{while $dom <= $last_dom}
					{$dom_time = $date + (86400*($dom-1))}
					<td
						{if $dom_time > (time() + 86400 - (time() % 86400))}
							class="future"
						{elseif $dom_time > (time() - (time() % 86400))}
							class="today"
						{/if}
						>
						<span class="dom">{$dom}</span>
						{if isset($attendance[$dom_time])}
							<br />
							<a href="{$app_root}/Attendance/Date/{$dom_time|date_format:"%Y-%m-%d"}">
								{$attendance[$dom_time]} students
							</a>
						{/if}
					</td>
					{$dom = $dom + 1}
					{$dow = $dow + 1}
					{if $dow % 7 === 0 && $dom <= $last_dom}
						</tr><tr>
					{/if}
				{/while}
			</tr>
		</tbody>
	</table>
</div>
