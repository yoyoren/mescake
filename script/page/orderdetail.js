(function(){
	var detailTmpl = '<div class="order-detail-con">订单号：<b><%=order.order_sn%></b><span class="odc-date"> <%=order.formated_add_time%></span>\
	    <%if(order.order_status==0){%>\
	    	<a href="#" id="canel_order" class="td-u link-color fl-r">取消订单</a>\
	    <%}%>\
	    </div>\
        <div class="odc-container">\
          <div class="odc-title">\
            <p class="fl-l"><b><%=order.formated_total_fee%></b></p>\
            <p class="fl-r">状态：\
							<%if(order.shipping_status==0){%>未发货\
							<%}else if(order.shipping_status==1){%>已发货\
							<%}else if(order.shipping_status==2){%>已收货\
							<%}else if(order.shipping_status==3){%>备货中\
							<%}else if(order.shipping_status==4){%>已发货(部分商品)\
							<%}else if(order.shipping_status==5){%>发货中(处理分单)\
						    <%}else {%>已发货(部分商品)<%}%>\
            <%if(order.pay_id==4){%>货到付款\
            <% } else {%>\
            	<%if(order.pay_status==0||!order.pay_status){%>\
            	<a href="#" id="pay_online" class="btn">付款</a>\
            	<% }else if(order.pay_status==1){%>\
            	<a href="#" id="" class="btn">付款中</a>\
            	<% } else {%>\
            	<a href="#" id="" class="btn">已付款</a>\
            	<% } %>\
            <% } %>\
            </p>\
          </div>\
          <ul class="order-ul">\
			<%for(var i=0;i<goods.length;i++){%>\
            <li class="order-item">\
              <div class="or-main-container">\
                <span class="or-name">\
					<%if(goods[i].goods_id == 61){%>\
						<img src="img/lazhu.png" class="or-name-img"><span class="or-name-intro"><%=goods[i].goods_name%></span>\
					<%}else if(goods[i].goods_id == 60){%>\
						<img src="img/canju.png" class="or-name-img"><span class="or-name-intro"><%=goods[i].goods_name%></span>\
					<%}else{%>\
						<a target="_blank" href="goods.php?id=<%=goods[i].goods_id%>">\
						  <img src="themes/default/images/sgoods/<%=goods[i].goods_sn.substring(0,3)%>.png" class="or-name-img"><span class="or-name-intro"><%=goods[i].goods_name%>（<%=goods[i].goods_attr%>）</span>\
						</a>\
					<% } %>\
                </span>\
                <span class="or-price"><%=goods[i].goods_price%> x<%=goods[i].goods_number%></span>\
              </div>\
            </li>\
			<% } %>\
          </ul>\
          <div class="odc-adress-area">\
            <p class="send-to">送至：</p>\
            <p>\
              <%=order.consignee%>，<%=order.mobile%><br>\
              北京市 <%=order.cityName%> <%=order.address%><br>\
              将于<b><%=order.best_time%></b> 送至\
            </p>\
          </div>\
        </div>';
    var orderId = location.href.split('order_id=').pop();
	var OrderDetail = {
		render:function(){
			
			$.get('route.php?action=get_user_order_detail&mod=account',{
				order_id:orderId
			},function(d){
				if(d.code == 0){
					var html = mstmpl(detailTmpl,{
						goods:d.goods_list,
						order:d.order
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
							location.href="route.php?mod=account&action=order_list";
						}
					},'json');
				});
			});
			});
		}
	}

	OrderDetail.render();
	
})();