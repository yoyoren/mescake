<?php
	include_once("weixin/WxPayPubHelper/WxPayPubHelper.php");
	//error_reporting(E_ALL);
	//使用jsapi接口
	$jsApi = new JsApi_pub();

	//=========步骤1：网页授权获取用户openid============
	//通过code获得openid
	
	if (!isset($_GET['code']))
	{
		//触发微信返回code码
		$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
		Header("Location: $url"); 
	}else
	{
		//获取code码，以获取openid
	    $code = $_GET['code'];
		$jsApi->setCode($code);
		$openid = $jsApi->getOpenId();
	}
	//=========步骤2：使用统一支付接口，获取prepay_id============
	//使用统一支付接口
	$unifiedOrder = new UnifiedOrder_pub();
	
	$unifiedOrder->setParameter("openid","$openid");//商品描述
	$unifiedOrder->setParameter("body","MES商品结算");//商品描述
	//自定义订单号，此处仅作举例
	$timeStamp = time();
	$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
	$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 
	$unifiedOrder->setParameter("total_fee","100");//总金额
	$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
	//非必填参数，商户可根据实际情况选填
	$prepay_id = $unifiedOrder->getPrepayId();
	//=========步骤3：使用jsapi调起支付============
	$jsApi->setPrepayId($prepay_id);
	$jsApiParameters = $jsApi->getParameters();
?>
<!DOCTYPE html>
<html>
<head>
  <title>MES-结算</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,height=device-height"/>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name='apple-touch-fullscreen' content='yes'>
<meta name="full-screen" content="yes">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">
<meta name="description" content="mes,每实,蛋糕,北京蛋糕">
<link rel="stylesheet" href="http://s1.static.mescake.com/touch/css/reset.css"/>
<link rel="stylesheet" href="http://s1.static.mescake.com/touch/css/orderbuy.css"/>
<link rel="stylesheet" href="http://s1.static.mescake.com/touch/css/dialog.css"/>
<script src="http://s1.static.mescake.com/touch/zepto.min.js"></script>
<script src="http://s1.static.mescake.com/touch/lib.js"></script>
<script>
document.domain = 'mescake.com';
</script>
<style type="text/css">
a,input,em,.wap-price-intro,.wap-check-area,.wap-func-more-ico,.ra-con{ outline:0;-webkit-tap-highlight-color:rgba(0,0,0,0);}
</style>

 <style>
 .error-border{border-color:red}
 </style>
