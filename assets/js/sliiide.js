var postClick=false;

$(document).ready(function() {
		$menuLeft = $('.pushmenu-left');
		$nav_list = $('#nav_list');
		
		$nav_list.click(function() {
			$(this).toggleClass('active');
			$('.pushmenu-push').toggleClass('pushmenu-push-toright');
			$menuLeft.toggleClass('pushmenu-open');
			
			
			if($('body').hasClass('menu_fuera'))
			{
				$('body').removeClass('menu_fuera');
			}
			else
			{
				$('body').addClass('menu_fuera');
				postClick=true;
			}
			
		});
		
		
		$(document).click(function()
		{	
			if(postClick)
			{
				postClick=false;
			}
			else
			{
				if($('body').hasClass('menu_fuera'))
				{
					$('#nav_list').toggleClass('active');
					$('.pushmenu-push').toggleClass('pushmenu-push-toright');
					$menuLeft.toggleClass('pushmenu-open');
					$('body').removeClass('menu_fuera');
				}
			}
			
			
		});
		
	});