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

	$('.advanced-settings__opener').click(function(e) {
		let item = $(this).closest('.advanced-settings');
		item.find('.advanced-settings__content').slideToggle(function(){
			item.toggleClass('active');
		});
		e.preventDefault();
		// let $contentDiv = $(this).next('.advanced-settings__content');
		// $contentDiv.toggle();
	});

	// $(document).ready(function() {
	// 	$('.js-example-basic-multiple').each(function(index, el) {
	// 		let = selectItem = $(this);
	// 		selectItem.select2({
	// 			minimumResultsForSearch: -1
	// 		});
			
	// 	});
		
	// });
	function preserveOrderOnSelect2Choice(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value='+id+']');
		option.detach();
		$(e.target).append(option).change();
	}
	po_select2s = $('.js-example-basic-multiple').select2()
	po_select2s.each(function(){
		$(this).on('select2:select',preserveOrderOnSelect2Choice);
	});
	
})(jQuery);