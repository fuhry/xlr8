{foreach $messages as $msg}
	<div class="alert alert-{$message_levels[$msg['level']]}">
		{$msg['message']|escape:'html'}
	</div>
{/foreach}
