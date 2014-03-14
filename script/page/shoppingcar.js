(function(){

   var orderListTmpl = '<%for(var i=0;i<data.length;i++) {%>\
				 <%if(data[i].goods_id!=60){%>\
   				<li class="order-item" id="sub_order_<%=data[i].rec_id%>">\
		          <div class="or-main-container">\
		            <span class="or-name">\
		              <a href="#">\
						<%if(data[i].goods_id==61){%>\
						<img style="background-color:#fff;height: auto" src="img/lazhu.png" class="or-name-img"/>\
						<% } else {%>\
		              	<img style="height: auto" src="themes/default/images/sgoods/<%=data[i].goods_sn.substring(0,3)%>.png" class="or-name-img"/>\
						<% } %>\
		              	<span class="or-name-intro"><%=data[i].goods_name%><%if(data[i].goods_attr){%>（<%=data[i].goods_attr%>）<% } %></span></a>\
		            </span>\
		            <span class="or-price"><%=data[i].goods_price%></span>\
		            <span class="or-num or-num-ico">\
		              <em class="or-plus order_des" data-id="<%=data[i].rec_id%>">-</em>\
		              <span class="or-num-num"><%=data[i].goods_number%></span>\
		              <em class="or-add order_add" data-id="<%=data[i].rec_id%>">+</em>\
		            </span>\
		            <span class="or-total">\
						<span id="sub_total_<%=data[i].rec_id%>"><%=data[i].subtotal%></span>\
					</span>\
					<a href="#" class="or-handle order_cancel" data-id="<%=data[i].rec_id%>" data-goods="<%=data[i].goods_id%>">删除</a>\
		          </div>\
				  <%if(data[i].goods_id!=61){%>\
					  <div class="or-child-container">\
						<span class="or-name">\
						  <img src="img/canju.png" class="or-child-img"/><span class="or-name-intro">配套餐具</span>\
						</span>\
						<span class="or-price" id="fork_price_<%=data[i].rec_id%>">\
							<%if(data[i].extra_fork){%>0.5\
							<%}else{%>免费<% } %>\
						</span>\
						<span class="or-num or-num-ico2">\
						<em class="or-plus order_des_fork" free-num="<%=data[i].free_fork%>" data-id="<%=data[i].rec_id%>">-</em>\
						<span id="fork_num_<%=data[i].rec_id%>"><%=data[i].free_fork+data[i].extra_fork%>人份</span>\
						<em class="or-add order_add_fork" free-num="<%=data[i].free_fork%>" data-id="<%=data[i].rec_id%>">+</em>\
						</span>\
						<span class="or-total" id="fork_total_<%=data[i].rec_id%>"><%=0.5*data[i].extra_fork%>元</span>\
					  </div>\
					  <%if(!window.SHOPPING_CAR){%>\
						  <div class="or-child-container">\
							<span class="or-name" style="height:44px; overflow:hidden;">\
							  <span class="add-pai-area add_brith_brand"><em class="or-child-pai"></em><span class="or-name-intro brith_brand">添加一个生日牌</span></span>\
							  <div class="check-container" id="brith_input_container" style="display:none;">\
							    <input type="text" class="global-input vt-a brith_brand_input" placeholder="输入生日牌内容（10字以内）" />\
							    <span class="tips-container" style="display:none">生日牌不能超过10个字</span>\
							  </div>\
							</span>\
							<span class="or-price">免费</span>\
						  </div>\
					  <% } %>\
				  <% } %>\
		        </li>\
				<% } %>\
		      <% } %>' 

	//记录一个蜡烛的id

   var BRITH_ORDER_ID;
   var Order = {
   		getOrderList : function(){
			  var me = this;
			 if(window.ORDER_LIST){
				me._renderOrderList(ORDER_LIST);
				return;
			 }
			
			 $.get('route.php',{
				_tc:Math.random(),
				mod:'order',
				action:'get_order_list'
			 },function(d){
				me._renderOrderList(d);
			 },'json');
		},

		_renderOrderList:function(d){
			if(!d.goods_list.length){
					location.href="route.php?mod=order&action=empty";
					return false;
				}
				$('#my_order_frame').show();
			 	var html = mstmpl(orderListTmpl,{
			 		data:d.goods_list
			 	});

				//如果买了蜡烛 需要显示出来
				for(var i=0;i<d.goods_list.length;i++){
					if(d.goods_list[i].goods_id == 61){
						$('#birth_chk')[0].checked =true;
						BRITH_ORDER_ID = d.goods_list[i].rec_id;
					}
				}
				$('#order_list').after(html);
				MES.updateTotalPriceDisplay(d);
		},

		updateCakeNum:function(number,id){
			$.get('flow.php',{
				step:'update_cart',
				number:number,
				'rec_id':id,
				_tc:Math.random()
			},function(d){

			},'json');
		},
		eventDelegate:function(){
			var me = this;
			var container = $('#order_list').parent();
			var updateCart = function(id,num){
				$.get('route.php',{
					id:id,
					num:num,
					mod:'order',
					action:'update_cart',
					_tc:Math.random()
				},function(d){
					$('#sub_total_'+id).html(d.result);
					//update free fork number;
					$('#fork_num_'+id).html(d.free_fork+d.extra_fork+'人份');
					$('#fork_num_'+id).prev().attr('free-num',d.free_fork);
					$('#fork_num_'+id).next().attr('free-num',d.free_fork);

					//update total price
					MES.updateTotalPriceDisplay(d);
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
						MES.updateTotalPriceDisplay(d);
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
				if(num<1){
					num = 1;
				}
				_this.next().html(num);
				updateCart(id,num);
			}).delegate('.order_cancel','click',function(){
				var _this = $(this);
				var id=_this.data('id');
				var goods_id=_this.data('goods');
				require(['ui/confirm'],function(confirm){
					new confirm('确认取消这个子订单吗？',function(){
						MES.get({
							mod:'order',
							action:'drop_shopcart',
							param:{
								id:id
							},
							callback:function(d){
								//蜡烛 删除的时候把复选框也给干掉
								if(goods_id == 61){
									$('#birth_chk')[0].checked = false;
								}
								//重新结算帐单价格
								$('#sub_order_'+id).remove();
								MES.updateTotalPriceDisplay(d);
							}
						});
					});
				});
				return false;

			}).delegate('.order_des_fork','click',function(){
				//减少餐具
				var _this = $(this);
				var id=_this.data('id');
				var num = parseInt(_this.next().html(),10);
				num-=1;
				if(num<0){
					num = 0;
				}
				updateFork(id,num);
				return false;
			}).delegate('.order_add_fork','click',function(){
				//增加餐具
				var _this = $(this);
				var id=_this.data('id');
				var num = parseInt(_this.prev().html(),10);
				num+=1;
				updateFork(id,num);
			}).delegate('.add_brith_brand','click',function(){
				//触发生日牌的修改

				var _this = $(this);
				var text = _this.find('.or-name-intro')[0].innerHTML;
				_this.find('.or-name-intro').hide();
				//一些细节
				if($.trim(text) !== '添加一个生日牌'){
					_this.next().find('input').val(text);
				}

				_this.next().show();
				$('.brith_brand_input').focus();
			}).delegate('.brith_brand_input','blur',function(){
				var _this = $(this);
				var text = _this.val();

				if($.trim(text) == ''){
					text = '添加一个生日牌';
				}
				
				//验证生日牌
				if($.trim(text).split('').length>10){
					_this.next().show();
					return;
					// require(['ui/confirm'],function(confirm){
					// 	new confirm('生日牌不能超过10个字');
					// });
					// return;
				}

				_this.parent().hide();
				//这里加了个父级容器来显示错误和placeholder，由于focus了，提示就暂时没有了。
				_this.parent().prev().find('.or-name-intro').text(text).show();
				_this.val('');
				return false;
			}).delegate('.brith_brand_input','focus',function(){
				$(this).next().hide();
			});
		},

		bind:function(){
			$('#leave_message').click(function(){
				setTimeout(function(){
				 var jqInput = $('#message_input');
				 if($('#leaveMes')[0].checked){
					jqInput.show();
					$('#leaving_message').val(jqInput.val());
				 }else{
					jqInput.hide();
					$('#leaving_message').val('');
				 }
				},0);
			});

			$('#voucher_label').click(function(){
				if($('#balance')[0].checked==true){
 					$('#voucher')[0].checked=false;
 					require(['ui/confirm'],function(confirm){
 						new confirm('现金券礼金卡不能同时使用哦~');
 					});
 				}else{
 					 if($('#voucher')[0].checked){
 						$('#money_card_input_frame').show();
 					 }else{
 						$('#money_card_input_frame').hide();
 						$('#disaccount').html(0);
 						$('#final_total').html($('.order_total').html());
 					 }
 				}
			});
			var lock = false;
			$('#birth_title').click(function(){
				  //取消蜡烛
				  setTimeout(function(){
				  if(lock){
					return false;
				  }
				  lock = true;
				 
				  setTimeout(function(){
					  if(!$('#birth_chk')[0].checked){
						 var id = BRITH_ORDER_ID;
						 //$('#birth_chk')[0].checked = false;
						 $.get('route.php',{
								_tc:Math.random(),
								id:id,
								mod:'order',
								action:'drop_shopcart'
							},function(d){
								//重新结算帐单价格
								$('#sub_order_'+id).remove();
								MES.updateTotalPriceDisplay(d);
							},'json');
					  }else{
						 //$('#birth_chk')[0].checked = true;
						  var goods        = new Object();
						  var spec_arr     = new Array();
						  var fittings_arr = new Array();
						  var number       = 1;
						  var quick		   = 0;

						  //商品重量
						  goods.spec     = spec_arr;
						  goods.goods_id = 61;
						  //数量
						  goods.number   = number;
						  goods.parent   = 0;//(typeof(parentId) == "undefined") ? 0 : parseInt(parentId);
						  $.post('route.php?mod=order&action=add_to_cart', {
							goods:$.toJSON(goods),
							goods_id:60
						  }, function(d){
						  		//第一次加蜡烛没有总价
						  		d.data.subtotal = '￥5.00';
								var html = mstmpl(orderListTmpl,{
									data:[d.data]
								});

								$('#order_list').after(html);
								BRITH_ORDER_ID = d.data.rec_id;
								MES.updateTotalPriceDisplay(d);
						  },'json');
					  }
					  setTimeout(function(){
						lock = false;
					  },100);
				  },100);
				 },0);
			});
		}
	}
	Order.getOrderList();
	Order.eventDelegate();
	Order.bind();


})();