</head>
<body>
  
  <div id="app_main" class="app-main app-nav">
    <div class="return-area"><a class="ra-ico" href="/"></a><span class="ra-con">订单确认</span></div>
    <div class="scroll-area" id="scroll_container" style="padding-bottom:15px;padding-top:40px;">
      
      <div class="data-item">
        <p class="wap-order-title-item" id="staff_container">订单信息</p>
        <div class="line-item" style="display:none" id="fee_bar">运费：<span class="woi-price" style="color:#ff7e00;" id="address_fee_top">免邮</span></div>
        <div class="leave-mes" style="padding:0;">
          <div class="check-container" style="width:100%;">
            <input type="text" id="message_input" class="global-input has-bor-input" placeholder="留言说明（20字以内）" style="margin-bottom:0;">
          </div>
        </div>
      </div>

      <div class="data-item" id="new_address_container" style="display:none">
        <p class="wap-order-title-item">收货地址</p>
        <div class="check-container">
          <input type="text" id="new_contact" class="global-input has-bor-input" placeholder="收货人姓名">
        </div>
        <div class="check-container">
          <input type="telephone" id="new_tel" class="global-input has-bor-input" placeholder="联系电话">
        </div>
        <div class="clearfix tl-c">
          <div class="check-container fl-l" style="width:20%;">
            <input type="text" class="global-input has-bor-input" placeholder="北京市" disabled="disabled">
	    <input type="hidden" id="city_sel" value="441">
          </div>
          <div class="check-container fl-r" style="width:45%;display:none" id="street_container">
	    <input type="text" class="global-input has-bor-input" placeholder="请选择街道" id="street_picker">
	    <input type="hidden" id="district_sel" value="0">
          </div>
          <div class="check-container" style="width:75%;" id="zone_container">
             <input type="text" class="global-input has-bor-input" placeholder="请选择区" id="zone_picker">
	     <input type="hidden" id="region_sel" value="0">
          </div>
        </div>
        
        <div class="check-container">
          <input type="text" id="new_address" class="global-input has-bor-input" placeholder="详细地址">
        </div>
      </div>

      <div class="data-item" id="new_address_link" style="display:none">
        <p class="pos-r">新增地址<em class="wap-more-ico"></em></p>
      </div>
     

      <div class="data-item" id="address_container" style="display:none">
        <p class="wap-order-title-item">收货地址</p>
      </div>

      <div class="clearfix pad-l-r10">
        <em class="check-btn fl-l" style="display:none" id="new_address_btn">添加新地址</em>
        <em class="check-btn fl-r" style="display:none" id="more_address_btn">显示更多地址<em class="wap-ad-down-ico"></em></em>
      </div>

      <div class="data-item">
        <p class="wap-order-title-item">送货时间</p>
        <div class="check-container" id="date_picker_parent">
          <input type="text" class="global-input has-bor-input" placeholder="请选择送货日期" id="date_picker" onkeydown="return false;">
	  <em class="date-ico"></em>
	  <input type="hidden" id="year_sel" value="0">
	  <input type="hidden" id="month_sel" value="0">
	  <input type="hidden" id="day_sel" value="0">
        </div>
        <div class="check-container">
          <input type="text" class="global-input has-bor-input" placeholder="请选择送货时段" id="time_picker" onkeydown="return false;">
	  <em class="time-ico"></em>
	  <input type="hidden" id="hour_sel" value="0">
	  <input type="hidden" id="minute_sel" value="0">
        </div>
      </div>
      <div class="data-item" style="padding-bottom:15px;">      
	      <h4 class="wap-order-title-item">付款方式</h4>
	      <div class="wap-order-item pay_container" style="padding:14px 10px;" id="cash_container" data-id="1">
		<label for="cashon" >货到付款
		    <em class="wap-radio wap-radio-item checked pay_sel" id="cashon"></em>
		</label>
	      </div>
	      <div class="wap-order-item pay_container" style="padding:14px 10px;" id="alipay_container" data-id="3">
		<label for="alipay">支付宝<em class="wap-radio wap-radio-item pay_sel"  id="alipay" ></em></label>
	      </div>
	      <div class="wap-order-item pay_container" style="padding:14px 10px;" id="kuaiqian_container" data-id="4">
		<label for="kuaiqian">快钱<em class="wap-radio wap-radio-item pay_sel" id="kuaiqian"></em></label>
	      </div>
	    </div>
    </div>

    <div class="wap-total-btn-area">
      <p class="wtb-item-btn">
        <a href="#" onclick="callpay()" class="btn big-btn status1-btn">微信付款</a>
		<a href="#" id="done_button" class="btn big-btn status1-btn">付款</a>
		
      </p>
      <p class="wtb-item-total">
        <span style="font-size:18px; margin-bottom:4px; display:block;">合计<span id="total_price"></span>元
	<span style="display:none;font-size:14px;" id="shipping_fee_display">(含10元运费)</span>
	</span>
        <span style="color:#999;"><span id="staff_count">0</span>件商品</span>
      </p>
    </div>
  </div>
						<form action="http://www.mescake.com/flow.php" method="post" id="submit_form">
							<input type="hidden" id="leaving_message" name="leaving_message">
							<input type="hidden" name="pay_id" value="1" id="pay_id">
							<input type="hidden" name="surplus" value="0.00" id="surplus">
							<input type="hidden" name="bonus_id" value="请输入10位现金券券号" id="bonus_id">
							<input type="hidden" name="shipping_fee" value="0.00"  id="shipping_fee">
							<input type="hidden" name="x" value="97">
							<input type="hidden" name="y" value="32">
							<input type="hidden" name="token" value="from_mobile">
							<input type="hidden" name="step" value="done">
						</form>
  <script>
  window.HAS_BIG_STAFF = false;
  window.HAS_NO_SUGAR_STAFF = false;
  window.server_date = '<%=date%>';
  M.loading();
  
  //Loader(['touch/page/picker.js','touch/page/checkout.js','touch/page/shopcar_checkout.js']);
  </script>
  <script src="http://s1.static.mescake.com/touch/page/picker.js"></script>
  <script>
    (function(){
	   var SEL_ADDRESS_ID = location.href.split('addressid=').pop();
	   var CURRENT_ADDRESS_ID;
       var address_container = $('#address_container');

		var addressSingleTmpl = '<p class="address-detail address_item" data-id="<%=data.address_id%>" id="address_<%=data.address_id%>"\
							data-id="<%=data.address_id%>"\
							data-address="<%=data.address%>"\
							data-tel="<%=data.mobile%>"\
							data-contact="<%=data.consignee%>"\
							data-city="<%=data.city%>"\
							data-district="<%=data.district%>"\
						>\
						北京市-<%=data.cityName%> <%=data.districtName%> <%=data.address%><br>\
						<%=data.consignee%> <span class="address-num"><%=data.mobile%></span>\
						<em class="wap-more-ico"></em>\
						</p>';

  

  
	M.checklogin(function(isLogin) {
		if (isLogin) {
			window.IS_LOGIN = true;
			M.get('route.php?mod=order&action=get_order_address', {}, function(d) {
				var renderData;
				//如果有地址
				if(d.length){
					d = d.reverse();
					
					if(SEL_ADDRESS_ID){
					   for(var i=0;i<d.length;i++){
						  if(d[i].address_id == SEL_ADDRESS_ID){
							 renderData = d[i];
						  }
					   }
					   if(!renderData){
						  renderData = d[0];
					   }
					}else{
						renderData = d[0];
					}
					var html = M.mstmpl(addressSingleTmpl, {
						data : renderData
					});

					if (d.length) {
						CURRENT_ADDRESS_ID = renderData.address_id;
						address_container.show().append(html);
					}
					ifAddressNeedFee();
				}else{
				  //添加新地址的地方
				  $('#new_address_link').show();
				  $('#new_address_link')[CLICK](function(){
					location.href = '/newaddress';
				  });
				}
			});
		} else {
			$('#new_address_container').show();
		}
	});

	var streetData;
	var street_container = $('#street_container');
	var zone_container = $('#zone_container');
	var curCity;
	$('#zone_picker')[CLICK](function(e) {
		e.preventDefault();
		$(this).blur();
		new Picker({
			type : 'zone',
			el : this,
			onclick : function(id) {
				curCity = id;
				calAddressFee(curCity,0);
				M.get('route.php?mod=order&action=get_district', {
					city : id
				}, function(d) {
					if (d.code == 0) {
						if (d.data) {
							for (var i in d.data) {
								var name = d.data[i].name;
								if (!d.data[i].free) {
									if (name.indexOf('*') < 0) {
										d.data[i].name = '*' + name;
									}
								}
							}
							streetData = d.data;
							street_container.show();
				
							zone_container.css({'width':'30%'});
						} else {
							street_container.hide();
							zone_container.css({'width':'75%'});
						}
					}
				});
			}
		});
		return false;
	});

	$('#street_picker')[CLICK](function(e) {
		e.preventDefault();
		$(this).blur();

		new Picker({
			type : 'street',
			el : this,
			data : streetData,
			onclick:function(id){
				calAddressFee(curCity,id);
			}
		});
		return false;
	});
	//日期选择
	var time = (new Date(server_date * 1));
	var year = time.getFullYear();
	var month = time.getMonth() + 1;
	var day = time.getDate();
	var hour = time.getHours();
	var minutes = time.getMinutes();
	var monthHtml = '';
	var dayHtml = '';
	var jq = {
		year_sel : $('#year_sel'),
		month_sel : $('#month_sel'),
		day_sel : $('#day_sel'),
		hour_sel : $('#hour_sel'),
		minute_sel : $('#minute_sel'),
		region_sel : $('#region_sel'),
		dis_district : $('#district_sel'),
		message_input : $('#message_input'),
		shipping_fee_display : $('#shipping_fee_display'),
		shipping_fee : $('#shipping_fee')
	}
	var getSelDate = function() {
		var month = jq.month_sel.val();
		if(month<10){
		   month = '0'+month;
		}
		return jq.year_sel.val() + '-' + month + '-' + jq.day_sel.val();
	}
	var getCurDate = function() {
		return year + '-' + month + '-' + day;
	}
	var endHour = 22;
	var beginHour = 10;
	var tips = '';
    

	$('#date_picker')[CLICK](function(e) {
		e.preventDefault();
		$(this).blur();
		$('#time_picker').val('');
		tips = false;
		new Picker({
			type : 'date',
			el : this,
			onclick : function(year, month, day) {
				jq.year_sel.val(year);
				jq.month_sel.val(month);
				jq.day_sel.val(day);
				beginHour = 10;
				endHour = 22;

				var _date = (new Date(server_date * 1));
				var html = '';
				var selDate = getSelDate();
				var currentDate = getCurDate();
				var currHour = _date.getHours();
				var currTime = (new Date(window.server_date)).getTime();
				var selTime = (new Date(selDate)).getTime();

				if (window.HAS_BIG_STAFF || window.HAS_NO_SUGAR_STAFF) {
					if (selTime - currTime > 3600 * 1000 * 24) {
						beginHour = 14;
					} else {
						if (window.HAS_NO_SUGAR_STAFF) {
							tips = '无糖蛋糕制作需要24小时，所选择日期不能送货';
						} else {
							tips = '大于5磅蛋糕制作需要24小时，所选择日期不能送货';
						}
					}
				} else {
					//10点以后了 选择第二天的订单 只能是14点之后的
					if ((selTime - currTime == 3600 * 1000 * 24 && currHour > 21) || (selTime == currTime && currHour < 10)) {
						beginHour = 14;
					} else {
						//其他时间点下单

						var _hour = hour;
						var minute = minutes;
						_hour += 5;
						if (currentDate == selDate) {
							if (minute >= 30) {
								_hour += 1;
							}
							if (_hour > endHour) {
								tips = '制作需要5小时，今天已不能送货';
							} else if (hour < 10) {
								beginHour = 14;
							} else {
								beginHour = _hour;
							}
						}
					}
				}
				if (tips) {
					$('#time_picker').val(tips);
				}
			}
		});
	});

	$('#time_picker')[CLICK](function(e) {
		e.preventDefault();
		$(this).blur();
		if (tips) {
			return;
		}
		if($('#date_picker').val()==''){
			M.confirm('请先选择一个送货日期');
			return;
		}
		new Picker({
			type : 'time',
			el : this,
			beginHour : beginHour,
			endHour : endHour,
			tips : tips,
			onclick:function(hour,minute){
				jq.hour_sel.val(hour);
				jq.minute_sel.val(minute);
			}
		});
	});

    //锁定支付
	var payLock = false;
	$('body').delegate('.pay_container', CLICK, function() {
		if (payLock) {
			return;
		}
		payLock = true;
		var _this = $(this);
		var payId = _this.data('id');
		_this.find('em').trigger(CLICK);
		$('#pay_id').val(payId);
		setTimeout(function() {
			payLock = false;
		}, 20);

	})
    
    var _feeDomOperate = function(d){
		if (parseInt(d.fee)) {
	   		jq.shipping_fee_display.show();
			$('#fee_bar').show();
			$('#address_fee_top').html('10元');
		} else {
			jq.shipping_fee_display.hide();
			$('#fee_bar').hide();
			$('#address_fee_top').html('免邮费');
		}
		jq.shipping_fee.val(d.fee);
		updateTotalPriceDisplay(d);
	}

	var calAddressFee = function(city,district){
		M.get('route.php?mod=order&action=shipping_fee_cal', {
			city:city,
			district:district
		},function(d){
			_feeDomOperate(d);
		});
	}
	
	//地址选择
	var ifAddressNeedFee = function() {
		M.get('route.php?mod=order&action=if_address_need_fee', {
			'address_id' : CURRENT_ADDRESS_ID
		}, function(d) {
			if (d.code == 0) {
				_feeDomOperate(d);
			}
		});
	}

	address_container.delegate('.address_item', CLICK, function() {

		var _this = $(this);
		address_container.find('.address_item').removeClass('ama-item-current');
		_this.addClass('ama-item-current');

		//set current id
		CURRENT_ADDRESS_ID = _this.data('id');

		//计算一个地址是否需要运送费
		

	}).delegate('.addr_del', CLICK, function() {
		//delete an address info if you want
		var _this = $(this);
		var id = _this.data('id');
		M.confirm('确认删除这个地址信息吗？', function() {
			M.post('route.php?mod=order&action=del_order_address', {
				id : id
			}, function(d) {
				if (d.code == 0) {
					//把当前选中的送货地址删除了 就要更新这个id
					if (window.CURRENT_ADDRESS_ID == id) {
						window.CURRENT_ADDRESS_ID = null;
					}
					//remove it from UI
					$('#address_' + id).remove();
				}
			});
		})
		return false;
	}).delegate('.addr_edit', CLICK, function() {
		//edit address info
		var _this = $(this);
		var id = _this.data('id');
		_this = $('#address_' + id);
		//update current mod ID
		CURRENT_ID = id;
		require(['ui/newaddress'], function(newaddress) {
			newaddress.show({
				mod : true,
				id : id,
				data : {
					city : _this.data('city'),
					address : _this.data('address'),
					tel : _this.data('tel'),
					contact : _this.data('contact'),
					district : _this.data('district')
				},
				callback : function(id) {
					JQ.address_container.find('.address_item').removeClass('ama-item-current');
					CURRENT_ADDRESS_ID = id;
					$('#address_' + id).addClass('ama-item-current');
					ifAddressNeedFee();
				}
			});
		});
		return false;
	}).delegate('.address_item',CLICK,function(){
		M.loading();
		var id = $(this).data('id');
		location.href = '/addressmanager?id='+id;
	});

	//提交相关
	var checkout = function() {
		//直接提交数据到订购表单
		M.post('route.php?mod=order&action=checkout', {
			card_message : '',
			vaild_code : '',
			source : 'FROM_MOBILE'
		}, function(d) {
			var jqInput = jq.message_input;
			$('#leaving_message').val(jqInput.val());
			if (d.code == 0) {
				$('#submit_form').submit();
			} else {
				submitFail();
			}
		});

	}
	var vaildDate = function() {
        var ret = true;
		

		if (!getSelDate()) {
			ret = false;
		}

		var hour = jq.hour_sel.val();
		if (hour > 22 || hour < 10) {
			ret = false;
		}

		var minute = jq.minute_sel.val();
		if (minute != 0 && minute != 30) {
			ret = false;
		}

		if($('#date_picker').val()==''){
		   inputVaildError($('#date_picker'), 350);
		   return ret;
		}
		
		return ret;
	}
	var submitFail = function() {
		M.loadingEnd();
	}
	var inputVaildError = function(jqObj, t) {
		var _p = jqObj.parent();
		jqObj.addClass('error-border');
		t-=100;
		$('#scroll_container').scrollTop(t);
		setTimeout(function(){
		  jqObj.removeClass('error-border');
		},2000);
	}
	var addressInfoVaild = function() {
		if ($('#new_contact').val() == '') {
			inputVaildError($('#new_contact'), 350);
			return false;
		}

		if (!M.IS_MOBILE($('#new_tel').val())) {
			inputVaildError($('#new_tel'), 400);
			return false;
		}
		
		if($('#district_sel').val()==0&&$('#street_container').css('display')!='none'){
			inputVaildError($('#street_picker'), 300);
			return false;
		}

		if($('#region_sel').val()==0){
			inputVaildError($('#zone_picker'), 300);
			return false;
		}

		if ($('#new_address').val() == '') {
			inputVaildError($('#new_address'), 300);
			return false;
		}
		
				
		return true;
	}

	var saveconsignee = function(_this) {
	
		var me = this;
		var addressObj;
	    if(window.IS_LOGIN&&!CURRENT_ADDRESS_ID){
		   M.confirm('您还没有创建收货地址，请先创建一个收货地址',function(){
			  location.href = '/newaddress';
		   });
		   return;
		}
		if (CURRENT_ADDRESS_ID) {
			addressObj = $('#address_' + CURRENT_ADDRESS_ID);
		} else {
			if (!addressInfoVaild()) {
				return false;
			}
		}
        
		var data = {
			address_id : CURRENT_ADDRESS_ID || 0,
			consignee : addressObj ? addressObj.data('contact') : $('#new_contact').val(),
			country : 441,
			city : addressObj ? addressObj.data('city') : jq.region_sel.val(),
			address : addressObj ? addressObj.data('address') : $('#new_address').val(),
			district : addressObj ? addressObj.data('district') : jq.dis_district.val(),
			mobile : addressObj ? addressObj.data('tel') : $('#new_tel').val(),
			bdate : getSelDate(),
			hour : jq.hour_sel.val(),
			minute : jq.minute_sel.val(),
			message_input : jq.message_input.val().substring(0, 140),
			inv_payee : '',
			inv_content : ''
		};

		if (!vaildDate()) {
			submitFail();
			return;
		}

		M.loading();
		//保存订单
		M.post('route.php?action=save_consignee&mod=order', data || {}, function(d) {
			if (d.msg == 'time error') {
				M.confirm('选择的送货时间距离制作时间不能少于5小时!');
				submitFail();
				return;
			}

			if (d.code != 0) {
				M.confirm('收货信息填写不完整，重新填写后再提交');
				submitFail();
				return;
			}

			if (window.IS_LOGIN) {
				checkout();
			} else {
				var username = $('#new_tel').val();
				if (!M.IS_MOBILE(username)) {
					M.inputError('new_tel_error');
					return;
				}

				M.get('route.php?action=check_user_exsit&mod=account', {
					username : username
				}, function(d) {
					if (d.exsit) {
						M.confirm('您所使用的手机号已经被注册，请登录后再继续订购', function() {
							location.href = '/login';
						});
						submitFail();
					} else {
						var username = data.mobile;
						M.post('route.php?action=auto_register&mod=account', {
							username : username
						}, function(d) {
							if (d.code == 0) {
								//注册成功后给这个用户结帐
								setTimeout(function() {
									checkout();
								}, 100);
							} else {
								submitFail();
							}
						});
					}
				});
			}

		});
	}

	//保存支付信息
	$('#done_button')[CLICK](function() {
		saveconsignee();
	});

	$('.pay_sel')[CLICK](function() {
		$('.pay_sel').removeClass('checked');
		$(this).addClass('checked');
	}); 
    
 })(); 
  </script>
  <script type="text/javascript">

		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					alert(res.err_code+res.err_desc+res.err_msg);
					
					if(res.err_msg == "ok"){
						
					}else if(res.err_msg == "cancle"){
						
					}
					
				}
			);
		}

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}
	</script>
  <script src="http://s1.static.mescake.com/touch/page/shopcar_checkout.js"></script>
</body>
</html>
