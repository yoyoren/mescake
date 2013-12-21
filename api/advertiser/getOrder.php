<?php
include_once 'Service.php';
header("Content-type:text/html;charset=UTF-8");
ob_clean();
/**
 * 订单查询接口
 * 
 * @auther lsj
 */
	$campaignId = $_GET["cid"];  // 活动id
	$orderStartTime = $_GET["orderStartTime"]; // 下单起始时间
	$orderEndTime = $_GET["orderEndTime"]; // 下单终止时间
	$userip = $_SERVER["REMOTE_ADDR"];
	$arr = $_GET; 
	unset($arr['mid']);
	ksort($arr);
	$get = '';
	foreach($arr as $k=>$v){
		$get.= $k.'='.$v.'&';
	}
	$get = substr($get,0,-1);

	$servic = new Service();
	
    if(limit_ip){
		 $arr = explode(',',ip_list);
		 if(!in_array($userip,$arr)){
			 echo 'ip is limited!';
			 return ;
		 }

	}
	if (null == $campaignId || null == $orderStartTime|| null == $orderEndTime || !is_numeric($campaignId)){
		echo "Paramter is null or campaignId isn't the numeric!";
		return ;
	}

	if(is_sign){
		$sign = $_GET['mid'];//验证码

	    if($sign != md5($get) ){
          echo "sign is error!";
		  return;

		}
	}
	$servic -> getOrderInfoByJSON($campaignId, $orderStartTime,$orderEndTime);
	
	
?>