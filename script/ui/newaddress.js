define(['ui/dialog'],function(Dialog){
	var body = '<div>\
          <a href="javascript:void(0);" class="area-intro space">\
              北京市\
              <em class="area-intro-ico"></em>\
              <span class="ai-intro">北京市五环内免费送货，五环外不送货。</span>\
          </a>\
           <select id="region_sel_popup">\
              <option value="0">请选择区域</option>\
            </select>\
			<select id="dis_district_popup" style="display:none">\
                <option value="0">选择送货街道</option>\
            </select><span class="error-tip" style="display:none">请选择一个送货区域</span><br>\
          <div class="check-container mart-10">\
            <textarea class="text-area" placeholder="详细地址" id="new_address_popup"></textarea>\
            <span class="tips-container" style="display:none">地址格式错误</span>\
          </div>\
          <div class="check-container">\
            <input type="text" class="global-input" placeholder="收货人姓名" id="new_contact_popup">\
              <span class="tips-container" style="display:none">请填写收货人</span><!-- 错误信息容器,出现2秒后消失 -->\
          </div>\
          <div class="check-container">\
            <input type="text" class="global-input" placeholder="联系人的手机号码" id="new_tel_popup">\
              <span class="tips-container" style="display:none">手机号码格式错误</span>\
          </div>\
          <div class="single-btn-area">\
             <input class="btn status1-btn" type="button" value="保存" id="save_address_popup">\
			 <input class="btn status1-btn" type="button" value="修改" id="mod_address_popup">\
          </div>\
        </div>';

	var CURRENT_ID;
	var single;
	var getDistrictInfo = function(city,callback){
		MES.get({
				mod:'order',
				action:'get_district',
				param:{city:city},
				callback:function(d){
					if(d.code == 0){
						var html = '<option value="0">选择送货街道</option>';
						if(d.data){
							for(var i in d.data){
								html+='<option value="'+i+'">'+d.data[i].name+'</option>'
							}
							$('#dis_district_popup').html(html).show();
							callback&&callback();
						}else{
							$('#dis_district_popup').html(html).hide();
						}
					}	
				}
			});
	}

	//把表单当中的内容清理掉
	var callback = function(){}
	var clearForm  = function(){
		var jqRegionSel = $('#region_sel_popup');
		var jqDistrictSel = $('#dis_district_popup');
		var jqNewAddressInput = $('#new_address_popup');
		var jqNewContactInput =  $('#new_contact_popup');
		var jqNewTelInput =  $('#new_tel_popup');
			jqRegionSel.val(0);
			jqDistrictSel.val(0).hide();
			jqNewAddressInput.val('');
			jqNewContactInput.val('');
			jqNewTelInput.val('');
	}
	var changemobile = {
		
		init:function(){

			if(single){
				single.show();
			}else{
				 single = new Dialog({
						title:'添加新地址',
						bottom:' ',
						body:body,
						onclose:function(){
							clearForm();
						},
						onshow:function(d){
							//修改和新增由不同的按钮来完成
							if(d&&d.mod){
								callback = d.callback||function(){};
								CURRENT_ID = d.id;
								$('#mod_address_popup').show();
								$('#save_address_popup').hide();
								$('#region_sel_popup').val(d.data.city);
								if(d.data.district){
									getDistrictInfo(d.data.city,function(){
										$('#dis_district_popup').val(d.data.district);
									});
								}else{
									$('#dis_district_popup').hide();
								}
								$('#new_address_popup').val(d.data.address);
								$('#new_contact_popup').val(d.data.contact);
								$('#new_tel_popup').val(d.data.tel);
							}else{
								$('#mod_address_popup').hide();
								$('#save_address_popup').show();
							}
					
							$('#new_tel_popup').placeholder().trigger('focus');
							$('#new_contact_popup').placeholder().trigger('focus');
							$('#new_address_popup').placeholder().trigger('focus');
						},
						afterRender:function(){
							//获得地址信息
						
							var jqRegionSel = $('#region_sel_popup');
							var jqDistrictSel = $('#dis_district_popup');
							var jqNewAddressInput = $('#new_address_popup');
							var jqNewContactInput =  $('#new_contact_popup');
							var jqNewTelInput =  $('#new_tel_popup');
							var getRegionInfo = function(){
								MES.get({
									mod:'order',
									action:'get_region',
									callback:function(d){
										var html='';
										for(var i=0;i<d.length;i++){
											html+='<option value="'+d[i].region_id+'">'+d[i].region_name+'</option>'
										}
										jqRegionSel.append(html);
									}
								 });
							}
							getRegionInfo();

							//获得二级地理信息
							jqRegionSel.change(function(){
								$.get('route.php?mod=order&action=get_district',{
									_tc:Math.random(),
									city:$(this).val()
								},function(d){
									if(d.code == 0){
										var html = '<option value="0">选择送货街道</option>';
										if(d.data){
											for(var i in d.data){
												html+='<option value="'+i+'">'+d.data[i].name+'</option>'
											}
											jqDistrictSel.html(html).show();
										}else{
											jqDistrictSel.html(html).hide();
										}
									}	
								},'json');
							});
							
							

							//地址表单的验证
							var vaildForm = function(){
								
									if($.trim($('#region_sel_popup').val())==0){
										jqDistrictSel.next().show();
										setTimeout(function(){
											jqDistrictSel.next().hide();
										},2000);
										return false;
									}

									if($.trim(jqDistrictSel.val())==0&&jqDistrictSel.css('display')!='none'){
										jqDistrictSel.next().show();
										setTimeout(function(){
											jqDistrictSel.next().hide();
										},2000);
										return false;
									}

									if($.trim(jqNewAddressInput.val())==''){
										jqNewAddressInput.next().show();
										setTimeout(function(){
											jqNewAddressInput.next().hide();
										},2000);
										return false;
									}

									if($.trim(jqNewContactInput.val())==''){
										jqNewContactInput.next().show();
										setTimeout(function(){
											jqNewContactInput.next().hide();
										},2000);
										return false;
									}

									var tel = $.trim(jqNewTelInput.val());
									if(!/\d{5,}/.test(tel)){
										jqNewTelInput.next().show();
										setTimeout(function(){
											jqNewTelInput.next().hide();
										},2000)
										return false;
									}

									return true;
							}

							//取消
							$('#cancel_address_popup').click(function(){
								single.hide();
								clearForm();
							});
							

							$('#mod_address_popup').click(function(){
								var city = jqRegionSel.val();
								var district = jqDistrictSel.val();
								var address = jqNewAddressInput.val();
								var tel = jqNewTelInput.val();
								var contact = jqNewContactInput.val();
						
								//表单验证失败
								if(!vaildForm()){
									return;
								}
								
								MES.post({
									mod:'order',
									action:'update_order_address',
									param:{
										country:501,
										city:city,
										address:address,
										district:district,
										tel:tel,
										contact:contact,
										id:CURRENT_ID
									},
									callback:function(d){
										if(d.code==0){
											var html = mstmpl(addressTmpl,{
												data:[d.data]
											});
											var jqAddressForm = $('#address_'+CURRENT_ID);
											jqAddressForm.replaceWith(html);
											single.hide();
											clearForm();
											setTimeout(function(){
												callback(CURRENT_ID);
											},0);
	
										}
									}
								});
							});



							//确认新增地址
							$('#save_address_popup').click(function(){
								var city = jqRegionSel.val();
								var district = jqDistrictSel.val();
								var address = jqNewAddressInput.val();
								var tel = jqNewTelInput.val();
								var contact = jqNewContactInput.val();
								
								//表单验证失败
								if(!vaildForm()){
									return;
								}

								MES.post({
									mod:'order',
									action:'add_order_address',
									param:{
										country:501,
										city:city,
										address:address,
										district:district,
										tel:tel,
										contact:contact
									},
									callback:function(d){
										if(d.code==0){
											var html = mstmpl(addressTmpl,{
												data:[d.data]
											});
											//clear highlight style
											$('#address_container').find('.address_item').removeClass('ama-item-current');
											$('#address_container').prepend(html);
											window.CURRENT_ADDRESS_ID = d.data.address_id;

											//hide new form area and clear it
											single.hide();
											clearForm();
											//me.clearAddressForm();
										}
									}
								});
								
							});
						
						
						}
							

						
							
				});
			}
		}
	}

	changemobile.init();
	return single;
})