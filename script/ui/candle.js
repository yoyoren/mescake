define(['ui/dialog'],function(Dialog){
	var NORMAL_CANDLE = CANDLE;
	var NUMBER_CANDLE = NUM_CANDLE;
	var body = '<div class="check-candle-area">\
					<div class="cca-item cca-item-current clearfix candle_sel_frame"  data-id="'+NORMAL_CANDLE+'">\
					  <em class="radiobox-item radiobox-checked fl-l candle_type_sel"  data-id="'+NORMAL_CANDLE+'"></em>\
					  <p class="cca-intro fl-l"><b>艺术蜡烛</b><br>￥5/只</p>\
					  <div class="cca-img-area fl-l">\
						  <img src="css/img/lazhu1.jpg" class="big-candle">\
					  </div>\
					  <div class="cca-func-area fl-l">\
						<p>请输入蜡烛数量</p>\
						<input type="text" class="global-input" id="num_candle_input" style="width:70px;">\
					  </div>\
					</div>\
					<div class="cca-item clearfix candle_sel_frame"  data-id="'+NUMBER_CANDLE+'">\
					  <em class="radiobox-item fl-l candle_type_sel" data-id="'+NUMBER_CANDLE+'" id="number_candle_sel"></em>\
					  <p class="cca-intro fl-l"><b>数字蜡烛</b><br>￥5/个</p>\
					  <div class="cca-img-area fl-l" id="num_candle_display">\
						<img class="candle-item" src="css/img/lazhu-num-5.jpg">\
						<img class="candle-item" src="css/img/lazhu-num-2.jpg">\
						<img class="candle-item" src="css/img/lazhu-num-0.jpg">\
					  </div>\
					  <div class="cca-func-area fl-l">\
						<p>请输入你要的数字</p>\
						<input type="text" class="global-input" style="width:70px;" placeholder="520" id="candle_number_input">\
					  </div>\
					</div>\
				  </div>\
				  <div class="single-btn-area">\
					<input class="btn status1-btn" type="button" id="candle_confirm" value="确定">\
				  </div>'

	var errorTip = '<span class="tips-container">{msg}</span>';
	var single;
	var rec_id;
	var callback;
	var brithCard = {
		addOne:function(candleId,parent_id,goods_attr,number){
			var goods = {};
			var spec_arr = [];
			var fittings_arr = [];
			var number = number||1;
			var quick = 0;
	
			//商品重量
			goods.spec = spec_arr;
			goods.goods_id = candleId;
			//数量
			goods.number = number;
			goods.parent = 0;
			MES.post({
				mod:'order',
				action:'add_to_cart',
				param:{
					goods : $.toJSON(goods),
					goods_id : candleId,
					parent_id : parent_id,
					goods_attr: goods_attr||0,
					is_cut:0
				},
				callback:function(d){
					callback(d,candleId);
				}
			});
		},
		init:function(){
	
			if(single){
				single.show();
			}else{
				 single = new Dialog({
					    width:500,
						title:'选择蜡烛样式',
						body:body,
						bottom:' ',
						onshow:function(d){
							if(d){
								rec_id = d.id;
								callback = d.callback||function(){};
							}
						},
						onconfirm:function(){
							
						},
						afterRender:function(_this){
							var candleId = NORMAL_CANDLE;
							var jqInput = $('#candle_number_input');
							
							jqInput.placeholder().val('');
							$('#candle_number_input').keydown(function(e){
								var cantype = false; 
								if(e.which<106&&e.which>95){
									cantype = true;
								}
								if(e.which<57&&e.which>48){
									cantype = true;
								}

								if(e.which == 8){
									cantype = true;
								}
								if(!cantype){
									e.preventDefault&&e.preventDefault();
									return false;
								}
							});
							$('#candle_number_input').keyup(function(e){
								var _this = $(this);
								var _val = _this.val();
								var max = 8;
								var realVal = _val.substring(0,max);
								var frame = $('#num_candle_display');
								if(_val.length>max){
									_this.val(realVal);
								}
								frame.removeClass('more-than4 for4');
								if(_val.length>4){
									frame.addClass('more-than4');
								}else if(_val.length==4){
									frame.addClass('for4');
								}
								realVal = realVal.split('');
								var html = '';
								for(var i =0;i<realVal.length;i++){
									html +='<img class="candle-item" src="css/img/lazhu-num-'+realVal[i]+'.jpg">';
								}
								
								frame.html(html);
								return;
							});

							$('#candle_confirm').click(function(){
					
								
								if(candleId == NORMAL_CANDLE){
									
									var candleNum = $('#num_candle_input').val();
									candleNum = parseInt(candleNum,10);
									brithCard.addOne(NORMAL_CANDLE,rec_id,0,candleNum);
								}else{
									var num = $('#candle_number_input').val();
									if(num.length<1||num.length>8){
									   return false;
									}
									num += '';
									num = num.split('');
									var _numHash = {};
									for(var i=0;i<num.length;i++){
										var _number = num[i];
										if(!_numHash[_number]){
											_numHash[_number] = 1;
										}else{
											_numHash[_number]++;
										}
									}

									for(var num in _numHash){
										brithCard.addOne(NUMBER_CANDLE,rec_id,num,_numHash[num]);
									}
								}
								single.hide();
							});
	
							$('.candle_type_sel').click(function(){
							
								var _this = $(this);
								$('.candle_type_sel').removeClass('radiobox-checked');
								_this.addClass('radiobox-checked');
								candleId = _this.data('id');
								return;
							});

							$('.candle_sel_frame').click(function(){
								var _this = $(this);
								$('.candle_sel_frame').removeClass('cca-item-current');
								$('.candle_type_sel').removeClass('radiobox-checked');
								_this.addClass('cca-item-current').find('.candle_type_sel').addClass('radiobox-checked');
								candleId = _this.data('id');
							});

						}
					});
			}
		}
	}

	brithCard.init();
	return single;
})