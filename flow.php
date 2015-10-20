<?php

/**
 * ECSHOP 购物流程
 * ============================================================================
 * 版权所有 2005-2010 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: douqinghua $
 * $Id: flow.php 17218 2011-01-24 04:10:41Z douqinghua $
 */

define('IN_ECS', true);

require (dirname(__FILE__) . '/includes/init.php');
require (ROOT_PATH . 'includes/lib_order.php');

/* 载入语言文件 */
require_once (ROOT_PATH . 'languages/' . $_CFG['lang'] . '/user.php');
require_once (ROOT_PATH . 'languages/' . $_CFG['lang'] . '/shopping_flow.php');

/*------------------------------------------------------ */
//-- INPUT
/*------------------------------------------------------ */

if (!isset($_REQUEST['step'])) {
	$_REQUEST['step'] = "cart";
}

/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */

assign_template();
assign_dynamic('flow');
$position = assign_ur_here(0, $_LANG['shopping_flow']);
$smarty -> assign('page_title', $position['title']);
// 页面标题
$smarty -> assign('ur_here', $position['ur_here']);
// 当前位置

$smarty -> assign('categories', get_categories_tree());
// 分类树
$smarty -> assign('helps', get_shop_help());
// 网店帮助
$smarty -> assign('lang', $_LANG);
$smarty -> assign('show_marketprice', $_CFG['show_marketprice']);
$smarty -> assign('data_dir', DATA_DIR);
// 数据目录

/*------------------------------------------------------ */
//-- 添加商品到购物车
/*------------------------------------------------------ */

