define(['ui/dialog'],function(Dialog){
	var body = '<form action="">\
          <div class="check-container">\
            <input id="old_password" type="password" class="global-input no-border-b" placeholder="请输入当前密码">\
            <span class="tips-container" style="display:none">密码必须是6位及以上</span><!-- 错误信息容器,出现2秒后消失 -->\
          </div>\
          <div class="check-container">\
            <input id="repeat_password"type="password" class="global-input no-border" placeholder="请输入6位或以上密码">\
            <span class="tips-container" style="display:none">密码必须是6位及以上</span>\
          </div>\
          <div class="check-container">\
            <input id="new_password" type="password" class="global-input no-border-t" placeholder="请再次确认密码">\
            <span class="tips-container" style="display:none">两次输入的密码不一致</span>\
          </div>\
        </form>';

	var errorTip = '<span class="tips-container">{msg}</span>';
	var single;
	var login = {

		init:function(){
			if(single){
				single.show();
			}else{
				 single = new Dialog({
						title:'修改密码',
						body:body,
						onconfirm:function(){
							var old = $('#old_password').val();
							if(!old||old.split('').length<6){
								$('#old_password').next().show();
								setTimeout(function(){
									$('#old_password').next().hide();
								},2000);
								return;
							}
							var repeate = $('#repeat_password').val();
							if(!repeate||repeate.split('').length<7){
								$('#repeat_password').next().show();
								setTimeout(function(){
									$('#repeat_password').next().hide();
								},2000);
								return;
							}

							var newPassword = $('#new_password').val();
							if(newPassword!==repeate){
								$('#new_password').next().show();
								setTimeout(function(){
									$('#new_password').next().hide();
								},2000);
								return;
							}

							$.post('route.php?mod=account&action=change_password',{
								'old':old,
								'new':newPassword
							},function(d){
								if(d.code == 0){
									require(['ui/confirm'],function(confirm){
										new confirm('密码修改成功!',function(){});
									});
								}else{
									require(['ui/confirm'],function(confirm){
										new confirm('密码修改失败!',function(){});
									});
								}
								$('#old_password').val('');
								$('#new_password').val('');
								$('#repeat_password').val('');
								single.hide();
							},'json');
						},
						afterRender:function(){
							$('#old_password').placeholder();
							$('#new_password').placeholder();
							$('#repeat_password').placeholder();
						}
					});
			}
		}
	}

	login.init();
	return single;
})