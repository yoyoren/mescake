(function(){

   var addressTmpl = '<%for(var i=0;i<data.length;i++){%>\
						<div class="ama-item address_item <%if(i==0){%>ama-item-current<%}%>" id="address_<%=data[i].address_id%>"\
							data-address="<%=data[i].address%>"\
							data-tel="<%=data[i].mobile%>"\
							data-contact="<%=data[i].consignee%>"\
							data-city="<%=data[i].city%>"\
						>\
						  <p class="ama-name-area"><b class="fl-l"><%=data[i].consignee%></b><span class="fl-r"><%=data[i].mobile%></span></p>\
						  <p class="address-area">\
							<span class="city">北京市</span>\
							<span class="area"><%=data[i].cityName%></span><br>\
							<span class="address"><%=data[i].address%></span>\
						  </p>\
						  <div class="clearfix handle-area">\
							<a href="#" class="ama-edit fl-l addr_edit" data-id="<%=data[i].address_id%>">修改</a>\
							<a href="#" class="ama-delete fl-r addr_del" data-id="<%=data[i].address_id%>">删除</a>\
						  </div>\
						</div>\
						<% }　%>'
  
   //current selected address id
   var CURRENT_ADDRESS_ID;

   //current modifiy address id
   var CURRENT_ID;

   var Order = {
		bind:function(){
			var me = this;

			//UI changed when you select an address
			$('#address_container').delegate('.address_item','click',function(){
				$('#address_container').find('.address_item').removeClass('ama-item-current');
				$(this).addClass('ama-item-current');

				//set current id
				CURRENT_ADDRESS_ID = $(this).data('id');
			}).delegate('.addr_del','click',function(){
			//delete an address info if you want
				var _this =$(this);
				var id = _this.data('id');

				//we need some UI plugin
				require(['ui/confirm'],function(confirm){
					new confirm('确认删除这个地址信息吗？',function(){
						$.post('route.php?mod=order&action=del_order_address',{
							id:id
						},function(d){
							if(d.code == 0){
								//remove it from UI
								$('#address_'+id).remove();
							}
						},'json');
					});
				});
			}).delegate('.addr_edit','click',function(){
				//edit address info
				var _this =$(this);
				var id = _this.data('id');

				//update current mod ID
				CURRENT_ID = id;

				_this = $('#address_'+id);
				$('#region_sel').val(_this.data('city'));
				$('#new_address').val(_this.data('address'));
				$('#new_tel').val(_this.data('tel'));
				$('#new_contact').val(_this.data('contact'));

				//button switch;
				$('#save_address').hide();
				$('#mod_address').show();
				$('#new_address_form').show();
			});


			//tax ticket 
			$('#fapiao_chk').click(function(){
				var _this = $(this);
				if(_this[0].checked){
					$('#fapiao_form').show();
				}else{
					$('#fapiao_form').hide();
				}
			});

			//show create address in UI
			$('#add_new_address').click(function(){

				//button switch
				$('#save_address').show();
				$('#mod_address').hide();

				//clear the form
				me.clearAddressForm();

				//show it
				$('#new_address_form').show();

			});

			//new address for user
			$('#save_address').click(function(){
				var city = $('#region_sel').val();
				var address = $('#new_address').val();
				var tel = $('#new_tel').val();
				var contact = $('#new_contact').val();

				$.post('route.php?mod=order&action=add_order_address',{
					country:501,
					city:city,
					address:address,
					tel:tel,
					contact:contact
				},function(d){
					if(d.code==0){
						var html = mstmpl(addressTmpl,{
							data:[d.data]
						});
						//clear highlight style
						$('#address_container').find('.address_item').removeClass('ama-item-current');
						$('#address_container').prepend(html);
						CURRENT_ADDRESS_ID = d.data.address_id;

						//hide new form area and clear it
						$('#new_address_form').hide();
						me.clearAddressForm();
					}

				},'json');
			});

			//mod address event
			$('#mod_address').click(function(){
				var city = $('#region_sel').val();
				var address = $('#new_address').val();
				var tel = $('#new_tel').val();
				var contact = $('#new_contact').val();

				$.post('route.php?mod=order&action=update_order_address',{
					country:501,
					city:city,
					address:address,
					tel:tel,
					contact:contact,
					id:CURRENT_ID
				},function(d){
					if(d.code==0){
						var html = mstmpl(addressTmpl,{
							data:[d.data]
						});
						$('#address_'+CURRENT_ID).replaceWith(html);

						//hide new form area and clear it
						$('#new_address_form').hide();
						me.clearAddressForm();
					}

				},'json');
			});

			$('#cancel_address').click(function(){
				$('#new_address_form').hide();
				me.clearAddressForm();
			});
		},
		
		//we need some front-end vaild we submit form
		vaildAddressForm:function(){

		},

		//clean new address form
		clearAddressForm:function(){
			$('#region_sel').val(0);
			$('#new_address').val('');
			$('#new_tel').val('');
			$('#new_contact').val('');
		},

		//init address UI
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
		},

		getRegion:function(){
			$.get('route.php',{
				mod:'order',
				action:'get_region'
			},function(d){
				var html='';
				for(var i=0;i<d.length;i++){
					html+='<option value="'+d[i].region_id+'">'+d[i].region_name+'</option>'
				}
				$('#region_sel').append(html);
			},'json');

		}
   }


   Order.bind();
   Order.getAddress();
   Order.getRegion();
   $(window).ready(function(){
   	$('#date_picker').click(function(){
   		//require(['datepicker/WdatePicker'],function(datePicker){
   			WdatePicker({minDate:'%y-%M-{%d}'});
   		//})
   		
   	});
   });

})();