if ($_REQUEST['step'] == 'add_to_cart') {
	include_once ('includes/cls_json.php');
	$_POST['goods'] = strip_tags(urldecode($_POST['goods']));
	$_POST['goods'] = json_str_iconv($_POST['goods']);

	if (!empty($_REQUEST['goods_id']) && empty($_POST['goods'])) {
		if (!is_numeric($_REQUEST['goods_id']) || intval($_REQUEST['goods_id']) <= 0) {
			ecs_header("Location:./\n");
		}
		$goods_id = intval($_REQUEST['goods_id']);
		exit ;
	}

	$result = array('error' => 0, 'message' => '', 'content' => '', 'goods_id' => '');
	$json = new JSON;

	if (empty($_POST['goods'])) {
		$result['error'] = 1;
		die($json -> encode($result));
	}

	$goods = $json -> decode($_POST['goods']);

	/* 检查：如果商品有规格，而post的数据没有规格，把商品的规格属性通过JSON传到前台 */
	if (empty($goods -> spec) AND empty($goods -> quick)) {
		$sql = "SELECT a.attr_id, a.attr_name, a.attr_type, " . "g.goods_attr_id, g.attr_value, g.attr_price " . 'FROM ' . $GLOBALS['ecs'] -> table('goods_attr') . ' AS g ' . 'LEFT JOIN ' . $GLOBALS['ecs'] -> table('attribute') . ' AS a ON a.attr_id = g.attr_id ' . "WHERE a.attr_type != 0 AND g.goods_id = '" . $goods -> goods_id . "' " . 'ORDER BY a.sort_order, g.attr_price, g.goods_attr_id';

		$res = $GLOBALS['db'] -> getAll($sql);

		if (!empty($res)) {
			$spe_arr = array();
			foreach ($res AS $row) {
				$spe_arr[$row['attr_id']]['attr_type'] = $row['attr_type'];
				$spe_arr[$row['attr_id']]['name'] = $row['attr_name'];
				$spe_arr[$row['attr_id']]['attr_id'] = $row['attr_id'];
				$spe_arr[$row['attr_id']]['values'][] = array('label' => $row['attr_value'], 'price' => $row['attr_price'], 'format_price' => price_format($row['attr_price'], false), 'id' => $row['goods_attr_id']);
			}
			$i = 0;
			$spe_array = array();
			foreach ($spe_arr AS $row) {
				$spe_array[] = $row;
			}
			$result['error'] = ERR_NEED_SELECT_ATTR;
			$result['goods_id'] = $goods -> goods_id;
			$result['parent'] = $goods -> parent;
			$result['message'] = $spe_array;

			die($json -> encode($result));
		}
	}

	/* 更新：如果是一步购物，先清空购物车 */
	if ($_CFG['one_step_buy'] == '1') {
		clear_cart();
	}

	/* 检查：商品数量是否合法 */
	if (!is_numeric($goods -> number) || intval($goods -> number) <= 0) {
		$result['error'] = 1;
		$result['message'] = $_LANG['invalid_number'];
	}
	/* 更新：购物车 */
	else {
		// 更新：添加到购物车
		if (addto_cart($goods -> goods_id, $goods -> number, $goods -> spec, $goods -> parent)) {
			if ($_CFG['cart_confirm'] > 2) {
				$result['message'] = '';
			} else {
				$result['message'] = $_CFG['cart_confirm'] == 1 ? $_LANG['addto_cart_success_1'] : $_LANG['addto_cart_success_2'];
			}
			$result['goods_id'] = stripslashes($goods -> goods_id);
			$result['content'] = insert_right_cart_info();
			$result['one_step_buy'] = $_CFG['one_step_buy'];
		} else {
			$result['message'] = $err -> last_message();
			$result['error'] = $err -> error_no;
			$result['goods_id'] = stripslashes($goods -> goods_id);
			if (is_array($goods -> spec)) {
				$result['product_spec'] = implode(',', $goods -> spec);
			} else {
				$result['product_spec'] = $goods -> spec;
			}
		}
	}

	$result['confirm_type'] = !empty($_CFG['cart_confirm']) ? $_CFG['cart_confirm'] : 2;
	die($json -> encode($result));
} elseif ($_REQUEST['step'] == 'link_buy') {
	$goods_id = intval($_GET['goods_id']);

	if (!cart_goods_exists($goods_id, array())) {
		addto_cart($goods_id);
	}
	ecs_header("Location:./flow.php\n");
	exit ;
} elseif ($_REQUEST['step'] == 'login') {
	include_once ('languages/' . $_CFG['lang'] . '/user.php');

	/*
	 * 用户登录注册
	 */
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		$smarty -> assign('anonymous_buy', $_CFG['anonymous_buy']);

		/* 检查是否有赠品，如果有提示登录后重新选择赠品 */
		$sql = "SELECT COUNT(*) FROM " . $ecs -> table('cart') . " WHERE session_id = '" . SESS_ID . "' AND is_gift > 0";
		if ($db -> getOne($sql) > 0) {
			$smarty -> assign('need_rechoose_gift', 1);
		}

		/* 检查是否需要注册码 */
		$captcha = intval($_CFG['captcha']);
		if (($captcha & CAPTCHA_LOGIN) && (!($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && $_SESSION['login_fail'] > 2)) && gd_version() > 0) {
			$smarty -> assign('enabled_login_captcha', 1);
			$smarty -> assign('rand', mt_rand());
		}
		if ($captcha & CAPTCHA_REGISTER) {
			$smarty -> assign('enabled_register_captcha', 1);
			$smarty -> assign('rand', mt_rand());
		}
	} else {
		include_once ('includes/lib_passport.php');
		if (!empty($_POST['act']) && $_POST['act'] == 'signin') {
			$captcha = intval($_CFG['captcha']);
			if (($captcha & CAPTCHA_LOGIN) && (!($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && $_SESSION['login_fail'] > 2)) && gd_version() > 0) {
				if (empty($_POST['captcha'])) {
					show_message($_LANG['invalid_captcha']);
				}

				/* 检查验证码 */
				include_once ('includes/cls_captcha.php');

				$validator = new captcha();
				$validator -> session_word = 'captcha_login';
				if (!$validator -> check_word($_POST['captcha'])) {
					show_message($_LANG['invalid_captcha']);
				}
			}

			if ($user -> login($_POST['username'], $_POST['password'], isset($_POST['remember']))) {
				update_user_info();
				//更新用户信息
				recalculate_price();
				// 重新计算购物车中的商品价格

				/* 检查购物车中是否有商品 没有商品则跳转到首页 */
				$sql = "SELECT COUNT(*) FROM " . $ecs -> table('cart') . " WHERE session_id = '" . SESS_ID . "' ";
				if ($db -> getOne($sql) > 0) {
					ecs_header("Location: flow.php?step=checkout\n");
				} else {
					ecs_header("Location:index.php\n");
				}

				exit ;
			} else {
				$_SESSION['login_fail']++;
				show_message($_LANG['signin_failed'], '', 'flow.php?step=login');
			}
		} elseif (!empty($_POST['act']) && $_POST['act'] == 'signup') {
			if ((intval($_CFG['captcha']) & CAPTCHA_REGISTER) && gd_version() > 0) {
				if (empty($_POST['captcha'])) {
					show_message($_LANG['invalid_captcha']);
				}

				/* 检查验证码 */
				include_once ('includes/cls_captcha.php');

				$validator = new captcha();
				if (!$validator -> check_word($_POST['captcha'])) {
					show_message($_LANG['invalid_captcha']);
				}
			}

			if (register(trim($_POST['username']), trim($_POST['password']), trim($_POST['email']))) {
				/* 用户注册成功 */
				ecs_header("Location: flow.php?step=consignee\n");
				exit ;
			} else {
				$err -> show();
			}
		} else {
			// TODO: 非法访问的处理
		}
	}
} elseif ($_REQUEST['step'] == 'consignee') {
	/*------------------------------------------------------ */
	//-- 收货人信息
	/*------------------------------------------------------ */
	include_once ('includes/lib_transaction.php');

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		//        /* 取得购物类型 */
		//        $flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;
		//
		//        /*
		//         * 收货人信息填写界面
		//         */
		//
		//        if (isset($_REQUEST['direct_shopping']))
		//        {
		//            $_SESSION['direct_shopping'] = 1;
		//        }
		//
		//        /* 取得国家列表、商店所在国家、商店所在国家的省列表 */
		//        $smarty->assign('country_list',       get_regions());
		//        $smarty->assign('shop_country',       $_CFG['shop_country']);
		//        $smarty->assign('shop_province_list', get_regions(1, $_CFG['shop_country']));
		//
		//        /* 获得用户所有的收货人信息 */
		//        if ($_SESSION['user_id'] > 0)
		//        {
		//            $consignee_list = get_consignee_list($_SESSION['user_id']);
		//
		//            if (count($consignee_list) < 5)
		//            {
		//                /* 如果用户收货人信息的总数小于 5 则增加一个新的收货人信息 */
		//                $consignee_list[] = array('country' => $_CFG['shop_country'], 'email' => isset($_SESSION['email']) ? $_SESSION['email'] : '');
		//            }
		//        }
		//        else
		//        {
		//            if (isset($_SESSION['flow_consignee'])){
		//                $consignee_list = array($_SESSION['flow_consignee']);
		//            }
		//            else
		//            {
		//                $consignee_list[] = array('country' => $_CFG['shop_country']);
		//            }
		//        }
		//        $smarty->assign('name_of_region',   array($_CFG['name_of_region_1'], $_CFG['name_of_region_2'], $_CFG['name_of_region_3'], $_CFG['name_of_region_4']));
		//        $smarty->assign('consignee_list', $consignee_list);
		//
		//        /* 取得每个收货地址的省市区列表 */
		//        $province_list = array();
		//        $city_list = array();
		//        $district_list = array();
		//        foreach ($consignee_list as $region_id => $consignee)
		//        {
		//            $consignee['country']  = isset($consignee['country'])  ? intval($consignee['country'])  : 0;
		//            $consignee['province'] = isset($consignee['province']) ? intval($consignee['province']) : 0;
		//            $consignee['city']     = isset($consignee['city'])     ? intval($consignee['city'])     : 0;
		//
		//            $province_list[$region_id] = get_regions(1, $consignee['country']);
		//            $city_list[$region_id]     = get_regions(2, $consignee['province']);
		//            $district_list[$region_id] = get_regions(3, $consignee['city']);
		//        }
		//        $smarty->assign('province_list', $province_list);
		//        $smarty->assign('city_list',     $city_list);
		//        $smarty->assign('district_list', $district_list);
		//
		//        /* 返回收货人页面代码 */
		//        $smarty->assign('real_goods_count', exist_real_goods(0, $flow_type) ? 1 : 0);
	} else {
		/*
		 * 保存收货人信息
		 */
		$consignee = array('address_id' => empty($_POST['address_id']) ? 0 : intval($_POST['address_id']), 'consignee' => empty($_POST['consignee']) ? '' : compile_str(trim($_POST['consignee'])), 'country' => empty($_POST['country']) ? '' : intval($_POST['country']), 'province' => empty($_POST['province']) ? 502 : intval($_POST['province']), 'city' => empty($_POST['city']) ? '' : intval($_POST['city']), 'district' => empty($_POST['district']) ? '' : intval($_POST['district']), 'email' => empty($_POST['email']) ? '' : compile_str($_POST['email']), 'address' => empty($_POST['address']) ? '' : compile_str($_POST['address']), 'zipcode' => empty($_POST['zipcode']) ? '' : compile_str(make_semiangle(trim($_POST['zipcode']))), 'tel' => empty($_POST['tel']) ? '' : compile_str(make_semiangle(trim($_POST['tel']))), 'mobile' => empty($_POST['mobile']) ? '' : compile_str(make_semiangle(trim($_POST['mobile']))), 'sign_building' => empty($_POST['sign_building']) ? '' : compile_str($_POST['sign_building']), 'best_time' => $_POST['bdate'] . " " . $_POST['hour'] . ":" . $_POST['minute'] . ":00", );
		//echo	$k = strpos("{$consignee['address']}","区");
		//$consignee['address']= mb_substr($consignee['address'],$k,'uft-8');

		if ($_SESSION['user_id'] > 0) {
			include_once (ROOT_PATH . 'includes/lib_transaction.php');

			/* 如果用户已经登录，则保存收货人信息 */
			$consignee['user_id'] = $_SESSION['user_id'];

			//save_consignee($consignee, true);
		}

		/* 保存到session */
		$_SESSION['flow_consignee'] = stripslashes_deep($consignee);
		//print_r($consignee);exit;
		if (isset($_POST['is_ajax']) && $_POST['is_ajax'] == 1) {
			include_once ('includes/cls_json.php');
			include_once ('includes/check_order.php');
			$result = array('error' => 0, 'message' => '', 'content' => '');
			//$nowtime=strtotime($consignee['best_time'])+8*3600;
			//$time8=time()+16*3600;
			$cart_goods = cart_goods(0);
			$meg = check_order($consignee, $cart_goods);
			if ($meg) {
				$result['error'] = 1;
				$result['message'] = $meg;
			}
			$json = new JSON;
			echo $json -> encode($result);
		} else {
			//ecs_header("Location: flow.php?step=checkout\n");
		}

		exit ;
	}
} elseif ($_REQUEST['step'] == 'drop_consignee') {
	/*------------------------------------------------------ */
	//-- 删除收货人信息
	/*------------------------------------------------------ */
	include_once ('includes/lib_transaction.php');

	$consignee_id = intval($_GET['id']);

	if (drop_consignee($consignee_id)) {
		ecs_header("Location: flow.php?step=consignee\n");
		exit ;
	} else {
		show_message($_LANG['not_fount_consignee']);
	}
} elseif ($_REQUEST['step'] == 'checkout') {
	/*------------------------------------------------------ */
	//-- 订单确认
	/*------------------------------------------------------ */
    die;
	/* 取得购物类型 */
	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

	/* 团购标志 */
	if ($flow_type == CART_GROUP_BUY_GOODS) {
		$smarty -> assign('is_group_buy', 1);
	}
	/* 积分兑换商品 */
	elseif ($flow_type == CART_EXCHANGE_GOODS) {
		$smarty -> assign('is_exchange_goods', 1);
	} else {
		//正常购物流程  清空其他购物流程情况
		$_SESSION['flow_order']['extension_code'] = '';
	}

	/* 检查购物车中是否有商品 */
	$sql = "SELECT COUNT(*) FROM " . $ecs -> table('cart') . " WHERE session_id = '" . SESS_ID . "' " . "AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";

	if ($db -> getOne($sql) == 0) {
		show_message($_LANG['no_goods_in_cart'], '', '', 'warning');
	}

	/*
	 * 检查用户是否已经登录
	 * 如果用户已经登录了则检查是否有默认的收货地址
	 * 如果没有登录则跳转到登录和注册页面
	 */
	if (empty($_SESSION['direct_shopping']) && $_SESSION['user_id'] == 0) {
		/* 用户没有登录且没有选定匿名购物，转向到登录页面 */
		ecs_header("Location: flow.php?step=login\n");
		exit ;
	}

	//$consignee = get_consignee($_SESSION['user_id']);

	/* 检查收货人信息是否完整 */
	//if (!check_consignee_info($consignee, $flow_type))
	//{
	/* 如果不完整则转向到收货人信息填写界面 */
	//    ecs_header("Location: flow.php?step=cart\n");
	//    exit;
	//}

	//	$consignee=array(
	//				consignee'=>$_POST['consignee'],
	//				contry'=>$_POST['contry'],
	//				province'=>'502',
	//				city'=>$_POST['city'],
	//				address'=>$_POST['address'],
	//				tel'=>$_POST['tel'],
	//	);
	//	$_SESSION['flow_consignee'] = $consignee;
	//print_r($_SESSION['flow_consignee']);
	$country = "北京市 ";
	$id = $_SESSION['flow_consignee']['city'];
	if(!$id){
		return json_ecode(array('code'=>'10010','msg'=>'address id lose!'));
	}
	$card_message = ($_POST['card_message']);

	foreach ($card_message as $key => $v) {
		$card_message[$key] = $v == '文字内容(八个字内)' ? null : $v;
	}
	$numbe1 = count($card_message);

	$cardname = array();

	foreach ($card_message as $key => $v) {
		if ($v == '') {$cardname[] = '无';
		} else {$cardname[] = '其它';
		}
	}

	$cardname = implode(";", $cardname);
	$card_message = implode(";", $card_message);
	//print_r($card_message);
	$_SESSION['card_message'] = $card_message;
	$_SESSION['card_name'] = $cardname;

	$city = $db -> getOne("SELECT region_name FROM " . $ecs -> table('region') . " WHERE region_id={$id}");
	$_SESSION['flow_consignee']['addressname'] = $country . $city . $_SESSION['flow_consignee']['address'];

	$smarty -> assign('consignee', $_SESSION['flow_consignee']);
	/* 对商品信息赋值 */
	$cart_goods = cart_goods($flow_type);
	// 取得商品列表，计算合计
	$smarty -> assign('goods_list', $cart_goods);

	/* 对是否允许修改购物车赋值 */
	if ($flow_type != CART_GENERAL_GOODS || $_CFG['one_step_buy'] == '1') {
		$smarty -> assign('allow_edit_cart', 0);
	} else {
		$smarty -> assign('allow_edit_cart', 1);
	}

	/*
	 * 取得购物流程设置
	 */
	$smarty -> assign('config', $_CFG);
	/*
	 * 取得订单信息
	 */
	$order = flow_order_info();
	$order['best_time'] = $_SESSION['flow_consignee']['best_time'];

	/* 计算折扣 */
	if ($flow_type != CART_EXCHANGE_GOODS && $flow_type != CART_GROUP_BUY_GOODS) {
		$discount = compute_discount();
		$smarty -> assign('discount', $discount['discount']);
		$favour_name = empty($discount['name']) ? '' : join(',', $discount['name']);
		$smarty -> assign('your_discount', sprintf($_LANG['your_discount'], $favour_name, price_format($discount['discount'])));
	}

	/*
	 * 计算订单的费用
	 */
	$total = order_fee($order, $cart_goods, $consignee);

	$smarty -> assign('total', $total);
	$smarty -> assign('shopping_money', sprintf($_LANG['shopping_money'], $total['goods_price_formated']));
	$smarty -> assign('market_price_desc', sprintf($_LANG['than_market_price'], $total['formated_market_price'], $total['formated_saving'], $total['save_rate']));

	/* 取得配送列表 */
	$region = array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district']);
	//$shipping_list     = available_shipping_list($region);
	//$cart_weight_price = cart_weight_price($flow_type);
	$insure_disabled = true;
	$cod_disabled = true;

	// 查看购物车中是否全为免运费商品，若是则把运费赋为零
	/*$sql = 'SELECT count(*) FROM ' . $ecs->table('cart') . " WHERE `session_id` = '" . SESS_ID. "' AND `extension_code` != 'package_buy' AND `is_shipping` = 0";
	 $shipping_count = $db->getOne($sql);

	 foreach ($shipping_list AS $key => $val)
	 {
	 $shipping_cfg = unserialize_config($val['configure']);
	 $shipping_fee = ($shipping_count == 0 AND $cart_weight_price['free_shipping'] == 1) ? 0 : shipping_fee($val['shipping_code'], unserialize($val['configure']),
	 $cart_weight_price['weight'], $cart_weight_price['amount'], $cart_weight_price['number']);

	 $shipping_list[$key]['format_shipping_fee'] = price_format($shipping_fee, false);
	 $shipping_list[$key]['shipping_fee']        = $shipping_fee;
	 $shipping_list[$key]['free_money']          = price_format($shipping_cfg['free_money'], false);
	 $shipping_list[$key]['insure_formated']     = strpos($val['insure'], '%') === false ?
	 price_format($val['insure'], false) : $val['insure'];

	 //当前的配送方式是否支持保价
	 if ($val['shipping_id'] == $order['shipping_id'])
	 {
	 $insure_disabled = ($val['insure'] == 0);
	 $cod_disabled    = ($val['support_cod'] == 0);
	 }
	 }

	 $smarty->assign('shipping_list',   $shipping_list);
	 $smarty->assign('insure_disabled', $insure_disabled);
	 $smarty->assign('cod_disabled',    $cod_disabled);
	 */
	/* 取得支付列表
	 if ($order['shipping_id'] == 0)
	 {
	 $cod        = true;
	 $cod_fee    = 0;
	 }
	 else
	 {
	 $shipping = shipping_info($order['shipping_id']);
	 $cod = $shipping['support_cod'];

	 if ($cod)
	 {
	 // 如果是团购，且保证金大于0，不能使用货到付款
	 if ($flow_type == CART_GROUP_BUY_GOODS)
	 {
	 $group_buy_id = $_SESSION['extension_id'];
	 if ($group_buy_id <= 0)
	 {
	 show_message('error group_buy_id');
	 }
	 $group_buy = group_buy_info($group_buy_id);
	 if (empty($group_buy))
	 {
	 show_message('group buy not exists: ' . $group_buy_id);
	 }

	 if ($group_buy['deposit'] > 0)
	 {
	 $cod = false;
	 $cod_fee = 0;

	 // 赋值保证金
	 $smarty->assign('gb_deposit', $group_buy['deposit']);
	 }
	 }

	 if ($cod)
	 {
	 $shipping_area_info = shipping_area_info($order['shipping_id'], $region);
	 $cod_fee            = $shipping_area_info['pay_fee'];
	 }
	 }
	 else
	 {
	 $cod_fee = 0;
	 }
	 }*/

	// 给货到付款的手续费加<span id>，以便改变配送的时候动态显示
	/*$payment_list = available_payment_list(1, $cod_fee);
	 if(isset($payment_list))
	 {
	 foreach ($payment_list as $key => $payment)
	 {
	 if ($payment['is_cod'] == '1')
	 {
	 $payment_list[$key]['format_pay_fee'] = '<span id="ECS_CODFEE">' . $payment['format_pay_fee'] . '</span>';
	 }
	 // 如果有易宝神州行支付 如果订单金额大于300 则不显示
	 if ($payment['pay_code'] == 'yeepayszx' && $total['amount'] > 300)
	 {
	 unset($payment_list[$key]);
	 }
	 // 如果有余额支付
	 if ($payment['pay_code'] == 'balance')
	 {
	 // 如果未登录，不显示
	 if ($_SESSION['user_id'] == 0)
	 {
	 unset($payment_list[$key]);
	 }
	 else
	 {
	 if ($_SESSION['flow_order']['pay_id'] == $payment['pay_id'])
	 {
	 $smarty->assign('disable_surplus', 1);
	 }
	 }
	 }
	 }
	 }
	 $smarty->assign('payment_list', $payment_list);*/

	/* 取得包装与贺卡
	 if ($total['real_goods_count'] > 0)
	 {
	 // 只有有实体商品,才要判断包装和贺卡
	 if (!isset($_CFG['use_package']) || $_CFG['use_package'] == '1')
	 {
	 // 如果使用包装，取得包装列表及用户选择的包装
	 $smarty->assign('pack_list', pack_list());
	 }

	 // 如果使用贺卡，取得贺卡列表及用户选择的贺卡
	 if (!isset($_CFG['use_card']) || $_CFG['use_card'] == '1')
	 {
	 $smarty->assign('card_list', card_list());
	 }
	 }*/
	$user_info = user_info($_SESSION['user_id']);
	$order['orderman'] = $user_info['user_name'];
	$order['mobile'] = $user_info['mobile_phone'];
	$order['email'] = $user_info['email'];

	$smarty -> assign('order', $order);
	/* 如果使用余额，取得用户余额 */
	if ($_SESSION['user_id'] > 0 && $user_info['user_money'] >= 0) {
		// 能使用余额
		$smarty -> assign('allow_use_surplus', 1);
		$smarty -> assign('your_surplus', $user_info['user_money']);
	}

	/* 如果使用积分，取得用户可用积分及本订单最多可以使用的积分 */
	if ((!isset($_CFG['use_integral']) || $_CFG['use_integral'] == '1') && $_SESSION['user_id'] > 0 && $user_info['pay_points'] > 0 && ($flow_type != CART_GROUP_BUY_GOODS && $flow_type != CART_EXCHANGE_GOODS)) {
		// 能使用积分
		$smarty -> assign('allow_use_integral', 1);
		$smarty -> assign('order_max_integral', flow_available_points());
		// 可用积分
		$smarty -> assign('your_integral', $user_info['pay_points']);
		// 用户积分
	}

	/* 如果使用红包，取得用户可以使用的红包及用户选择的红包
	 if ((!isset($_CFG['use_bonus']) || $_CFG['use_bonus'] == '1')
	 && ($flow_type != CART_GROUP_BUY_GOODS && $flow_type != CART_EXCHANGE_GOODS))
	 {
	 // 取得用户可用红包
	 $user_bonus = user_bonus($_SESSION['user_id'], $total['goods_price']);
	 if (!empty($user_bonus))
	 {
	 foreach ($user_bonus AS $key => $val)
	 {
	 $user_bonus[$key]['bonus_money_formated'] = price_format($val['type_money'], false);
	 }
	 $smarty->assign('bonus_list', $user_bonus);
	 }

	 // 能使用红包
	 $smarty->assign('allow_use_bonus', 1);
	 }*/

	/* 如果使用缺货处理，取得缺货处理列表
	 if (!isset($_CFG['use_how_oos']) || $_CFG['use_how_oos'] == '1')
	 {
	 if (is_array($GLOBALS['_LANG']['oos']) && !empty($GLOBALS['_LANG']['oos']))
	 {
	 $smarty->assign('how_oos_list', $GLOBALS['_LANG']['oos']);
	 }
	 }*/

	/* 如果能开发票，取得发票内容列表
	 if ((!isset($_CFG['can_invoice']) || $_CFG['can_invoice'] == '1')
	 && isset($_CFG['invoice_content'])
	 && trim($_CFG['invoice_content']) != '' && $flow_type != CART_EXCHANGE_GOODS)
	 {
	 $inv_content_list = explode("\n", str_replace("\r", '', $_CFG['invoice_content']));
	 $smarty->assign('inv_content_list', $inv_content_list);

	 $inv_type_list = array();
	 foreach ($_CFG['invoice_type']['type'] as $key => $type)
	 {
	 if (!empty($type))
	 {
	 $inv_type_list[$type] = $type . ' [' . floatval($_CFG['invoice_type']['rate'][$key]) . '%]';
	 }
	 }
	 $smarty->assign('inv_type_list', $inv_type_list);
	 }*/

	/* 保存 session */
	$_SESSION['flow_order'] = $order;
	//echo "<pre>";print_r($order);
} elseif ($_REQUEST['step'] == 'select_shipping') {
	/*------------------------------------------------------ */
	//-- 改变配送方式
	/*------------------------------------------------------ */
	include_once ('includes/cls_json.php');
	$json = new JSON;
	$result = array('error' => '', 'content' => '', 'need_insure' => 0);

	/* 取得购物类型 */
	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

	/* 获得收货人信息 */
	$consignee = get_consignee($_SESSION['user_id']);

	/* 对商品信息赋值 */
	$cart_goods = cart_goods($flow_type);
	// 取得商品列表，计算合计

	if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type)) {
		$result['error'] = $_LANG['no_goods_in_cart'];
	} else {
		/* 取得购物流程设置 */
		$smarty -> assign('config', $_CFG);

		/* 取得订单信息 */
		$order = flow_order_info();

		$order['shipping_id'] = intval($_REQUEST['shipping']);
		$regions = array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district']);
		$shipping_info = shipping_area_info($order['shipping_id'], $regions);

		/* 计算订单的费用 */
		$total = order_fee($order, $cart_goods, $consignee);
		$smarty -> assign('total', $total);

		/* 取得可以得到的积分和红包 */
		$smarty -> assign('total_integral', cart_amount(false, $flow_type) - $total['bonus'] - $total['integral_money']);
		$smarty -> assign('total_bonus', price_format(get_total_bonus(), false));

		/* 团购标志 */
		if ($flow_type == CART_GROUP_BUY_GOODS) {
			$smarty -> assign('is_group_buy', 1);
		}

		$result['cod_fee'] = $shipping_info['pay_fee'];
		if (strpos($result['cod_fee'], '%') === false) {
			$result['cod_fee'] = price_format($result['cod_fee'], false);
		}
		$result['need_insure'] = ($shipping_info['insure'] > 0 && !empty($order['need_insure'])) ? 1 : 0;
		$result['content'] = $smarty -> fetch('library/order_total.lbi');
	}

	echo $json -> encode($result);
	exit ;
} elseif ($_REQUEST['step'] == 'select_insure') {
	/*------------------------------------------------------ */
	//-- 选定/取消配送的保价
	/*------------------------------------------------------ */

	include_once ('includes/cls_json.php');
	$json = new JSON;
	$result = array('error' => '', 'content' => '', 'need_insure' => 0);

	/* 取得购物类型 */
	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

	/* 获得收货人信息 */
	$consignee = get_consignee($_SESSION['user_id']);

	/* 对商品信息赋值 */
	$cart_goods = cart_goods($flow_type);
	// 取得商品列表，计算合计

	if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type)) {
		$result['error'] = $_LANG['no_goods_in_cart'];
	} else {
		/* 取得购物流程设置 */
		$smarty -> assign('config', $_CFG);

		/* 取得订单信息 */
		$order = flow_order_info();

		$order['need_insure'] = intval($_REQUEST['insure']);

		/* 保存 session */
		$_SESSION['flow_order'] = $order;

		/* 计算订单的费用 */
		$total = order_fee($order, $cart_goods, $consignee);
		$smarty -> assign('total', $total);

		/* 取得可以得到的积分和红包 */
		$smarty -> assign('total_integral', cart_amount(false, $flow_type) - $total['bonus'] - $total['integral_money']);
		$smarty -> assign('total_bonus', price_format(get_total_bonus(), false));

		/* 团购标志 */
		if ($flow_type == CART_GROUP_BUY_GOODS) {
			$smarty -> assign('is_group_buy', 1);
		}

		$result['content'] = $smarty -> fetch('library/order_total.lbi');
	}

	echo $json -> encode($result);
	exit ;
} elseif ($_REQUEST['step'] == 'select_payment') {
	/*------------------------------------------------------ */
	//-- 改变支付方式
	/*------------------------------------------------------ */

	include_once ('includes/cls_json.php');
	$json = new JSON;
	$result = array('error' => '', 'content' => '', 'need_insure' => 0, 'payment' => 1);

	/* 取得购物类型 */
	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

	/* 获得收货人信息 */
	$consignee = get_consignee($_SESSION['user_id']);

	/* 对商品信息赋值 */
	$cart_goods = cart_goods($flow_type);
	// 取得商品列表，计算合计

	if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type)) {
		$result['error'] = $_LANG['no_goods_in_cart'];
	} else {
		/* 取得购物流程设置 */
		$smarty -> assign('config', $_CFG);

		/* 取得订单信息 */
		$order = flow_order_info();

		$order['pay_id'] = intval($_REQUEST['payment']);
		$payment_info = payment_info($order['pay_id']);
		$result['pay_code'] = $payment_info['pay_code'];

		/* 保存 session */
		$_SESSION['flow_order'] = $order;

		/* 计算订单的费用 */
		$total = order_fee($order, $cart_goods, $consignee);
		$smarty -> assign('total', $total);

		/* 取得可以得到的积分和红包 */
		$smarty -> assign('total_integral', cart_amount(false, $flow_type) - $total['bonus'] - $total['integral_money']);
		$smarty -> assign('total_bonus', price_format(get_total_bonus(), false));

		/* 团购标志 */
		if ($flow_type == CART_GROUP_BUY_GOODS) {
			$smarty -> assign('is_group_buy', 1);
		}

		$result['content'] = $smarty -> fetch('library/order_total.lbi');
	}

	echo $json -> encode($result);
	exit ;
} elseif ($_REQUEST['step'] == 'select_pack') {
	/*------------------------------------------------------ */
	//-- 改变商品包装
	/*------------------------------------------------------ */

	include_once ('includes/cls_json.php');
	$json = new JSON;
	$result = array('error' => '', 'content' => '', 'need_insure' => 0);

	/* 取得购物类型 */
	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

	/* 获得收货人信息 */
	$consignee = get_consignee($_SESSION['user_id']);

	/* 对商品信息赋值 */
	$cart_goods = cart_goods($flow_type);
	// 取得商品列表，计算合计

	if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type)) {
		$result['error'] = $_LANG['no_goods_in_cart'];
	} else {
		/* 取得购物流程设置 */
		$smarty -> assign('config', $_CFG);

		/* 取得订单信息 */
		$order = flow_order_info();

		$order['pack_id'] = intval($_REQUEST['pack']);

		/* 保存 session */
		$_SESSION['flow_order'] = $order;

		/* 计算订单的费用 */
		$total = order_fee($order, $cart_goods, $consignee);
		$smarty -> assign('total', $total);

		/* 取得可以得到的积分和红包 */
		$smarty -> assign('total_integral', cart_amount(false, $flow_type) - $total['bonus'] - $total['integral_money']);
		$smarty -> assign('total_bonus', price_format(get_total_bonus(), false));

		/* 团购标志 */
		if ($flow_type == CART_GROUP_BUY_GOODS) {
			$smarty -> assign('is_group_buy', 1);
		}

		$result['content'] = $smarty -> fetch('library/order_total.lbi');
	}

	echo $json -> encode($result);
	exit ;
} elseif ($_REQUEST['step'] == 'select_card') {
	/*------------------------------------------------------ */
	//-- 改变贺卡
	/*------------------------------------------------------ */

	include_once ('includes/cls_json.php');
	$json = new JSON;
	$result = array('error' => '', 'content' => '', 'need_insure' => 0);

	/* 取得购物类型 */
	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

	/* 获得收货人信息 */
	$consignee = get_consignee($_SESSION['user_id']);

	/* 对商品信息赋值 */
	$cart_goods = cart_goods($flow_type);
	// 取得商品列表，计算合计

	if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type)) {
		$result['error'] = $_LANG['no_goods_in_cart'];
	} else {
		/* 取得购物流程设置 */
		$smarty -> assign('config', $_CFG);

		/* 取得订单信息 */
		$order = flow_order_info();

		$order['card_id'] = intval($_REQUEST['card']);

		/* 保存 session */
		$_SESSION['flow_order'] = $order;

		/* 计算订单的费用 */
		$total = order_fee($order, $cart_goods, $consignee);
		$smarty -> assign('total', $total);

		/* 取得可以得到的积分和红包 */
		$smarty -> assign('total_integral', cart_amount(false, $flow_type) - $order['bonus'] - $total['integral_money']);
		$smarty -> assign('total_bonus', price_format(get_total_bonus(), false));

		/* 团购标志 */
		if ($flow_type == CART_GROUP_BUY_GOODS) {
			$smarty -> assign('is_group_buy', 1);
		}

		$result['content'] = $smarty -> fetch('library/order_total.lbi');
	}

	echo $json -> encode($result);
	exit ;
} elseif ($_REQUEST['step'] == 'change_surplus') {
	/*------------------------------------------------------ */
	//-- 改变余额
	/*------------------------------------------------------ */
	include_once ('includes/cls_json.php');

	$surplus = floatval($_GET['surplus']);
	$user_info = user_info($_SESSION['user_id']);

	if ($user_info['user_money'] + $user_info['credit_line'] < $surplus) {
		$result['error'] = $_LANG['surplus_not_enough'];
	} else {
		/* 取得购物类型 */
		$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

		/* 取得购物流程设置 */
		$smarty -> assign('config', $_CFG);

		/* 获得收货人信息 */
		$consignee = get_consignee($_SESSION['user_id']);

		/* 对商品信息赋值 */
		$cart_goods = cart_goods($flow_type);
		// 取得商品列表，计算合计

		if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type)) {
			$result['error'] = $_LANG['no_goods_in_cart'];
		} else {
			/* 取得订单信息 */
			$order = flow_order_info();
			$order['surplus'] = $surplus;

			/* 计算订单的费用 */
			$total = order_fee($order, $cart_goods, $consignee);
			$smarty -> assign('total', $total);

			/* 团购标志 */
			if ($flow_type == CART_GROUP_BUY_GOODS) {
				$smarty -> assign('is_group_buy', 1);
			}

			$result['content'] = $smarty -> fetch('library/order_total.lbi');
		}
	}

	$json = new JSON();
	die($json -> encode($result));
} elseif ($_REQUEST['step'] == 'change_integral') {
	/*------------------------------------------------------ */
	//-- 改变积分
	/*------------------------------------------------------ */
	include_once ('includes/cls_json.php');

	$points = floatval($_GET['points']);
	$user_info = user_info($_SESSION['user_id']);

	/* 取得订单信息 */
	$order = flow_order_info();

	$flow_points = flow_available_points();
	// 该订单允许使用的积分
	$user_points = $user_info['pay_points'];
	// 用户的积分总数

	if ($points > $user_points) {
		$result['error'] = $_LANG['integral_not_enough'];
	} elseif ($points > $flow_points) {
		$result['error'] = sprintf($_LANG['integral_too_much'], $flow_points);
	} else {
		/* 取得购物类型 */
		$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

		$order['integral'] = $points;

		/* 获得收货人信息 */
		$consignee = get_consignee($_SESSION['user_id']);

		/* 对商品信息赋值 */
		$cart_goods = cart_goods($flow_type);
		// 取得商品列表，计算合计

		if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type)) {
			$result['error'] = $_LANG['no_goods_in_cart'];
		} else {
			/* 计算订单的费用 */
			$total = order_fee($order, $cart_goods, $consignee);
			$smarty -> assign('total', $total);
			$smarty -> assign('config', $_CFG);

			/* 团购标志 */
			if ($flow_type == CART_GROUP_BUY_GOODS) {
				$smarty -> assign('is_group_buy', 1);
			}

			$result['content'] = $smarty -> fetch('library/order_total.lbi');
			$result['error'] = '';
		}
	}

	$json = new JSON();
	die($json -> encode($result));
} elseif ($_REQUEST['step'] == 'change_bonus') {
	/*------------------------------------------------------ */
	//-- 改变红包
	/*------------------------------------------------------ */
	include_once ('includes/cls_json.php');
	$result = array('error' => '', 'content' => '');

	/* 取得购物类型 */
	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

	/* 获得收货人信息 */
	$consignee = get_consignee($_SESSION['user_id']);

	/* 对商品信息赋值 */
	$cart_goods = cart_goods($flow_type);
	// 取得商品列表，计算合计

	if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type)) {
		$result['error'] = $_LANG['no_goods_in_cart'];
	} else {
		/* 取得购物流程设置 */
		$smarty -> assign('config', $_CFG);

		/* 取得订单信息 */
		$order = flow_order_info();

		$bonus = bonus_info(intval($_GET['bonus']));

		if ((!empty($bonus) && $bonus['user_id'] == $_SESSION['user_id']) || $_GET['bonus'] == 0) {
			$order['bonus_id'] = intval($_GET['bonus']);
		} else {
			$order['bonus_id'] = 0;
			$result['error'] = $_LANG['invalid_bonus'];
		}

		/* 计算订单的费用 */
		$total = order_fee($order, $cart_goods, $consignee);
		$smarty -> assign('total', $total);

		/* 团购标志 */
		if ($flow_type == CART_GROUP_BUY_GOODS) {
			$smarty -> assign('is_group_buy', 1);
		}

		$result['content'] = $smarty -> fetch('library/order_total.lbi');

	}

	$json = new JSON();
	die($json -> encode($result));
} elseif ($_REQUEST['step'] == 'change_needinv') {
	/*------------------------------------------------------ */
	//-- 改变发票的设置
	/*------------------------------------------------------ */
	include_once ('includes/cls_json.php');
	$result = array('error' => '', 'content' => '');
	$json = new JSON();
	$_GET['inv_type'] = !empty($_GET['inv_type']) ? json_str_iconv(urldecode($_GET['inv_type'])) : '';
	$_GET['invPayee'] = !empty($_GET['invPayee']) ? json_str_iconv(urldecode($_GET['invPayee'])) : '';
	$_GET['inv_content'] = !empty($_GET['inv_content']) ? json_str_iconv(urldecode($_GET['inv_content'])) : '';

	/* 取得购物类型 */
	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

	/* 获得收货人信息 */
	$consignee = get_consignee($_SESSION['user_id']);

	/* 对商品信息赋值 */
	$cart_goods = cart_goods($flow_type);
	// 取得商品列表，计算合计

	if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type)) {
		$result['error'] = $_LANG['no_goods_in_cart'];
		die($json -> encode($result));
	} else {
		/* 取得购物流程设置 */
		$smarty -> assign('config', $_CFG);

		/* 取得订单信息 */
		$order = flow_order_info();

		if (isset($_GET['need_inv']) && intval($_GET['need_inv']) == 1) {
			$order['need_inv'] = 1;
			$order['inv_type'] = trim(stripslashes($_GET['inv_type']));
			$order['inv_payee'] = trim(stripslashes($_GET['inv_payee']));
			$order['inv_content'] = trim(stripslashes($_GET['inv_content']));
		} else {
			$order['need_inv'] = 0;
			$order['inv_type'] = '';
			$order['inv_payee'] = '';
			$order['inv_content'] = '';
		}

		/* 计算订单的费用 */
		$total = order_fee($order, $cart_goods, $consignee);
		$smarty -> assign('total', $total);

		/* 团购标志 */
		if ($flow_type == CART_GROUP_BUY_GOODS) {
			$smarty -> assign('is_group_buy', 1);
		}

		die($smarty -> fetch('library/order_total.lbi'));
	}
} elseif ($_REQUEST['step'] == 'change_oos') {
	/*------------------------------------------------------ */
	//-- 改变缺货处理时的方式
	/*------------------------------------------------------ */

	/* 取得订单信息 */
	$order = flow_order_info();

	$order['how_oos'] = intval($_GET['oos']);

	/* 保存 session */
	$_SESSION['flow_order'] = $order;
} elseif ($_REQUEST['step'] == 'check_surplus') {
	/*------------------------------------------------------ */
	//-- 检查用户输入的余额
	/*------------------------------------------------------ */
	$surplus = floatval($_GET['surplus']);
	$user_info = user_info($_SESSION['user_id']);

	if (($user_info['user_money'] + $user_info['credit_line'] < $surplus)) {
		die($_LANG['surplus_not_enough']);
	}

	exit ;
} elseif ($_REQUEST['step'] == 'check_integral') {
	/*------------------------------------------------------ */
	//-- 检查用户输入的余额
	/*------------------------------------------------------ */
	$points = floatval($_GET['integral']);
	$user_info = user_info($_SESSION['user_id']);
	$flow_points = flow_available_points();
	// 该订单允许使用的积分
	$user_points = $user_info['pay_points'];
	// 用户的积分总数

	if ($points > $user_points) {
		die($_LANG['integral_not_enough']);
	}

	if ($points > $flow_points) {
		die(sprintf($_LANG['integral_too_much'], $flow_points));
	}

	exit ;
}
/*------------------------------------------------------ */
//-- 完成所有订单操作，提交到数据库
/*------------------------------------------------------ */
elseif ($_REQUEST['step'] == 'done') {

	include_once ('includes/lib_clips.php');
	include_once ('includes/lib_payment.php');
	$token = $_COOKIE['serviceToken'];
	$uuid = $_COOKIE['uuid'];
	$res = false;
	if($token&&GET_REDIS($uuid,'user')== $token){
	  $res = true;
	}

	//anti csrf
	$order_token = $_POST['token'];

	$user_id = GET_REDIS($_COOKIE['uuid'],'user_id');
	
	if ($order_token !== $_SESSION['order_token']&&$order_token!=='from_mobile') {
		ecs_header("Location: route.php?mod=account&action=order_list");
		exit ;
	}

	/* 取得购物类型 */
	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

	/* 检查购物车中是否有商品 */
	$sql = "SELECT COUNT(*) FROM " . $ecs -> table('cart') . " WHERE session_id = '" . SESS_ID . "' " . "AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";
	if ($db -> getOne($sql) == 0) {
		header("Location: route.php?mod=order&action=empty");
		exit ;
	}
	/*
	 * 检查用户是否已经登录
	 * 如果用户已经登录了则检查是否有默认的收货地址
	 * 如果没有登录则跳转到登录和注册页面
	 */
	if (empty($_SESSION['direct_shopping']) && $user_id == 0) {
		/* 用户没有登录且没有选定匿名购物，转向到登录页面 */
		ecs_header("Location: flow.php?step=login\n");
		exit ;
	}
	define('PAY_ALIPAY',2);
	define('PAY_KUAIQAIN',3);
	define('PAY_POS',4);
	define('PAY_WX',9);
	define('SHIPPING_SELF',9);
	
	//配送方式的选择
	//自提站点支持
	if($_POST['shipping_type'] == 'ZT'){
		require_once('lib/self_station.php');
		$ship_site_id = $_POST['shipping_site'];
		//获得自提站的地址信息
		$station_info = Mes_self_station::get_by_stationid($ship_site_id);
		//var_dump($station_info);
		$_POST['shipping'] = SHIPPING_SELF;
	}else{
		$_POST['shipping'] = 1;
	}
	
	$pay_id = intval($_POST['pay_id']);
	if ($_POST['pay_id'] == 1) {
		$pay_id = PAY_POS;
	}else if ($_POST['pay_id'] == 2) {
		$pay_id = PAY_POS;
	}else if ($_POST['pay_id'] == 3) {
		$pay_id = PAY_ALIPAY;
	}else if ($_POST['pay_id'] == 4) {
		$pay_id = PAY_KUAIQAIN;
	}else if ($_POST['pay_id'] == 9) {
		//新增的微信支付方式
		$pay_id = PAY_WX;
	}else{
		ecs_header("Location: index.htm");
		exit ;
	}
	
	$_POST['inv_type'] = !empty($_POST['inv_type']) ? compile_str($_POST['inv_type']) : '';
	$_POST['inv_payee'] = isset($_POST['inv_payee']) ? compile_str($_POST['inv_payee']) : '';
	$_POST['inv_content'] = isset($_POST['inv_content']) ? compile_str($_POST['inv_content']) : '';
	$_POST['postscript'] = isset($_POST['postscript']) ? compile_str($_POST['postscript']) : '';
	if (!$_SESSION['need_shipping_fee']) {
		$_SESSION['need_shipping_fee'] = '0.00';
	}
	$shipping_fee = $_SESSION['need_shipping_fee'];
	
	//自提不需要配送费用
	if($_POST['shipping_type'] == 'ZT'){
		$shipping_fee = 0;
	}
	$shipping_fee = str_replace("￥", " ", $shipping_fee);
	$shipping_fee = trim($shipping_fee);
	$leaving_message = isset($_POST['leaving_message']) ? $_POST['leaving_message']: "";
	//$leaving_message = isset($_POST['leaving_message']) ? ($_POST['leaving_message'] == "输入内容" ? "" : $_POST['leaving_message']) : "";
	/* 订单中的商品 */
	$leaving_message.=';;;;[制作需求]：';
	$cart_goods = cart_goods($flow_type);
	$cart_goods_count = count($cart_goods);
	$product_message = '';
	for($i=0;$i<$cart_goods_count;$i++){
		$curr_goods = $cart_goods[$i];
		$curr_message = $curr_goods['goods_name'].$curr_goods['goods_attr'];
		$curr_no_sugar = false;
		$_goods_attr_id = $curr_goods['goods_attr_id'];
		if($_goods_attr_id>1000){
			$sql_get_attr_type = "select attr_id from ecs_goods_attr where goods_attr_id={$_goods_attr_id}";
			$cur_attr_id = $GLOBALS['db']->getOne($sql_get_attr_type);
			if($cur_attr_id == ATTR_NO_SUGAR){
				$curr_message.='[无糖制作]';
				$curr_no_sugar = true;
			}
		}

		if($curr_goods['is_cut']==1){
			$curr_message.='[需要切块]';
		}
		if($curr_goods['is_cut']==1||$curr_no_sugar){
			$curr_message.='X'. $curr_goods['goods_number'].';';
			$product_message.=$curr_message;
		}
		
	}

	if($product_message == ''){
		$product_message = '无';
	}

	$leaving_message.=$product_message;
	
	$order = array(
		'shipping_id' => intval($_POST['shipping']), 
		'pay_id' => intval($pay_id), 
		'pack_id' => isset($_POST['pack']) ? intval($_POST['pack']) : 0, 
		'card_id' => isset($_POST['card']) ? intval($_POST['card']) : 0, 
		'card_name' => $_SESSION['card_name'], 
		'card_message' => trim(implode(";", $_SESSION['card_message'])),
		//订单中新增的餐具信息
		'fork_message' => trim(json_encode($_SESSION['extra_fork'])), 
		'surplus' => isset($_POST['surplus']) ? floatval($_POST['surplus']) : 0.00,
		'bonus' => isset($_POST['bonus']) ? floatval($_POST['bonus']) : 0.00,
		'bonus_id' => isset($_POST['bonus_id']) ? floatval($_POST['bonus_id']) : "", 
		'integral' => isset($_POST['integral']) ? intval($_POST['integral']) : 0,
		'bonus_id' => isset($_POST['bonus_id']) ? intval($_POST['bonus_id']) : 0, 
		'need_inv' => empty($_POST['need_inv']) ? 0 : 1, 
		'inv_type' => $_POST['inv_type'],
		'inv_payee' => trim($_POST['inv_payee']), 
		'inv_content' => $_POST['inv_content'], 
		'postscript' => trim($_POST['postscript']), 
		'how_oos' => isset($_LANG['oos'][$_POST['how_oos']]) ? addslashes($_LANG['oos'][$_POST['how_oos']]) : '', 
		'need_insure' => isset($_POST['need_insure']) ? intval($_POST['need_insure']) : 0, 
		'user_id' => $user_id, 
		'add_time' => gmtime(), 
		'order_status' => OS_UNCONFIRMED, 
		'shipping_status' => SS_UNSHIPPED, 
		'pay_status' => PS_UNPAYED, 
		'agency_id' => 1, 
		'scts' => $leaving_message, 
		'wsts' => $leaving_message, 
	);
  

	/* 扩展信息 */
	if (isset($_SESSION['flow_type']) && intval($_SESSION['flow_type']) != CART_GENERAL_GOODS) {
		$order['extension_code'] = $_SESSION['extension_code'];
		$order['extension_id'] = $_SESSION['extension_id'];
	} else {
		$order['extension_code'] = '';
		$order['extension_id'] = 0;
	}

	/* 检查积分余额是否合法 */

	if ($user_id > 0) {
		$user_info = user_info($user_id);

		$order['surplus'] = min($order['surplus'], $user_info['user_money'] + $user_info['credit_line']);
		if ($order['surplus'] < 0) {
			$order['surplus'] = 0;
		}
		$order['orderman'] = !empty($user_info['rea_name']) ? $user_info['rea_name'] : $user_info['user_name'];
		$order['ordertel'] = !empty($user_info['mobile_phone']) ? $user_info['mobile_phone'] : $user_info['home_phone'];
		// 查询用户有多少积分
		$flow_points = flow_available_points();
		// 该订单允许使用的积分
		$user_points = $user_info['pay_points'];
		// 用户的积分总数

		$order['integral'] = min($order['integral'], $user_points, $flow_points);
		if ($order['integral'] < 0) {
			$order['integral'] = 0;
		}
	} else {
		$order['surplus'] = 0;
		$order['integral'] = 0;
	}

	/* 检查红包是否存在*/
	if ($order['bonus_id'] > 0) {
		$bonus = bonus_info($order['bonus_id']);
		$order['to_buyer'] .= '券号:' . $bonus['bonus_sn'] . ';';
		$order['wsts'] .= '收卡';
	}


	

	if (empty($cart_goods)) {
		//var_dump($cart_goods);
		header("Location: route.php?mod=order&action=empty");
		return;
	}

	/* 检查商品总额是否达到最低限购金额 */
	if ($flow_type == CART_GENERAL_GOODS && cart_amount(true, CART_GENERAL_GOODS) < $_CFG['min_goods_amount']) {
		show_message(sprintf($_LANG['goods_amount_not_enough'], price_format($_CFG['min_goods_amount'], false)));
	}

	/* 收货人信息 */
	$consignee = $_SESSION['flow_consignee'];
	
	
	
	//保证不被注入 必须再检测一下
	foreach ($consignee as $key => $value) {
		$order[$key] = addslashes($value);
	}
	//自提的收获地址要修改成自提站的
	if($_POST['shipping_type'] == 'ZT'){
		$ZT_address = '[用户自提] 自提站地址：'.$station_info['station_city_name'].' '.$station_info['station_district_name'].' '.$station_info['station_address'];
		$order['address'] = $ZT_address;
		$order['addressname'] = $ZT_address;
	}
    //var_dump($order);
	/* 订单中的总额 */
	$total = order_fee($order, $cart_goods, $consignee);
	$order['bonus'] = $total['bonus'];
	$order['goods_amount'] = $total['goods_price'];
	$order['discount'] = $total['discount'];
	$order['surplus'] = $total['surplus'];
	$order['tax'] = $total['tax'];
	$order['tax'] = $total['tax'];

	// 购物车中的商品能享受红包支付的总额
	$discount_amout = compute_discount_amount();
	// 红包和积分最多能支付的金额为商品总额
	$temp_amout = $order['goods_amount'] - $discount_amout;
	if ($temp_amout <= 0) {
		$order['bonus_id'] = 0;
	}

	/* 配送方式 */
	if ($order['shipping_id'] > 0) {
		//$shipping = shipping_info($order['shipping_id']);
		$order['shipping_name'] = '配送';
	}
	if($_POST['shipping_type'] == 'ZT'){
		$order['shipping_name'] = '自提';
	}
	

	//不能无脑设置没有运费阿
	$total['shipping_fee'] = $_SESSION['need_shipping_fee'];
	$order['shipping_fee'] = $total['shipping_fee'];
	//$order['insure_fee']   = $total['shipping_insure'];

	/* 支付方式 */
	if ($order['pay_id'] > 0) {
		$payment = payment_info($order['pay_id']);
		$order['pay_name'] = addslashes($payment['pay_name']);
	}
	$order['pay_fee'] = $total['pay_fee'];
	$order['cod_fee'] = $total['cod_fee'];

	//商品包装
	$order['pack_fee'] = $total['pack_fee'];

	$order['order_amount'] = number_format($total['amount'], 2, '.', '');

	/* 如果全部使用余额支付，检查余额是否足够 */
	if ($payment['pay_code'] == 'balance' && $order['order_amount'] > 0) {
		if ($order['surplus'] > 0)//余额支付里如果输入了一个金额
		{
			$order['order_amount'] = $order['order_amount'] + $order['surplus'];
			$order['surplus'] = 0;
		}
		if ($order['order_amount'] > ($user_info['user_money'] + $user_info['credit_line'])) {
			$can_use_money = $user_info['user_money'] + $user_info['credit_line'];
			if ($can_use_money > 0) {
				$_SESSION['flow_order']['surplus'] = $can_use_money;
				$order_amount = $order['order_amount'] - $can_use_money;
				show_message(sprintf($_LANG['balance_not_enough'], $can_use_money, $order_amount), $_LANG['back_home'], 'flow.php?step=checkout');
			} else {
				show_message($_LANG['balance_not_enough_0']);
			}

		} else {
			$order['surplus'] = $order['order_amount'];
			$order['pay_id'] = 4;
			$order['pay_note'] = "现金:0";
			$order['order_amount'] = 0;
		}
	}

	/* 如果订单金额为0（使用余额或积分或红包支付），修改订单状态为已确认、已付款 */
	if ($order['order_amount'] <= 0) {
		$order['order_status'] = OS_CONFIRMED;
		$order['confirm_time'] = gmtime();
		$order['pay_status'] = PS_PAYED;
		$order['pay_time'] = gmtime();
		$order['order_amount'] = 0;
	}

	$order['integral_money'] = $total['integral_money'];
	$order['integral'] = $total['integral'];
	if ($_POST['pay_id'] == 2) {
		$order['pay_note'] = 'POS机:' . $order['order_amount'];
		//$order['to_buyer'] = 'POS机:'.$order['order_amount'];
		$order['wsts'] .= '带POS机;';
	}
	if ($_POST['pay_id'] == 1) {
		$order['pay_note'] = '现金:' . $order['order_amount'];
	}
	if ($order['pay_id'] == 2) {
		$order['pay_note'] = '支付宝';

	} elseif ($order['pay_id'] == 3) {
		$order['pay_note'] = '快钱';
	}elseif ($order['pay_id'] == 9) {
		$order['pay_note'] = '微信';
	}
	if ($order['extension_code'] == 'exchange_goods') {
		$order['integral_money'] = 0;
		$order['integral'] = $total['exchange_integral'];
	}

	$order['from_ad'] = !empty($_SESSION['from_ad']) ? $_SESSION['from_ad'] : '0';
	$order['referer'] = !empty($_SESSION['referer']) ? addslashes($_SESSION['referer']) : '';

	/* 记录扩展信息 */
	if ($flow_type != CART_GENERAL_GOODS) {
		$order['extension_code'] = $_SESSION['extension_code'];
		$order['extension_id'] = $_SESSION['extension_id'];
	}

	$affiliate = unserialize($_CFG['affiliate']);
	if (isset($affiliate['on']) && $affiliate['on'] == 1 && $affiliate['config']['separate_by'] == 1) {
		//推荐订单分成
		$parent_id = get_affiliate();
		if ($user_id == $parent_id) {
			$parent_id = 0;
		}
	} elseif (isset($affiliate['on']) && $affiliate['on'] == 1 && $affiliate['config']['separate_by'] == 0) {
		//推荐注册分成
		$parent_id = 0;
	} else {
		//分成功能关闭
		$parent_id = 0;
	}
	$order['parent_id'] = $parent_id;
	$ttt = substr($order['best_time'], 11, 5);
	if ($consignee['country'] == '441') {
		$cc = 'bj';
		$order['agency_id'] = 3;

		if ($ttt < '14:00') {
			$turn = 1;
		} elseif ($ttt >= '14:00' && $ttt < '16:30') {
			$turn = 2;
		} elseif ($ttt >= '16:30' && $ttt < '19:30') {
			$turn = 3;
		} elseif ($ttt >= '19:30' && $ttt < '22:30') {
			$turn = 4;
		}

		$address = $order['address'];
		$sql = "SELECT route_id FROM ship_area WHERE 1 and  locate(area_name,'{$address}') > 0";
		$routeid = $db -> getOne($sql);
	}
	/* 插入订单表 */
	$db -> query("insert into order_genid (remark) values('9999')");
	$gen_id = $db -> insert_id();
	$order['order_id'] = $new_order_id = $gen_id;	
	$order['order_sn'] = $cc . date("Ymd") . substr($order['order_id'], -5);
	include_once ('includes/tender.php');
	tender($order['order_id'], $order['user_id'], $order['pay_id'], $order['pay_note'], $order['order_amount'], $order['bonus'], $order['bonus_id'], $order['surplus'], $order['integral']);
	$dt = date("Y-m-d", $order['add_time']);
	$bt = substr($order['best_time'], 0, 10);
	$dtime = date("Y-m-d", time());
	$xiangkuangnum = $db -> getOne("select count(*) from ecs_order_info where from_unixtime(add_time,'%Y-%m-%d')=" . $dtime . " and (scts like '%相框%' or wsts like '%相框%') and (order_status=0 or order_status=1)");

	$if_exsit = $db -> getAll('select * from ecs_order_info where order_id="'.$new_order_id.'"');
	$if_exsit = count($if_exsit);
	if($if_exsit>0){
		echo '订单提交失败,请联系客服';
		return;
	}

	$ba = $GLOBALS['db'] -> autoExecute($GLOBALS['ecs'] -> table('order_info'), $order, 'INSERT');

	//只有货到付款才能立刻发短信
	if ($ba) {
		include_once ('includes/sendsms.php');
		$mobile = $db -> getOne("select mobile_phone from ecs_users where user_id = $user_id");
		if($pay_id == PAY_POS){
			sms_send2($mobile, 1);
		}else{
			sms_send_unpay($mobile, 1);
		}

	}
	$dispatch['order_id'] = $order['order_id'];
	$dispatch['route_id'] = empty($routeid) ? 0 : intval($routeid);
	$dispatch['turn'] = empty($turn) ? 0 : $turn;

	$GLOBALS['db'] -> autoExecute("order_dispatch", $dispatch, 'INSERT');
	$order_sn = $order['order_sn'];
	$db -> query("update ecs_order_info set shipping_fee={$shipping_fee} where order_sn='{$order_sn}' ");
	/* 插入订单商品 */
	$sends = 0;
	foreach ($cart_goods as $val) {
		if ($val['goods_price'] > 100) {
			$attr = substr($val['goods_attr'], 0, 3);
			if ($attr == '1.0')
				$num = 5;
			if ($attr == '2.0')
				$num = 10;
			if ($attr == '3.0')
				$num = 15;
			if ($attr == '5.0')
				$num = 20;
			if ($attr == '10.')
				$num = 40;
			if ($attr == '15.')
				$num = 50;
			if ($attr == '20.')
				$num = 80;
			if ($attr == '25.')
				$num = 100;
			if ($attr == '30.')
				$num = 120;
			$sends += $num * $val['goods_number'];
		}
	}
	$sql = "INSERT INTO " . $ecs -> table('order_goods') . "( " . "order_id, goods_id, goods_name, goods_sn, goods_number,  " . "goods_price, goods_attr, goods_discount,is_integral, origin_rec_id,parent_id, goods_attr_id,is_cut) " . " SELECT '$new_order_id', goods_id, goods_name, goods_sn, goods_number,  " . "goods_price, goods_attr, if(is_integral,-1,1) ,is_integral,rec_id,parent_id, goods_attr_id,is_cut" . " FROM " . $ecs -> table('cart') . " WHERE session_id = '" . SESS_ID . "' AND rec_type = '$flow_type'";	

	$db -> query($sql);

	if ($sends > 0) {
		$sql = "INSERT INTO " . $ecs -> table('order_goods') . "( " . "order_id, goods_id, goods_name, goods_sn, goods_number,  " . "goods_price, goods_attr, goods_discount,is_integral) " . " values('$new_order_id', 60, '餐具套装', '00', '$sends','0', '',1,0) ";
		$db -> query($sql);
	}
	/*
	if (isset($_COOKIE['emar'])) {
		include_once 'api/advertiser/Sender.php';
		include_once 'api/advertiser/Order.php';
		include_once 'api/advertiser/OrderStatus.php';
		include_once 'api/advertiser/Product.php';
		include_once 'api/advertiser/Service.php';
		include_once 'api/util/Config.php';
		$value = urldecode($_COOKIE['emar']);
		$arr = explode(":", $value);
		$cps = new Order();
		$cps -> setOrderNo($order['order_sn']);
		$add_time = date("Y-m-d H:i:s", $order['add_time'] + 8 * 3600);
		$cps -> setOrderTime($add_time);
		// 设置下单时间
		$cps -> setUpdateTime($add_time);
		// 设置订单更新时间，如果没有下单时间，要提前对接人提前说明
		$cps -> setCampaignId($arr[2]);
		// 测试时使用"101"，正式上线之后活动id必须要从cookie中获取
		$cps -> setFeedback($arr[3]);
		// 测试时使用"101"，正式上线之后活动id必须要从cookie中获取
		$cps -> setFare($order['shipping_fee']);
		$cps -> setFavorable($order['bonus'] + $order['surplus']);
		// 设置优惠券
		$cps -> setOrderStatus($order['order_status']);
		// 设置订单状态
		$cps -> setPaymentStatus($order['pay_status']);
		// 设置支付状态
		$cps -> setPaymentType($order['pay_name']);
		// 支付方式

		foreach ($cart_goods as $k => $v) {
			if ($v['goods_price'] > 0) {
				$pro = new Product();
				// $pro2 -> setOrderNo($order -> getOrderNo());
				$pro -> setProductNo($v['goods_sn']);
				$pro -> setName($v['goods_name']);
				$pro -> setCategory("蛋糕");
				$pro -> setCommissionType("");
				$pro -> setAmount($v['goods_number']);
				$a = number_format($v['goods_price'] * (1 - (($order['bonus'] + $order['surplus']) / ($order['bonus'] + $order['surplus'] + $order['order_amount']))), 2, ".", "");
				$pro -> setPrice($a);
				$products[] = $pro;
			}
		}
		$cps -> setProducts($products);

		//var_dump(get_object_vars($cps));
		$sender = new Sender();
		$sender -> setOrder($cps);
		$sender -> sendOrder();
		$ordertime = $order['add_time'] + 8 * 3600;
		$sql = "INSERT INTO cps ( " . "order_id,src,channel,cid,wi,order_time)" . " values('$new_order_id','emar','cps'," . $arr[2] . ",'" . $arr[3] . "','" . $ordertime . "') ";
		$db -> query($sql);

	}*/

	/* 处理余额、积分、红包 */
	if ($order['user_id'] > 0 && $order['surplus'] > 0) {
		//log_account_change($order['user_id'], $order['surplus'] * (-1), 0, 0, 0, sprintf($_LANG['pay_order'], $order['order_sn']));
		log_mcard_change($order['user_id'], $order['surplus'] * (-1), sprintf($_LANG['pay_order'], $order['order_sn']), 0, $order['order_id'], 1);
	}
	if ($order['user_id'] > 0 && $order['integral'] > 0) {
		log_account_change($order['user_id'], 0, 0, 0, $order['integral'] * (-1), sprintf($_LANG['pay_order'], $order['order_sn']));
	}

	if ($order['bonus_id'] > 0 && $temp_amout > 0) {
		$reusable=$bonus['reusable'];
 		use_bonus($order['bonus_id'], $new_order_id,$user_id,$reusable);
	}
	/* 如果使用库存，且下订单时减库存，则减少库存 */
	if ($_CFG['use_storage'] == '1' && $_CFG['stock_dec_time'] == SDT_PLACE) {
		change_order_goods_storage($order['order_id'], true, SDT_PLACE);
	}

	/* 如果订单金额为0 处理虚拟卡 */
	if ($order['order_amount'] <= 0) {
		$sql = "SELECT goods_id, goods_name, goods_number AS num FROM " . $GLOBALS['ecs'] -> table('cart') . " WHERE is_real = 0 AND extension_code = 'virtual_card'" . " AND session_id = '" . SESS_ID . "' AND rec_type = '$flow_type'";

		$res = $GLOBALS['db'] -> getAll($sql);

		$virtual_goods = array();
		foreach ($res AS $row) {
			$virtual_goods['virtual_card'][] = array('goods_id' => $row['goods_id'], 'goods_name' => $row['goods_name'], 'num' => $row['num']);
		}

		if ($virtual_goods AND $flow_type != CART_GROUP_BUY_GOODS) {
			/* 虚拟卡发货 */
			if (virtual_goods_ship($virtual_goods, $msg, $order['order_sn'], true)) {
				/* 如果没有实体商品，修改发货状态，送积分和红包 */
				$sql = "SELECT COUNT(*)" . " FROM " . $ecs -> table('order_goods') . " WHERE order_id = '$order[order_id]' " . " AND is_real = 1";
				if ($db -> getOne($sql) <= 0) {
					/* 修改订单状态 */
					update_order($order['order_id'], array('shipping_status' => SS_SHIPPED, 'shipping_time' => gmtime()));

					/* 如果订单用户不为空，计算积分，并发给用户；发红包 */
					if ($order['user_id'] > 0) {
						/* 取得用户信息 */
						$user = user_info($order['user_id']);

						/* 计算并发放积分 */
						$integral = integral_to_give($order);
						log_account_change($order['user_id'], 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), sprintf($_LANG['order_gift_integral'], $order['order_sn']));

						/* 发放红包 */
						send_order_bonus($order['order_id']);
					}
				}
			}
		}

	}

	/* 清空购物车 */
	clear_cart($flow_type);
	/* 清除缓存，否则买了商品，但是前台页面读取缓存，商品数量不减少 */
	clear_all_files();

	/* 插入支付日志 */
	$order['log_id'] = insert_pay_log($new_order_id, $order['order_amount'], PAY_ORDER);
	/* 取得支付信息，生成支付代码 */
	if ($order['order_amount'] > 0) {
		$payment = payment_info($order['pay_id']);

		include_once ('includes/modules/payment/' . $payment['pay_code'] . '.php');
		$pay_obj = new $payment['pay_code'];

		$pay_online = $pay_obj -> get_code($order, unserialize_config($payment['pay_config']));
		if($pay_id == 3){
			//块钱支付
			$pay_online_url = '';
			$pay_online = str_replace('script','a',$pay_online);
		}else{
			//支付宝支付
			$pay_online_url = $pay_obj -> get_code($order, unserialize_config($payment['pay_config']),false,true);
		}

		$order['pay_desc'] = $payment['pay_desc'];

		$smarty -> assign('pay_online', $pay_online);
		$smarty -> assign('pay_online_url', $pay_online_url);
		$smarty -> assign('pay_id', $pay_id);
	}

	if (!empty($order['shipping_name'])) {
		$order['shipping_name'] = trim(stripcslashes($order['shipping_name']));
	}

	foreach ($cart_goods as $k => $v) {
		if ($v['goods_price'] > 100) {
			$goods['name'] .= "\"" . $v['goods_name'] . "\" ";
			$goods['img'] = $cart_goods[$k]['goodsimg'] = substr($v['goods_sn'], 0, 3);
		}
	}
	
	/* 订单信息 */
	$smarty -> assign('order', $order);
	$smarty -> assign('total', $total);
	$smarty -> assign('goods', $goods);

	$smarty -> assign('goods_list', $cart_goods);

	$smarty -> assign('order_submit_back', sprintf($_LANG['order_submit_back'], $_LANG['back_home'], $_LANG['goto_user_center']));
	// 返回提示

	user_uc_call('add_feed', array($order['order_id'], BUY_GOODS));
	//推送feed到uc
	unset($_SESSION['flow_consignee']);
	// 清除session中保存的收货人信息
	unset($_SESSION['flow_order']);
	unset($_SESSION['direct_shopping']);
	unset($_SESSION['need_shipping_fee']);
	unset($_SESSION['extra_fork']);
	if($order_token=='from_mobile'){
		//exit;
		$redirect_domain = 'http://touch.mescake.com';
		if($_POST['redirect_domain']!= NULL){
		   $redirect_domain = $_POST['redirect_domain'];
		}
		if ($_SESSION['user_auto_register'] == '11') {
			//如果是那种非注册用户就要引导注册
			header("Location: ".$redirect_domain."/setpassword");
		} else {
			header("Location: ".$redirect_domain."/done?oid=".$order['order_id']);
		}
		exit;
	}
}

