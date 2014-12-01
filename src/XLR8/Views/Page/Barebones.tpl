<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<title>Application</title>
		{include file="Page/CommonHeaders.tpl"}
	</head>
	<body>
		<div class="container-fluid">
			{if isset($page)}
				{include file="$page.tpl"}
			{else}
				<div class="alert alert-danger">
					<i class="fa fa-exclamation-circle"></i> <strong>Logic error</strong><br />
					No page was set by the controller. Assign the Smarty variable "$page" to display a page. The variable corresponds to a file in the application's
					"Views" directory minus the .tpl extension.
				</div>
			{/if}
		</div>
	</body>
</html>
