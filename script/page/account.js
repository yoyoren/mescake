(function(){
	$('#change_tel').click(function(){
		MES.actionCheckLogin(function(){
			require(['ui/changemobile'],function(changemobile){
				changemobile.show();
			});
		});
		return false;
	});

	$('#change_password').click(function(){
		MES.actionCheckLogin(function(){
					require(['ui/changepassword'],function(dialog){
						dialog.show();
					});
		});
		return false;
	});

	MES.get({
		mod:'account',
		action:'get_users_info',
		callback:function(d){
			if(d.code == 0){
				$('#my_current_number').html(d.info[0].mobile_phone);
				$('#my_name').val(d.info[0].rea_name);
				$('#my_money').html(d.info[0].user_money);
			}
		}
	});

	var changeSex = function(sex){
		$.post('route.php?mod=account&action=change_sex',{
			sex:sex
		},function(d){
			if(d.code == 0){
				$('#mod_tip').show();
				setTimeout(function(){
					$('#mod_tip').hide();
				},2000);
			}	
		},'json');
	}
	
	$('#charge_button').click(function(){
		MES.actionCheckLogin(function(){
			require(['ui/chargepopup'],function(dialog){
				dialog.show();
			});
		});
	});
})();