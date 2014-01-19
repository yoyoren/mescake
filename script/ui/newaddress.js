define(['ui/dialog'],function(Dialog){
	var body = '<dl class="global-form-container clearfix">\
          <dt class="l-title">送货地址：</dt>\
          <dd class="r-con clearfix">\
            <a href="javascript:void(0);" class="area-intro space">\
                北京市\
                <em class="area-intro-ico"></em>\
               <span class="ai-intro">目前仅针对北京开展送货业务，请见谅</span>\
            </a>\
            <select id="region_sel_popup">\
              <option>请选择区域</option>\
            </select>\
			<select id="dis_district_popup" style="display:none">\
                <option value="0">选择送货街道</option>\
            </select>\
			<br>\
            <div class="check-container mart-10">\
              <textarea class="text-area" placeholder="详细地址" id="new_address_popup"></textarea>\
              <span class="tips-container" style="display:none">地址格式错误</span>\
            </div>\
          </dd>\
          <dt class="l-title lh-input">收货人：</dt>\
          <dd class="r-con clearfix">\
            <div class="check-container">\
              <input type="text" class="global-input" placeholder="收货人姓名" id="new_contact_popup">\
              <span class="tips-container" style="display:none">请填写收货人</span><!-- 错误信息容器,出现2秒后消失 -->\
            </div>\
          </dd>\
          <dt class="l-title lh-input">联系手机：</dt>\
          <dd class="r-con clearfix">\
            <div class="check-container">\
              <input type="text" class="global-input" placeholder="联系人的手机号码" id="new_tel_popup">\
              <span class="tips-container" style="display:none">手机号码格式错误</span>\
            </div>\
          </dd>\
          <dt class="l-title">&nbsp;</dt>\
          <dd class="r-con clearfix">\
             <input class="btn green-btn" type="button" value="保存" id="save_address_popup">\
              <input class="btn" type="button" value="取消" id="cancel_address_popup"">\
          </dd></dl>'

	
	var single;
	var changemobile = {
		
		init:function(){
			if(single){
				single.show();
			}else{
				 single = new Dialog({
						title:'添加新地址',
						bottom:' ',
						body:body,
						afterRender:function(){
							$.get('route.php',{
								_tc:Math.random(),
								mod:'order',
								action:'get_region'
							},function(d){
								var html='';
								for(var i=0;i<d.length;i++){
									html+='<option value="'+d[i].region_id+'">'+d[i].region_name+'</option>'
								}
								$('#region_sel_popup').append(html);
							},'json');


							$('#region_sel_popup').change(function(){
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
											$('#dis_district_popup').html(html).show();
										}else{
											$('#dis_district_popup').html(html).hide();
										}
									}	
								},'json');
							});
							var vaildForm = function(){
									if($.trim($('#region_sel_popup').val())==0){
										require(['ui/confirm'],function(confirm){
											new confirm('请选择一个送货的区域！');
										});
										return false;
									}

									if($.trim($('#new_address_popup').val())==''){
										$('#new_address_popup').next().show();
										setTimeout(function(){
											$('#new_address_popup').next().hide();
										},2000)
										return false;
									}

									if($.trim($('#new_contact_popup').val())==''){
										$('#new_contact').next().show();
										setTimeout(function(){
											$('#new_contact_popup').next().hide();
										},2000)
										return false;
									}
									var tel = $.trim($('#new_tel_popup').val());
									if(!/\d{5,}/.test(tel)){
										$('#new_tel_popup').next().show();
										setTimeout(function(){
											$('#new_tel_popup').next().hide();
										},2000)
										return false;
									}

									return true;
							}
							$('#save_address_popup').click(function(){
								single.hide();
							});
							
							$('#save_address_popup').click(function(){
								var city = $('#region_sel_popup').val();
								var address = $('#new_address_popup').val();
								var tel = $('#new_tel_popup').val();
								var contact = $('#new_contact_popup').val();
								if(!vaildForm()){
									return
								}
								MES.post({
									mod:'order',
									action:'add_order_address',
									param:{
										country:501,
										city:city,
										address:address,
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