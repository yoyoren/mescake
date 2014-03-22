define(['ui/dialog'],function(Dialog){
	var NORMAL_CANDLE = CANDLE;
	var NUMBER_CANDLE = NUM_CANDLE;
	var body = '<div>\
				  <div class="check-candle-area clearfix">\
					<div class="cca-item">\
					  <div class="cca-img-area">\
						<img src="css/img/lazhu1.jpg">\
					  </div>\
					  <p class="cca-intro">艺术蜡烛</p>\
					  <div><em class="radiobox-item radiobox-checked candle_type_sel" data-id="'+NORMAL_CANDLE+'"></em>￥5</div>\
					</div>\
					<div class="cca-item">\
					  <div class="cca-img-area">\
						<img src="css/img/lazhu-num-7.jpg" id="candle_number_1">\
						<img src="css/img/lazhu-num-7.jpg" id="candle_number_2">\
					  </div>\
					  <input type="text" class="global-input" style="width:70px;" placeholder="输入数字" id="candle_number_input">\
					  <div><em class="radiobox-item candle_type_sel" data-id="'+NUMBER_CANDLE+'" id="number_candle_sel"></em>￥10</div>\
					</div>\
				  </div>\
				  <div class="single-btn-area">\
					<input class="btn status1-btn" type="button" id="candle_confirm" value="确定">\
				  </div>\
				</div>'

	var errorTip = '<span class="tips-container">{msg}</span>';
	var single;
	var rec_id;
	var callback;
	var brithCard = {
		addOne:function(candleId,parent_id,goods_attr){
			var goods = {};
			var spec_arr = [];
			var fittings_arr = [];
			var number = 1;
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
					goods_attr: goods_attr||0
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
					    width:460,
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
							
							jqInput.placeholder();
							$('#candle_number_input').keyup(function(){
								var _this = $(this);
								var _val = _this.val();
								
								if(_val.length>2){
									_this.val(_val.substring(0,2));
								}
								
								if(_val.length<1){
									$('#candle_number_1').attr('src','css/img/lazhu-num-7.jpg').show();
									$('#candle_number_2').attr('src','css/img/lazhu-num-7.jpg').show();
									return;
								}
								
								$('#number_candle_sel').trigger('click');
								_val = parseInt(_val,10);
								if(_val<10){
									$('#candle_number_1').hide();
									$('#candle_number_2').attr('src','css/img/lazhu-num-'+_val+'.jpg');
								}else{
									_val = ''+_val;
									$('#candle_number_1').show();
									$('#candle_number_1').attr('src','css/img/lazhu-num-'+_val[0]+'.jpg');
									$('#candle_number_2').attr('src','css/img/lazhu-num-'+_val[1]+'.jpg');
								}
							});

							$('#candle_confirm').click(function(){
								var text = jqInput.val();
								if(candleId == NORMAL_CANDLE){
									brithCard.addOne(NORMAL_CANDLE,rec_id,0);
								}else{
									var num = jqInput.val();
									if(num.length<1||num.length>2){
									   return false;
									}
									brithCard.addOne(NUMBER_CANDLE,rec_id,num);
								}
								single.hide();
							});

							$('.candle_type_sel').click(function(){
								var _this = $(this);
								$('.candle_type_sel').removeClass('radiobox-checked');
								_this.addClass('radiobox-checked');
								candleId = _this.data('id');
								return;
							})
						}
					});
			}
		}
	}

	brithCard.init();
	return single;
})