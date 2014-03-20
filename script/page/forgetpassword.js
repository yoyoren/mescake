(function(){
	var codeTimer;
	var jqTimerDisplay = $('#time_display');
	$('#new_password').placeholder();
	$('#new_password_repeate').placeholder();
	$('#mobile_input').placeholder();
	$('#code_input').placeholder();

	$('#confirm_change').click(function(){
		var new_password = $('#new_password').val();
		var new_password_repeat = $('#new_password_repeate').val();
		var mobile = $('#mobile_input').val();
		if(!new_password||new_password.split('').length<6){
			$('#new_password').next().show();
			setTimeout(function(){
				$('#new_password').next().hide();
			},2000);
			return false;
		}
		if(new_password!=new_password_repeat){
			$('#new_password_repeate').next().show();
			setTimeout(function(){
				$('#new_password_repeate').next().hide();
			},2000);
			return false;
		}

		MES.post({
			mod:'account',
			action:'forget_password_step2',
			param:{
				mobile:mobile,
				password:new_password
			},
			callback:function(d){
				if(d.code==0){
					require(['ui/confirm'],function(confirm){
						new confirm('密码修改成功！确认后重新登录',function(){
							require(["ui/login"], function(login) {login.show();});
						});
					});
				}else{
					require(['ui/confirm'],function(confirm){
						new confirm('密码修改失败！');
					});
				}
			}
		});
	});
	
	$('#next').click(function(){
		var mobile = $('#mobile_input').val();
		var code = $('#code_input').val();

		if(!mobile||mobile.split('').length<6){
			$('#mobile_input').next().show();
			setTimeout(function(){
				$('#mobile_input').next().hide();
			},2000);
			return false;
		}
		if(!code||code.split('').length!=6){
			$('#code_input').next().show();
			setTimeout(function(){
				$('#code_input').next().hide();
			},2000);
			return false;
		}
		MES.post({
			mod:'account',
			action:'forget_password_step1',
			param:{
				mobile:mobile,
				code:code
			},
			callback:function(d){
				if(d.code==0){
					$('#step1').hide();
					$('#step2').show();
				}else{
					require(['ui/confirm'],function(confirm){
						new confirm('验证码不正确！');
					});
				}
			}
		});
	});

	$('#get_code').click(function(){
		var mobile = $('#mobile_input').val();

		if(!mobile||mobile.split('').length<6){
			$('#mobile_input').next().show();
			setTimeout(function(){
				$('#mobile_input').next().hide();
			},2000);
			return false;
		}

		$('#reget_code').show();
		$('#get_code').hide();
		codeTimer = setInterval(function(){
			time = jqTimerDisplay.text();
			if(time>0){
				jqTimerDisplay.text(--time);
			}else{
				clearInterval(codeTimer);
				jqTimerDisplay.text(60);
				$('#reget_code').hide();
				$('#get_code').show();
			}
		},1000);
		MES.post({
			mod:'account',
			action:'get_forget_password_code',
			param:{
				mobile:mobile
			},
			callback:function(d){
				if(d.code == 0){
					
				}else{
					require(['ui/confirm'],function(confirm){
						new confirm('\
									  <b>该手机号并未注册账户</b><br><br>\
									  您可以现在就去<a href="user.php?act=register" class="td-u">注册一个新账户</a><br>\
									');
					});
					clearInterval(codeTimer);
					jqTimerDisplay.text(60);
					$('#reget_code').hide();
					$('#get_code').show();
				}
			}
		})
	});
	

})();