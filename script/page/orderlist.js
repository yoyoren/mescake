(function(){
$('#my_order_frame').show();
  var orderListTmpl = '<%for(var i=0;i<data.length;i++) {%>\
						<tr id="orderitem_<%=data[i].order_id%>">\
						  <td><%=data[i].order_sn%></td>\
						  <td><%=data[i].best_time.split(" ")[0]%></td>\
						  <td>\
							<%for(var j=0;j<1;j++){%>\
							<%if(data[i].detail[j].goods_sn!=61){%>\
							<a href="route.php?mod=account&action=order_detail&order_id=<%=data[i].order_id%>">\
								<img src="themes/default/images/sgoods/<%=data[i].detail[j].goods_sn.substring(0,3)%>.png">\
							</a>\
							<% } %><% } %></td>\
						  <td><%=data[i].order_amount%></td>\
						  <td>\
							<%if(data[i].pay_id==4){%>货到付款<%} else {%>\
						    <%if(data[i].pay_status==0){%>未付款\
						    <%}else if(data[i].pay_status==1){%>付款中\
						    <%}else {%>已付款<%}%>\
							<% } %>\
							( <%if(data[i].shipping_status==0){%>未发货\
							<%}else if(data[i].shipping_status==1){%>已发货\
							<%}else if(data[i].shipping_status==2){%>已收货\
							<%}else if(data[i].shipping_status==3){%>备货中\
							<%}else if(data[i].shipping_status==4){%>已发货(部分商品)\
							<%}else if(data[i].shipping_status==5){%>发货中(处理分单)\
						    <%}else {%>已发货(部分商品)<%}%>)\
						  </td>\
						  <td><a href="route.php?mod=account&action=order_detail&order_id=<%=data[i].order_id%>" class="td-u link-color">查看</a><br>\
						  <%if(data[i].order_status==0){%>\
						  	<a href="#" class="td-u link-color cancel_order" data-id="<%=data[i].order_id%>">取消订单</a></td>\
						  <% } %>\
						  <td>\
						  <%if(data[i].pay_id<4&&data[i].pay_status==0){%>\
						  <a href="<%=data[i].pay_online%>" class="btn pay_order" data-id="<%=data[i].order_id%>">\
						  	去付款\
						  </a>\
						  <% } %>\
						  </td>\
						</tr>\
						<% } %>';
 var OrderList = {
	render:function(){
		MES.get({
			mod:'account',
			action:'get_user_order_list',
			callback:function(d){
				var html = mstmpl(orderListTmpl,{data:d.orders});
				$('#order_list').append(html);
			}
		});
	},
	checksetPassword:function(){
		$.get('route.php?action=is_unset_password_user&mod=account',{
			_tc:Math.random()
		},function(d){
			if(d.msg){
				$('#order_login_tip').show();
				$('#set_password').click(function(){
					location.href="route.php?action=set_password&mod=account";
				});
			}
		},'json');
	},

	bind:function(){
		//pay_order
		$('body').delegate('.cancel_order','click',function(){
			var _id = $(this).data('id');
			require(['ui/confirm'],function(confirm){
				new confirm('确认取消该订单吗？取消后将无法恢复！',function(){
					$.post('route.php?action=del_one_order&mod=account',{
						'order_id':_id
					},function(d){
						if(d.code == 0){
							$('#orderitem_'+_id).remove();
						}else{
							require(['ui/confirm'],function(confirm){
								new confirm("订单取消失败！可能是该订单已经确认，将不能取消");
							});
						}
					},'json');
				});
			});
		}).delegate('.pay_order','click',function(){
			var payUrl = $(this).attr('href');
			var f = window.open(payUrl);
			if(f==null){
				require(['ui/confirm'],function(confirm){
					new confirm("您的浏览器启用拦截支付宝弹出窗口过滤功能！\n\n请暂时先关闭此功能以完成支付！")
				});
			}
			return false;
		});

	}
 }
 OrderList.render();
 OrderList.bind();
 OrderList.checksetPassword();
})();