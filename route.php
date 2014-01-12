<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'lib/order.php');
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
			echo ECS_Order::get_order_list();
		}else if($action == 'get_order_address'){
			echo ECS_Order::get_order_address();
		}else if($action == 'add_order_address'){
			$contact= $_GET['contact'];
			$country= $_GET['country'];
			$city= $_GET['city'];
			$address= $_GET['address'];
			$tel= $_GET['tel'];
			echo ECS_Order::add_order_address($contact,$country,$city,$address,$tel);
		}else if($action == 'get_region'){
			echo ECS_Order::get_region();
		}
        break;
    case 'login':
        break;
    default:
        break;
}


?>