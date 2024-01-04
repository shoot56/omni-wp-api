(function($) {
	var activeTabIndex = localStorage.getItem('activeTabIndex');

	if (activeTabIndex === null) {
		activeTabIndex = 0;
	}

	$('.tab-opener').eq(activeTabIndex).addClass('active');
	$('.tab-item').eq(activeTabIndex).addClass('active');

	$('.tab-opener').click(function(event) {
		event.preventDefault();
		if (!$(this).hasClass('active')) {
			var aim = $(this).parents('.tab-control').find('.tab-opener').removeClass('active').index(this);
			$(this).addClass('active');
			$(this).parents('.tabset').find('.tab-item').removeClass('active').eq(aim).addClass('active');

			localStorage.setItem('activeTabIndex', aim);
		}
	});



	
})(jQuery);