(function(){

   var addressTmpl = '<%for(var i=0;i<data.length;i++){%>\
						<div class="ama-item address_item <%if(i==0){%>ama-item-current<%}%>" data-id="<%=data[i].address_id%>">\
						  <p class="ama-name-area"><b class="fl-l"><%=data[i].consignee%></b><span class="fl-r"><%=data[i].mobile%></span></p>\
						  <p class="address-area">\
							<span class="city">北京市</span>\
							<span class="area"><%=data[i].cityName%></span><br>\
							<span class="address"><%=data[i].address%></span>\
						  </p>\
						  <div class="clearfix handle-area">\
							<a href="#" class="ama-edit fl-l" data-id="<%=data[i].address_id%>">修改</a>\
							<a href="#" class="ama-delete fl-r" data-id="<%=data[i].address_id%>">删除</a>\
						  </div>\
						</div>\
						<% }　%>'
   var CURRENT_ADDRESS_ID;
   var Order = {
		bind:function(){
			$('#address_container').delegate('.address_item','click',function(){
				$('#address_container').find('.address_item').removeClass('ama-item-current');
				$(this).addClass('ama-item-current');

				//set current id
				CURRENT_ADDRESS_ID = $(this).data('id');
			});

			$('#add_new_address').click(function(){
				$('#new_address_form').show();
			});

			$('#fapiao_chk').click(function(){
				var _this = $(this);
				if(_this[0].checked){
					$('#fapiao_form').show();
				}else{
					$('#fapiao_form').hide();
				}
			});
		},
		getAddress:function(){
			$.get('route.php',{
				mod:'order',
				action:'get_order_address'
			},function(d){
				
				//has address
				if(d.length){
					var html = mstmpl(addressTmpl,{
						data:d
					});
					$('#address_container').prepend(html);

					//index 0 is the current address
					CURRENT_ADDRESS_ID = d[0].address_id;
				}else{
				
				}
			},'json');
		}
   }


   Order.bind();
   Order.getAddress();

})();