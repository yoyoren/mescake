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
			$contact= $_POST['contact'];
			$country= $_POST['country'];
			$city= $_POST['city'];
			$address= $_POST['address'];
			$tel= $_POST['tel'];
			echo ECS_Order::add_order_address($contact,$country,$city,$address,$tel);
		}else if($action == 'del_order_address'){
			$id = $_POST['id'];
			echo ECS_Order::del_order_address($id);
		}else if($action == 'update_order_address'){
			$id = $_POST['id'];
			$contact= $_POST['contact'];
			$country= $_POST['country'];
			$city= $_POST['city'];
			$address= $_POST['address'];
			echo ECS_Order::update_order_address($id,$country,$city,$contact,$address);
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