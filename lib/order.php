<?php

require_once('lib/user.php');
require_once('lib/fee.php');

$GOODS_FREE_FORK = array(
	''=>'5',
	'1.0磅'=>'5',
	'2.0磅'=>'10',
	'3.0磅'=>'15',
	'5.0磅'=>'20',
	'10.0磅'=>'40',
	'15.0磅'=>'50',
	'20.0磅'=>'80',
	'25.0磅'=>'100',
	'30.0磅'=>'120',
);


class MES_Order{
	
	//获得街道的地址
	private static function _get_distruct_name($city,$district){
		$hash = MES_Fee::get_fee_region();
		if($hash[$city]&&$city){
			if($hash[$city][$district]){
				return  $hash[$city][$district]['name'];
			}
		}
		return '';
	}


	//获得地区列表
	public static function get_region(){
		GLOBAL $db;
		$district_list=$db->getAll('select * from ship_region where parent_id=501');
		return json_encode($district_list);
	}

	//仅仅是获得区域的id
	public static function get_district($city){
		$district = MES_Fee::get_fee_region();
		$district = $district[$city];
		return json_encode(array('code'=>RES_SUCCSEE,'data'=>$district));
	}

	

	//获得一个用户所有的地址信息
	public static function get_order_address(){
		GLOBAL $db;
		if(MES_User::server_check_login()){
			$user_id = GET_REDIS($_COOKIE['uuid'],'user_id');
			$sql="select * from ecs_user_address where user_id={$user_id}";	
			$address=$db->getAll($sql);
			
			for($i=0;$i<count($address);$i++){
				$region_id = $address[$i]['city'];
				$district_id = $address[$i]['district'];
				$city_name = $db->getOne("select region_name from ship_region where region_id={$address[$i]['city']}");
				$address[$i]['cityName'] = $city_name;
				$address[$i]['districtName'] = MES_Order::_get_distruct_name($region_id,$district_id);		
			}
		}else{
			$address = array();
		}
		return json_encode($address);
	}
	
	//删除送货地址
	public static function del_order_address($address_id){
		GLOBAL $db;
		$user_id = GET_REDIS($_COOKIE['uuid'],'user_id');
		$sql="delete from ecs_user_address where address_id={$address_id} and user_id={$user_id}";
		$address=$db->query($sql);
		return json_encode(array(
			'code'=>RES_SUCCSEE,
			'msg'=>'ok'
		));
	}

