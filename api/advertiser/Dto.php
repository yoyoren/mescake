<?php
define('IN_ECS', true);
$filename='../../includes/init.php';
if(file_exists($filename))
{
	require('../../includes/init.php');
}
include_once '../util/Config.php';
class Dto{
	public function getOrderByOrderTime($campaignId,$orderStatTime,$orderEndTime){
	 	if (empty($campaignId) || empty($orderStatTime)||empty($orderEndTime)){
	 		echo "campaignId ,orderStatTime or orderEndTime is null";
			exit;
	 	}
		 $sql="SELECT p.order_id,order_sn,add_time,order_status,pay_status,pay_name,order_time,cid,wi,order_status,pay_status,pay_name,shipping_fee,surplus,bonus,order_amount FROM `cps` as p LEFT OUTER JOIN ecs_order_info as i on i.order_id=p.order_id where p.cid=".$campaignId." and order_time>".$orderStatTime." and order_time<".$orderEndTime;
		$dborder=$GLOBALS['db']->getAll($sql);	
		
		if(empty($dborder)) return NULL;
		foreach($dborder as $k=>$v){
			$order = new Order();
			$order -> setOrderNo($v['order_sn']);
			$order_time=date('Y-m-d H:i:s',$v['order_time']);
			$order -> setOrderTime($order_time);  // 设置下单时间
			$order -> setUpdateTime($order_time); // 设置订单更新时间，如果没有下单时间，要提前对接人提前说明
			$order -> setCampaignId($v['cid']);                 // 测试时使用"101"，正式上线之后活动id必须要从数据库里面取
			$order -> setFeedback($v['wi']);
			$order -> setFare($v['shipping_fee']);
        	$order -> setFavorable($v['bonus']+$v['surplus']);	
			//$orderStatus = new OrderStatus();
			//$orderStatus -> setOrderNo($order -> getOrderNo());
			$order -> setOrderStatus($v['order_status']);       // 设置订单状态
			$order -> setPaymentStatus($v['pay_status']);   				// 设置支付状态
			$order -> setPaymentType($v['pay_name']);		// 支付方式
			
			$sql="select * from ecs_order_goods where order_id=".$v['order_id']." and goods_price>100";
		
			$order_goods=$GLOBALS['db']->getAll($sql);
			//echo "<pre>";print_r($order_goods);
			foreach($order_goods as $k1=>$v1){
				 $pro = new Product();
		    	//$pro -> setOrderNo($order -> getOrderNo());
				$pro -> setProductNo($v1['goods_sn']);
				$pro -> setName($v1['goods_name']);
				$pro -> setCategory("蛋糕");
				$pro -> setCommissionType("");
				$pro -> setAmount($v1['goods_number']);
				$a=number_format($v1['goods_price']*(1-(($v['bonus']+$v['surplus'])/($v['bonus']+$v['surplus']+$v['order_amount']))),2,".","");
				$pro -> setPrice($a);
				$products[]=$pro;
			}	
			$order -> setProducts($products);
			$orderlist[]=$order;
			$products=array();
		}     
		//print_r($orderlist);         
		//echo json_encode($orderlist);	
	 	return $orderlist;
	}
	
	/**
	 * 根据活动id和订单更新时间查询订单信息
	 * @param 活动id $campaignId
	 * @param 订单更新时间 $date
	 */
	public function getOrderByUpdateTime($campaignId,$updateStatTime,$updateEndTime){
	 	if (empty($campaignId) || empty($updateStatTime)||empty($updateEndTime)){
	 		throw new Exception("CampaignId or date is null!", 648, "");
	 	}
	    
	 	$orderStatusList [] = null;
		$a = rand(0,999999);
        $b = rand(1,100000);
        $c = rand(0,999999);
    	$orderno = $a+$b.$c; 
	    $orderStatus = new OrderStatus();
        $orderStatus -> setOrderNo($orderno);
		$orderStatus -> setUpdateTime("2012-04-18 20:09:09"); // 设置订单更新时间，如果没有下单时间，要提前对接人提前说明
		$orderStatus -> setFeedback("NDgwMDB8dGVzdA");
		$orderStatus -> setOrderStatus("active");             // 设置订单状态
        $orderStatus -> setPaymentStatus("pay");   				// 设置支付状态
        $orderStatus -> setPaymentType("1");		// 支付方式


		//$a = rand(0,999999);
        //$b = rand(1,100000);
       // $c = rand(0,999999);
    	//$orderno = $a+$b.$c;
		$orderStatus1 = new OrderStatus();
        $orderStatus1 -> setOrderNo('376557338653');
		$orderStatus -> setFeedback("NDgwMDB8dGVzdA==");
		$orderStatus1 -> setUpdateTime("2012-04-18 20:09:09");
		$orderStatus1 -> setOrderStatus("已完成");             // 设置订单状态
        $orderStatus1 -> setPaymentStatus("已付款");   				// 设置支付状态
        $orderStatus1 -> setPaymentType("在线支付(支付宝)");		// 支付方式


		$orderStatusList[0]=$orderStatus;
		$orderStatusList[1]=$orderStatus1;

		//echo json_encode($orderlist);
		
	 	return $orderStatusList;
	}
	
 }