/*------------------------------------------------------ */
//-- 更新购物车
/*------------------------------------------------------ */

elseif ($_REQUEST['step'] == 'update_cart') {
	if (isset($_REQUEST['number']) && isset($_REQUEST['rec_id'])) {
		include ('includes/cls_json.php');

		$json = new JSON;
		$res = array('err_msg' => '', 'result' => '', 'total' => '', 'rec' => 0);
		$number = $_REQUEST['number'];
		$rec_id = $_REQUEST['rec_id'];

		if ($number < 1) {
			$res['err_msg'] = "错误的数量！";
			$res['err_no'] = 1;
		} else {
			$sql = "UPDATE " . $ecs -> table('cart') . " SET goods_number = '$number' WHERE rec_id = '$rec_id' and session_id='" . SESS_ID . "'";
			$db -> query($sql);
			$sql = "select rec_id,goods_price,goods_number from " . $ecs -> table('cart') . " WHERE session_id='" . SESS_ID . "'";
			$goods = $db -> getAll($sql);
			foreach ($goods as $val) {

				$total['amount'] += $val['goods_price'] * $val['goods_number'];

				if ($val['rec_id'] == $rec_id) {
					$res['result'] = price_format($val['goods_price'] * $number, false);
				}
			}

			$res['total'] = sprintf($_LANG['shopping_money'], price_format($total['amount'], false));
			$res['rec'] = "sub_" . $rec_id;
		}
		die($json -> encode($res));
	}
	ecs_header("Location: flow.php\n");
	exit ;
}

