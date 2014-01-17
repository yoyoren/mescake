(function(){

   var addressTmpl = '<%for(var i=0;i<data.length;i++){%>\
						<div class="ama-item address_item <%if(i==0){%>ama-item-current<%}%>" id="address_<%=data[i].address_id%>"\
							data-id="<%=data[i].address_id%>"\
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
				
				var _this = $(this);
				$('#address_container').find('.address_item').removeClass('ama-item-current');
				_this.addClass('ama-item-current');
			
				//set current id
				CURRENT_ADDRESS_ID = _this.data('id');
				
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
				if(!me.vaildAddressForm()){
					return
				}
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
				if(!me.vaildAddressForm()){
					return
				}
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

			//submit this order to server
			$('#submit_order_btn').click(function(){
				var jqThis = $('#address_'+CURRENT_ADDRESS_ID);
				
					me.saveconsignee(jqThis);
				
			});
		},
		
		//we need some front-end vaild we submit form
		vaildAddressForm:function(){
			
			if($.trim($('#region_sel').val())==0){
				require(['ui/confirm'],function(confirm){
								new confirm('请选择一个送货的区域！',function(){
								});
							});
				return false;
			}

			if($.trim($('#new_address').val())==''){
				$('#new_address').next().show();
				setTimeout(function(){
					$('#new_address').next().hide();
				},2000)
				return false;
			}

			if($.trim($('#new_contact').val())==''){
				$('#new_contact').next().show();
				setTimeout(function(){
					$('#new_contact').next().hide();
				},2000)
				return false;
			}

			if($.trim($('#new_tel').val())==''){
				$('#new_tel').next().show();
				setTimeout(function(){
					$('#new_tel').next().hide();
				},2000)
				return false;
			}

			return true;
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
					//no address ,show address form
					$('#new_address_form').show();
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

		},

		vaildDate:function(){
			if(!$('#date_picker').val()){
				return false;
			}
			
			var hour = $('#hour_picker').val();
			if(hour>22||hour<10){
				return false;
			}

			var minute = $('#minute_picker').val();
			if(minute!=0&&minute!=30){
				return false;
			}
			return true;
		},
		//save it in session
		saveconsignee:function(_this){
			var me = this;
			if(window.IS_LOGIN){
				var data = {
						address_id:_this.data('id'),
						consignee:_this.data('contact'),
						country:501,
						city:_this.data('city'),
						address:_this.data('address'),
						mobile:_this.data('tel'),
						bdate:$('#date_picker').val(),
						hour:$('#hour_picker').val(),
						minute:$('#minute_picker').val()
				};
			}else{
				var data = {
						address_id:0,
						consignee:$('#new_contact').val(),
						country:501,
						city:$('#region_sel').val(),
						address:$('#new_address').val(),
						mobile:$('#new_tel').val(),
						bdate:$('#date_picker').val(),
						hour:$('#hour_picker').val(),
						minute:$('#minute_picker').val()
				}

				if(!me.vaildAddressForm()){
					return;
				}
			}
			if(!me.vaildDate()){
				require(['ui/confirm'],function(confirm){
					new confirm('您选择的送货日期不正确，请重新选择',function(){});
				});
				return;
			}
			$.post('route.php?action=save_consignee&mod=order',data||{},function(d){
			
				if(window.IS_LOGIN){
					me.checkout();
				}else{
					//检查没有登录的用户手机号码是否被注册了
					$.get('route.php?action=check_user_exsit&mod=account',{
						username:$('#new_contact').val()
					},function(d){

						//用户已经存在于数据库中
						if(d.exsit){
							require(['ui/confirm'],function(confirm){
								new confirm('您所使用的手机号已经被注册，请登录后再继续订购',function(){
									require(["ui/login"], function(login) {login.show();});
								});
							});
							
						}else{
							//给这个用户注册一个账户 并且帮他登录
							$.post('route.php?action=auto_register&mod=account',{
								username:$('#new_tel').val()
							},function(d){
								if(d.code == 0){
									me.checkout();
								}
							},'json');
						}
					},'json')
				}
				
			},'json');
		},

		//最终的结算
		checkout:function(){
			$.post('route.php?action=checkout&mod=order',{},function(d){
				//结算数据form submit
				$('#submit_form').submit();
			},'json');
		},

		//变更支付的方式
		changePayMethod:function(){
			var payForm = $('#pay_id');
			$('#cash').click(function(){
				var cash = $('#cash_sel').val();
				payForm.val(cash);
			});
			
			$('#alipay').click(function(){
				payForm.val(3);
			});

			$('#kuaiqian').click(function(){
				payForm.val(4);
			});

			$('#cash_sel').change(function(){
				payForm.val($(this).val());
				$('#cash').find('input')[0].checked = true;
			});
		},
		getSurplus:function(){
			$('#blance_label').click(function(){
				var chk = $(this).find('input')[0];
				if(chk.checked){
					$.get('flow.php?step=validate_gift&is_surplus=1',function(d){
						$('#balance_display').html('可用余额'+d.surplus).show();
					},'json');
				}else{
					$('#balance_display').hide();	
				}
				
			})
			
		},

		chargeInPage:function(){
			$('#cash_code').blur(function(){
				$('#no').hide();
				var bonus_sn = $('#cash_code').val();
				if(bonus_sn.split('').length!=10){
					$('#no').show();
					return;
				}
				$.get('flow.php?step=validate_bonus&bonus_sn='+bonus_sn,function(d){
							
				},'json');
			});
		}



   }


   Order.bind();
   Order.getAddress();
   Order.getRegion();
   Order.changePayMethod();
   Order.getSurplus();
   Order.chargeInPage();
   $(window).ready(function(){
	 

	var _html='';
	
	for(var i=10;i<=22;i++){
		_html+='<option value="'+i+'">'+i+'</option>'
	}
	$('#hour_picker').append(_html);
   	$('#date_picker').click(function(){
   		//require(['datepicker/WdatePicker'],function(datePicker){
   		WdatePicker({minDate:'%y-%M-{%d}'});
   		//})
   		
   	});
   	MES.checkLogin(function(){
		//login
   		$('#login_tip').hide();
		$('#login_address_operate').show();
		$('#add_new_address').show();
		//显示登陆后的礼金操作地址
		$('#money_card_frame').show();
		window.IS_LOGIN = true;
   	},function(){
		//unlogin
   		$('#login_tip').show();
		$('#add_new_address').hide();
		$('#login_address_operate').hide();
   		$('.user_login').click(function(){
			require(["ui/login"], function(login) {login.show();});
   		});
		$('#money_card_frame').hide();
		window.IS_LOGIN = false;
   	})
   	
   });

})();