(function() {
  var jqFristSelection = $($('.js_choose_weight')[0]);
  var attr = jqFristSelection.data('attr');
  jqFristSelection.addClass('current');
  jqFristSelection.find('em').addClass('radiobox-checked');
  window.ATTR = attr;
  MES.get({
    mod : 'goods',
    action : 'get_price_by_weight',
    param : {
      id : window.GOODS_ID,
      attr : attr,
      number : 1
    },
    callback : function(d) {
	  var price = d.result;
	  $('#price_container').show();
	  $('#staff_price').html(price);
    }
  });

  function addToCart(goodsId, callback) {
    var goods = {};
    var spec_arr = [];
    var fittings_arr = [];
    var number = 1;
    var formBuy = document.forms['ECS_FORMBUY'];
    var quick = 0;

    // 检查是否有商品规格
    goods.quick = 1;
    //商品重量
    goods.spec = window.ATTR;
    goods.goods_id = window.GOODS_ID;
    //数量
    goods.number = 1;
    goods.parent = 0;
    //(typeof(parentId) == "undefined") ? 0 : parseInt(parentId);
    $.post('route.php?mod=order&action=add_to_cart', {
      goods : $.toJSON(goods),
      goods_id : goodsId
    }, function(d) {
      callback();
    }, 'json');
  }


  $('#order_now_btn').click(function() {
    addToCart(window.GOODS_ID, function() {
      MES.reload("route.php?mod=order&action=step2");
    });
  });

  $('#add_to_cart_btn').click(function() {
    addToCart(window.GOODS_ID, function() {
      require(['ui/confirm'], function(confirm) {
        new confirm('该商品已经添加到购物车');
      });
    });
    return false;
  })
})(); 