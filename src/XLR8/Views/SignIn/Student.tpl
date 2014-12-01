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
	</div>
	
	<div class="page homework">
		<h2>How much homework, <span class="signin-name-placeholder"></span>?</h2>
		
		<div class="homework">
			
			<form method="post" enctype="multipart/form-data" action="{$app_root}/SignIn/StudentPost">
				<div class="subject">
					<label class="subject-label col-lg-4 col-md-6 col-sm-4 col-sm-offset-2 col-xs-6">
						<i class="fa fa-book"></i>
						<span>Reading</span>
					</label>
					<div class="btn-group col-lg-8 col-md-6 col-sm-6 col-xs-6" data-toggle="buttons">
						<label class="btn btn-default active"><input type="radio" name="subject[reading]" value="0" checked="checked" />None</label>
						<label class="btn btn-default"><input type="radio" name="subject[reading]" value="1" />A little</label>
						<label class="btn btn-default"><input type="radio" name="subject[reading]" value="2" />A lot</label>
					</div>
				</div>
				
				<div class="subject">
					<label class="subject-label col-lg-4 col-md-6 col-sm-4 col-sm-offset-2 col-xs-6">
						<i class="fa fa-calculator"></i>
						<span>Math</span>
					</label>
					<div class="btn-group col-lg-8 col-md-6 col-sm-6 col-xs-6" data-toggle="buttons">
						<label class="btn btn-default active"><input type="radio" name="subject[math]" value="0" checked="checked" />None</label>
						<label class="btn btn-default"><input type="radio" name="subject[math]" value="1" />A little</label>
						<label class="btn btn-default"><input type="radio" name="subject[math]" value="2" />A lot</label>
					</div>
				</div>
				
				<div class="subject">
					<label class="subject-label col-lg-4 col-md-6 col-sm-4 col-sm-offset-2 col-xs-6">
						<i class="fa fa-bug"></i>
						<span>Science</span>
					</label>
					<div class="btn-group col-lg-8 col-md-6 col-sm-6 col-xs-6" data-toggle="buttons">
						<label class="btn btn-default active"><input type="radio" name="subject[science]" value="0" checked="checked" />None</label>
						<label class="btn btn-default"><input type="radio" name="subject[science]" value="1" />A little</label>
						<label class="btn btn-default"><input type="radio" name="subject[science]" value="2" />A lot</label>
					</div>
				</div>
				
				<div class="subject">
					<label class="subject-label col-lg-4 col-md-6 col-sm-4 col-sm-offset-2 col-xs-6">
						<i class="fa fa-graduation-cap"></i>
						<span>Other subjects</span>
					</label>
					<div class="btn-group col-lg-8 col-md-6 col-sm-6 col-xs-6" data-toggle="buttons">
						<label class="btn btn-default active"><input type="radio" name="subject[other]" value="0" checked="checked" />None</label>
						<label class="btn btn-default"><input type="radio" name="subject[other]" value="1" />A little</label>
						<label class="btn btn-default"><input type="radio" name="subject[other]" value="2" />A lot</label>
					</div>
				</div>
				
				<div class="text-center submission">
					<input type="hidden" class="signin-user-id" name="user_id" value="" />
					<input type="submit" class="btn btn-primary btn-lg" value="All done &ndash; sign in" />
				</div>
			</form>
			
		</div>
	</div>
</div>

