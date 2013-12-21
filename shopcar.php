<?php



define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_order.php');
require(ROOT_PATH . 'includes/lib_transaction.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/shopping_flow.php');
//购物车
	$cart_goods = get_cart_goods();
	//echo "<pre>";print_r($cart_goods);
	foreach($cart_goods['goods_list'] as $k=>$v)
	{
		//echo $v['goods_id'];exit;
		if($v['goods_id']==60) $smarty->assign('canju', 1);
		if($v['goods_id']==61) $smarty->assign('lazhu', 1);
	}
    $smarty->assign('goods_list', $cart_goods['goods_list']);
	$smarty->assign('total', $cart_goods['total']);
	//print_r($cart_goods);
	//购物车的描述的格式化
    $smarty->assign('shopping_money',         sprintf($_LANG['shopping_money'], $cart_goods['total']['goods_price']));
    $smarty->assign('market_price_desc',      sprintf($_LANG['than_market_price'],
        $cart_goods['total']['market_price'], $cart_goods['total']['saving'], $cart_goods['total']['save_rate']));
	$smarty->display('shopcar.dwt');




?>