<script type="text/javascript" src="{$app_root}/res/XLR8/signin.js"></script>
<link rel="stylesheet" type="text/css" href="{$app_root}/res/XLR8/signin.css" />

<div class="signin">
	<div class="page initial">
		<h1>Welcome to XLR(8)!</h1>
		<h2>Tap the first letter of your name:</h2>
		
		<div class="chiclets">
		</div>
		
		<div class="new-student">
			<a class="btn btn-success btn-lg" href="{$app_root}/SignIn/StudentNew">New Student<br /><small>This is my first time at XLR(8)</small></a>
		</div>
	</div>
	
	<div class="page name">
		<h1>Tap your name:</h1>
		
		<div class="chiclets">
		</div>
		
		<a class="btn btn-danger btn-lg" href="{$app_root}/SignIn/Student">Go back</a>
	</div>
	
	<div class="page homework">
		<h2>How much homework, <span class="signin-name-placeholder"></span>?</h2>
		
		<div class="homework">
			
			<form method="post" enctype="multipart/form-data" action="{$app_root}/SignIn/StudentPost">
				{include file="SignIn/Subjects.tpl"}
				
				<div class="text-center submission">
					<input type="hidden" class="signin-user-id" name="user_id" value="" />
					<input type="submit" class="btn btn-primary btn-lg" value="All done &ndash; sign in" />
					<a class="btn btn-danger btn-lg" href="{$app_root}/SignIn/Student">Go back</a>
				</div>
			</form>
			
		</div>
	</div>
</div>

