define(['ui/dialog'],function(Dialog){
	var body = '<form>\
          <div class="tl-c" >\
            <input type="text" class="global-input charge_num" style="width:52px;" id="charge_num_1">\
            <input type="text" class="global-input charge_num" style="width:52px;" id="charge_num_2">\
            <input type="text" class="global-input charge_num" style="width:52px;" id="charge_num_3">\
            <input type="text" class="global-input charge_num" style="width:52px;" id="charge_num_4">\
            <span class="tips-container" style="display:none" id="num_error_tip">冲值卡号输入错误</span>\
          </div>\
          <div class="check-container">\
            <input type="password" class="global-input" placeholder="请输入卡的密码" id="charge_password">\
            <span class="tips-container" style="display:none" id="password_error_tip">密码输入错误</span>\
          </div>\
          <div class="check-container">\
            <input type="text" class="global-input" placeholder="请输入您的手机号码" style="width:198px;" id="charge_mobile">\
            <span class="tips-container" style="display:none" id="mobile_error_tip">手机号码输入错误</span>\
          </div><a href="#" class="btn vert-btn code-btn"  id="charge_vaild_button">获取验证码</a><a href="#" class="btn vert-btn code-btn" style="display:none" id="charge_sended_button">已发送</a>\
          <div class="check-container">\
            <input type="text" class="global-input" placeholder="请输入短信验证码"  id="charge_vaild">\
            <span class="tips-container" style="display:none"  id="vaild_error_tip">验证码错误</span>\
          </div>\
        </form>'

	
	var single;
	var changemobile = {
		
		init:function(){
			if(single){
				single.show();
			}else{
				 single = new Dialog({
						title:'代金券充值',
						onconfirm:function(){
							var card_num='';
							var num_error = false;
							var password = $('#charge_password').val();
							var mobile = $('#charge_mobile').val();
							var vaild_code = $('#charge_vaild').val();
							$('.charge_num').each(function(i,el){
								if($(el).val().length!=4){
									num_error = true;
								}
								card_num+= (''+$(el).val());
							});
							//前端验证
							if(num_error){
								MES.inputError('num_error_tip');
								return;
							}

							if(password.length<1){
								MES.inputError('password_error_tip');
								return;
							}

							
							if(mobile.length<6){
								MES.inputError('mobile_error_tip');
								return;
							}

							if(vaild_code.length!=6){
								MES.inputError('vaild_error_tip');
								return;
							}

							MES.post({
									action:'do_charge',
									mod:'account',
									param:{
										mobile:mobile,
										vaild_code:vaild_code,
										card_num:card_num,
										card_pwd:password
									},
									callback:function(d){
										if(d.code == 0){
											require(['ui/confirm'],function(confirm){
												new confirm("冲值成功!");
											});
											single.hide();
										}else{
											require(['ui/confirm'],function(confirm){
												new confirm(d.message||d.msg);
											});
										}
										single.hide();
									}
							});
						},
						body:body,
						afterRender:function(){
							$('#charge_password').placeholder();
							$('#charge_mobile').placeholder();
							$('#charge_vaild').placeholder();
							
							//验证码
							$('#charge_vaild_button').click(function(){
								
								var mobile = $('#charge_mobile').val();
								if(mobile.length<6){
									MES.inputError('mobile_error_tip');
									return;
								}

								$(this).hide();
								$('#charge_sended_button').show();

								MES.post({
									action:'charge_vaild',
									mod:'account',
									param:{
										mobile:mobile
									},
									callback:function(d){
									
									}
								});
							});
							
							$('.charge_num').keyup(function(){
								var _this = $(this);
								if(_this.val().length>3){
									_this.next().focus();
								}
								
								if(_this.val().length>4){
									_this.val(_this.val().substring(0,4));
								}
							});


						}
							
					});
			}
		}
	}

	changemobile.init();
	return single;
})