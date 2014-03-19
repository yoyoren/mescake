(function(){
	$('#signup_login').click(function(){
		$('#header_login').trigger('click');
		return false;
	});

	$('#get_vaild_code').click(function(){
		var mobile = $('#username').val();
		MES.post({
		  mod:'account',
	      action:'signup_vaild_code',
		  param:{
			mobile:mobile
		  },
		  callback:function(d){
			 require(['ui/confirm'],function(confirm){
					new confirm('验证码已经发送，请注意查收！');
			  });
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
		
		if(username.length<6){
			$('#username_error').show();
			setTimeout(function(){
				$('#username_error').hide();
			},2000);
			return;
		}

		if(vaild_code.length!=6){
			$('#vaildcode_error').show();
			setTimeout(function(){
				$('#vaildcode_error').hide();
			},2000);
			return;
		}

		if(password.length<6){
			$('#password_error').show();
			setTimeout(function(){
				$('#password_error').hide();
			},2000);
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