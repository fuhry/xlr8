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
		<h2>How's it going, <span class="signin-name-placeholder"></span>?</h2>
		
		<form method="post" enctype="multipart/form-data" action="{$app_root}/SignIn/StudentPost">
		
			<div class="mood form">
				<label class="form-label col-lg-4 col-md-4 col-md-offset-1 col-sm-4 col-sm-offset-2 col-xs-6">
					<span>Mood:</span>
				</label>
				<div class="col-lg-6 col-md-7 col-sm-6 col-xs-6 text-left">
					<div data-toggle="buttons" class="btn-group">
						<label class="btn btn-danger"        ><input type="radio" name="mood" value="angry"                  />&#x1f624;</label>
						<label class="btn btn-default"       ><input type="radio" name="mood" value="neutral"                />&#x1f610;</label>
						<label class="btn btn-info active"   ><input type="radio" name="mood" value="okay" checked="checked" />&#x1f642;</label>
						<label class="btn btn-success"       ><input type="radio" name="mood" value="great"                  />&#x1f600;</label>
					</div>
				</div>
			</div>
			
			<div class="homework">
				
				{include file="SignIn/Subjects.tpl"}
				
				<div class="text-center submission">
					<input type="hidden" class="signin-user-id" name="user_id" value="" />
					<input type="submit" class="btn btn-primary btn-lg" value="All done &ndash; sign in" />
					<a class="btn btn-danger btn-lg" href="{$app_root}/SignIn/Student">Go back</a>
				</div>
			</div>
		
		</form>
	</div>
</div>

