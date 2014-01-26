(function(){
	$('#change_tel').click(function(){
		require(['ui/changemobile'],function(changemobile){
			changemobile.show();
		});
	});

	$('#change_password').click(function(){
		require(['ui/changepassword'],function(changepassword){
			changepassword.show();
		});
	});

	MES.get({
		mod:'account',
		action:'get_users_info',
		callback:function(d){
			if(d.code == 0){
				$('#my_current_number').html(d.info[0].mobile_phone);
				$('#my_name').val(d.info[0].rea_name);
				$('#my_money').html(d.info[0].user_money);
				var sex = d.info[0].sex;
				if(sex == 0){
					$('#man').find('input')[0].checked = true;
				}else{
					$('#lady').find('input')[0].checked = true;
				}
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

	$('#man').click(function(){
		changeSex(0);
	});

	$('#lady').click(function(){
		changeSex(1);
	});

	$('#my_name').blur(function(){
		$.post('route.php?mod=account&action=change_real_name',{
			name:$(this).val()
		},function(d){
			if(d.code == 0){
				$('#mod_tip').show();
				setTimeout(function(){
					$('#mod_tip').hide();
				},2000);
			}	
		},'json');
	});
})();