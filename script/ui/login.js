define(['ui/dialog'],function(Dialog){
	var body = '<div class="check-container" id="popup_login_container">\
            <input type="text" class="global-input" id="popup_login_id" placeholder="手机号码">\
			<span class="tips-container" style="display:none">用户名不能为空</span>\
          </div><br>\
          <div class="check-container">\
            <input type="password" class="global-input" id="popup_login_password" placeholder="用户密码">\
			<span class="tips-container" style="display:none">密码不能为空</span>\
			<span class="tips-container" id="popup_login_error" style="display:none">用户名或密码错误</span>\
          </div><a href="route.php?action=forget_password_page&mod=account" class="vert-btn">忘记密码</a><br>'

	var bottom = ' <input class="v-btn green-btn" type="submit" value="登录" id="popup_login"><a href="user.php?act=register" class="v-btn vert-btn">注册账户</a>';
	var errorTip = '<span class="tips-container">{msg}</span>';
	var single;
	var login = {

		init:function(){
			if(single){
				single.show();
			}else{
				 single = new Dialog({
						title:'用户登录',
						body:body,
						bottom:bottom,
						afterRender:function(){
							$('#popup_login_container').find('input','focus',function(){
								$('#popup_login_container').find('.tips-container').hide();
							});
							
							$('#popup_login').click(function(){
								var username = $('#popup_login_id').val();
								var password = $('#popup_login_password').val();
								
								if($.trim(username)==''){
									$('#popup_login_id').next().show();
									return false;
								}
								
								if($.trim(password)==''){
									$('#popup_login_password').next().show();
									return false;
								}

								$.post('route.php?mod=account&action=login',{
									username:username,
									password:password
								},function(d){
									if(d.code==0){
										single.close();
										location.reload();
									}else{
										$('#popup_login_error').show();
									}
								},'json');
								return false;
							});
						}
					});
			}
		}
	}
	login.init();
	return single;
})
