(function(){
	$('#password_input').focus(function(){
		$('#set_warn').hide();
	});

	$('#change_password').click(function(){
		var password = $('#password_input').val();
		//检测一下密码的长度
		if(!password||password.split('').length<6){
			$('#set_warn').show();
			return;
		}
	
		$.post('route.php?mod=account&action=change_unregister_password',{
			password:password
		},function(d){
			if(d.code == 0){
			   require(['ui/confirm'],function(confirm){
					new confirm('现在你已经成为了mescake的会员，可以在我的订单中追踪订购的商品',function(){
						location.href="route.php?mod=account&action=order_list";
					});
				});
			}else if(d.code == 1){
				$('#set_warn').show();
			}else{
				new confirm('系统错误！');
			}
		},'json');
	});

	//设置一下UI 上的展示效果
	$.get('route.php?action=get_auto_register_mobile&mod=account',{
		_tc:Math.random()
	},function(d){
		if(d.code == 0){
			$('#moblie_number').html(d.msg);
		}
	},'json')

})();