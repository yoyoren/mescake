define(['ui/dialog'],function(Dialog){
	var body = '<dt class="l-title lh-input">新手机号码：</dt>\
            <dd class="r-con clearfix">\
              <div class="check-container small-check-container">\
                <input type="text" class="global-input" id="mobile_input" placeholder="请输入新的手机号码">\
                <span class="tips-container" style="display:none">输入错误</span>\
              </div>\
              <a href="#" class="btn vert-btn" id="get_code">获取验证码</a>\
            </dd>\
            <dt class="l-title lh-input">验证码：</dt>\
            <dd class="r-con clearfix">\
              <div class="check-container small-check-container">\
                <input type="text" id="password_input" class="global-input" placeholder="请输入短信验证码">\
                <span class="tips-container" style="display:none">输入错误</span>\
              </div>\
            </dd>'

	
	var single;
	var changemobile = {
		
		init:function(){
			if(single){
				single.show();
			}else{
				 single = new Dialog({
						title:'修改手机号',
						onconfirm:function(){
							

							if($.trim($('#mobile_input').val())==''){
								$('#mobile_input').next().show();
								setTimeout(function(){
									$('#mobile_input').next().hide();
								},2000);
								return;
							}

							if($.trim($('#password_input').val())==''){
								$('#password_input').next().show();
								setTimeout(function(){
									$('#password_input').next().hide();
								},2000)
								return;
							}

							$.post('route.php?mod=account&action=change_mobile',{
								mobile:$('#mobile_input').val(),
								code:$('#password_input').val()
							},function(d){
								if(d.code == 0){
									$('#my_current_number').html(d.mobile);
									require(['ui/confirm'],function(confirm){
										new confirm('修改成功!',function(){});
									});
								}else{
									require(['ui/confirm'],function(confirm){
										new confirm('修改失败!可能是验证码已经失效，或手机号码错误！',function(){});
									});
								}
								$('#mobile_input').val('');
								$('#password_input').val('')
								single.hide();
							},'json');
						},
						body:body,
						afterRender:function(){
							$('#get_code').click(function(){

								if($('#get_code').html()!='获取验证码'){
									return false;
								}

								if($.trim($('#mobile_input').val())==''){
									$('#mobile_input').next().show();
									setTimeout(function(){
										$('#mobile_input').next().hide();
									},2000);
									return;
								}
								$.post('route.php?mod=account&action=change_mobile_get_code',{
									mobile:$('#mobile_input').val()
								},function(d){
									if(d.code == 0){
										$('#get_code').html('已经发送!(<span id="second">5</span>)');
										var timer = setInterval(function(){
											var number = parseInt($('#second').html());
											number-=1;
											$('#second').html(number);
											if(number == 0){
												$('#get_code').html('获取验证码');
												clearInterval(timer);
											}
										},1000);
									}
								},'json');
							});


						}
							
					});
			}
		}
	}

	changemobile.init();
	return single;
})