/*------------------------------------------------------ */
//-- 删除购物车中的商品
/*------------------------------------------------------ */

elseif ($_REQUEST['step'] == 'drop_goods') {
	$rec_id = intval($_GET['id']);
	flow_drop_cart_goods($rec_id);
	if ($_GET['ajax']) {
		include_once ('includes/cls_json.php');
		$result = array('error' => 0, 'message' => '', 'content' => '', 'goods_id' => '');
		$json = new JSON;
		$result['content'] = insert_right_cart_info();
		die($json -> encode($result));

	} else {
		ecs_header("Location: flow.php\n");
	}
	exit ;
} elseif ($_REQUEST['step'] == 'drop_shopcar') {
	$rec_id = intval($_GET['id']);
	flow_drop_cart_goods($rec_id);
	if ($_GET['ajax']) {
		include_once ('includes/cls_json.php');
		$result = array('error' => 0, 'message' => '', 'content' => '', 'goods_id' => '');
		$json = new JSON;
		$result['content'] = insert_right_cart_info();
		die($json -> encode($result));

	} else {
		ecs_header("Location: shopcar.php");
	}
	exit ;
}

/* 把优惠活动加入购物车 */
elseif ($_REQUEST['step'] == 'add_favourable') {
	/* 取得优惠活动信息 */
	$act_id = intval($_POST['act_id']);
	$favourable = favourable_info($act_id);
	if (empty($favourable)) {
		show_message($_LANG['favourable_not_exist']);
	}

	/* 判断用户能否享受该优惠 */
	if (!favourable_available($favourable)) {
		show_message($_LANG['favourable_not_available']);
	}

	/* 检查购物车中是否已有该优惠 */
	$cart_favourable = cart_favourable();
	if (favourable_used($favourable, $cart_favourable)) {
		show_message($_LANG['favourable_used']);
	}

	/* 赠品（特惠品）优惠 */
	if ($favourable['act_type'] == FAT_GOODS) {
		/* 检查是否选择了赠品 */
		if (empty($_POST['gift'])) {
			show_message($_LANG['pls_select_gift']);
		}

		/* 检查是否已在购物车 */
		$sql = "SELECT goods_name" . " FROM " . $ecs -> table('cart') . " WHERE session_id = '" . SESS_ID . "'" . " AND rec_type = '" . CART_GENERAL_GOODS . "'" . " AND is_gift = '$act_id'" . " AND goods_id " . db_create_in($_POST['gift']);
		$gift_name = $db -> getCol($sql);
		if (!empty($gift_name)) {
			show_message(sprintf($_LANG['gift_in_cart'], join(',', $gift_name)));
		}

		/* 检查数量是否超过上限 */
		$count = isset($cart_favourable[$act_id]) ? $cart_favourable[$act_id] : 0;
		if ($favourable['act_type_ext'] > 0 && $count + count($_POST['gift']) > $favourable['act_type_ext']) {
			show_message($_LANG['gift_count_exceed']);
		}

		/* 添加赠品到购物车 */
		foreach ($favourable['gift'] as $gift) {
			if (in_array($gift['id'], $_POST['gift'])) {
				add_gift_to_cart($act_id, $gift['id'], $gift['price']);
			}
		}
	} elseif ($favourable['act_type'] == FAT_DISCOUNT) {
		add_favourable_to_cart($act_id, $favourable['act_name'], cart_favourable_amount($favourable) * (100 - $favourable['act_type_ext']) / 100);
	} elseif ($favourable['act_type'] == FAT_PRICE) {
		add_favourable_to_cart($act_id, $favourable['act_name'], $favourable['act_type_ext']);
	}

	/* 刷新购物车 */
	ecs_header("Location: flow.php\n");
	exit ;
} elseif ($_REQUEST['step'] == 'clear') {
	$sql = "DELETE FROM " . $ecs -> table('cart') . " WHERE session_id='" . SESS_ID . "'";
	$db -> query($sql);

	ecs_header("Location:./\n");
} elseif ($_REQUEST['step'] == 'clear2') {
	$sql = "DELETE FROM " . $ecs -> table('cart') . " WHERE session_id='" . SESS_ID . "'";
	$db -> query($sql);

	ecs_header("Location: shopcar.php");
} elseif ($_REQUEST['step'] == 'drop_to_collect') {
	if ($_SESSION['user_id'] > 0) {
		$rec_id = intval($_GET['id']);
		$goods_id = $db -> getOne("SELECT  goods_id FROM " . $ecs -> table('cart') . " WHERE rec_id = '$rec_id' AND session_id = '" . SESS_ID . "' ");
		$count = $db -> getOne("SELECT goods_id FROM " . $ecs -> table('collect_goods') . " WHERE user_id = '$_SESSION[user_id]' AND goods_id = '$goods_id'");
		if (empty($count)) {
			$time = gmtime();
			$sql = "INSERT INTO " . $GLOBALS['ecs'] -> table('collect_goods') . " (user_id, goods_id, add_time)" . "VALUES ('$_SESSION[user_id]', '$goods_id', '$time')";
			$db -> query($sql);
		}
		flow_drop_cart_goods($rec_id);
	}
	ecs_header("Location: flow.php\n");
	exit ;
}

