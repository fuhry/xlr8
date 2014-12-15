$(function()
	{
		$('#accounts-edit-login-ok').bind('change', function()
			{
				if ( $(this).prop('checked') ) {
					$('.hide-unless-login-ok').removeClass('hide');
				}
				else {
					$('.hide-unless-login-ok').addClass('hide');
				}
			});
		
		$('#accounts-edit-role').bind('change', function()
			{
				var val = $(this).val();
				$('.require-role:not(.' + val + ')').addClass('hide');
				$('.require-role.' + val).removeClass('hide');
			});
	});
