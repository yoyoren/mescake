(function(){
	var VaildLock = false;
	var JQ ={
		'get_vaild_code':$('#get_vaild_code'),
		'vaild_code_sended':$('#vaild_code_sended')
	}
	
	//直接登录按钮
	$('#signup_login').click(function(){
		$('#header_login').trigger('click');
		return false;
	});
	
	JQ.vaild_code_sended.click(function(){
		require(['ui/confirm'],function(confirm){
			new confirm('请在60秒之后再新发送验证码');
		});
	});

	JQ.get_vaild_code.click(function(){
		var mobile = $('#username').val();
		if(!MES.IS_MOBILE(mobile)){
			MES.inputError('username_error');
			return;
		}
		if(VaildLock){
			require(['ui/confirm'],function(confirm){
				new confirm('请在60秒之后再新发送验证码');
			});
			return;
		}

		//防止频繁发送
		VaildLock = true;
		JQ.get_vaild_code.hide();
		JQ.vaild_code_sended.show();
		setTimeout(function(){
			JQ.get_vaild_code.show();
			JQ.vaild_code_sended.hide();
			VaildLock = false;
		},60000);
		
		MES.post({
		  mod:'account',
	      action:'signup_vaild_code',
		  param:{
			mobile:mobile
		  },
		  callback:function(d){
			
		  }
		});
		return false;
	});
	$('#password').placeholder();
	$('#username').placeholder();
	$('#vaild_code').placeholder();
	$('#do_signup').click(function(){
		var password = $('#password').val();
		var username = $('#username').val();
		var vaild_code = $('#vaild_code').val();
		
		if(!MES.IS_MOBILE(username)){
			MES.inputError('username_error');
			return;
		}

		if(vaild_code.length!=6){
			MES.inputError('vaildcode_error');
			return;
		}

		if(password.length<6){
			MES.inputError('password_error');
			return;
		}

		

		MES.post({
		  mod:'account',
	      action:'signup',
		  param:{
			password:password,
			username:username,
			'vaild_code':vaild_code
		  },
		  callback:function(d){
			if(d.code == 10010){
				$('#vaildcode_error').show();
				setTimeout(function(){
					$('#vaildcode_error').hide();
				},2000);
			}

			if(d.code == 0){
			  require(['ui/alert'],function(alert){
				 orderAlert = new alert('注册成功！页面将跳转到首页...');
				 setTimeout(function(){
					location.href = '/';
				 },2000);
			  });
			}else{
				require(['ui/confirm'],function(confirm){
					new confirm('注册失败！可能是你使用的手机号已经被注册！');
			    });
			}
		  }
		});
		return false;
	});
})();