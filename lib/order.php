<?php

class ECS_Order{
	public static function get_order_address(){
		GLOBAL $db;
		$sql="select * from ecs_user_address where user_id={$_SESSION['user_id']}";	
		$address=$db->getAll($sql);
		for($i=0;$i<count($address);$i++){
			$city=$db->getOne("select region_name from ship_region where region_id={$address[$i]['city']}");
			$address[$i]['cityName'] = $city;
		}
		return json_encode($address);
	}

	public static function del_order_address($address_id){
		GLOBAL $db;
		$sql="delete * from ecs_user_address where user_id={$_SESSION['user_id']} and address_id=".$address_id;	
		$address=$db->getAll($sql);
		return json_encode(array('msg'=>'ok','code'=>'1'));
	}

   //收货人,城市,地区,地质,和电话
	public static function add_order_address($contact,$country,$city,$address,$tel){
		GLOBAL $db;
		$db->query("INSERT INTO ecs_user_address(address_name, user_id, consignee, country, province, city, district, address, tel, mobile, money_address, route_id, ExchangeState, ExchangeState2)
		VALUES('',{$_SESSION['user_id']},'{$contact}','{$country}','0', '{$city}', '0', '{$address}', '', '{$tel}', NULL, '0', '0', '0')");
		return json_encode(array('msg'=>'ok','code'=>'1'));
	}

	public static function get_order_list(){
		require(ROOT_PATH . 'includes/lib_order.php');
		require(ROOT_PATH . 'includes/lib_transaction.php');
		$cart_goods = get_cart_goods();
		return json_encode($cart_goods);
	}
	//获得地区列表
	public static function get_region(){
		GLOBAL $db;
		$district_list=$db->getAll('select * from ship_region where parent_id=501');
		return json_encode($district_list);
	}



		

}

?>