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
		}else if($action == 'update_fork'){
			$id =  $_POST['id'];
			$num =  $_POST['num'];
			echo MES_Order::update_fork($id,$num);
		}else if($action == 'save_consignee'){
			//save consignee when user select a address!
			$consignee = array(
		            'address_id'    => empty($_POST['address_id']) ? 0  :   intval($_POST['address_id']),
		            'consignee'     => empty($_POST['consignee'])  ? '' :   compile_str(trim($_POST['consignee'])),
		            'country'       => empty($_POST['country'])    ? '' :   intval($_POST['country']),
		            'province'      => empty($_POST['province'])   ? 502 :  intval($_POST['province']),
		            'city'          => empty($_POST['city'])       ? '' :   intval($_POST['city']),
		            'district'      => empty($_POST['district'])   ? '' :   intval($_POST['district']),
		            'email'         => empty($_POST['email'])      ? '' :   compile_str($_POST['email']),
		            'address'       => empty($_POST['address'])    ? '' :   compile_str($_POST['address']),
		            'zipcode'       => empty($_POST['zipcode'])    ? '' :   compile_str(make_semiangle(trim($_POST['zipcode']))),
		            'tel'           => empty($_POST['tel'])        ? '' :   compile_str(make_semiangle(trim($_POST['tel']))),
		            'mobile'        => empty($_POST['mobile'])     ? '' :   compile_str(make_semiangle(trim($_POST['mobile']))),
		            'sign_building' => empty($_POST['sign_building']) ? '' :compile_str($_POST['sign_building']),
		            'best_time'     => $_POST['bdate']." ".$_POST['hour'].":".$_POST['minute'].":00",
					
		        );
			echo MES_Order::save_consignee($consignee);
		}else if($action == 'checkout'){
			//checkout and cal total price
			echo MES_Order::checkout();
		}else if($action == 'add_to_cart'){
			//add an cake or fork to your cart
			
			 $_POST['goods']=strip_tags(urldecode($_POST['goods']));
   			 $_POST['goods'] = json_str_iconv($_POST['goods']);
			$goods = $_POST['goods'];
			if (!empty($_REQUEST['goods_id']) && empty($goods)){
		        if (!is_numeric($_REQUEST['goods_id']) || intval($_REQUEST['goods_id']) <= 0){
		            ecs_header("Location:./\n");
		        }
		        $goods_id = intval($_REQUEST['goods_id']);
		        exit;
		    }

			echo MES_Order::add_to_cart($goods,$_REQUEST['goods_id']);
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