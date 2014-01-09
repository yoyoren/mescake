<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_order.php');
require(ROOT_PATH . 'includes/lib_transaction.php');

if ((DEBUG_MODE & 2) != 2){
    $smarty->caching = true;
}

$action = $_GET['action'];
$mod = $_GET['mod'];

switch ($mod) {
    case 'order':
        if($action == 'step1'){
			$smarty->display('shoppingcar_new.dwt');
			return;
		}else if($action == 'step2'){
			$smarty->display('order_new.dwt');
			return;
		}else if($action == 'step3'){
		
		}else if($action == 'get_order_list'){
			//defined in lib_order;
			$cart_goods = get_cart_goods();
			echo json_encode($cart_goods);
		}

        break;
    case 'login':
        break;
    default:
        break;
}


?>