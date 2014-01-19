<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

if ((DEBUG_MODE & 2) != 2){
    $smarty->caching = true;
}
require_once(ROOT_PATH . 'lib/safe.php');

//路由分发的依据
$action = ANTI_SPAM($_GET['action']);
$mod = ANTI_SPAM($_GET['mod']);

switch ($mod) {
    case 'order':
    
		require_once(ROOT_PATH . 'lib/order.php');
        if($action == 'step1'){
			$smarty->display('shoppingcar_new.dwt');
			return;
		}else if($action == 'step2'){
			
			$smarty->display('order_new.dwt');
			return;
		}else if($action == 'empty'){
			$smarty->display('order_empty.dwt');
		}else if($action == 'get_order_list'){

			//获得这个人目前的订单
			echo MES_Order::get_order_list();
		}else if($action == 'get_order_address'){

			//获得这人所有的地址信息
			echo MES_Order::get_order_address();
		}else if($action == 'add_order_address'){

			//增加新的地址信息
			$contact= ANTI_SPAM($_POST['contact']);
			$country= ANTI_SPAM($_POST['country']);
			$city= ANTI_SPAM($_POST['city']);
			$address= ANTI_SPAM($_POST['address']);
			$district= ANTI_SPAM($_POST['district']);
			$tel= ANTI_SPAM($_POST['tel']);
			echo MES_Order::add_order_address($contact,$country,$city,$address,$tel,$district);
		}else if($action == 'del_order_address'){

			//删除地址
			$id = ANTI_SPAM($_POST['id']);
			echo MES_Order::del_order_address($id);
		}else if($action == 'update_order_address'){

			//更新地址信息
			$id = ANTI_SPAM($_POST['id']);
			$contact= ANTI_SPAM($_POST['contact']);
			$country= ANTI_SPAM($_POST['country']);
			$city= ANTI_SPAM($_POST['city']);
			$address= ANTI_SPAM($_POST['address']);
			$district= ANTI_SPAM($_POST['district']);
			$tel= ANTI_SPAM($_POST['tel']);
			echo MES_Order::update_order_address($id,$country,$city,$contact,$address,$tel,$district);
		}else if($action == 'get_region'){
			
			//获得二级区域的信息
			echo MES_Order::get_region();
		}else if($action == 'get_district'){

			$city = ANTI_SPAM($_GET['city']);
			//获得送货的收费区信息
			echo MES_Order::get_district($city);
		}else if($action == 'update_cart'){
			
			$id = ANTI_SPAM($_GET['id']);
			$num = ANTI_SPAM($_GET['num']);
			echo MES_Order::update_cart($num,$id);
		}else if($action == 'drop_shopcart'){
			
			//删除购物车
			$id = ANTI_SPAM($_GET['id']);
			echo MES_Order::drop_shopcart($id);
		}else if($action == 'update_fork'){

			//更新餐具
			$id =  ANTI_SPAM($_POST['id']);
			$num =  ANTI_SPAM($_POST['num']);
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
			$card_message =  ANTI_SPAM($_POST['card_message']);
			if(!$card_message){
				$card_message = '';
			}else{
				$card_message = explode("|",$card_message);
			}
			echo MES_Order::checkout($card_message);
		}else if($action == 'add_to_cart'){
			//add an cake or fork to your cart
			
			$_POST['goods']= strip_tags(urldecode($_POST['goods']));
   			$_POST['goods']= json_str_iconv($_POST['goods']);
			$goods = $_POST['goods'];
			if (!empty($_REQUEST['goods_id']) && empty($goods)){
		        if (!is_numeric($_REQUEST['goods_id']) || intval($_REQUEST['goods_id']) <= 0){
		            ecs_header("Location:./\n");
		        }
		        $goods_id = intval($_REQUEST['goods_id']);
		        exit;
		    }

			echo MES_Order::add_to_cart($goods,ANTI_SPAM($_REQUEST['goods_id']));
		}else if($action == 'shipping_fee_cal'){

			//计算配送的费用
			$city = ANTI_SPAM($_GET['city']);
			$district = ANTI_SPAM($_GET['district']);
			echo MES_Order::shipping_fee_cal($city,$district);
		}

		
        break;

	//账户相关的请求
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
			echo MES_User::ajax_login(ANTI_SPAM($username),ANTI_SPAM($password));
    	}else if($action == 'check_login'){
    		
    		//检测用户是否已经登录
			echo MES_User::check_login();
		}else if($action == 'logout'){

			//登出操作
			echo MES_User::logout();
		}else if($action == 'signup'){
			
			//用户注册 
		}else if($action == 'check_user_exsit'){
			
			//检测一个用户是否存在
			$username = ANTI_SPAM($_GET['username']);
			echo MES_User::check_user_exsit($username);
		}else if($action == 'auto_register'){

			//自动注册其实用的就是那个手机号码
			$username = ANTI_SPAM($_POST['username']);
			echo MES_User::auto_register($username);
		}else if($action=="change_unregister_password"){
			
			//修改未注册但是曾经下单用户的密码
			$password = ANTI_SPAM($_POST['password']);
			echo MES_User::change_unregister_password($password);
		}else if($action=="get_user_order_detail"){
			
			//获得一个用户订单的详情
			$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
			echo MES_User::get_user_order_detail(ANTI_SPAM($order_id));
		}else if($action=="get_user_order_list"){
			
			//获得一个用户所有的订单
			echo MES_User::get_user_order_list();
		}else if($action=="del_one_order"){
			
			//删除一个订单
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
			echo MES_User::del_one_order(ANTI_SPAM($order_id));
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
			if($_SESSION['user_auto_register'] == '11'){
				$smarty->display('order_set_password.dwt');
			}else{
				echo 0;
			}
		}else if($action == 'query_order'){
			
			//query your order by moblie
			$smarty->display('order_query.dwt');
		}else if($action == 'get_password_moblie'){
			
			//get password by your mobile
			$moblie = ANTI_SPAM($_POST['moblie']);
			
			echo MES_User::get_password_moblie($moblie);
		}else if($action == 'query_login'){
	
			//login by moblie query
			$username = ANTI_SPAM($_POST['username']);
			$password = ANTI_SPAM($_POST['password']);
			echo MES_User::query_login($username,$password);
		}else if($action == 'get_auto_register_mobile'){

			//获得自动注册的手机号码
			echo MES_User::get_auto_register_mobile();
		}else if($action == 'account'){

			//个人账户管理页面
			$smarty->display('account.dwt');
		}else if($action == 'change_mobile_get_code'){

			//修改手机的验证码
			$mobile = ANTI_SPAM($_POST['mobile']);
			echo MES_User::change_mobile_get_code($mobile);
		}else if($action == 'change_mobile'){

			//chang your tel 
			$mobile = ANTI_SPAM($_POST['mobile']);
			$code = ANTI_SPAM($_POST['code']);
			echo MES_User::change_mobile($mobile,$code);
		}else if($action == 'get_user_mobile_number'){

			//get tel only
			echo MES_User::get_user_mobile_number();
		}else if($action == 'change_password'){

			//change your password
			$old = ANTI_SPAM($_POST['old']);
			$new = ANTI_SPAM($_POST['new']);
			echo MES_User::change_password($old,$new);
		}else if($action == 'get_users_info'){

			//get name,tel and sex
			echo MES_User::get_users_info();
		}else if($action == 'change_sex'){

			//change your sex
			$sex = ANTI_SPAM($_POST['sex']);
			echo MES_User::change_sex($sex);
		}else if($action == 'change_real_name'){

			//change your real name
			$name = ANTI_SPAM($_POST['name']);
			echo MES_User::change_real_name($name);
		}

        break;
    default:
        break;
}


?>