/* 验证红包序列号 */
elseif ($_REQUEST['step'] == 'validate_bonus') {
	$bonus_sn = trim($_REQUEST['bonus_sn']);
	if (is_numeric($bonus_sn)) {
		$bonus = bonus_info(0, $bonus_sn);
	} else {
		$bonus = array();
	}

	//    if (empty($bonus) || $bonus['user_id'] > 0 || $bonus['order_id'] > 0)
	//    {
	//        die($_LANG['bonus_sn_error']);
	//    }
	//    if ($bonus['min_goods_amount'] > cart_amount())
	//    {
	//        die(sprintf($_LANG['bonus_min_amount_error'], price_format($bonus['min_goods_amount'], false)));
	//    }
	//    die(sprintf($_LANG['bonus_is_ok'], price_format($bonus['type_money'], false)));
	$bonus_kill = price_format($bonus['type_money'], false);

	include_once ('includes/cls_json.php');
	$result = array('error' => '', 'content' => '');

	/* 取得购物类型 */
	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

	/* 获得收货人信息 */
	//$consignee = get_consignee($_SESSION['user_id']);

	/* 对商品信息赋值 */
	$cart_goods = cart_goods($flow_type);
	// 取得商品列表，计算合计
	//|| !check_consignee_info($consignee, $flow_type)
	if (empty($cart_goods)) {
		$result['error'] = $_LANG['no_goods_in_cart'];
	} else {
		/* 取得购物流程设置 */
		$smarty -> assign('config', $_CFG);

		/* 取得订单信息 */
		$order = flow_order_info();

		if (((!empty($bonus) && $bonus['user_id'] == $_SESSION['user_id']) || ($bonus['type_money'] > 0 && empty($bonus['user_id']))) && $bonus['order_id'] <= 0) {
			//$order['bonus_kill'] = $bonus['type_money'];
			$now = gmtime();
			if ($now > $bonus['use_end_date']) {
				$order['bonus_id'] = '';
				$result['error'] = $_LANG['bonus_use_expire'];
			} else {
				$order['bonus_id'] = $bonus['bonus_id'];
				$order['bonus_sn'] = $bonus_sn;
			}
		} else {
			//$order['bonus_kill'] = 0;
			$order['bonus_id'] = '';
			$result['error'] = $_LANG['invalid_bonus'];
		}

		/* 计算订单的费用 */
		$total = order_fee($order, $cart_goods, $consignee);

		if ($total['goods_price'] < $bonus['min_goods_amount']) {
			$order['bonus_id'] = '';
			/* 重新计算订单 */
			$total = order_fee($order, $cart_goods, $consignee);
			$result['error'] = sprintf($_LANG['bonus_min_amount_error'], price_format($bonus['min_goods_amount'], false));
		}

		$smarty -> assign('total', $total);

		/* 团购标志 */
		if ($flow_type == CART_GROUP_BUY_GOODS) {
			$smarty -> assign('is_group_buy', 1);
		}

		$result['content'] = $smarty -> fetch('library/order_total.lbi');
		$total['amount']+=$total['surplus'];
 		$total['amount_formated']=price_format($total['amount'], false);
		$result['total'] = $total;
	}
	if ($order['bonus_id'] != '')
		$result['bonus_id'] = $order['bonus_id'];
	$json = new JSON();
	die($json -> encode($result));
}
/* 验证礼金卡 */
elseif ($_REQUEST['step'] == 'validate_gift') {
	$surplus = trim($_REQUEST['is_surplus']);

	include_once ('includes/cls_json.php');
	$result = array('error' => '', 'content' => '');

	/* 取得购物类型 */
	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

	/* 获得收货人信息 */
	//$consignee = get_consignee($_SESSION['user_id']);

	/* 对商品信息赋值 */
	$cart_goods = cart_goods($flow_type);
	// 取得商品列表，计算合计
	//|| !check_consignee_info($consignee, $flow_type)
	if (empty($cart_goods)) {
		$result['error'] = $_LANG['no_goods_in_cart'];
	} else {
		/* 取得购物流程设置 */
		$smarty -> assign('config', $_CFG);

		/* 取得订单信息 */
		$order = flow_order_info();
		$order['bonus_id'] = 0;
		$user_info = user_info($_SESSION['user_id']);
		/* 计算订单的费用 */
		$total = order_fee($order, $cart_goods, $consignee);
		if ($surplus <= 0) {
			$order['surplus'] = 0;
		} else {
			$order['surplus'] = min($total['amount'], $user_info['user_money']);
		}
		$total = order_fee($order, $cart_goods, $consignee);

		$smarty -> assign('total', $total);

		/* 团购标志 */
		if ($flow_type == CART_GROUP_BUY_GOODS) {
			$smarty -> assign('is_group_buy', 1);
		}

		$result['content'] = $smarty -> fetch('library/order_total.lbi');
	}

	$result['surplus'] = $order['surplus'];
	$result['total'] = $total;
	$result['user_money'] = $user_info['user_money'];
	$json = new JSON();
	die($json -> encode($result));
}
/*------------------------------------------------------ */
//-- 添加礼包到购物车
/*------------------------------------------------------ */
elseif ($_REQUEST['step'] == 'add_package_to_cart') {
	include_once ('includes/cls_json.php');
	$_POST['package_info'] = json_str_iconv($_POST['package_info']);

	$result = array('error' => 0, 'message' => '', 'content' => '', 'package_id' => '');
	$json = new JSON;

	if (empty($_POST['package_info'])) {
		$result['error'] = 1;
		die($json -> encode($result));
	}

	$package = $json -> decode($_POST['package_info']);

	/* 如果是一步购物，先清空购物车 */
	if ($_CFG['one_step_buy'] == '1') {
		clear_cart();
	}

	/* 商品数量是否合法 */
	if (!is_numeric($package -> number) || intval($package -> number) <= 0) {
		$result['error'] = 1;
		$result['message'] = $_LANG['invalid_number'];
	} else {
		/* 添加到购物车 */
		if (add_package_to_cart($package -> package_id, $package -> number)) {
			if ($_CFG['cart_confirm'] > 2) {
				$result['message'] = '';
			} else {
				$result['message'] = $_CFG['cart_confirm'] == 1 ? $_LANG['addto_cart_success_1'] : $_LANG['addto_cart_success_2'];
			}

			$result['content'] = insert_cart_info();
			$result['one_step_buy'] = $_CFG['one_step_buy'];
		} else {
			$result['message'] = $err -> last_message();
			$result['error'] = $err -> error_no;
			$result['package_id'] = stripslashes($package -> package_id);
		}
	}
	$result['confirm_type'] = !empty($_CFG['cart_confirm']) ? $_CFG['cart_confirm'] : 2;
	die($json -> encode($result));
} elseif ($_REQUEST['step'] == 'is_login') {
	$flag = ($_SESSION['user_id'] == 0) ? 0 : $_SESSION['user_id'];
	if ($flag > 0) {
		echo $flag;
	} else {
		echo "0";
	}
	exit ;
} else {
	require (ROOT_PATH . 'includes/lib_transaction.php');
	/* 标记购物流程为普通商品 */
	$_SESSION['flow_type'] = CART_GENERAL_GOODS;

	/* 如果是一步购物，跳到结算中心 */
	if ($_CFG['one_step_buy'] == '1') {
		ecs_header("Location: flow.php?step=checkout\n");
		exit ;
	}

	/* 取得商品列表，计算合计 */
	$cart_goods = get_cart_goods();
	//echo "<pre>";print_r($cart_goods);
	foreach ($cart_goods['goods_list'] as $k => $v) {
		//echo $v['goods_id'];exit;
		if ($v['goods_id'] == 60)
			$smarty -> assign('canju', 1);
		if ($v['goods_id'] == 61)
			$smarty -> assign('lazhu', 1);
	}
	$smarty -> assign('goods_list', $cart_goods['goods_list']);
	//print_r($cart_goods['goods_list']);
	$smarty -> assign('total', $cart_goods['total']);

	//购物车的描述的格式化
	$smarty -> assign('shopping_money', sprintf($_LANG['shopping_money'], $cart_goods['total']['goods_price']));
	$smarty -> assign('market_price_desc', sprintf($_LANG['than_market_price'], $cart_goods['total']['market_price'], $cart_goods['total']['saving'], $cart_goods['total']['save_rate']));

	// 显示收藏夹内的商品
	if ($_SESSION['user_id'] > 0) {
		require_once (ROOT_PATH . 'includes/lib_clips.php');
		$collection_goods = get_collection_goods($_SESSION['user_id']);
		$smarty -> assign('collection_goods', $collection_goods);
		$user_info = user_info($_SESSION['user_id']);
		$smarty -> assign('user_info', $user_info);
	}

	/* 取得优惠活动 */
	$favourable_list = favourable_list($_SESSION['user_rank']);
	usort($favourable_list, 'cmp_favourable');

	$smarty -> assign('favourable_list', $favourable_list);

	/* 计算折扣 */
	$discount = compute_discount();
	$smarty -> assign('discount', $discount['discount']);
	$favour_name = empty($discount['name']) ? '' : join(',', $discount['name']);
	$smarty -> assign('your_discount', sprintf($_LANG['your_discount'], $favour_name, price_format($discount['discount'])));

	/* 增加是否在购物车里显示商品图 */
	$smarty -> assign('show_goods_thumb', $GLOBALS['_CFG']['show_goods_in_cart']);

	/* 增加是否在购物车里显示商品属性 */
	$smarty -> assign('show_goods_attribute', $GLOBALS['_CFG']['show_attr_in_cart']);

	/* 购物车中商品配件列表 */
	//取得购物车中基本件ID
	$sql = "SELECT goods_id " . "FROM " . $GLOBALS['ecs'] -> table('cart') . " WHERE session_id = '" . SESS_ID . "' " . "AND rec_type = '" . CART_GENERAL_GOODS . "' " . "AND is_gift = 0 " . "AND extension_code <> 'package_buy' " . "AND parent_id = 0 ";
	$parent_list = $GLOBALS['db'] -> getCol($sql);

	$fittings_list = get_goods_fittings($parent_list);
	$smarty -> assign('fittings_list', $fittings_list);
	$smarty -> assign('city_list', get_regions(0, 441));
	$smarty -> assign('district_list', get_regions(2, 501));
	$consignee_list = get_consignee_list($_SESSION['user_id']);
	$consignee = get_consignee($_SESSION['user_id']);
	$GLOBALS['smarty'] -> assign('consignee', $consignee);

}
$rowss = $db -> getRow("select * from ecs_user_address where user_id={$_SESSION['user_id']} limit 0,1");
if ($rowss['city']) {
	$daqu = $db -> getOne("select region_name from ship_region where region_id={$rowss['city']}");
	$rowss['daqu'] = $daqu;
}
$address0 = '北京市' . $rowss['daqu'];
//print_r($rowss);
$smarty -> assign('accepter', $rowss);
//送货地址
$smarty -> assign('address0', $address0);
$smarty -> assign('currency_format', $_CFG['currency_format']);
$smarty -> assign('integral_scale', $_CFG['integral_scale']);
$smarty -> assign('step', $_REQUEST['step']);
assign_dynamic('shopping_flow');

