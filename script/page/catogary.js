(function(){
	var imgs = $('.lazy_load');
	var lazyLoadImage = function(count){
			imgs.each(function(index,el){
				if(index<count){
					el.onload = function(){
						$(this).hide().fadeIn('normal');
					}
					el.src = $(el).data('src');
				}
			});	
	}

	lazyLoadImage(4);
	$(window).scroll(function(){
		lazyLoadImage(imgs.length);
		$(window).unbind('scroll');
	});

})();