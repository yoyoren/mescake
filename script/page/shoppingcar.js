(function(){
   var orderListTmpl = '<%for(var i=0;i<data.length;i++) {%>\
   				<li class="order-item">\
		          <div class="or-main-container">\
		            <span class="or-name">\
		              <a href="#">\
		              	<img src="themes/default/images/sgoods/<%=data[i].goods_sn.replace(/11$/,"")%>.png" class="or-name-img"/>\
		              	<sapn class="or-name-intro"><%=data[i].goods_name%>（<%=data[i].goods_attr%>）</span></a>\
		            </span>\
		            <span class="or-price"><%=data[i].goods_price%></span>\
		            <span class="or-num">\
		              <em class="or-plus order_des" data-id="<%=data[i].rec_id%>">-</em>\
		              <span class="or-num-num"><%=data[i].goods_number%></span>\
		              <em class="or-add order_add" data-id="<%=data[i].rec_id%>">+</em>\
		            </span>\
		            <span class="or-total"><%=data[i].goods_price%></sapn>\
		            <em class="or-close order_cancel">x</em>\
		          </div>\
		          <div class="or-child-container">\
		            <span class="or-name">\
		              <img src="" class="or-child-img"/><sapn class="or-name-intro">配套餐具</span>\
		            </span>\
		            <span class="or-price">免费</span>\
		            <span class="or-num"><span>5人份</span></span>\
		            <span class="or-total">0元</sapn>\
		          </div>\
		          <div class="or-child-container">\
		            <span class="or-name">\
		              <img src="" class="or-child-img"/><sapn class="or-name-intro">添加一个生日牌</span>\
		            </span>\
		            <span class="or-price">免费</span>\
		          </div>\
		        </li>\
		      <% } %>' 


   var Order = {
   		getOrderList : function(){
			 $.get('route.php',{
				mod:'order',
				action:'get_order_list'
			 },function(d){
			 	var html = mstmpl(orderListTmpl,{
			 		data:d.goods_list
			 	})
				$('#order_list').after(html);
			 },'json');
		},

		updateCakeNum:function(number,id){
			$.get('flow.php',{
				step:'update_cart',
				number:number,
				'rec_id':id,
				'_tc':Math.random()
			},function(d){

			},'json');
		},
		eventDelegate:function(){
			var container = $('#order_list').parent();
			container.delegate('.order_add','click',function(){
				var _this = $(this);
				var id=_this.data('id');
				var num = _this.prev().html();
				
			}).delegate('.order_des','click',function(){
				var _this = $(this);
				var id=_this.data('id');
				var num = _this.next().html();
			})
		}
	}
	Order.getOrderList();
	Order.eventDelegate();
})();