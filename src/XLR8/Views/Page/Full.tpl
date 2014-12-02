<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<title>Application</title>
		{include file="Page/CommonHeaders.tpl"}
	</head>
	<body style="padding-top: 70px;">
		<nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="{$app_root}/Console">{$App->getName()}</a>
				</div>
				
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						{if $user}
							<li><a href="{$app_root}/Console">Home</a></li>
							<li><a href="{$app_root}/SignIn">Sign-in page</a></li>
							<li><a href="{$app_root}/Attendance">Attendance</a></li>
							<li><a href="{$app_root}/Messages">Messages</a></li>
						{else}
							<li><a href="{$app_root}/Session/Login">Sign in</a></li>
						{/if}
					</ul>
					<ul class="nav navbar-nav navbar-right">
						{if $user}
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
									<i class="fa fa-user"></i>
									{$user->get('given_name')}
									{$user->get('surname')}
									<span class="caret"></span>
								</a>
								
								<ul class="dropdown-menu" role="menu">
									<li><a href="{$app_root}/Session/ManageAccount">Account Settings</a></li>
									<li><a href="{$app_root}/Session/Logout">Sign out</a></li>
								</ul>
							</li>
						{/if}
					</ul>
				</div><!-- /.navbar-collapse -->
			</div>
		</nav>
		<div class="container-fluid">
			{include file="Messages.tpl"}
			
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
