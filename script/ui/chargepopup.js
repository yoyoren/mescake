define(['ui/dialog'],function(Dialog){
	var body = '<form>\
          <div class="tl-c" >\
            <input type="text" class="global-input charge_num" style="width:52px;" id="charge_num_1">\
            <input type="text" class="global-input charge_num" style="width:52px;" id="charge_num_2">\
            <input type="text" class="global-input charge_num" style="width:52px;" id="charge_num_3">\
            <input type="text" class="global-input charge_num" style="width:52px;" id="charge_num_4">\
            <span class="tips-container" style="display:none">输入错误</span><!-- 错误信息容器,出现2秒后消失 -->\
          </div>\
          <div class="check-container">\
            <input type="password" class="global-input" placeholder="请输入卡的密码" id="charge_password">\
            <span class="tips-container" style="display:none">输入错误</span><!-- 错误信息容器,出现2秒后消失 -->\
          </div>\
          <div class="check-container">\
            <input type="text" class="global-input" placeholder="请输入您的手机号码" style="width:198px;" id="charge_mobile">\
            <span class="tips-container" style="display:none">>输入错误</span><!-- 错误信息容器,出现2秒后消失 -->\
          </div><a href="#" class="btn vert-btn code-btn"  id="charge_vaild_button">获取验证码</a>\
          <div class="check-container">\
            <input type="text" class="global-input" placeholder="请输入短信验证码"  id="charge_vaild">\
            <span class="tips-container" style="display:none">输入错误</span><!-- 错误信息容器,出现2秒后消失 -->\
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
							

						
						},
						body:body,
						afterRender:function(){
							$('#charge_password').placeholder();
							$('#charge_mobile').placeholder();
							$('#charge_vaild').placeholder();
							
							//验证码
							$('#charge_vaild_button').click(function(){
								
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