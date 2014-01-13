<?php

class MES_Order{
	public static function get_order_address(){
		GLOBAL $db;
		if($_SESSION['user_id']){;
			$sql="select * from ecs_user_address where user_id={$_SESSION['user_id']}";	
			$address=$db->getAll($sql);
			for($i=0;$i<count($address);$i++){
				$city=$db->getOne("select region_name from ship_region where region_id={$address[$i]['city']}");
				$address[$i]['cityName'] = $city;
			}
		}else{
			$address = [];
		}
		return json_encode($address);
	}

	public static function del_order_address($address_id){
		GLOBAL $db;
		$sql="delete from ecs_user_address where address_id={$address_id}";
		$address=$db->query($sql);
		return json_encode(array('msg'=>'ok','code'=>'0'));
	}


	public static function update_order_address($address_id,$country,$city,$contact,$address,$tel){
		GLOBAL $db;
		$db->query("update ecs_user_address set country={$country},city={$city},consignee='{$contact}',address='{$address}',mobile='{$tel}'
			where user_id={$_SESSION['user_id']} and address_id={$address_id}");


		//get updated address
		$sql="select * from ecs_user_address where user_id={$_SESSION['user_id']} and address_id={$address_id}";	
		$address=$db->getRow($sql);
		
		//get cityname from anther table;
		$city=$db->getOne("select region_name from ship_region where region_id={$address['city']}");
		$address['cityName'] = $city;
		return json_encode(array('msg'=>'ok','code'=>'0','data'=>$address));
	}


   //收货人,城市,地区,地质,和电话
	public static function add_order_address($contact,$country,$city,$address,$tel){
		GLOBAL $db;
		$db->query("INSERT INTO ecs_user_address(address_name, user_id, consignee, country, province, city, district, address, tel, mobile, money_address, route_id, ExchangeState, ExchangeState2)
		VALUES('',{$_SESSION['user_id']},'{$contact}','{$country}','0', '{$city}', '0', '{$address}', '', '{$tel}', NULL, '0', '0', '0')");
		
		$sql="select * from ecs_user_address where user_id={$_SESSION['user_id']} limit 0,1";	
		$address=$db->getRow($sql);
		
		//get cityname from anther table;
		$city=$db->getOne("select region_name from ship_region where region_id={$address['city']}");
		$address['cityName'] = $city;

		return json_encode(array('msg'=>'ok','code'=>'0','data'=>$address));
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
	
	//更新购物车里面的商品数量
	public static function update_cart($number,$id){
        GLOBAL $db;
		GLOBAL $ecs;
		GLOBAL $_LANG;

		$res  = array('err_msg' => '', 'result' => '', 'total' => '','rec' =>0);
        if ($number < 1){
            $number = 0;
        }

	    $sql = "UPDATE " . $ecs->table('cart') . " SET goods_number = '$number' WHERE rec_id = '$id' and session_id='" . SESS_ID . "'";
        $db->query($sql);
		$sql = "select rec_id,goods_price,goods_number from " . $ecs->table('cart') . " WHERE session_id='" . SESS_ID . "'";
		$goods = $db->getAll($sql);
		foreach($goods as $val){
			$total['amount'] += $val['goods_price'] * $val['goods_number'];
			if($val['rec_id']==$id){
			      $res['result'] = price_format($val['goods_price'] * $number,false);
			}
		}		
        $res['total'] = sprintf($_LANG['shopping_money'], price_format($total['amount'],false));
		$res['rec'] = "sub_".$id;
	    
		return json_encode($res);
	}
	
	//从购物车中删除一个商品
	public static function drop_shopcart($id){
		$result = array('err' => 0, 'message' => '删除成功');
		
		/* 取得商品id */
		$sql = "SELECT * FROM " .$GLOBALS['ecs']->table('cart'). " WHERE rec_id = '$id'";
		$row = $GLOBALS['db']->getRow($sql);
		if ($row){
			//如果是超值礼包
			if ($row['extension_code'] == 'package_buy')
			{
				$sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') .
						" WHERE session_id = '" . SESS_ID . "' " .
						"AND rec_id = '$id' LIMIT 1";
			}

			//如果是普通商品，同时删除所有赠品及其配件
			elseif ($row['parent_id'] == 0 && $row['is_gift'] == 0)
			{
				/* 检查购物车中该普通商品的不可单独销售的配件并删除 */
				$sql = "SELECT c.rec_id
						FROM " . $GLOBALS['ecs']->table('cart') . " AS c, " . $GLOBALS['ecs']->table('group_goods') . " AS gg, " . $GLOBALS['ecs']->table('goods'). " AS g
						WHERE gg.parent_id = '" . $row['goods_id'] . "'
						AND c.goods_id = gg.goods_id
						AND c.parent_id = '" . $row['goods_id'] . "'
						AND c.extension_code <> 'package_buy'
						AND gg.goods_id = g.goods_id
						AND g.is_alone_sale = 0";
				$res = $GLOBALS['db']->query($sql);
				$_del_str = $id . ',';
				while ($id_alone_sale_goods = $GLOBALS['db']->fetchRow($res))
				{
					$_del_str .= $id_alone_sale_goods['rec_id'] . ',';
				}
				$_del_str = trim($_del_str, ',');

				$sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') .
						" WHERE session_id = '" . SESS_ID . "' " .
						"AND (rec_id IN ($_del_str) OR parent_id = '$row[goods_id]' OR is_gift <> 0)";
			}

			//如果不是普通商品，只删除该商品即可
			else{
				$sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') .
						" WHERE session_id = '" . SESS_ID . "' " .
						"AND rec_id = '$id' LIMIT 1";
			}

			$GLOBALS['db']->query($sql);
		}

		MES_Order::flow_clear_cart_alone();
		return json_encode($result);
	}

	private static function flow_clear_cart_alone(){
		/* 查询：购物车中所有不可以单独销售的配件 */
		$sql = "SELECT c.rec_id, gg.parent_id
				FROM " . $GLOBALS['ecs']->table('cart') . " AS c
					LEFT JOIN " . $GLOBALS['ecs']->table('group_goods') . " AS gg ON c.goods_id = gg.goods_id
					LEFT JOIN" . $GLOBALS['ecs']->table('goods') . " AS g ON c.goods_id = g.goods_id
				WHERE c.session_id = '" . SESS_ID . "'
				AND c.extension_code <> 'package_buy'
				AND gg.parent_id > 0
				AND g.is_alone_sale = 0";
		$res = $GLOBALS['db']->query($sql);
		$rec_id = array();
		while ($row = $GLOBALS['db']->fetchRow($res)){
			$rec_id[$row['rec_id']][] = $row['parent_id'];
		}

		if (empty($rec_id)){
			return;
		}

		/* 查询：购物车中所有商品 */
		$sql = "SELECT DISTINCT goods_id
				FROM " . $GLOBALS['ecs']->table('cart') . "
				WHERE session_id = '" . SESS_ID . "'
				AND extension_code <> 'package_buy'";
		$res = $GLOBALS['db']->query($sql);
		$cart_good = array();
		while ($row = $GLOBALS['db']->fetchRow($res)){
			$cart_good[] = $row['goods_id'];
		}

		if (empty($cart_good)){
			return;
		}

		/* 如果购物车中不可以单独销售配件的基本件不存在则删除该配件 */
		$del_rec_id = '';
		foreach ($rec_id as $key => $value)
		{
			foreach ($value as $v)
			{
				if (in_array($v, $cart_good))
				{
					continue 2;
				}
			}

			$del_rec_id = $key . ',';
		}
		$del_rec_id = trim($del_rec_id, ',');

		if ($del_rec_id == ''){
			return;
		}

		/* 删除 */
		$sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') ."
				WHERE session_id = '" . SESS_ID . "'
				AND rec_id IN ($del_rec_id)";
		$GLOBALS['db']->query($sql);
	}	
}

?>