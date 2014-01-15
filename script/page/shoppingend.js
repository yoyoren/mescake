(function(){
	$('#password_input').focus(function(){
		$('#set_warn').hide();
	});

	$('#change_password').click(function(){
		var password = $('#password_input').val();
		$.post('route.php?mod=account&action=change_unregister_password',{
			password:password
		},function(d){
			if(d.code == 0){
			   require(['ui/confirm'],function(confirm){
					new confirm('现在你已经成为了mescake的会员，可以在我的订单中追踪订购的商品',function(){
						location.href="index.php";
					});
				});
			}else if(d.code == 1){
				$('#set_warn').show();
			}else{
				alert('系统错误！');
			}
		},'json');
	});
	

})();