$smarty -> assign('consignee_list', $consignee_list);

if ($_SESSION['user_auto_register'] == '11') {
	//如果是那种非注册用户就要引导注册
	$smarty -> display('order_set_password.dwt');
} else {
	$smarty -> display('done_v2.dwt');
}

/*------------------------------------------------------ */
//-- PRIVATE FUNCTION
/*------------------------------------------------------ */

/**
 * 获得用户的可用积分
 *
 * @access  private
 * @return  integral
 */
function flow_available_points() {
	$sql = "SELECT SUM(g.integral * c.goods_number) " . "FROM " . $GLOBALS['ecs'] -> table('cart') . " AS c, " . $GLOBALS['ecs'] -> table('goods') . " AS g " . "WHERE c.session_id = '" . SESS_ID . "' AND c.goods_id = g.goods_id AND c.is_gift = 0 AND g.integral > 0 " . "AND c.rec_type = '" . CART_GENERAL_GOODS . "'";

	$val = intval($GLOBALS['db'] -> getOne($sql));

	return integral_of_value($val);
}

/**
 * 更新购物车中的商品数量
 *
 * @access  public
 * @param   array   $arr
 * @return  void
 */
function flow_update_cart($arr) {
	/* 处理 */
	foreach ($arr AS $key => $val) {
		$val = intval(make_semiangle($val));
		if ($val <= 0 || !is_numeric($key)) {
			continue;
		}

		//查询：
		$sql = "SELECT `goods_id`, `goods_attr_id`, `product_id`, `extension_code` FROM" . $GLOBALS['ecs'] -> table('cart') . " WHERE rec_id='$key' AND session_id='" . SESS_ID . "'";
		$goods = $GLOBALS['db'] -> getRow($sql);

		$sql = "SELECT g.goods_name, g.goods_number " . "FROM " . $GLOBALS['ecs'] -> table('goods') . " AS g, " . $GLOBALS['ecs'] -> table('cart') . " AS c " . "WHERE g.goods_id = c.goods_id AND c.rec_id = '$key'";
		$row = $GLOBALS['db'] -> getRow($sql);

		//查询：系统启用了库存，检查输入的商品数量是否有效
		if (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] != 'package_buy') {
			if ($row['goods_number'] < $val) {
				show_message(sprintf($GLOBALS['_LANG']['stock_insufficiency'], $row['goods_name'], $row['goods_number'], $row['goods_number']));
				exit ;
			}
			/* 是货品 */
			$goods['product_id'] = trim($goods['product_id']);
			if (!empty($goods['product_id'])) {
				$sql = "SELECT product_number FROM " . $GLOBALS['ecs'] -> table('products') . " WHERE goods_id = '" . $goods['goods_id'] . "' AND product_id = '" . $goods['product_id'] . "'";

				$product_number = $GLOBALS['db'] -> getOne($sql);
				if ($product_number < $val) {
					show_message(sprintf($GLOBALS['_LANG']['stock_insufficiency'], $row['goods_name'], $product_number['product_number'], $product_number['product_number']));
					exit ;
				}
			}
		} elseif (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] == 'package_buy') {
			if (judge_package_stock($goods['goods_id'], $val)) {
				show_message($GLOBALS['_LANG']['package_stock_insufficiency']);
				exit ;
			}
		}

		/* 查询：检查该项是否为基本件 以及是否存在配件 */
		/* 此处配件是指添加商品时附加的并且是设置了优惠价格的配件 此类配件都有parent_id goods_number为1 */
		$sql = "SELECT b.goods_number, b.rec_id
                FROM " . $GLOBALS['ecs'] -> table('cart') . " a, " . $GLOBALS['ecs'] -> table('cart') . " b
                WHERE a.rec_id = '$key'
                AND a.session_id = '" . SESS_ID . "'
                AND a.extension_code <> 'package_buy'
                AND b.parent_id = a.goods_id
                AND b.session_id = '" . SESS_ID . "'";

		$offers_accessories_res = $GLOBALS['db'] -> query($sql);

		//订货数量大于0
		if ($val > 0) {
			/* 判断是否为超出数量的优惠价格的配件 删除*/
			$row_num = 1;
			while ($offers_accessories_row = $GLOBALS['db'] -> fetchRow($offers_accessories_res)) {
				if ($row_num > $val) {
					$sql = "DELETE FROM " . $GLOBALS['ecs'] -> table('cart') . " WHERE session_id = '" . SESS_ID . "' " . "AND rec_id = '" . $offers_accessories_row['rec_id'] . "' LIMIT 1";
					$GLOBALS['db'] -> query($sql);
				}

				$row_num++;
			}

			/* 处理超值礼包 */
			if ($goods['extension_code'] == 'package_buy') {
				//更新购物车中的商品数量
				$sql = "UPDATE " . $GLOBALS['ecs'] -> table('cart') . " SET goods_number = '$val' WHERE rec_id='$key' AND session_id='" . SESS_ID . "'";
			}
			/* 处理普通商品或非优惠的配件 */
			else {
				$attr_id = empty($goods['goods_attr_id']) ? array() : explode(',', $goods['goods_attr_id']);
				$goods_price = get_final_price($goods['goods_id'], $val, true, $attr_id);

				//更新购物车中的商品数量
				$sql = "UPDATE " . $GLOBALS['ecs'] -> table('cart') . " SET goods_number = '$val', goods_price = '$goods_price' WHERE rec_id='$key' AND session_id='" . SESS_ID . "'";
			}
		}
		//订货数量等于0
		else {
			/* 如果是基本件并且有优惠价格的配件则删除优惠价格的配件 */
			while ($offers_accessories_row = $GLOBALS['db'] -> fetchRow($offers_accessories_res)) {
				$sql = "DELETE FROM " . $GLOBALS['ecs'] -> table('cart') . " WHERE session_id = '" . SESS_ID . "' " . "AND rec_id = '" . $offers_accessories_row['rec_id'] . "' LIMIT 1";
				$GLOBALS['db'] -> query($sql);
			}

			$sql = "DELETE FROM " . $GLOBALS['ecs'] -> table('cart') . " WHERE rec_id='$key' AND session_id='" . SESS_ID . "'";
		}

		$GLOBALS['db'] -> query($sql);
	}

	/* 删除所有赠品 */
	$sql = "DELETE FROM " . $GLOBALS['ecs'] -> table('cart') . " WHERE session_id = '" . SESS_ID . "' AND is_gift <> 0";
	$GLOBALS['db'] -> query($sql);
}

