<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

if ((DEBUG_MODE & 2) != 2){
    $smarty->caching = true;
}

$action = $_GET['action'];
$mod = $_GET['mod'];

switch ($mod) {
    case 'order':
		require(ROOT_PATH . 'lib/order.php');
        if($action == 'step1'){
			$smarty->display('shoppingcar_new.dwt');
			return;
		}else if($action == 'step2'){
			$smarty->display('order_new.dwt');
			return;
		}else if($action == 'step3'){
		
		}else if($action == 'get_order_list'){
			echo MES_Order::get_order_list();
		}else if($action == 'get_order_address'){
			echo MES_Order::get_order_address();
		}else if($action == 'add_order_address'){
			$contact= $_POST['contact'];
			$country= $_POST['country'];
			$city= $_POST['city'];
			$address= $_POST['address'];
			$tel= $_POST['tel'];
			echo MES_Order::add_order_address($contact,$country,$city,$address,$tel);
		}else if($action == 'del_order_address'){
			//删除地址
			$id = $_POST['id'];
			echo MES_Order::del_order_address($id);
		}else if($action == 'update_order_address'){
			//更新地址信息
			$id = $_POST['id'];
			$contact= $_POST['contact'];
			$country= $_POST['country'];
			$city= $_POST['city'];
			$address= $_POST['address'];
			$tel= $_POST['tel'];
			echo MES_Order::update_order_address($id,$country,$city,$contact,$address,$tel);
		}else if($action == 'get_region'){
			echo MES_Order::get_region();
		}else if($action == 'update_cart'){
			$id =  $_GET['id'];
			$num =  $_GET['num'];
			echo MES_Order::update_cart($num,$id);
		}else if($action == 'drop_shopcart'){
			$id =  $_GET['id'];
			echo MES_Order::drop_shopcart($id);
		}

		
        break;

	//ÕË»§Ïà¹ØµÄ²Ù×÷
    case 'account':
		//
		require_once(ROOT_PATH . 'includes/lib_order.php');

		//ÖØ¹¹ºóµÄÓÃ»§Ä£¿é
		require_once(ROOT_PATH . 'lib/user.php');

		//JSONÐòÁÐ»¯
		require_once(ROOT_PATH .'includes/cls_json.php');

		//ÓÃ»§ÓïÑÔ°ü
		require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');
		
		//µÇÂ½
    	if($action == 'login'){
			$username = !empty($_POST['username']) ? json_str_iconv(trim($_POST['username'])) : '';
			$password = !empty($_POST['password']) ? trim($_POST['password']) : '';
			echo MES_User::ajax_login($username,$password);
    	}else if($action == 'check_login'){
			echo MES_User::check_login();
		}else if($action == 'logout'){
			echo MES_User::logout();
		}else if($action == 'signup'){
			
		}
        break;
    default:
        break;
}


?>