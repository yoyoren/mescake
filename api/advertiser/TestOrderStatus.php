<?php
header("Content-type:text/html;charset=gbk");
include_once 'Sender.php';

/**
 * 接口测试类
 * 
 * 正式调用时订单、商品信息必须要严格填写。
 * @var
 */

      //  $service = new Service();
		
		$config = new Config();
        $order = new OrderStatus();
        //$order -> setOrderNo($_POST["orderNo"]);      // 设置订单编号
        //$order -> setOrderNo($_GET["orderNo"]);
        $a = rand(0,999999);
        $b = rand(1,100000);
        $c = rand(0,999999);
    	$orderno = $a+$b.$c;      	

        $order -> setOrderNo($orderno);
        $order -> setUpdateTime("2012-04-06 20:09:09"); // 设置订单更新时间，如果没有下单时间，要提前对接人提前说明
        $order -> setFeedback("NDgwMDB8dGVzdA==");			// 测试时使用"101"，正式上线之后活动id必须要从cookie中获取
      
		$order -> setOrderStatus("active");             // 设置订单状态
        $order -> setPaymentStatus("1");   				// 设置支付状态
        $order -> setPaymentType("支付宝");		// 支付方式



        //var_dump(get_object_vars($order));
		$sender = new Sender();
		$sender -> setOrderStatus($order);
		$sender -> sendorderStatus();

?>