/**
 * 检查订单中商品库存
 *
 * @access  public
 * @param   array   $arr
 *
 * @return  void
 */
function flow_cart_stock($arr) {
	foreach ($arr AS $key => $val) {
		$val = intval(make_semiangle($val));
		if ($val <= 0 || !is_numeric($key)) {
			continue;
		}

		$sql = "SELECT `goods_id`, `goods_attr_id`, `extension_code` FROM" . $GLOBALS['ecs'] -> table('cart') . " WHERE rec_id='$key' AND session_id='" . SESS_ID . "'";
		$goods = $GLOBALS['db'] -> getRow($sql);

		$sql = "SELECT g.goods_name, g.goods_number, c.product_id " . "FROM " . $GLOBALS['ecs'] -> table('goods') . " AS g, " . $GLOBALS['ecs'] -> table('cart') . " AS c " . "WHERE g.goods_id = c.goods_id AND c.rec_id = '$key'";
		$row = $GLOBALS['db'] -> getRow($sql);

		//系统启用了库存，检查输入的商品数量是否有效
		if (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] != 'package_buy') {
			if ($row['goods_number'] < $val) {
				show_message(sprintf($GLOBALS['_LANG']['stock_insufficiency'], $row['goods_name'], $row['goods_number'], $row['goods_number']));
				exit ;
			}

			/* 是货品 */
			$row['product_id'] = trim($row['product_id']);
			if (!empty($row['product_id'])) {
				$sql = "SELECT product_number FROM " . $GLOBALS['ecs'] -> table('products') . " WHERE goods_id = '" . $goods['goods_id'] . "' AND product_id = '" . $row['product_id'] . "'";
				$product_number = $GLOBALS['db'] -> getOne($sql);
				if ($product_number < $val) {
					show_message(sprintf($GLOBALS['_LANG']['stock_insufficiency'], $row['goods_name'], $row['goods_number'], $row['goods_number']));
					exit ;
				}
			}
		} elseif (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] == 'package_buy') {
			if (judge_package_stock($goods['goods_id'], $val)) {
				show_message($GLOBALS['_LANG']['package_stock_insufficiency']);
				exit ;
			}
		}
	}

}

