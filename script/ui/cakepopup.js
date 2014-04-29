define(['ui/dialog'],function(Dialog){
	var body = '<div class="cake-tip-select">\
          <img class="cts-img-item" id="popup_goods_image" src="http://mescake.com/themes/default/images/sgoods/S02.jpg">\
          <p class="cts-intro" id="can_cut_frame" style="display:none">\
            <label for="cut_check">\
              <input type="checkbox" class="check-item" id="cut_check">\
              <b>请帮我切块（免费）</b><br>\
              在制作时帮您把蛋糕切块，方便分享（<span id="cut_weight_display"></span>蛋糕可切<span id="popup_cut_num"></span>块）\
            </label>\
          </p>\
          <p class="cts-intro" id="no_sugar_frame" style="display:none"> \
            <label for="nosugar_check"><input type="checkbox" class="check-item" id="nosugar_check">\
            <b>请使用无糖食材制作（加￥<span id="nosugar_add_price"></span>元）</b><br>\
            一些蛋糕可以使用无糖的食材来制作，适合血糖敏感等人群食用</label>\
          </p>\
          <div class="single-btn-area">\
            <input class="btn status1-btn" type="button" id="goto_checkout" value="继续">\
          </div>\
        </div>'

	
	var single;
	var callback = function(){};
	var no_sugar_attrid;
	var cakeDialog = {
		
		init:function(){
			if(single){
				single.show();
			}else{
				 single = new Dialog({
						title:'您选择了<span id="pop_goods_weight"></span>的<span id="pop_goods_name"></span>（￥<span id="pop_goods_price"></span>）',
						notShow:true,
						width:500,
						onshow:function(d){
							if(d){
								var weight = window.GOODS_WEIGHT.split('：')[0];
								var price = $('#staff_price').html();
								callback = d.callback;
								$('#pop_goods_name').html(window.GOODS_NAME);
								$('#pop_goods_price').html(price);
								$('#pop_goods_weight').html(weight);
								$('#popup_goods_image').attr('src','/themes/default/images/sgoods/'+window.GOODS_SN.substring(0,3)+'.jpg');
								if(window.CAN_CUT){
									$('#can_cut_frame').show();
								}

								if(window.NO_SUGAR){
									$('#no_sugar_frame').show();
								}
								$('#cut_weight_display').html(weight);
								MES.get({
									mod:'goods',
									action:'get_cutnum_goods_attr',
									param:{
									  id : window.GOODS_ID,
									  attr_value :window.GOODS_WEIGHT
									},
									callback:function(d){
										if(d.data){
											$('#can_cut_frame').show();
											$('#popup_cut_num').html(d.data.attr_price);
										}else{
											$('#can_cut_frame').hide();
										}
									}
								});

								MES.get({
									mod:'goods',
									action:'get_nosugar_goods_attr',
									param:{
									  id : window.GOODS_ID,
									  attr_value :window.GOODS_WEIGHT
									},
									callback:function(d){
										if(d.data){
											var nosuagr_price = d.data.attr_price;
											price = price.replace('￥');
											var add_price = nosuagr_price - price;
											$('#nosugar_add_price').html(add_price);
											no_sugar_attrid = d.data.goods_attr_id;
										}else{	
											$('#no_sugar_frame').hide();
										}
										
									}
								});
								
							}
						},
						bottom: ' ',
						body:body,
						afterRender:function(){
							$('#goto_checkout').click(function(){
								var cut = false;
								var nosugar = false;
								if($('#cut_check')[0].checked){
									cut = true;
								}

								if($('#nosugar_check')[0].checked){
									nosugar = true;
									window.ATTR = no_sugar_attrid;
								}
								callback(cut,nosugar);
								single.hide();
							});
						}
							
					});
			}
		}
	}

	cakeDialog.init();
	return single;
})