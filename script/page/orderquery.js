(function(){
	$('.js_login').click(function(){
		require(["ui/login"], function(login) {login.show();});
	});

	$('#query_order').click(function(){
		var username = $('#mobile_input').val();
		var password = $('#vaild_input').val();
		$('#moblie_error').hide();
		$('#vaild_error').hide();
		//前端验证
		if(!/\d{5,}/.test(username)){
			$('#moblie_error').show();
			return; 
		}

		if(!password){
			$('#vaild_error').show();
			return; 
		}

		$.post('route.php?action=query_login&mod=account',{
			username:username,
			password:password
		},function(d){
			if(d.code == 0){
				location.href= "route.php?action=order_list&mod=account";
			}else{
				new confirm('您的输入的验证码错误！',function(){});
			}
		},'json');
	});


	$('#get_vaild_code').click(function(){
		var moblie = $('#mobile_input').val();
		$('#moblie_error').hide();
		//前端验证
		if(!/\d+/.test(moblie)){
			$('#moblie_error').show();
			return; 
		}
		$.post('route.php?action=get_password_moblie&mod=account',{
			moblie:moblie
		},function(d){
			if(d.code == 0){
				require(['ui/confirm'],function(confirm){
					new confirm('登录验证码已经发送至您的手机！',function(){});
				});
			}else{
				require(['ui/confirm'],function(confirm){
					//用户存在 注册
					if(d.user_type === '0'){
						new confirm('您的手机号已经注册，请直接登录后查询订单状态',function(){
							setTimeout(function(){
								require(["ui/login"], function(login) {login.show();});
							},100);
							
						});
					}else{
					//没有订单记录
						new confirm('您所使用的手机号没有查询到相关订单记录');
					}
				});
			}
		},'json')
	});
})();