/**
 * 删除购物车中的商品
 *
 * @access  public
 * @param   integer $id
 * @return  void
 */
function flow_drop_cart_goods($id) {
	/* 取得商品id */
	$sql = "SELECT * FROM " . $GLOBALS['ecs'] -> table('cart') . " WHERE rec_id = '$id'";
	$row = $GLOBALS['db'] -> getRow($sql);
	if ($row) {
		//如果是超值礼包
		if ($row['extension_code'] == 'package_buy') {
			$sql = "DELETE FROM " . $GLOBALS['ecs'] -> table('cart') . " WHERE session_id = '" . SESS_ID . "' " . "AND rec_id = '$id' LIMIT 1";
		}

		//如果是普通商品，同时删除所有赠品及其配件
		elseif ($row['parent_id'] == 0 && $row['is_gift'] == 0) {
			/* 检查购物车中该普通商品的不可单独销售的配件并删除 */
			$sql = "SELECT c.rec_id
                    FROM " . $GLOBALS['ecs'] -> table('cart') . " AS c, " . $GLOBALS['ecs'] -> table('group_goods') . " AS gg, " . $GLOBALS['ecs'] -> table('goods') . " AS g
                    WHERE gg.parent_id = '" . $row['goods_id'] . "'
                    AND c.goods_id = gg.goods_id
                    AND c.parent_id = '" . $row['goods_id'] . "'
                    AND c.extension_code <> 'package_buy'
                    AND gg.goods_id = g.goods_id
                    AND g.is_alone_sale = 0";
			$res = $GLOBALS['db'] -> query($sql);
			$_del_str = $id . ',';
			while ($id_alone_sale_goods = $GLOBALS['db'] -> fetchRow($res)) {
				$_del_str .= $id_alone_sale_goods['rec_id'] . ',';
			}
			$_del_str = trim($_del_str, ',');

			$sql = "DELETE FROM " . $GLOBALS['ecs'] -> table('cart') . " WHERE session_id = '" . SESS_ID . "' " . "AND (rec_id IN ($_del_str) OR parent_id = '$row[goods_id]' OR is_gift <> 0)";
		}

		//如果不是普通商品，只删除该商品即可
		else {
			$sql = "DELETE FROM " . $GLOBALS['ecs'] -> table('cart') . " WHERE session_id = '" . SESS_ID . "' " . "AND rec_id = '$id' LIMIT 1";
		}

		$GLOBALS['db'] -> query($sql);
	}

	flow_clear_cart_alone();
}

/**
 * 删除购物车中不能单独销售的商品
 *
 * @access  public
 * @return  void
 */
function flow_clear_cart_alone() {
	/* 查询：购物车中所有不可以单独销售的配件 */
	$sql = "SELECT c.rec_id, gg.parent_id
            FROM " . $GLOBALS['ecs'] -> table('cart') . " AS c
                LEFT JOIN " . $GLOBALS['ecs'] -> table('group_goods') . " AS gg ON c.goods_id = gg.goods_id
                LEFT JOIN" . $GLOBALS['ecs'] -> table('goods') . " AS g ON c.goods_id = g.goods_id
            WHERE c.session_id = '" . SESS_ID . "'
            AND c.extension_code <> 'package_buy'
            AND gg.parent_id > 0
            AND g.is_alone_sale = 0";
	$res = $GLOBALS['db'] -> query($sql);
	$rec_id = array();
	while ($row = $GLOBALS['db'] -> fetchRow($res)) {
		$rec_id[$row['rec_id']][] = $row['parent_id'];
	}

	if (empty($rec_id)) {
		return;
	}

	/* 查询：购物车中所有商品 */
	$sql = "SELECT DISTINCT goods_id
            FROM " . $GLOBALS['ecs'] -> table('cart') . "
            WHERE session_id = '" . SESS_ID . "'
            AND extension_code <> 'package_buy'";
	$res = $GLOBALS['db'] -> query($sql);
	$cart_good = array();
	while ($row = $GLOBALS['db'] -> fetchRow($res)) {
		$cart_good[] = $row['goods_id'];
	}

	if (empty($cart_good)) {
		return;
	}

	/* 如果购物车中不可以单独销售配件的基本件不存在则删除该配件 */
	$del_rec_id = '';
	foreach ($rec_id as $key => $value) {
		foreach ($value as $v) {
			if (in_array($v, $cart_good)) {
				continue 2;
			}
		}

		$del_rec_id = $key . ',';
	}
	$del_rec_id = trim($del_rec_id, ',');

	if ($del_rec_id == '') {
		return;
	}

	/* 删除 */
	$sql = "DELETE FROM " . $GLOBALS['ecs'] -> table('cart') . "
            WHERE session_id = '" . SESS_ID . "'
            AND rec_id IN ($del_rec_id)";
	$GLOBALS['db'] -> query($sql);
}

/**
 * 比较优惠活动的函数，用于排序（把可用的排在前面）
 * @param   array   $a      优惠活动a
 * @param   array   $b      优惠活动b
 * @return  int     相等返回0，小于返回-1，大于返回1
 */
function cmp_favourable($a, $b) {
	if ($a['available'] == $b['available']) {
		if ($a['sort_order'] == $b['sort_order']) {
			return 0;
		} else {
			return $a['sort_order'] < $b['sort_order'] ? -1 : 1;
		}
	} else {
		return $a['available'] ? -1 : 1;
	}
}

/**
 * 取得某用户等级当前时间可以享受的优惠活动
 * @param   int     $user_rank      用户等级id，0表示非会员
 * @return  array
 */
function favourable_list($user_rank) {
	/* 购物车中已有的优惠活动及数量 */
	$used_list = cart_favourable();

	/* 当前用户可享受的优惠活动 */
	$favourable_list = array();
	$user_rank = ',' . $user_rank . ',';
	$now = gmtime();
	$sql = "SELECT * " . "FROM " . $GLOBALS['ecs'] -> table('favourable_activity') . " WHERE CONCAT(',', user_rank, ',') LIKE '%" . $user_rank . "%'" . " AND start_time <= '$now' AND end_time >= '$now'" . " AND act_type = '" . FAT_GOODS . "'" . " ORDER BY sort_order";
	$res = $GLOBALS['db'] -> query($sql);
	while ($favourable = $GLOBALS['db'] -> fetchRow($res)) {
		$favourable['start_time'] = local_date($GLOBALS['_CFG']['time_format'], $favourable['start_time']);
		$favourable['end_time'] = local_date($GLOBALS['_CFG']['time_format'], $favourable['end_time']);
		$favourable['formated_min_amount'] = price_format($favourable['min_amount'], false);
		$favourable['formated_max_amount'] = price_format($favourable['max_amount'], false);
		$favourable['gift'] = unserialize($favourable['gift']);

		foreach ($favourable['gift'] as $key => $value) {
			$favourable['gift'][$key]['formated_price'] = price_format($value['price'], false);
			$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs'] -> table('goods') . " WHERE is_on_sale = 1 AND goods_id = " . $value['id'];
			$is_sale = $GLOBALS['db'] -> getOne($sql);
			if (!$is_sale) {
				unset($favourable['gift'][$key]);
			}
		}

		$favourable['act_range_desc'] = act_range_desc($favourable);
		$favourable['act_type_desc'] = sprintf($GLOBALS['_LANG']['fat_ext'][$favourable['act_type']], $favourable['act_type_ext']);

		/* 是否能享受 */
		$favourable['available'] = favourable_available($favourable);
		if ($favourable['available']) {
			/* 是否尚未享受 */
			$favourable['available'] = !favourable_used($favourable, $used_list);
		}

		$favourable_list[] = $favourable;
	}

	return $favourable_list;
}

/**
 * 根据购物车判断是否可以享受某优惠活动
 * @param   array   $favourable     优惠活动信息
 * @return  bool
 */
function favourable_available($favourable) {
	/* 会员等级是否符合 */
	$user_rank = $_SESSION['user_rank'];
	if (strpos(',' . $favourable['user_rank'] . ',', ',' . $user_rank . ',') === false) {
		return false;
	}

	/* 优惠范围内的商品总额 */
	$amount = cart_favourable_amount($favourable);

	/* 金额上限为0表示没有上限 */
	return $amount >= $favourable['min_amount'] && ($amount <= $favourable['max_amount'] || $favourable['max_amount'] == 0);
}

/**
 * 取得优惠范围描述
 * @param   array   $favourable     优惠活动
 * @return  string
 */
function act_range_desc($favourable) {
	if ($favourable['act_range'] == FAR_BRAND) {
		$sql = "SELECT brand_name FROM " . $GLOBALS['ecs'] -> table('brand') . " WHERE brand_id " . db_create_in($favourable['act_range_ext']);
		return join(',', $GLOBALS['db'] -> getCol($sql));
	} elseif ($favourable['act_range'] == FAR_CATEGORY) {
		$sql = "SELECT cat_name FROM " . $GLOBALS['ecs'] -> table('category') . " WHERE cat_id " . db_create_in($favourable['act_range_ext']);
		return join(',', $GLOBALS['db'] -> getCol($sql));
	} elseif ($favourable['act_range'] == FAR_GOODS) {
		$sql = "SELECT goods_name FROM " . $GLOBALS['ecs'] -> table('goods') . " WHERE goods_id " . db_create_in($favourable['act_range_ext']);
		return join(',', $GLOBALS['db'] -> getCol($sql));
	} else {
		return '';
	}
}

/**
 * 取得购物车中已有的优惠活动及数量
 * @return  array
 */
function cart_favourable() {
	$list = array();
	$sql = "SELECT is_gift, COUNT(*) AS num " . "FROM " . $GLOBALS['ecs'] -> table('cart') . " WHERE session_id = '" . SESS_ID . "'" . " AND rec_type = '" . CART_GENERAL_GOODS . "'" . " AND is_gift > 0" . " GROUP BY is_gift";
	$res = $GLOBALS['db'] -> query($sql);
	while ($row = $GLOBALS['db'] -> fetchRow($res)) {
		$list[$row['is_gift']] = $row['num'];
	}

	return $list;
}

/**
 * 购物车中是否已经有某优惠
 * @param   array   $favourable     优惠活动
 * @param   array   $cart_favourable购物车中已有的优惠活动及数量
 */
function favourable_used($favourable, $cart_favourable) {
	if ($favourable['act_type'] == FAT_GOODS) {
		return isset($cart_favourable[$favourable['act_id']]) && $cart_favourable[$favourable['act_id']] >= $favourable['act_type_ext'] && $favourable['act_type_ext'] > 0;
	} else {
		return isset($cart_favourable[$favourable['act_id']]);
	}
}

/**
 * 添加优惠活动（赠品）到购物车
 * @param   int     $act_id     优惠活动id
 * @param   int     $id         赠品id
 * @param   float   $price      赠品价格
 */
function add_gift_to_cart($act_id, $id, $price) {
	$sql = "INSERT INTO " . $GLOBALS['ecs'] -> table('cart') . " (" . "user_id, session_id, goods_id, goods_sn, goods_name, market_price, goods_price, " . "goods_number, is_real, extension_code, parent_id, is_gift, rec_type ) " . "SELECT '$_SESSION[user_id]', '" . SESS_ID . "', goods_id, goods_sn, goods_name, market_price, " . "'$price', 1, is_real, extension_code, 0, '$act_id', '" . CART_GENERAL_GOODS . "' " . "FROM " . $GLOBALS['ecs'] -> table('goods') . " WHERE goods_id = '$id'";
	$GLOBALS['db'] -> query($sql);
}

/**
 * 添加优惠活动（非赠品）到购物车
 * @param   int     $act_id     优惠活动id
 * @param   string  $act_name   优惠活动name
 * @param   float   $amount     优惠金额
 */
function add_favourable_to_cart($act_id, $act_name, $amount) {
	$sql = "INSERT INTO " . $GLOBALS['ecs'] -> table('cart') . "(" . "user_id, session_id, goods_id, goods_sn, goods_name, market_price, goods_price, " . "goods_number, is_real, extension_code, parent_id, is_gift, rec_type ) " . "VALUES('$_SESSION[user_id]', '" . SESS_ID . "', 0, '', '$act_name', 0, " . "'" . (-1) * $amount . "', 1, 0, '', 0, '$act_id', '" . CART_GENERAL_GOODS . "')";
	$GLOBALS['db'] -> query($sql);
}

/**
 * 取得购物车中某优惠活动范围内的总金额
 * @param   array   $favourable     优惠活动
 * @return  float
 */
function cart_favourable_amount($favourable) {
	/* 查询优惠范围内商品总额的sql */
	$sql = "SELECT SUM(c.goods_price * c.goods_number) " . "FROM " . $GLOBALS['ecs'] -> table('cart') . " AS c, " . $GLOBALS['ecs'] -> table('goods') . " AS g " . "WHERE c.goods_id = g.goods_id " . "AND c.session_id = '" . SESS_ID . "' " . "AND c.rec_type = '" . CART_GENERAL_GOODS . "' " . "AND c.is_gift = 0 " . "AND c.goods_id > 0 ";

	/* 根据优惠范围修正sql */
	if ($favourable['act_range'] == FAR_ALL) {
		// sql do not change
	} elseif ($favourable['act_range'] == FAR_CATEGORY) {
		/* 取得优惠范围分类的所有下级分类 */
		$id_list = array();
		$cat_list = explode(',', $favourable['act_range_ext']);
		foreach ($cat_list as $id) {
			$id_list = array_merge($id_list, array_keys(cat_list(intval($id), 0, false)));
		}

		$sql .= "AND g.cat_id " . db_create_in($id_list);
	} elseif ($favourable['act_range'] == FAR_BRAND) {
		$id_list = explode(',', $favourable['act_range_ext']);

		$sql .= "AND g.brand_id " . db_create_in($id_list);
	} else {
		$id_list = explode(',', $favourable['act_range_ext']);

		$sql .= "AND g.goods_id " . db_create_in($id_list);
	}

	/* 优惠范围内的商品总额 */
	return $GLOBALS['db'] -> getOne($sql);
}
?>
