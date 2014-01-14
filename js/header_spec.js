
$(function (){
   var now=0;
   var picTimer;
   var ulwidth = $(".banner").width(); 
   var lilen=$(".banner ul li").length;
   $(".banner ul").css("width",ulwidth * (lilen)); //UL宽度
	
	//小按钮
	$('.banner ol li').click(function (){
		now=$(this).index();
		$('.banner ol li').removeClass('active');
		$(this).addClass('active');
		$('.banner ul').animate({left: -ulwidth*$(this).index()});
	});
	
	//箭头移入显示隐藏
	$('.banner').hover(function(){	
	//alert('dd');	
		$('.btn').stop().animate({opacity: 1});
		},function(){
		$('.btn').stop().animate({opacity: 0});		
			});
			
	//普通切换		
	function tab(now)
	{
		$('.banner ol li').removeClass('active');
		$('.banner ol li').eq(now).addClass('active');
		var nowLeft = -now*ulwidth;
		$(".banner ul").stop(true,false).animate({"left":nowLeft},300); 
	}
	   			
	//右按钮	
	$('.btn_r').click(function(){
	    now += 1;
		if(now == lilen) {now = 0;}
		tab(now);
	 })		
	 
	 //左按钮
	 $('.btn_l').click(function(){
		now -= 1;
		if(now == -1) {now = lilen - 1;}
	    tab(now);	
	 })		
	 
	 //鼠标滑上焦点图时停止自动播放，滑出时开始自动播放
	$(".banner").hover(function() {
		clearInterval(picTimer);
	},function() {
		picTimer = setInterval(function() {
			tab(now);
			now++;
			if(now == lilen) {now = 0;}
		},4000);
	}).trigger("mouseout");
});
