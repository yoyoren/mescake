(function(){
  var orderListTmpl = '<%for(var i=0;i<data.length;i++) {%>\
						<tr id="orderitem_<%=data[i].order_id%>">\
						  <td><%=data[i].order_sn%></td>\
						  <td><%=data[i].best_time.split(" ")[0]%></td>\
						  <td><img src=""></td>\
						  <td><%=data[i].order_amount%></td>\
						  <td>\
							<%if(data[i].pay_id<3){%>货到付款<%} else {%>\
						    <%if(data[i].pay_status==0){%>未付款<%}else{%>已付款<%}%>\
							<% } %>\
						  </td>\
						  <td><a href="route.php?mod=account&action=order_detail&order_id=<%=data[i].order_id%>" class="td-u link-color">查看</a><br>\
						  <a href="#" class="td-u link-color cancel_order" data-id="<%=data[i].order_id%>">取消订单</a></td>\
						  <td>\
						  <%if(data[i].pay_id>2&&data[i].pay_status==0){%>\
						  <a href="#" class="btn pay_order" data-id="<%=data[i].order_id%>">\
						  	去付款\
						  </a>\
						  <% } %>\
						  </td>\
						</tr>\
						<% } %>';
 var OrderList = {
	render:function(){
	  $.get('route.php',{
		  action:'get_user_order_list',
		  mod:'account'
	  },function(d){
		  var html = mstmpl(orderListTmpl,{data:d.orders});
		  $('#order_list').append(html);
	  },'json');
	},
	checksetPassword:function(){
		$.get('route.php?action=is_unset_password_user&mod=account',function(d){
			if(d.msg){
				$('#order_login_tip').show();
				$('#set_password').click(function(){
					location.href="route.php?action=set_password&mod=account";
				});
			}
		},'json');
	},

	bind:function(){
		$(document).delegate('.cancel_order','click',function(){
			var _id = $(this).data('id');
			require(['ui/confirm'],function(confirm){
				new confirm('确认取消该订单吗？取消后将无法恢复！',function(){
					$.post('route.php?action=del_one_order&mod=account',{
						'order_id':_id
					},function(d){
						if(d.code == 0){
							$('#orderitem_'+_id).remove();

						}
					},'json');
				});
			});
		});

	}
 }
 OrderList.render();
 OrderList.bind();
 OrderList.checksetPassword();
})();