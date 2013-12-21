<?php

//根据pay_id,pay_name判断支付方式
function tender($order_id,$user_id,$opay_id,$note,$amount,$bonus=0,$bonus_id=0,$surplus=0,$integral=0)
{
	//$order_pay[]=array('pay_id'=>0,'pay_name'=>'');
	
	if($amount>0){
		if($opay_id==1||$opay_id==4)
		{
			if(strpos($note,'S')){				
				$order_pay['pay_id']=($opay_id==4)?2:4;
				$n=$order_pay['pay_name']='POS机';
			}else{				
				$order_pay['pay_id']=($opay_id==4)?1:3;
				$n=$order_pay['pay_name']='现金';
			}
			if($opay_id==1){
				$order_pay['pay_name']='异地'.$n;
			}		
			$order_pay['amount']=$amount;	
		}
	}
	if($opay_id==2 || $opay_id==3|| $opay_id==21 || $opay_id==6){
			$order_pay['pay_id']=($opay_id==2)?5:(($opay_id==3)?6:($opay_id==21)?7:8);
			if($opay_id==2)
			{
				$order_pay['pay_name']='支付宝';
			}
			if($opay_id==3)
			{
				$order_pay['pay_name']='快钱';
			}
			if($opay_id==6)
			{
				$order_pay['pay_name']='免费支付';
			}
			//$order_pay['pay_name']=$note;
			$order_pay['amount']=$amount;
	}
	if($opay_id==5){//大客户
		if($note=='月结'){
			$order_pay['pay_id']=11;
			$order_pay['pay_name']='大客户月结';
			$order_pay['amount']=$surplus;			
		}else{
			$order_pay['pay_id']=12;
			$order_pay['pay_name']='大客户预付款';
			$order_pay['amount']=$surplus;	
		}			
	}
	if(isset($order_pay)){
		$order_pay['order_id']=$order_id;
		$order_pay['user_id']=$user_id;
		$order_pay['type']=get_type($order_pay['pay_id']);
		//echo "<pre>";print_r($order_pay);
		$GLOBALS['db']->autoExecute('tender_info', $order_pay);
	}
	if($opay_id!=5&&$surplus>0){
		$order_pay['order_id']=$order_id;
		$order_pay['user_id']=$user_id;
		$order_pay['pay_id']=10;
		$order_pay['pay_name']='礼金卡';
		$order_pay['amount']=$surplus;	
		$order_pay['type']=get_type($order_pay['pay_id']);
		//print_r($order_pay);
		$GLOBALS['db']->autoExecute('tender_info', $order_pay);
	}
	if($bonus>0){
		$order_pay['pay_id']=9;
		$order_pay['order_id']=$order_id;
		$order_pay['user_id']=$user_id;
		//网站
		$bonus = bonus_info($bonus_id);
		$order_pay['pay_name']=$bonus['type_name'];
		$order_pay['amount']=1;	
		$order_pay['remark']=$bonus['bonus_sn'];
		$order_pay['type']=get_type($order_pay['pay_id']);
		//print_r($order_pay);
		$GLOBALS['db']->autoExecute('tender_info', $order_pay);
	}	
}
function get_type($pay_id)
{
	if($pay_id>0&&$pay_id<5) $type=1;	
	if($pay_id>4&&$pay_id<8) $type=2;
	if($pay_id==8) $type=3;
	if($pay_id==9) $type=4;
	if($pay_id>9&&$pay_id<13) $type=2;
	if($pay_id==13) $type=5;
	return $type;
}
?>