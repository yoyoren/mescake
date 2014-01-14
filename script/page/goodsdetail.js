(function(){

	function addToCart(goodsId, callback){
	  var goods        = new Object();
	  var spec_arr     = new Array();
	  var fittings_arr = new Array();
	  var number       = 1;
	  var formBuy      = document.forms['ECS_FORMBUY'];
	  var quick		   = 0;

	  // 检查是否有商品规格 
	  if (formBuy){
	    spec_arr = getSelectedAttributes(formBuy);
	    if (formBuy.elements['number']){
	      number = formBuy.elements['number'].value;
	    }
		quick = 1;
	  }
	  goods.quick    = quick;
	  //商品重量
	  goods.spec     = spec_arr;
	  goods.goods_id = goodsId;
	  //数量
	  goods.number   = number;
	  goods.parent   = 0;//(typeof(parentId) == "undefined") ? 0 : parseInt(parentId);
	  $.post('route.php?mod=order&action=add_to_cart', {
	  	goods:$.toJSON(goods),
	  	goods_id:goodsId
	  }, function(d){
	  		callback();
	  },'json');
	}
	$('#order_now_btn').click(function(){
		addToCart(window.GOOD_ID,function(){
			location.href="route.php?mod=order&action=step2";
		});
	});

	$('#add_to_cart_btn').click(function(){
		addToCart(window.GOOD_ID,function(){
			alert('该商品已经添加到购物车');
		});
	})

})();