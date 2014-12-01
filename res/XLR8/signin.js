$(function()
	{
		$('div.page').hide();
		$('div.page.initial').show();
		
		$.get(app_root + '/API/UserLookup/getUsedFirstLetters', function(result)
			{
				for ( var i = 0; i < result.length; i++ ) {
					var $btn = $('<a />'),
						letter = result[i];
					
					$btn
						.addClass('btn')
						.addClass('btn-default')
						.addClass('btn-lg')
						.text(letter.toUpperCase())
						.click(selectLetter)
						.data('letter', letter);
						
					$('div.page.initial div.chiclets').append($btn);
				}
			}, 'json');
	});

function selectLetter()
{
	var letter = $(this).data('letter');
	
	$.get(app_root + '/API/UserLookup/getStudentsByFirstLetter/' + letter, function(result)
		{
			$('div.page.initial').hide();
			$('div.page.name').show();
			
			for ( var i = 0; i < result.length; i++ ) {
					var $btn = $('<a />'),
						user = result[i];
						
					$btn
						.addClass('btn')
						.addClass('btn-default')
						.addClass('btn-lg')
						.text(user.given_name)
						.click(selectName)
						.data('user', user);
						
					$('div.page.name div.chiclets').append($btn);
				}
		});
}

function selectName()
{
	var user = $(this).data('user');
	
	$('div.page.name').hide();
	$('div.page.homework span.signin-name-placeholder').text(user.given_name);
	$('div.page.homework input.signin-user-id').val(user.user_id);
	$('div.page.homework').show();
}
