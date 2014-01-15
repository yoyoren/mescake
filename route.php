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
    
		require_once(ROOT_PATH . 'lib/order.php');
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
			
			//用户注册 
		}else if($action == 'check_user_exsit'){
			
			//检测一个用户是否存在
			$username = $_GET['username'];
			echo MES_User::check_user_exsit($username);
		}else if($action == 'auto_register'){

			//自动注册其实用的就是那个手机号码
			$username = $_POST['username'];
			echo MES_User::auto_register($username);
		}else if($action=="change_unregister_password"){
			
			//修改未注册但是曾经下单用户的密码
			$password = $_POST['password'];
			echo MES_User::change_unregister_password($password);
		}else if($action=="get_user_order_detail"){
			
			//获得一个用户订单的详情
			$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
			echo MES_User::get_user_order_detail($order_id);
		}else if($action=="get_user_order_list"){
			
			//获得一个用户所有的订单
			echo MES_User::get_user_order_list();
		}else if($action=="del_one_order"){
			
			//删除一个订单
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
			echo MES_User::del_one_order($order_id);
		}else if($action=="order_list"){

			//order list page
			$smarty->display('order_list.dwt');
		}else if($action=="order_detail"){
			
			//order detail page
			$smarty->display('order_detail.dwt');
		}else if($action == 'is_unset_password_user'){
			
			//check user if set password
			echo MES_User::is_unset_password_user();
		}else if($action == 'set_password'){
			
			//check user if set password
			$smarty->display('order_set_password.dwt');
		}


        break;
    default:
        break;
}


?>