	//更新送货的地址
	public static function update_order_address($address_id,$country,$city,$contact,$address,$tel,$district=0){
		GLOBAL $db;
		$user_id = GET_REDIS($_COOKIE['uuid'],'user_id');
		//根据用户id来更新地址
		$db->query("update ecs_user_address set country={$country},city={$city},district={$district},consignee='{$contact}',address='{$address}',mobile='{$tel}'
			where user_id={$user_id} and address_id={$address_id}");


		//get updated address
		$sql="select * from ecs_user_address where user_id={$user_id} and address_id={$address_id}";	
		$address=$db->getRow($sql);
		
		//get cityname from anther table;
		$city_name=$db->getOne("select region_name from ship_region where region_id={$address['city']}");
		$address['cityName'] = $city_name;
		$address['districtName'] = MES_Order::_get_distruct_name($city,$district);
		return json_encode(array(
			'msg'=>'ok',
			'code'=>RES_SUCCSEE,
			'data'=>$address
		));
	}


   //收货人,城市,地区,地质,和电话和街道，增加地址
	public static function add_order_address($contact,$country,$city,$address,$tel,$district=0){
		GLOBAL $db;
		$user_id = GET_REDIS($_COOKIE['uuid'],'user_id');
		$db->query("INSERT INTO ecs_user_address(address_name, user_id, consignee, country, province, city, district, address, tel, mobile, money_address, route_id, ExchangeState, ExchangeState2)
		VALUES('',{$user_id},'{$contact}','{$country}','0', '{$city}', '{$district}', '{$address}', '', '{$tel}', NULL, '0', '0', '0')");
		
		$sql="select * from ecs_user_address where user_id={$user_id} and address='{$address}' limit 0,1";	
		$address=$db->getRow($sql);
		
		//get cityname from anther table;
		$city_name=$db->getOne("select region_name from ship_region where region_id={$address['city']}");
		$address['cityName'] = $city_name;
		$address['districtName'] = MES_Order::_get_distruct_name($city,$district);
		return json_encode(array('msg'=>'ok','code'=>RES_SUCCSEE,'data'=>$address));
	}
	
	//获得订单列表
	public static function get_order_list($service_side=false){
		//require(ROOT_PATH . 'includes/lib_order.php');
		//require(ROOT_PATH . 'includes/lib_transaction.php');
		GLOBAL $GOODS_FREE_FORK;
		GLOBAL $db;

	    $goods_list = array();
	    $total = array(
	        'goods_price'  => 0, // 本店售价合计（有格式）
	        'market_price' => 0, // 市场售价合计（有格式）
	        'saving'       => 0, // 节省金额（有格式）
	        'save_rate'    => 0, // 节省百分比
	        'goods_amount' => 0, // 本店售价合计（无格式）
	    );

	    /* 循环、统计 */
	    $sql = "SELECT *, IF(parent_id, parent_id, goods_id) AS pid " .
	            " FROM " . $GLOBALS['ecs']->table('cart') . " " .
	            " WHERE session_id = '" . SESS_ID . "' AND rec_type = '" . CART_GENERAL_GOODS . "'" .
	            " ORDER BY pid, parent_id";
	    $res = $GLOBALS['db']->query($sql);

	    /* 用于统计购物车中实体商品和虚拟商品的个数 */
	    $virtual_goods_count = 0;
	    $real_goods_count    = 0;

	    while ($row = $GLOBALS['db']->fetchRow($res))
	    {
	        $total['goods_price']  += $row['goods_price'] * $row['goods_number'];
	        $total['market_price'] += $row['market_price'] * $row['goods_number'];

	        $row['subtotal']     = price_format($row['goods_price'] * $row['goods_number'], false);
	        $row['goods_price']  = $row['goods_price'];
	        $row['market_price'] = price_format($row['market_price'], false);
			$row['url'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);
			$goods_attr_id = $row['goods_attr_id'];
			
			if($goods_attr_id){
				$sql_get_attr_type = "select attr_id from ecs_goods_attr where goods_attr_id={$goods_attr_id}";
				$row['attr_id'] = $db->getOne($sql_get_attr_type);
			}
	        /* 统计实体商品和虚拟商品的个数 */
	        if ($row['is_real']){
	            $real_goods_count++;
	        }else{
	            $virtual_goods_count++;
	        }


	        //增加是否在购物车里显示商品图 
	        //$goods_thumb = $GLOBALS['db']->getOne("SELECT `goods_thumb` FROM " . $GLOBALS['ecs']->table('goods') . " WHERE `goods_id`='{$row['goods_id']}'");
	        //$row['goods_thumb'] = get_image_path($row['goods_id'], $goods_thumb, true);
	        
	        if ($row['extension_code'] == 'package_buy'){
	            $row['package_goods_list'] = get_package_goods($row['goods_id']);
	        }


	        $row['free_fork'] = $GOODS_FREE_FORK[$row['goods_attr']]*$row['goods_number'];
			if($_SESSION['extra_fork'][$row['rec_id']]){
				$row['extra_fork'] =  $_SESSION['extra_fork'][$row['rec_id']];
			}else{
				$row['extra_fork'] = 0;
			}
	        $goods_list[] = $row;
	    }

	    $total['goods_amount'] = $total['goods_price'];
	    $total['saving']       = price_format($total['market_price'] - $total['goods_price'], false);
	    if ($total['market_price'] > 0)
	    {
	        $total['save_rate'] = $total['market_price'] ? round(($total['market_price'] - $total['goods_price']) *
	        100 / $total['market_price']).'%' : 0;
	    }
	    $total['goods_price']  = price_format($total['goods_price'], false);
	    $total['market_price'] = price_format($total['market_price'], false);
	    $total['real_goods_count']    = $real_goods_count;
	    $total['virtual_goods_count'] = $virtual_goods_count;
	    $cart_goods = array(
			'goods_list' => $goods_list, 
			'total' => $total,
			'order_total'=>MES_Order::get_total_price_in_cart()
		);

		if($service_side){
			return $cart_goods;
		}else{
			return json_encode($cart_goods);
		}
		
	}
	
	//根据地址id判断一个地址是否需要加送运费
	public static function if_address_need_fee($address_id){
		GLOBAL $db;
		$user_id = GET_REDIS($_COOKIE['uuid'],'user_id');
		$sql="select * from ecs_user_address where address_id={$address_id} and user_id={$user_id}";	
		$address=$db->getAll($sql);
		$address = $address[0];
		if($address){
			$region_id = $address['city'];
			$district_id = $address['district'];
			$need_fee = MES_Fee::cal_fee($region_id,$district_id);
	
			if($need_fee){
				$_SESSION['need_shipping_fee'] = '10.00';
			}else{
				$_SESSION['need_shipping_fee'] = '0.00';
			}
			return json_encode(array(
				'code'=>RES_SUCCSEE,
				'fee'=>$_SESSION['need_shipping_fee'],
				'order_total'=>MES_Order::get_total_price_in_cart()
			));
		}

		return json_encode(array(
			'code'=>RES_FAIL,
			'fee'=>'1',
			
		));
		
	}
	
	//更新购物车里面的商品数量
	public static function update_cart($number,$id){
        GLOBAL $db;
		GLOBAL $ecs;
		GLOBAL $_LANG;
		GLOBAL $GOODS_FREE_FORK;

		$total = 0;
		$res  = array('err_msg' => '', 'result' => '', 'total' => '','rec' =>0);
        if ($number < 1){
            $number = 0;
        }

		//先更新商品
	    $sql = "UPDATE " . $ecs->table('cart') . " SET goods_number = '$number' WHERE rec_id = '$id' and session_id='" . SESS_ID . "'";
        $db->query($sql);

		//重新选择所有的商品
		$sql = "select rec_id,goods_price,goods_number,goods_attr,goods_id from " . $ecs->table('cart') . " WHERE session_id='" . SESS_ID . "'";
		$goods = $db->getAll($sql);
		foreach($goods as $val){
			$total += $val['goods_price'] * $val['goods_number'];
			
			//计算额外餐具的价格
			if($_SESSION['extra_fork'][$val['rec_id']]){
				$total += $_SESSION['extra_fork'][$val['rec_id']]/2;
			}

			if($val['rec_id']==$id){
			      $res['result'] = price_format($val['goods_price'] * $number,false);

			      //cal free fork number;
			      $res['free_fork'] =  $number* $GOODS_FREE_FORK[$val['goods_attr']];
				  $goods_id=$val['goods_id'];
				  $rec_id=$val['rec_id'];
				  //获得额外的餐具 
				  if( $_SESSION['extra_fork'][$rec_id]){
					$res['extra_fork'] =  $_SESSION['extra_fork'][$rec_id];
				  }else{
					$res['extra_fork'] = 0;	
				  }
				 
			}
		}		
		//var_dump($_LANG['shopping_money']);
        $res['total'] = price_format($total,false);
		$res['rec'] = "sub_".$id;
	    $res['order_total'] = MES_Order::get_total_price_in_cart();
		return json_encode($res);
	}
	

	public static function drop_shopcart_server($id){
		GLOBAL $db;
		GLOBAL $ecs;
		GLOBAL $_LANG;
		$sql = "SELECT * FROM " .$GLOBALS['ecs']->table('cart'). " WHERE rec_id = '$id'";
		$row = $GLOBALS['db']->getRow($sql);
		unset($_SESSION['extra_fork'][$row['rec_id']]);
		if ($row){
			//如果是超值礼包
			if ($row['extension_code'] == 'package_buy')
			{
				$sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') .
						" WHERE session_id = '" . SESS_ID . "' " .
						"AND rec_id = '$id' LIMIT 1";
			}

			//如果是普通商品，同时删除所有赠品及其配件
			elseif ($row['parent_id'] == 0 && $row['is_gift'] == 0){
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
						"AND (rec_id IN ($_del_str) OR parent_id = '$id' OR is_gift <> 0)";
			}

			//如果不是普通商品，只删除该商品即可
			else{
				$sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') .
						" WHERE session_id = '" . SESS_ID . "' " .
						"AND rec_id = '$id' LIMIT 1";
			}

			$GLOBALS['db']->query($sql);
		}
	}

	private static function update_fork_in_shopcart(){
		GLOBAL $db;
		GLOBAL $ecs;
		GLOBAL $_LANG;
			$total_extra = 0;
			if($_SESSION['extra_fork']!=NULL){
				$forks = $_SESSION['extra_fork'];
				foreach ($forks as $value){
					$total_extra+=$value;
				}
			}else{
				$_SESSION['extra_fork'] = array();
			}

			//额外的餐具加到购物车里面
			if($total_extra&&$total_extra>0){
				include_once('includes/lib_order.php');
				addto_cart(60,$total_extra);
			}else{
				//如果没有额外餐具就删了
				$sql = "select rec_id from " . $ecs->table('cart') . " WHERE goods_id=60 and session_id='" . SESS_ID . "'";
				$rec_id = $db->getOne($sql);
				if($rec_id){
					MES_Order::drop_shopcart_server($rec_id);
				}
			}
			return $total_extra;
		
	}

	//从购物车中删除一个商品
	public static function drop_shopcart($id){
		GLOBAL $db;
		GLOBAL $ecs;
		GLOBAL $_LANG;
		
		$result = array('err' => 0, 'message' => '删除成功');
		
		MES_Order::drop_shopcart_server($id);

		//重新结算帐单的总额
		/*$sql = "select rec_id,goods_price,goods_number,goods_attr,goods_id from " . $ecs->table('cart') . " WHERE session_id='" . SESS_ID . "'";
		$goods = $db->getAll($sql);
		$total = 0;
		foreach($goods as $val){
			$total += $val['goods_price'] * $val['goods_number'];
			$goods_id = $val['goods_id'];
			//计算额外餐具的价格
			if($_SESSION['extra_fork'][$goods_id]){
				$total += $_SESSION['extra_fork'][$goods_id]/2;
			}

			if($val['rec_id']==$id){
			      $res['result'] = price_format($val['goods_price'] * $number,false);

			      //cal free fork number;
			      $res['free_fork'] =  $number* intval($val['goods_attr'],10)*$free_fork_pre_cake;
				  unset($_SESSION['extra_fork'][$goods_id]);
				  //获得额外的餐具 

				  if( $_SESSION['extra_fork'][$goods_id]){
					$res['extra_fork'] =  $_SESSION['extra_fork'][$goods_id];
				  }else{
					$res['extra_fork'] = 0;	
				  }
			
				 
			}
		}
		*/
		$total_extra = MES_Order::update_fork_in_shopcart();
			
        $result['total'] = price_format($total,false);
		$result['order_total'] = MES_Order::get_total_price_in_cart();
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

	//your fork can be changed by this
	public static function update_fork($id,$num){
		GLOBAL $db;
		GLOBAL $ecs;
		GLOBAL $_LANG;
		GLOBAL $GOODS_FREE_FORK;
		include_once('includes/lib_order.php');
		$price_fork_pre_cake = 0.5;
		$free_fork_num = 0;
		$total = 0;
		
		//计算额外餐具数量
		$sql = "select rec_id,goods_price,goods_number,goods_id,goods_attr from " . $ecs->table('cart') . " WHERE session_id='" . SESS_ID . "'";
		$goods = $db->getAll($sql);
		foreach($goods as $val){
			if($val['rec_id']==$id){
				$goods_id = $val['goods_id'];
			    $free_fork_num =  $val['goods_number']* $GOODS_FREE_FORK[$val['goods_attr']];
			}
		}		
		
		if($free_fork_num>$num){
			return json_encode(array(
				'code'=>RES_FAIL
			));
		}else{
			//have extra fork

			//cal number of extra fork
			//var_dump($id);
			$extra = $_SESSION['extra_fork'][$id] = $num-$free_fork_num;
			if($extra<1){
			   $extra = 0;
			}

			$total_extra = MES_Order::update_fork_in_shopcart();
			
			$price = $extra/2;
			$total_price = $total_extra/2;

			//重新计算价格
			$sql = "select rec_id,goods_price,goods_number,goods_attr from " . $ecs->table('cart') . " WHERE session_id='" . SESS_ID . "'";
			$goods = $db->getAll($sql);
			foreach($goods as $val){
				$total += $val['goods_price'] * $val['goods_number'];
			}	
			//$total += $total_price;
			return json_encode(array(
				'code'=>RES_SUCCSEE,
				'num'=>$num,
				'price'=>$price,
				'total'=>$total,
				'extra'=>$extra,
				'total_extra'=>$total_extra,
				'order_total'=>MES_Order::get_total_price_in_cart()
			));
		}
		
	}

	//收货人信息保存到session
	public static function save_consignee($consignee){
		
		include_once('includes/check_order.php');
		include_once('includes/lib_order.php');
		$user_id = GET_REDIS($_COOKIE['uuid'],'user_id');
        if ($user_id){
            include_once(ROOT_PATH . 'includes/lib_transaction.php');
            /* 如果用户已经登录，则保存收货人信息 */
            $consignee['user_id'] = $user_id;
        }

        //收货人信息保存到session
        $_SESSION['flow_consignee'] = stripslashes_deep($consignee);
		
		$result = array(
			'code' => RES_SUCCSEE, 
			'message' => '', 
			'content' => ''
		);
		$cart_goods = cart_goods(0);
		
		//有二级城市 就必须要选择 否则返回错误
		$fee_city_hash = MES_Fee::get_fee_region();
		$city = $consignee['city'];
		if($fee_city_hash[$city]&&!$consignee['district']){
			$result['code']=RES_FAIL;
			return json_encode($result);
		}
		//在服务器检查订单是否合法
		//$meg = check_order($consignee, $cart_goods); 
		//if($meg) {
		//	$result['code']=2;
		//	$result['message']= $meg;
		//	return json_encode($result);
		//}

		$result['data'] = $consignee;
		return json_encode($result);	
	}

	public static function checkout($card_message){
		include_once('includes/lib_order.php');
		GLOBAL $ecs;
		GLOBAL $db;
		$res = array();
    	//取得购物类型
    	$user_id = GET_REDIS($_COOKIE['uuid'],'user_id');
    	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;


	    //正常购物流程  清空其他购物流程情况
	    $_SESSION['flow_order']['extension_code'] = '';


	    //检查购物车中是否有商品
	    $sql = "SELECT COUNT(*) FROM " . $ecs->table('cart') .
	        " WHERE session_id = '" . SESS_ID . "' " .
	        "AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";

	    if ($db->getOne($sql) == 0){
	    	return json_encode(array('code' =>RES_FAIL ,'msg'=> $_LANG['no_goods_in_cart']));
	    }

	    /*
	     * 检查用户是否已经登录
	     * 如果用户已经登录了则检查是否有默认的收货地址
	     * 如果没有登录则跳转到登录和注册页面
	     */
		
	    if (empty($_SESSION['direct_shopping']) && $user_id){
	        /* 用户没有登录且没有选定匿名购物，转向到登录页面 */
	        //ecs_header("Location: flow.php?step=login\n");
	        //exit;
	    }

		$country   = "北京市 ";
		
		//没有地址信息
		$id= $_SESSION['flow_consignee']['city'];
		if(!$id){
			return json_ecode(array('code'=>'10010','msg'=>'address id lose!'));
		}

		$card_message = explode('|',$card_message);
		for($i=0;$i<count($card_message);$i++){
			$card_message[$i]=$card_message[$i]=='' ? '无' : $card_message[$i];
		}

		$numbe1=count($card_message);
		$cardname=array();

		foreach($card_message as $key=>$v){
			if($v==''){
				$cardname[]='无';
			}else{
				$cardname[]='其它';
			}
		}
		

		$cardname=implode(";",$cardname);
		//$card_message=implode(";",$card_message);
		
		//生日卡 最后一步 执行done的时候会存入订单的数据库
		$_SESSION['card_message']=$card_message;
		//var_dump($_SESSION['card_message']);
		$_SESSION['card_name']=$cardname;
		
	    $city = $db->getOne("SELECT region_name FROM ".$ecs->table('region')." WHERE region_id={$id}");
		$_SESSION['flow_consignee']['addressname']=$country.$city.$_SESSION['flow_consignee']['address'];
	    

	    /* 对商品信息赋值 */
	    $cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计
	    
	    $order = flow_order_info();
		$order['best_time']=$_SESSION['flow_consignee']['best_time'];
	


	    //计算折扣
	    if ($flow_type != CART_EXCHANGE_GOODS && $flow_type != CART_GROUP_BUY_GOODS){
	        $discount = compute_discount();
	        //$smarty->assign('discount', $discount['discount']);
	        $favour_name = empty($discount['name']) ? '' : join(',', $discount['name']);
	        //$smarty->assign('your_discount', sprintf($_LANG['your_discount'], $favour_name, price_format($discount['discount'])));
	    }

	    //计算订单的费用
	    $total = order_fee($order, $cart_goods, $consignee);
		
	    //取得配送列表
	    $region            = array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district']);
	    $insure_disabled   = true;
	    $cod_disabled      = true;

	    $user_info = user_info($user_id);
		$order['orderman']=$user_info['user_name'];
		$order['mobile']=$user_info['mobile_phone'];
		$order['email']=$user_info['email'];
		$my_info = array();

	    if ( $user_id&& $user_info['user_money'] >= 0){
	        // 能使用余额
	        //$smarty->assign('allow_use_surplus', 1);
	        //$smarty->assign('your_surplus', $user_info['user_money']);
	        $my_info['user_money'] = $user_info['user_money'];
	    }

	    /* 如果使用积分，取得用户可用积分及本订单最多可以使用的积分 */
	    if ((!isset($_CFG['use_integral']) || $_CFG['use_integral'] == '1')
	        && $user_id
	        && $user_info['pay_points'] > 0
	        && ($flow_type != CART_GROUP_BUY_GOODS && $flow_type != CART_EXCHANGE_GOODS))
	    {
	        
	        //$smarty->assign('order_max_integral', flow_available_points());  // 可用积分
	        //$smarty->assign('your_integral',      $user_info['pay_points']); // 用户积分
	        $my_info['order_max_integral'] = flow_available_points();
	        $my_info['your_integral'] = $user_info['pay_points'];
	    }

	    $_SESSION['flow_order'] = $order;
	    return json_encode(array(
	    	'code'=>RES_SUCCSEE,
	    	'order' => $order,
	    	'total'=> $total,
	    	'goods'=>$cart_goods,
	    	'my_info'=>$my_info
	    ));
	}

	//完成一个订单
	public static function done($token,$pay_id){
		
	
	}

	//给出运送地点是否要运费
	public static function shipping_fee_cal($city,$district){
	   $need = MES_Fee::cal_fee($city,$district);
	   if($need){
				$_SESSION['need_shipping_fee'] = '10.00';
			}else{
				$_SESSION['need_shipping_fee'] = '0.00';
			}
	   if($need){
			return json_encode(array('code'=>RES_SUCCSEE,'fee'=>'10.00','order_total'=>MES_Order::get_total_price_in_cart()));
	   }
	   return json_encode(array('code'=>RES_SUCCSEE,'fee'=>'0','order_total'=>MES_Order::get_total_price_in_cart()));
	}

	//增加到购物车
	public static function add_to_cart($goods,$goods_id,$parent_id,$goods_attr=0,$is_cut=0){
		GLOBAL $db;
		GLOBAL $ecs;
		include_once('includes/cls_json.php');
		include_once('includes/lib_order.php');
		
	    $result = array('error' => 0, 'message' => '', 'content' => '', 'goods_id' => '');

	    if (empty($goods)){
	        $result['error'] = 1;
	        return json_encode($result);
	    }
	    $json  = new JSON;
	   	$goods = $json->decode($goods);


	    //检查：如果商品有规格，而post的数据没有规格，把商品的规格属性通过JSON传到前台
	    if (empty($goods->spec) AND empty($goods->quick)){
	        $sql = "SELECT a.attr_id, a.attr_name, a.attr_type, ".
	            "g.goods_attr_id, g.attr_value, g.attr_price " .
	        'FROM ' . $GLOBALS['ecs']->table('goods_attr') . ' AS g ' .
	        'LEFT JOIN ' . $GLOBALS['ecs']->table('attribute') . ' AS a ON a.attr_id = g.attr_id ' .
	        "WHERE a.attr_type != 0 AND g.goods_id = '" . $goods->goods_id . "' " .
	        'ORDER BY a.sort_order, g.attr_price, g.goods_attr_id';

	        $res = $db->getAll($sql);

	        if (!empty($res)){
	            $spe_arr = array();
	            foreach ($res AS $row){
	                $spe_arr[$row['attr_id']]['attr_type'] = $row['attr_type'];
	                $spe_arr[$row['attr_id']]['name']     = $row['attr_name'];
	                $spe_arr[$row['attr_id']]['attr_id']     = $row['attr_id'];
	                $spe_arr[$row['attr_id']]['values'][] = array(
	                                                            'label'        => $row['attr_value'],
	                                                            'price'        => $row['attr_price'],
	                                                            'format_price' => price_format($row['attr_price'], false),
	                                                            'id'           => $row['goods_attr_id']);
	            }
	            $i = 0;
	            $spe_array = array();
	            foreach ($spe_arr AS $row){
	                $spe_array[]=$row;
	            }
	            $result['error']   = ERR_NEED_SELECT_ATTR;
	            $result['goods_id'] = $goods->goods_id;
	            $result['parent'] = $parent_id;
	            $result['message'] = $spe_array;
	
	            return json_encode($result);
	        }
	    }

	    /* 更新：如果是一步购物，先清空购物车 */
	    if ($_CFG['one_step_buy'] == '1'){
	        clear_cart();
	    }

	    /* 检查：商品数量是否合法 */
	    if (!is_numeric($goods->number) || intval($goods->number) <= 0){
	        $result['error']   = 1;
	        $result['message'] = $_LANG['invalid_number'];
	    }else{
	        // 更新：添加到购物车
	        if (addto_cart($goods->goods_id, $goods->number, $goods->spec, $goods->parent,$parent_id,$goods_attr,$is_cut)){
	            if ($_CFG['cart_confirm'] > 2){
	                $result['message'] = '';
	            }else{
	                $result['message'] = $_CFG['cart_confirm'] == 1 ? $_LANG['addto_cart_success_1'] : $_LANG['addto_cart_success_2'];
	            }
				$result['goods_id'] = stripslashes($goods->goods_id);
	            $result['content'] = insert_right_cart_info();
	            $result['one_step_buy'] = $_CFG['one_step_buy'];
	        }else{
	            $result['message']  = $err->last_message();
	            $result['error'] = $err->error_no;
	            $result['goods_id'] = stripslashes($goods->goods_id);
	            if (is_array($goods->spec)){
	                $result['product_spec'] = implode(',', $goods->spec);
	            }else{
	                $result['product_spec'] = $goods->spec;
	            }
	        }
	    }
		$sql = "select * from " . $ecs->table('cart') . " WHERE  session_id='" . SESS_ID . "'";
		$goods = $db->getAll($sql);


		foreach($goods as $val){
			
			$total += $val['goods_price'] * $val['goods_number'];
			
			//计算额外餐具的价格
			if($_SESSION['extra_fork'][$val['rec_id']]){
				$total += $_SESSION['extra_fork'][$val['rec_id']]/2;
			}
			//蜡烛这玩意 需要在order页面返回给前端添加到订单里，其他商品不需要这么做
			//67数字蜡烛
			//61普通蜡烛
			if($val['goods_id']==CANDLE_ID&&$goods_id == CANDLE_ID&&$val['parent_id']==$parent_id){
				  $result['data'] = $val;	
			}

			//数字蛋糕 判定为唯一 需要attr
			if($val['goods_attr'] ==$goods_attr&&$val['goods_id']==NUM_CANDLE_ID&&$goods_id == NUM_CANDLE_ID&&$val['parent_id']==$parent_id){	
				  $result['data'] = $val;
			}
		}	
	    $result['confirm_type'] = !empty($_CFG['cart_confirm']) ? $_CFG['cart_confirm'] : 2;
		$result['order_total'] = MES_Order::get_total_price_in_cart();
	    return json_encode($result);
	}
			
	//获得额外的餐具数量
	private static function get_extra_fork_num(){
		$forks = $_SESSION['extra_fork'];
		foreach ($forks as $value){
			$total+=$value;
		}
		return $total;
	}
	
	//获得购物车里面的总价
	public static function get_total_price_in_cart(){
		GLOBAL $db;
		GLOBAL $ecs;
		include_once('includes/lib_order.php');
    	//取得购物类型
    	
    	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

    	//团购标志
    	if ($flow_type == CART_GROUP_BUY_GOODS){
	        //$smarty->assign('is_group_buy', 1);
	    }
	    //积分兑换商品 
	    elseif ($flow_type == CART_EXCHANGE_GOODS)
	    {
	        //$smarty->assign('is_exchange_goods', 1);
	    }else{
	        //正常购物流程  清空其他购物流程情况
	        $_SESSION['flow_order']['extension_code'] = '';
	    }

	    //检查购物车中是否有商品
		//parent_id 为0的商品才是单独销售的产品
	    $sql = "SELECT COUNT(*) FROM " . $ecs->table('cart') .
	        " WHERE session_id = '" . SESS_ID . "' " .
	        "AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";

	    if ($db->getOne($sql) == 0){
	    	return false;
	    }

	    

	    /* 对商品信息赋值 */
	    $cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计
	    
	    $order = flow_order_info();
		$order['best_time']=$_SESSION['flow_consignee']['best_time'];
	
	    //计算折扣
	    if ($flow_type != CART_EXCHANGE_GOODS && $flow_type != CART_GROUP_BUY_GOODS){
	        $discount = compute_discount();
	        $favour_name = empty($discount['name']) ? '' : join(',', $discount['name']);
	    }

	    //计算订单的费用
	    $total = order_fee($order, $cart_goods, $consignee);
		return $total;
	}

 
}

?>