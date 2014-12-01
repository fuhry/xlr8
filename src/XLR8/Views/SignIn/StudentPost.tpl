<link rel="stylesheet" type="text/css" href="{$app_root}/res/XLR8/signin.css" />

<div class="signin">
	<div class="page initial">
		<h1>Thanks, {$signin_user['given_name']}!</h1>
		<h2>You're all signed in.</h2>
		
		<a href="{$app_root}/SignIn/Student" class="btn btn-primary btn-lg">Back to the sign-in page</a>
	</div>
</div>

<script type="text/javascript">
{literal}
//<![CDATA[
$(function()
	{
		setTimeout(function()
			{
				window.location = app_root + '/SignIn/Student';
			}, 3000);
	});
//]]>
{/literal}
</script>
