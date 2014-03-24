(function(){
   var candleTmpl = '<li class="clearfix sub_order_<%=rec_id%>" id="sub_order_<%=data.rec_id%>">\
							  <div class="od-title1" data-id="<%=rec_id%>">\
								<span class="od-img-area">\
								  <img src="css/img/lazhu1.jpg" class="od-img" style="width:50px;">\
								</span>\
								<span class="or-name-intro">生日蜡烛</span>\
							  </div>\
							  <div class="od-title2">￥<%=data.goods_price%></div>\
							  <div class="od-title3">\
								  <em class="or-minus order_des" data-id="<%=data.rec_id%>"></em>\
								  <span class="or-num-num"><%=data.goods_number%></span>\
								  <em class="or-plus order_add" data-id="<%=data.rec_id%>"></em>\
							   </div>\
							  <div class="od-title4" id="sub_total_<%=data.rec_id%>">￥<%=data.goods_price*data.goods_number%>.00</div>\
							 <a href="#" class="or-handle order_cancel" data-id="<%=data.rec_id%>" data-goods="<%=data.goods_id%>">删除</a>\
							 </li>';
   var numCandleTmpl = '<li class="clearfix sub_order_<%=rec_id%>" id="sub_order_<%=data.rec_id%>">\
							  <div class="od-title1" data-id="<%=rec_id%>">\
								<span class="od-img-area">\
								  <img src="css/img/order-detail2.png" class="od-img" style="width:50px;">\
								</span>\
								<span class="or-name-intro">数字蜡烛(<%=data.goods_attr%>)</span>\
							  </div>\
							  <div class="od-title2">￥<%=data.goods_price%></div>\
							  <div class="od-title3">\
								  <em class="or-minus order_des" data-id="<%=data.rec_id%>"></em>\
								  <span class="or-num-num"><%=data.goods_number%></span>\
								  <em class="or-plus order_add" data-id="<%=data.rec_id%>"></em>\
							  </div>\
							  <div class="od-title4" id="sub_total_<%=data.rec_id%>">￥<%=data.goods_price*data.goods_number%>.00</div>\
							  <a href="#" class="or-handle order_cancel" data-id="<%=data.rec_id%>" data-goods="<%=data.goods_id%>">删除</a>\
							 </li>';
   var orderListTmpl = '<%for(var i=0;i<data.length;i++) {%>\
				 <%if(data[i].goods_id!=FORK){%>\
					<li class="clearfix sub_order_<%=data[i].rec_id%>"  id="sub_order_<%=data[i].rec_id%>">\
					  <div class="od-title1">\
						 <a href="route.php?mod=goods&action=goods_detail_page&id=<%=data[i].goods_id%>" target="_blank">\
						 <span class="od-img-area">\
							<%if(data[i].goods_id==CANDLE){%>\
								<img style="background-color:#fff;height: auto" src="img/lazhu.png"  class="od-img"/>\
							<% } else if(data[i].goods_id==NUM_CANDLE){%>\
								<img style="height: auto" width="70"src="css/img/order-detail2.png"  class="od-img"/>\
							<% } else {%>\
								<img style="height: auto" width="70" src="themes/default/images/sgoods/<%=data[i].goods_sn.substring(0,3)%>.png"  class="od-img"/>\
							<% } %>\
					     </span>\
						 <span class="or-name-intro"><%=data[i].goods_name%><%if(data[i].goods_attr){%>（<%=data[i].goods_attr%>）<% } %></span>\
						 </a>\
					  </div>\
					  <div class="od-title2">￥<%=data[i].goods_price%></div>\
					  <div class="od-title3">\
						<span class="or-num or-num-ico">\
						  <em class="or-minus order_des" data-id="<%=data[i].rec_id%>"></em>\
						  <span class="or-num-num"><%=data[i].goods_number%></span>\
						  <em class="or-plus order_add" data-id="<%=data[i].rec_id%>"></em>\
						</span>\
					  </div>\
					  <div class="od-title4"  id="sub_total_<%=data[i].rec_id%>"><%=data[i].subtotal%></div>\
					  <a href="#" class="or-handle order_cancel" data-id="<%=data[i].rec_id%>" data-goods="<%=data[i].goods_id%>">删除</a>\
				  </li>\
				  <% } %>\
				  <%if(data[i].goods_id!=NUM_CANDLE&&data[i].goods_id!=CANDLE&&data[i].goods_id!=FORK){%>\
					<li class="clearfix sub_order_<%=data[i].rec_id%>" id="sub_order_fork_<%=data[i].rec_id%>">\
					 <div class="od-title1">\
						 <a href="#" onclick="return false">\
						 <span class="od-img-area">\
							<img src="img/order-detail1.png"  class="od-img">\
					     </span>\
					     <span class="or-name-intro">配套餐具</span>\
						 </a>\
					  </div>\
					  <div class="od-title2">￥0.5</div>\
					  <div class="od-title3">\
						<span class="or-num or-num-ico">\
						  <em class="or-minus order_des_fork" free-num="<%=data[i].free_fork%>"  data-id="<%=data[i].rec_id%>"></em>\
						  <span class="or-num-num" id="fork_num_<%=data[i].rec_id%>"><%=data[i].free_fork+data[i].extra_fork%>人份</span>\
						  <em class="or-plus order_add_fork" free-num="<%=data[i].free_fork%>"  data-id="<%=data[i].rec_id%>"></em>\
						</span>\
					  </div>\
					  <div class="od-title4"  id="fork_total_<%=data[i].rec_id%>">￥<%=0.5*data[i].extra_fork%></div>\
					 </li>\
					 <%if(candleHash[data[i].rec_id]){%>\
						 <li class="clearfix sub_order_<%=data[i].rec_id%>" id="sub_order_<%=candleHash[data[i].rec_id].rec_id%>">\
						  <div class="od-title1" data-id="<%=data[i].rec_id%>">\
							<span class="od-img-area">\
							  <img src="css/img/lazhu1.jpg" class="od-img" style="width:50px;">\
							</span>\
							<span class="or-name-intro">生日蜡烛</span>\
						  </div>\
						  <div class="od-title2">￥<%=candleHash[data[i].rec_id].goods_price%></div>\
						  <div class="od-title3">\
								  <em class="or-minus order_des" data-id="<%=candleHash[data[i].rec_id].rec_id%>"></em>\
								  <span class="or-num-num"><%=candleHash[data[i].rec_id].goods_number%></span>\
								  <em class="or-plus order_add" data-id="<%=candleHash[data[i].rec_id].rec_id%>"></em>\
						  </div>\
						  <div class="od-title4" id="sub_total_<%=candleHash[data[i].rec_id].rec_id%>"><%=candleHash[data[i].rec_id].subtotal%></div>\
						  <a href="#" class="or-handle order_cancel" data-id="<%=candleHash[data[i].rec_id].rec_id%>" data-goods="<%=candleHash[data[i].rec_id].goods_id%>">删除</a>\
						 </li>\
					 <% } %>\
					 <%if(candleNumHash[data[i].rec_id]){%>\
						 <%for(var j=0;j<candleNumHash[data[i].rec_id].length;j++) {%>\
							 <li class="clearfix sub_order_<%=data[i].rec_id%>" id="sub_order_<%=candleNumHash[data[i].rec_id][j].rec_id%>">\
							  <div class="od-title1" data-id="<%=data[i].rec_id%>">\
								<span class="od-img-area">\
								  <img src="css/img/order-detail2.png" class="od-img" style="width:50px;">\
								</span>\
								<span class="or-name-intro">数字蜡烛(<%=candleNumHash[data[i].rec_id][j].goods_attr%>)</span>\
							  </div>\
							  <div class="od-title2">￥<%=candleNumHash[data[i].rec_id][j].goods_price%></div>\
							  <div class="od-title3">\
								  <em class="or-minus order_des" data-id="<%=candleNumHash[data[i].rec_id][j].rec_id%>"></em>\
								  <span class="or-num-num"><%=candleNumHash[data[i].rec_id][j].goods_number%></span>\
								  <em class="or-plus order_add" data-id="<%=candleNumHash[data[i].rec_id][j].rec_id%>"></em></div>\
							  <div class="od-title4" id="sub_total_<%=candleNumHash[data[i].rec_id][j].rec_id%>"><%=candleNumHash[data[i].rec_id][j].subtotal%></div>\
							 <a href="#" class="or-handle order_cancel" data-id="<%=candleNumHash[data[i].rec_id][j].rec_id%>" data-goods="<%=candleNumHash[data[i].rec_id][j].goods_id%>">删除</a>\
							 </li>\
						 <% } %>\
					 <% } %>\
					 <%if(!window.SHOPPING_CAR){%>\
					 <li class="clearfix sub_order_<%=data[i].rec_id%>" id="brith_card_add_<%=data[i].rec_id%>">\
						  <div class="od-title1 add_brith_card pointer" data-id="<%=data[i].rec_id%>" data-attr="<%=parseInt(data[i].goods_attr,10)%>">\
							<span class="od-img-area"><img src="img/add-ico.png" class="od-img od-img-add-ico"></span>添加生日牌（免费）\
						  </div>\
						  <div class="od-title2"></div>\
						  <div class="od-title3">\
						  </div>\
						  <div class="od-title4"></div>\
					 </li>\
					 <li class="clearfix sub_order_<%=data[i].rec_id%>" style="display:none" id="brith_card_display_<%=data[i].rec_id%>">\
					  <div class="od-title1 add_brith_card" data-id="<%=data[i].rec_id%>" data-attr="<%=parseInt(data[i].goods_attr,10)%>">\
						<span class="od-img-area">\
						  <img src="img/order-detail4.png" class="od-img">\
						</span>\
						<span class="or-name-intro">生日牌（<span class="brith_card_name"></span>）</span>\
					  </div>\
					  <div class="od-title2">免费</div>\
					  <div class="od-title3"></div>\
					  <div class="od-title4"></div>\
					 </li>\
					 <% }　%>\
					 <li class="clearfix sub_order_<%=data[i].rec_id%>">\
						  <div class="od-title1 add_candle pointer" data-id="<%=data[i].rec_id%>">\
							<span class="od-img-area"><img src="img/add-ico.png" class="od-img od-img-add-ico"></span>添加特制生日蜡烛\
						  </div>\
						  <div class="od-title2"></div>\
						  <div class="od-title3">\
						  </div>\
						  <div class="od-title4"></div>\
					 </li>\
				 <% } %>\
		      <% } %>' 

	//记录一个蜡烛的id

   var BRITH_ORDER_ID;
   var ShoppingCar = {
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
				var goodsData = d.goods_list;
				var staffData = [];
				//普通蜡烛
				var candleHash = {};
				
				//数字蜡烛
				var candleNumHash = {};
				//如果买了蜡烛 需要显示出来
				for(var i=0;i<goodsData.length;i++){
					var _good = goodsData[i];
					if(_good.goods_id == CANDLE){
					  candleHash[_good.parent_id] = goodsData[i];
					}else if(_good.goods_id == NUM_CANDLE){
					  if(!candleNumHash[_good.parent_id]){
						 candleNumHash[_good.parent_id] = [];
					  }
					  candleNumHash[_good.parent_id].push(_good);
					}else{
						staffData.push(_good);
					}

					//订单中有大于5磅的货物 则不能货到付款
					if(parseInt(_good.goods_attr,10)>5&&!window.SHOPPING_CAR){
						window.HAS_BIG_STAFF = true;
						setTimeout(function(){
							$('#alipay_radio').trigger('click');
						},0);
						$('#cash').hide();
						$('#cash_radio').hide();
						$('#cash_sel').hide();
					}
				}
		
			 	var html = mstmpl(orderListTmpl,{
			 		data:staffData,
					candleHash:candleHash,
					candleNumHash:candleNumHash
			 	});

				
				$('#order_list').prepend(html);
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
					new confirm('是否删除所选项目？',function(){
						MES.get({
							mod:'order',
							action:'drop_shopcart',
							param:{
								id:id
							},
							callback:function(d){
								//重新结算帐单价格
								$('.sub_order_'+id).remove();
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
			}).delegate('.add_brith_card','click',function(){
				//触发生日牌的修改
				var id = $(this).data('id');
				var attr = $(this).data('attr');
				var words = 10;
				if(attr==1){
					words = 4;
				}else if(attr==2){
					words = 6;
				}else if(attr==3){
					words = 8;
				}
				require(['ui/brithcard'],function(brithcardDialog){
					brithcardDialog.show({
						id:id,
						words:words
					});
				});
				return;
			}).delegate('.add_candle','click',function(){
				//触发生日牌的修改
				var id = $(this).data('id');
				require(['ui/candle'],function(candleDialog){
					candleDialog.show({
						id:id,
						callback:function(d,candle){
							var _tpl = candleTmpl;
							if(candle == NUM_CANDLE){
							   _tpl = numCandleTmpl;
							}
							var _renderData = d.data;
							
							var html = mstmpl(_tpl,{
									data:_renderData,
									rec_id:id
							});
									
							if($('#sub_order_'+_renderData.rec_id).length){
								var container = $('#sub_order_'+_renderData.rec_id)
								container.after(html);
								container.remove();
							}else{
								$('#sub_order_fork_'+id).after(html);
							}
							MES.updateTotalPriceDisplay(d);
						}
					});
				});
				return;
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
			$('#message_input').blur(function(){
				if($(this).val().length>140){
					MES.inputError('message_input_error');
				}
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
						  goods.goods_id = CANDLE;
						  //数量
						  goods.number   = number;
						  goods.parent   = 0;//(typeof(parentId) == "undefined") ? 0 : parseInt(parentId);
						  $.post('route.php?mod=order&action=add_to_cart', {
							goods:$.toJSON(goods),
							goods_id:FORK,
							parent_id:0
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
		},
		initPlacerHolderForIE:function(){
			$('#message_input').placeholder();
		}
	}
	ShoppingCar.getOrderList();
	ShoppingCar.eventDelegate();
	ShoppingCar.bind();
	ShoppingCar.initPlacerHolderForIE();


})();
