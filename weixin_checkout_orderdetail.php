<?php
    define('IN_ECS', true);
	require (dirname(__FILE__) . '/includes/init.php');

	$out_trade_no = $_GET['orderid'];
	if($out_trade_no == null){
		session_start();
		$out_trade_no = $_SESSION['wx_pay_order_sn'];
	}
	
	if($out_trade_no == null){
	   $out_trade_no = $_COOKIE['pay_from_detail'];
	}
	
	$sql="select * from ecs_order_info where order_sn='{$out_trade_no}'";	
	$order_info = $db->getRow($sql);
	$order_id = $order_info['order_id'];
	$pay_status = $order_info['pay_status'];
	$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('pay_log')." WHERE order_id = '$order_id'";
    $pay_log = $GLOBALS['db']->getRow($sql);
	$out_trade_no = $pay_log['log_id'];
	
	$total_fee = $order_info['order_amount'];
	$total_fee = floatval($total_fee);
	$total_fee = $total_fee*100;
	//var_dump($total_fee);
	//exit();
	$jsApiParameters = '';
	if($pay_status != 2){
		include_once("weixin/WxPayPubHelper/WxPayPubHelper.php");
		//error_reporting(E_ALL);
		//使用jsapi接口
		$jsApi = new JsApi_pub();

		//=========步骤1：网页授权获取用户openid============
		//通过code获得openid
		
		if (!isset($_GET['code']))
		{
			//触发微信返回code码
			
			$url = $jsApi->createOauthUrlForCode('http://www.mescake.com/weixin_checkout_orderdetail.php');
			Header("Location: $url"); 
		}else{
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
		
		//$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
		//$out_trade_no = $order_id;
		$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 
		$unifiedOrder->setParameter("total_fee","$total_fee");//总金额
		$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
		$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
		//非必填参数，商户可根据实际情况选填
		$prepay_id = $unifiedOrder->getPrepayId();
		//=========步骤3：使用jsapi调起支付============
		$jsApi->setPrepayId($prepay_id);
		$jsApiParameters = $jsApi->getParameters();
	}
?>
<!DOCTYPE html>
<html>
<head>
<title>MES</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,height=device-height"/>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name='apple-touch-fullscreen' content='yes'>
<meta name="full-screen" content="yes">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">
<meta name="description" content="mes,每实,蛋糕,北京蛋糕">
<link rel="stylesheet" href="http://s1.static.mescake.com/touch/css/reset.css"/>
<link rel="stylesheet" href="http://s1.static.mescake.com/touch/css/home-wap.css"/>
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
  <div class="head-area">
    <div class="mes-head clearfix">
      <a href="/" class="main-logo"></a>
      <div class="h-ico-area fl-r">
        <a href="#" id="nav_account" class="h-ico-item"><em class="nb-ico-acc"></em></a>
        <a href="http://touch.n.mescake.com/shopcar" class="h-ico-item"><em class="nb-ico-car"></em><em class="nb-num" id="shopcar_num" style="display:none">0</em></a>
        <a href="#" id="nav_my_order" class="h-ico-item"><em class="nb-ico-order"></em></a>
      </div>
    </div>
  </div>
  <script>
	M.checklogin(function(d){
	if(d){
		$('#nav_account').attr('href','http://touch.n.mescake.com/account');
		$('#nav_my_order').attr('href','http://touch.n.mescake.com/myorder');
	}else{
		$('#nav_account').attr('href','http://touch.n.mescake.com/login?from=account');
		$('#nav_my_order').attr('href','http://touch.n.mescake.com/login?from=myorder');
	}
	});

	$('#logout')[CLICK](function(){
	  M.loading();
	  M.checkLogout(function(){
		location.reload();
	  });
	});
	M.getShopCarCount = function(animation){
		var el = $('#shopcar_num');
		M.get('route.php?mod=account&action=get_order_count_by_sid',{},function(d){
			if(d.count){
				el.html(d.count).show();
				if(animation){
					el.addClass('animated bounce');
					el[0].addEventListener('webkitAnimationEnd',function(){
						el.removeClass('animated bounce');
					},false);
				}
			}
		});
	}
	M.getShopCarCount();
	</script>
	<div class="scroll-area" style="padding-bottom:100px;" id="container">
		<p class="need-login-tip tl-r" style="display:none">您还没有设置访问密码，建议<a href="#" class="td-u">设置</a>以完成注册，方便查看订单信息。</p>
		<h4 class="wap-order-title-item">订单信息</h4>
	</div>
</div>
<script>
M.loading();
var orderId = "<?php echo $order_id;?>";
window.IN_WX_PAY = true;
</script>

<script>
M.checklogin(function(d){
if(d){
	$('#nav_account').attr('href','http://touch.n.mescake.com/account');
	$('#nav_my_order').attr('href','http://touch.n.mescake.com/myorder');
}else{
	$('#nav_account').attr('href','http://touch.n.mescake.com/login?from=account');
	$('#nav_my_order').attr('href','http://touch.n.mescake.com/login?from=myorder');
	
}
});

$('#logout')[CLICK](function(){
  M.loading();
  M.checkLogout(function(){
	location.reload();
  });
});
M.getShopCarCount = function(animation){
	var el = $('#shopcar_num');
	M.get('route.php?mod=account&action=get_order_count_by_sid',{},function(d){
		if(d.count){
			el.html(d.count).show();
			if(animation){
				el.addClass('animated bounce');
				el[0].addEventListener('webkitAnimationEnd',function(){
					el.removeClass('animated bounce');
				},false);
			}
		}
	});
}
M.getShopCarCount();
</script>


<script type="text/javascript">
		var param = <?php echo $jsApiParameters; ?>;
		var str = '';
		for(var i in param){
			str += (i + ':' + param[i]);
		}
		;
		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					//WeixinJSBridge.log(res.err_msg);
					//alert(res.err_msg);
					if(res.err_msg.indexOf("ok")>-1){
						alert('支付成功，我们会尽快安排蛋糕制作');
						location.reload();
					}else if(res.err_msg.indexOf("cancel")>-1){
						alert('支付失败，请重新尝试完成付款');
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
	<script src="http://s1.static.mescake.com/touch/page/detail.js"></script>
<% include includes/baidu %>
</body>  
</html>
