define(['ui/dialog'],function(Dialog){
	var body = '<div>\
          <img src="css/img/birth-bg.jpg" class="birth-bg">\
          <div class="check-container">\
            <input type="text" class="global-input no-border-t" id="brith_card_input" placeholder="请输入生日牌的内容（10字以内）">\
            <span class="tips-container" id="brith_card_input_error" style="display:none">字数超过限制</span>\
          </div>\
          <div class="single-btn-area">\
            <input class="btn status1-btn" type="button" value="确定" id="brith_confirm">\
          </div>\
        </div>'

	var errorTip = '<span class="tips-container">{msg}</span>';
	var single;
	var rec_id;
	var words=10;
	var brithCard = {
		
		init:function(){
			if(single){
				single.show();
			}else{
				 single = new Dialog({
						title:'添加生日牌',
						body:body,
						bottom:' ',
						onshow:function(d){
							if(d){
								rec_id = d.id;
								words = d.words;
								$('#brith_card_input').attr('placeholder','请输入生日牌的内容（'+words+'字以内）');
							}
						},
						afterRender:function(_this){
							var jqInput = $('#brith_card_input');
							jqInput.placeholder();
							$('#brith_confirm').click(function(){
								var text = jqInput.val();
								if(!text){
									single.hide();
									return;
								}
								if(text.length>words){
									MES.inputError('brith_card_input_error');
									return;
								}else{
									
									var displayContainer = $('#brith_card_display_'+rec_id);
									$('#brith_card_add_'+rec_id).hide();
									displayContainer.find('.brith_card_name').html(text);
									displayContainer.show();
								}
								single.hide();
							});
						}
					});
			}
		}
	}

	brithCard.init();
	return single;
})