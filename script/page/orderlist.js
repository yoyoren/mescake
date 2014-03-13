(function(){
$('#my_order_frame').show();
  var orderListTmpl = '<%for(var i=0;i<data.length;i++) {%>\
						<tr id="orderitem_<%=data[i].order_id%>">\
						  <td><%=data[i].order_sn%></td>\
						  <td><%=data[i].best_time.split(" ")[0]%></td>\
						  <td>\
							<%for(var j=0;j<data[i].detail.length;j++){%>\
							<%if(data[i].detail[j].goods_id!=61&&data[i].detail[j].goods_id!=60){%>\
							<a href="route.php?mod=account&action=order_detail&order_id=<%=data[i].order_id%>">\
								<img src="themes/default/images/sgoods/<%=data[i].detail[j].goods_sn.substring(0,3)%>.png">\
							</a>\
							<% } %><% } %></td>\
						  <td><%=parseFloat(data[i].order_amount,10)%></td>\
						  <td class="order_status">\
							<%if(data[i].order_status==2){%>已取消\
							<%} else {%>\
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
							<%}%>\
						  </td>\
						  <td><a href="route.php?mod=account&action=order_detail&order_id=<%=data[i].order_id%>" class="td-u link-color">查看</a><br>\
						  <%if(data[i].order_status==0&&data[i].pay_status!==2){%>\
						  	<a href="#" class="td-u link-color cancel_order" data-id="<%=data[i].order_id%>">取消订单</a></td>\
						  <% } %>\
						  <td>\
						  <%if(data[i].pay_id<4&&data[i].pay_status==0&&data[i].order_status!=2){%>\
							  <%if(data[i].pay_name=="快钱"){%>\
								  <a href="#" class="btn pay_order" data-type="kuaiqian" data-id="<%=data[i].order_id%>">\
									去付款\
								  </a>\
								  <div style="display:none" id="pay_form_<%=data[i].order_id%>"><%=data[i].pay_online.pay_online.replace(/script/gi,"a")%></div>\
							  <%}else{%>\
								  <a href="<%=data[i].pay_online.pay_online%>" class="btn pay_order" data-id="<%=data[i].order_id%>">\
									去付款\
								  </a>\
							  <% } %>\
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
				$('#order_title').after('<table class="order-table"><tr>\
										  <th width="140">订单号</th>\
										  <th width="120">送货时间</th>\
										  <th width="160">商品</th>\
										  <th width="100">需支付金额</th>\
										  <th width="160">状态</th>\
										  <th width="100">操作</th>\
										  <th width="100">&nbsp;</th>\
										</tr>'+html+'</table>');
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
			var _jqThis = $(this);
			var _id = $(this).data('id');
			require(['ui/confirm'],function(confirm){
				new confirm('确认取消该订单吗？取消后将无法恢复！',function(){
					$.post('route.php?action=del_one_order&mod=account',{
						'order_id':_id
					},function(d){
						if(d.code == 0){
							$('#orderitem_'+_id).find('.order_status').html('已取消');
							$('#orderitem_'+_id).find('.pay_order').remove();
							_jqThis.hide();
						}else{
							require(['ui/confirm'],function(confirm){
								new confirm("订单取消失败！可能是该订单已经确认，将不能取消");
							});
						}
					},'json');
				});
			});
			return false;
		}).delegate('.pay_order','click',function(){
			var _this = $(this);
			var payUrl =_this.attr('href');
			if(payUrl=='#'&&_this.data('type')=='kuaiqian'){
				$('#pay_form_'+_this.data('id')).find('form')[0].submit();

			}else{
				var f = window.open(payUrl);
				if(f==null){
					require(['ui/confirm'],function(confirm){
						new confirm("您的浏览器启用拦截支付宝弹出窗口过滤功能！\n\n请暂时先关闭此功能以完成支付！")
					});
				}
			}

			return false;
		});

	}
 }
 OrderList.render();
 OrderList.bind();
 OrderList.checksetPassword();
})();