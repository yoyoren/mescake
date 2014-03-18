(function(){
	var detailTmpl = '<div class="order-detail-con clearfix">\
        <p class="fl-l">订单号：<%=order.order_sn%> | <%=order.formated_add_time%></p>\
        <p class="fl-r"><b>订单状态：\
			<%if(order.order_status==0){%>未确认\
			<%}else if(order.order_status==1){%>已确认\
			<%}else if(order.order_status==2){%>已取消\
			<%}else if(order.order_status==3){%>无效\
			<%}else if(order.order_status==4){%>退货\
			<%}else if(order.order_status==5){%>已分单\
			<%}else if(order.order_status==6){%>部分分单\
			<%}%>| 配送状态：\
							<%if(order.shipping_status==0){%>未发货\
							<%}else if(order.shipping_status==1){%>已发货\
							<%}else if(order.shipping_status==2){%>已收货\
							<%}else if(order.shipping_status==3){%>备货中\
							<%}else if(order.shipping_status==4){%>已发货(部分商品)\
							<%}else if(order.shipping_status==5){%>发货中(处理分单)\
						    <%}else if(order.shipping_status==6){%>已发货(部分商品)<%}%>|\
					<%if(order.pay_id==4){%>货到付款\
					<% } else {%>\
						<%if(order.pay_status==0||!order.pay_status){%>\
							未付款\
						<% }else if(order.pay_status==1){%>\
							付款中\
						<% } else {%>\
							已付款\
						<% } %>\
					<% } %></b>\
			</p>\
		</div>\
		<div class="n-box">\
			<em class="buy-line"></em>\
			<div class="box-inner">\
			  <div class="od-title-area">\
				<div class="od-title1">商品</div>\
				<div class="od-title2">单价</div>\
				<div class="od-title3">数量</div>\
				<div class="od-title4">小计</div>\
			  </div>\
			  <div class="list-line"></div>\
			  <ul class="od-ul">\
				<%for(var i=0;i<goods.length;i++){%>\
					<%if(goods[i].goods_id != 60){%>\
						<li>\
						  <div class="od-title1">\
							<%if(goods[i].goods_id == CANDLE||goods[i].goods_id == NUM_CANDLE){%>\
								<span class="od-img-area"><img src="img/lazhu.png" class="od-img" width="70"></span><%=goods[i].goods_name%>\
							<%}else{%>\
								<a target="_blank" href="route.php?mod=goods&action=goods_detail_page&id=<%=goods[i].goods_id%>">\
								  <span class="od-img-area">\
									<img src="themes/default/images/sgoods/<%=goods[i].goods_sn.substring(0,3)%>.png" class="od-img" width="70">\
								  </span><%=goods[i].goods_name%>（<%=goods[i].goods_attr%>）\
								</a>\
							<% } %>\
						  </div>\
						  <div class="od-title2"><%=goods[i].goods_price%>元</div>\
						  <div class="od-title3"><%=goods[i].goods_number%></div>\
						  <div class="od-title4">￥<%=goods[i].goods_number*parseInt(goods[i].goods_price.replace("￥",""),10)%>元</div>\
						</li>\
						<%if(goods[i].goods_id != CANDLE&&goods[i].goods_id != 60&&goods[i].goods_id != NUM_CANDLE){%>\
							<%if(goods[i].card_message!="无"){%>\
							  <li>\
								<div class="od-title1">\
									<span class="od-img-area">\
										<img src="img/order-detail4.png" class="od-img">\
									</span>生日牌(<%=goods[i].card_message%>)\
			                    </div>\
								<div class="od-title2">￥0.00元</div>\
								<div class="od-title3"><%=goods[i].goods_number%></div>\
								<div class="od-title4">￥0元</div>\
							  </li>\
							 <% } %>\
							<%if(order.fork_message&&order.fork_message[goods[i].goods_id]){%>\
								<li>\
								<div class="od-title1">\
									<span class="od-img-area">\
									 <img src="img/order-detail1.png" class="od-img">\
									</span>收费餐具\
								</div>\
								<div class="od-title2">￥0.50元</div>\
								<div class="od-title3"><%=order.fork_message[goods[i].goods_id]%></div>\
								<div class="od-title4">￥<%=order.fork_message[goods[i].goods_id]*0.5%>元</div>\
							  </li>\
							 <% } %>\
							<li>\
								<div class="od-title1">\
									<span class="od-img-area">\
									  <img src="img/order-detail1.png" class="od-img">\
									</span>免费配套餐具\
								</div>\
								<div class="od-title2">￥0.00元</div>\
								<div class="od-title3"><%=goods[i].goods_number*parseInt(goods[i].goods_attr)*5%></div>\
								<div class="od-title4">￥0.00元</div>\
							 </li>\
						  <% } %>\
						<% } %>\
					<% } %>\
							<li>\
								<div class="od-title1">\
									<span class="od-img-area">\
									  <img src="img/order-detail1.png" class="od-img">\
									</span>运费\
								</div>\
								<div class="od-title2"><%=order.formated_shipping_fee%></div>\
								<div class="od-title3">1</div>\
								<div class="od-title4"><%=order.formated_shipping_fee%>元</div>\
							 </li>\
			  </ul>\
			  <div class="list-line"></div>\
			  <div class="odc-adress-area">\
				<p>\
				  将于<%=order.best_time%>送至<br>\
				  北京市 <%=order.cityName%> <%=order.districtName%> <%=order.address%><br>\
				  <%=order.consignee%>，<%=order.mobile%>\
				</p>\
				<p class="od-total">总计：<b><%=order.order_amount%></b>元</p>\
			  </div>\
			</div>\
			<em class="buy-line2"></em>\
		  </div>\
		  <div class="od-btn-area tl-c">\
				<%if((order.pay_status==0||!order.pay_status)&&order.pay_id!=4&&order.order_status!=2){%>\
							<%if(order.pay_name=="快钱") {%>\
								<a href="#" class="btn status1-btn" onclick="document.forms[&quot;kqPay&quot;].submit();">付款</a>\
								<div style="display:none"><%=order.pay_online.replace(/script/gi,"a")%></div>\
							<%} else {%>\
								<a href="#" id="pay_online" class="btn status1-btn">付款</a>\
							<% } %>\
				<% } %>\
				<%if(order.order_status==0&&order.pay_status!==2){%>\
					<a href="#" class="btn" id="canel_order">取消订单</a>\
				<% } %>\
		   </div>';
    var orderId = location.href.split('order_id=').pop();
	var OrderDetail = {
		render:function(){
			
			$.get('route.php?action=get_user_order_detail&mod=account',{
				order_id:orderId
			},function(d){
				if(d.code == 0){
					var goods = d.goods_list;
					var order = d.order;
					
					var cardmessage = order.card_message.split(';');
					for(var i=0,j=0;i<goods.length;i++){
						if(goods[i].goods_id!=60&&goods[i].goods_id!=CANDLE){
							goods[i].card_message = cardmessage[j];
							j++;
						}
					}
					var html = mstmpl(detailTmpl,{
						goods:goods,
						order:order,
						cardmessage:cardmessage
					});
					$('#order_container').prepend(html);
					OrderDetail.bind();

					//如果是在线支付 则这样
					if(d.pay_online){
						$('#pay_online').click(function(){
							var f = window.open(d.pay_online);
							//不能支付的时候提示用户
							if(f==null){
								require(['ui/confirm'],function(confirm){
									new confirm("您的浏览器启用拦截支付宝弹出窗口过滤功能！\n\n请暂时先关闭此功能以完成支付！")
								});
							}
						});
					}
				}else{
				
				}
			},'json');
		},bind:function(){
			$('#canel_order').click(function(){
				require(['ui/confirm'],function(confirm){
				new confirm('确认取消该订单吗？取消后将无法恢复！',function(){
					$.post('route.php?action=del_one_order&mod=account',{
						'order_id':orderId
					},function(d){
						if(d.code == 0){
							location.reload();
						}else{
							require(['ui/confirm'],function(confirm){
								new confirm("订单取消失败！可能是该订单已经确认，将不能取消");
							});
						}
					},'json');
				});
				
				});
				return false;
			});
		}
	}

	OrderDetail.render();
	
})();