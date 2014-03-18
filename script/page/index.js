(function(){
	var silderImage = $('.slider_image');
	var silderIcon = $('#silder_icon').find('.slider-num-ico');
	var count = silderImage.length;
	var timer;
	var showSilder = function(index){
		var showIndex = index+1;
		var hideIndex = index;
		silderImage.hide();
		$(silderImage[hideIndex]).fadeIn();
		silderIcon.removeClass('sn-current');
		$(silderIcon[hideIndex]).addClass('sn-current');
		
		if((index+1)>(count-1)){
		   showIndex = 0;
		}
		timer = setTimeout(function(){
			$(silderImage[showIndex]).hide();
			$(silderImage[hideIndex]).hide();
			showSilder(showIndex);
			
		},5000);
	}
	$(silderIcon[0]).addClass('sn-current');
	showSilder(0);
	$('.slider_switch').click(function(){
		var index = $(this).data('id');
		clearTimeout(timer);
		showSilder(index);
		
	});
})();