(function(){
   var orderListTmpl = '<%for(var i=0;i<data.length;i++) {%>\
   				<li class="order-item" id="sub_order_<%=data[i].rec_id%>">\
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
		            <span class="or-total">\
						<span id="sub_total_<%=data[i].rec_id%>"><%=data[i].subtotal%></span>\
						<em class="or-close order_cancel" data-id="<%=data[i].rec_id%>">x</em>\
					</span>\
		          </div>\
		          <div class="or-child-container">\
		            <span class="or-name">\
		              <img src="" class="or-child-img"/><sapn class="or-name-intro">配套餐具</span>\
		            </span>\
		            <span class="or-price" id="fork_price_<%=data[i].rec_id%>">免费</span>\
					<span class="or-num">\
					<em class="or-plus order_des_fork" free-num="<%=data[i].free_fork%>" data-id="<%=data[i].rec_id%>">-</em>\
		            <span id="fork_num_<%=data[i].rec_id%>"><%=data[i].free_fork%>人份</span>\
					<em class="or-add order_add_fork" free-num="<%=data[i].free_fork%>" data-id="<%=data[i].rec_id%>">+</em>\
					</span>\
		            <span class="or-total" id="fork_total_<%=data[i].rec_id%>">0元</sapn>\
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
				$('#order_total').html(d.total.goods_price);
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
			var updateCart = function(id,num){
				$.get('route.php',{
					id:id,
					num:num,
					mod:'order',
					action:'update_cart'
				},function(d){
					$('#sub_total_'+id).html(d.result);

					//update free fork number;
					$('#fork_num_'+id).html(d.free_fork+'人份');
					$('#fork_num_'+id).prev().attr('free-num',d.free_fork);
					$('#fork_num_'+id).next().attr('free-num',d.free_fork);

					//update total price
					$('#order_total').html(d.total);
				},'json');
			}


			var updateFork = function(id,num){
				$.post('route.php?mod=order&action=update_fork',{
					id:id,
					num:num
				},function(d){
					if(d.code == 0){
						if(d.price>0){
							$('#fork_price_'+id).html('0.5');
						}else{
							$('#fork_price_'+id).html('免费');
						}

						$('#fork_total_'+id).html('￥'+d.price);
						//update free fork number;
						$('#fork_num_'+id).html(d.num+'人份');
						//update total price
						$('#order_total').html(d.total);
					}
				},'json');
			}
			
			//更新一个子订单内蛋糕的数目
			container.delegate('.order_add','click',function(){
				var _this = $(this);
				var id=_this.data('id');
				var num = parseInt(_this.prev().html(),10);
				num+=1;
				_this.prev().html(num);
				updateCart(id,num);
				
			}).delegate('.order_des','click',function(){
				var _this = $(this);
				var id=_this.data('id');
				var num =parseInt( _this.next().html(),10);
				num-=1;
				if(num<0){
					num = 0;
				}
				_this.next().html(num);
				updateCart(id,num);
			}).delegate('.order_cancel','click',function(){
				var _this = $(this);
				var id=_this.data('id');
				$.get('route.php',{
					id:id,
					mod:'order',
					action:'drop_shopcart'
				},function(d){
					$('#sub_order_'+id).remove();
				},'json');
			}).delegate('.order_des_fork','click',function(){
				var _this = $(this);
				var id=_this.data('id');
				var num = parseInt(_this.next().html(),10);
				num-=1;
				if(num<0){
					num = 0;
				}
				updateFork(id,num);
			}).delegate('.order_add_fork','click',function(){
				var _this = $(this);
				var id=_this.data('id');
				var num = parseInt(_this.prev().html(),10);
				num+=1;
				updateFork(id,num);
			});
		}
	}
	Order.getOrderList();
	Order.eventDelegate();


})();