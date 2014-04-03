<?php
define('IN_ECS', true);
define('FORK_ID',60);
define('CANDLE_ID',61);
define('NUM_CANDLE_ID',67);
define('CAT_CAKE_ID',68);
define('DOMAIN',$_SERVER['SERVER_NAME']);

if(DOMAIN=='test.mescake.com'){
	define('STATIC_DOMAIN','http://static.n.mescake.com/');
}else{
	define('STATIC_DOMAIN','http://static.mescake.com/');
}

//give user a sid for record
/*Session_start();
$SID;
if(!$_COOKIE['sid']){
	$sessionId = session_id();
	setcookie('sid',$sessionId);
	$SID = $sessionId;
}else{
	$SID = $_COOKIE['sid'];
}
define('SID',$SID);
*/
require (dirname(__FILE__) . '/includes/init.php');
require_once (ROOT_PATH . 'lib/safe.php');
require_once (ROOT_PATH . 'lib/user.php');

define('CLIENT_IP',GET_IP());

if ((DEBUG_MODE & 2) != 2) {
	$smarty -> caching = true;
}

//路由分发的依据
$action = ANTI_SPAM($_GET['action']);
$mod = ANTI_SPAM($_GET['mod']);
$smarty->assign('static_domain',STATIC_DOMAIN);
//过滤请求参数错误的
function error_exit() {
	return json_encode(array('code' => 2, 'msg' => 'param error'));
}

//需要登录的操作
$need_login_action = array('order' => array('get_order_address', 'del_order_address', 'update_order_address', ), 'account' => array('logout', 'get_user_order_detail', 'get_user_order_list', 'del_one_order', 'set_password', 'change_mobile', 'get_user_mobile_number', 'change_password', 'get_users_info', 'change_sex', 'change_real_name', 'do_charge'));
$_action_list = $need_login_action[$mod];

//验证必须登录的操作
if (in_array($action, $_action_list)) {

	if (!MES_User::server_check_login()) {
		echo json_encode(array('code' => RES_NEED_LOGIN));
		die ;
	}
}
switch ($mod) {
	case 'order' :
		require_once (ROOT_PATH . 'lib/order.php');
		if ($action == 'step1') {
			$order_list = MES_Order::get_order_list();
			$smarty -> assign('order_list', $order_list);
			$smarty -> display('shoppingcar_new.dwt');
			return;
		} else if ($action == 'step2') {
			date_default_timezone_set("Etc/GMT-8");

			//每次结算要记录一个ip防止被刷
			$leaving_messsage = $_GET['mes'];
			$current_ip = CLIENT_IP;
			$_key = 'checkout_times_' . $current_ip;
			$checkout_times = 0;

			if ($REDIS_CLIENT -> exists($_key)) {
				$checkout_times = intval($REDIS_CLIENT -> get($_key));
			}

			$_token = GEN_MES_TOKEN();
			$_SESSION['order_token'] = $_token;
			$smarty -> assign('leaving_messsage', $leaving_messsage);
			$smarty -> assign('order_token', $_token);
			$smarty -> assign('checkout_times', $checkout_times);
			
			$time = date('Y-m-d H:i:s',time());
			$smarty->assign('current_time', $time);

			unset($_SESSION['flow_order']['surplus']);
 			unset($_SESSION['flow_order']['bonus']);
 			unset($_SESSION['flow_order']['bonus_id']);
 			unset($_SESSION['flow_order']['bonus_sn']);
			$content = $smarty->fetch('order_new_v2.dwt');

			echo COMPRESS_HTML($content);
			//return;
		} else if ($action == 'update_server_time') {
			date_default_timezone_set("Etc/GMT-8");
			$time = date('Y-m-d H:i:s',time());
			echo json_encode(array('code'=>0,'time'=>$time));
		} else if ($action == 'empty') {
			$smarty -> display('order_empty.dwt');
		} else if ($action == 'get_order_list') {

			//获得这个人目前的订单
			echo MES_Order::get_order_list();
		} else if ($action == 'get_order_address') {

			//获得这人所有的地址信息
			echo MES_Order::get_order_address();
		} else if ($action == 'add_order_address') {

			//增加新的地址信息

			$contact = ANTI_SPAM($_POST['contact'], array('minLength' => 1, 'maxLength' => 100));

			$country = ANTI_SPAM($_POST['country'], array('values' => array(441, 501)));

			$city = ANTI_SPAM($_POST['city'], array('minValue' => 543, 'maxValue' => 573, 'type' => 'number'));

			$address = ANTI_SPAM($_POST['address'], array('minLength' => 1, 'maxLength' => 200));

			$district = ANTI_SPAM($_POST['district'], array('minValue' => 0, 'maxValue' => 20, 'type' => 'number', 'empty' => true));

			$tel = ANTI_SPAM($_POST['tel'], array('minLength' => 5, 'maxLength' => 20));

			echo MES_Order::add_order_address($contact, $country, $city, $address, $tel, $district);
		} else if ($action == 'del_order_address') {

			//删除地址
			$id = ANTI_SPAM($_POST['id'], array('minLength' => 1, 'maxLength' => 12, 'type' => 'number', ));
			echo MES_Order::del_order_address($id);
		} else if ($action == 'update_order_address') {

			//更新地址信息
			$id = ANTI_SPAM($_POST['id'], array('minLength' => 1, 'maxLength' => 12, 'type' => 'number', ));
			$contact = ANTI_SPAM($_POST['contact'], array('minLength' => 1, 'maxLength' => 100));
			$country = ANTI_SPAM($_POST['country'], array('values' => array(441, 501)));

			$city = ANTI_SPAM($_POST['city'], array('minValue' => 543, 'maxValue' => 573, 'type' => 'number'));

			$address = ANTI_SPAM($_POST['address'], array('minLength' => 1, 'maxLength' => 200));

			$district = ANTI_SPAM($_POST['district'], array('minValue' => 0, 'maxValue' => 20, 'type' => 'number', 'empty' => true));

			$tel = ANTI_SPAM($_POST['tel'], array('minLength' => 5, 'maxLength' => 20));

			echo MES_Order::update_order_address($id, $country, $city, $contact, $address, $tel, $district);
		} else if ($action == 'get_region') {

			//获得二级区域的信息
			echo MES_Order::get_region();
		} else if ($action == 'get_district') {

			$city = ANTI_SPAM($_GET['city'], array('minValue' => 543, 'maxValue' => 573, 'type' => 'number'));

			//获得送货的收费区信息
			echo MES_Order::get_district($city);
		} else if ($action == 'update_cart') {

			$id = ANTI_SPAM($_GET['id'], array('minLength' => 1, 'maxLength' => 12, 'type' => 'number', ));

			//最多的订购需要限额
			$num = ANTI_SPAM($_GET['num'], array('minValue' => 0, 'maxValue' => 99, 'type' => 'number', ));

			echo MES_Order::update_cart($num, $id);
		} else if ($action == 'drop_shopcart') {

			//删除购物车
			$id = ANTI_SPAM($_GET['id'], array('minLength' => 1, 'maxLength' => 12, 'type' => 'number', ));

			echo MES_Order::drop_shopcart($id);
		} else if ($action == 'update_fork') {

			//更新餐具
			$id = ANTI_SPAM($_POST['id'], array('minLength' => 1, 'maxLength' => 12, 'type' => 'number', ));

			//餐具限制最高上限10000个
			$num = ANTI_SPAM($_POST['num'], array('minValue' => 0, 'maxValue' => 10000, 'type' => 'number', ));

			echo MES_Order::update_fork($id, $num);
		} else if ($action == 'save_consignee') {
			//save consignee when user select a address!
				date_default_timezone_set("Etc/GMT-8");	
				$address_id = ANTI_SPAM($_POST['address_id'],array(
										'minLength'=>1,
										'maxLength'=>12,
										'type'=>'number',
										'empty'=>true,
							));
				
				$consignee = ANTI_SPAM($_POST['consignee']);
				$country = ANTI_SPAM($_POST['country'],array(
										'values'=>array(441,501)
							));
				$province = ANTI_SPAM($_POST['province'],array(
										'empty'=>true
							));
				
				$city = ANTI_SPAM($_POST['city'],array(
										'minValue'=>543,
										'maxValue'=>573,
										'type'=>'number'
									));
				$district = ANTI_SPAM($_POST['district'],array(
										'minValue'=>0,
										'maxValue'=>20,
										'type'=>'number',
										'empty'=>true
									));
				$email = ANTI_SPAM($_POST['email'],array(
										'empty'=>true
									));
;
				$address = ANTI_SPAM($_POST['address']);
				$zipcode = ANTI_SPAM($_POST['zipcode'],array(
										'empty'=>true
							));

				$tel = ANTI_SPAM($_POST['tel'],array(
										'empty'=>true
								));
				$mobile = ANTI_SPAM($_POST['mobile'],array(
										'empty'=>true
								));
				$sign_building = ANTI_SPAM($_POST['sign_building'],array(
										'empty'=>true
								));
				$message_input= ANTI_SPAM($_POST['message_input'],array(
 						'empty'=>true,
 						'minLength' => 0, 
						'maxLength' => 140, 
 				));

				$best_time = ANTI_SPAM($_POST['bdate']." ".$_POST['hour'].":".$_POST['minute'].":00");
				$inv_content=ANTI_SPAM($_POST['inv_content'],array(
 										'empty'=>true
 				));
 				
 				//如果开了发票 就必须写发票人
				if($inv_content){
 					$inv_payee=ANTI_SPAM($_POST['inv_payee']);
 				}else{
 					$inv_payee=ANTI_SPAM($_POST['inv_payee'],array(
 										'empty'=>true
 					));
 				}
				$data = array(
		            'address_id'    =>$address_id,
		            'consignee'     =>$consignee,
		            'country'       =>$country,
		            'province'      =>$province,
		            'city'          =>$city,
		            'district'      =>$district,
		            'email'         =>$email,
		            'address'       =>$address,
		            'zipcode'       =>$zipcode,
		            'tel'           =>$tel,
		            'mobile'        =>$mobile,
		            'sign_building' =>$sign_building,
		            'best_time'     =>$best_time,
					'message_input' =>$message_input,
					'inv_payee'     =>$inv_payee,
 					'inv_content'   =>$inv_content,
		        );


			//地址id为空可以，但是内容不能为空
			if (empty($address_id) && (empty($city) || empty($address))) {
				echo json_encode(array('code' => RES_PARAM_INVAILD, 'msg' => 'address error', ));
				die ;
			}

			//自己的手机和联系人的手机 至少写一个
			if (empty($tel) && empty($mobile)) {
				echo json_encode(array('code' => RES_PARAM_INVAILD, 'msg' => 'tel empty', ));
				die ;
			}
			
			//如果送货时间小于当前时间5小时 不能送
			$best_timestamp = strtotime($best_time);

			
			if (time() > ($best_timestamp - 5 * 3600)) {
				echo json_encode(array('code' => RES_PARAM_INVAILD, 'msg' => 'time error', ));
				die ;
			}
			$best_time_arr = explode(" ",$best_time);
			$best_time_date = strtotime($best_time_arr[0]);


			$best_time_arr = explode(":",$best_time_arr[1]);
			$best_time_hour = $best_time_arr[0];
			$best_time_minute = $best_time_arr[1];
			
			
			$current_time = date('Y-m-d H:i:s',time());

			$current_time = explode(" ",$current_time);

			$current_time_date = strtotime($current_time[0]);
			$current_time = explode(":",$current_time[1]);

			$current_time_hour = $current_time[0];
			$current_time_minute = $current_time[1];
			

			//相同日期 大于17点 无论如何都是不能下当天的单的。
			if($best_time_date == $current_time_date){
			   if($current_time_hour>17){
					echo json_encode(array(
							'code'=>RES_PARAM_INVAILD,
							'msg'=>'time error',
					));
					die;
			   }
			   if($current_time_hour<10&&$best_time_hour<14){
					echo json_encode(array(
							'code'=>RES_PARAM_INVAILD,
							'msg'=>'time error',
					));
					die;
			   }
			}

			//如果选择的日期是第二天
			if($best_time_date - $current_time_date==86400){
				//22点客服下班了 所以必须选择14点以后的单
				if($current_time_hour>=22&&$best_time_hour<14){
					echo json_encode(array(
							'code'=>RES_PARAM_INVAILD,
							'msg'=>'time error',
					));
					die;
				}
			}
		
			//发票内容不为空，抬头则不能为空
 			if($inv_content&&empty($inv_payee)){
 				echo json_encode(array(
 						'code'=>RES_PARAM_INVAILD,
 						'msg'=>'invoice error',
 				));
 				die;
 			}

			echo MES_Order::save_consignee($data);
		} else if ($action == 'checkout') {
			$current_ip = CLIENT_IP;
			$_key = 'checkout_times_' . $current_ip;
			$checkout_times = 0;
			if ($REDIS_CLIENT -> exists($_key)) {
				$checkout_times = intval($REDIS_CLIENT -> get($_key));
			}

			//大于三次的提交 才验证
	
			if ($checkout_times > 3) {
				error_reporting(0);
				$vaild_code = ANTI_SPAM($_POST['vaild_code']);
				include_once ('includes/cls_captcha.php');
				$validator = new captcha();
				if (!$validator -> check_word($vaild_code)) {
					echo json_encode(array(
					 'code' => RES_CAPTACH_INVAILD,
					 'msg' => 'vaild error', 
					));
					die ;
				}
			}
			//checkout and cal total price
			$card_message = ANTI_SPAM($_POST['card_message'],array('empty'=>true));
			if (!$card_message) {
				$card_message = '';
			}

			if($card_message!=""){
				$card_message_arr = explode("|", $card_message);
				for ($i = 0; $i < count($card_message_arr); $i++) {
					
					//可以不写 但是写不能超过长度
					ANTI_SPAM($card_message_arr[$i], array(
						'empty'=>true,
						'minLength' => 0, 
						'maxLength' => 10, 
					));
				}
			}
			//每次结算要记录一个ip防止被刷
	
			$_key = 'checkout_times_' . $current_ip;
			$_value;
			$expire_time = 24 * 3600;
			if ($REDIS_CLIENT -> exists($_key)) {
				$_value = intval($REDIS_CLIENT -> get($_key));
				$_value += 1;
				$REDIS_CLIENT -> setex($_key, $expire_time, $_value);
			} else {
				$REDIS_CLIENT -> setex($_key, $expire_time, 1);
			}

			echo MES_Order::checkout($card_message);
		} else if ($action == 'done') {
			$token = ANTI_SPAM($_POST['token']);
			$pay_id = ANTI_SPAM($_POST['pay_id']);

			echo MES_Order::done($token, $pay_id);
		} else if ($action == 'add_to_cart') {
			//add an cake or fork to your cart

			//goods是一个json string
			$goods = strip_tags(urldecode($_POST['goods']));
			$goods = json_str_iconv($goods);
			
			$parent_id = ANTI_SPAM($_POST['parent_id'],array('empty'=>true));
			$goods_id = ANTI_SPAM($_POST['goods_id']);
			$goods_attr = ANTI_SPAM($_POST['goods_attr'],array('empty'=>true));
			if (!empty($goods_id) && empty($goods)) {
				if (!is_numeric($goods_id) || intval($goods_id) <= 0) {
					ecs_header("Location:./\n");
				}
				//$goods_id = intval($goods_id);
				die ;
			}

			//67为数字蜡烛 必须要符合添加的规范
			if($goods_id ==NUM_CANDLE_ID){
				if($goods_attr<1||$goods_attr>99||!is_numeric($goods_attr)){
					echo json_encode(array(
						'code'=>RES_FAIL,
						'msg'=>'invaild candle number!',
					));
					die;
				}
			}
		
			echo MES_Order::add_to_cart($goods, ANTI_SPAM($goods_id),$parent_id,$goods_attr);
		} else if ($action == 'shipping_fee_cal') {

			//计算配送的费用
			$city = ANTI_SPAM($_GET['city'], array('minValue' => 543, 'maxValue' => 573, 'type' => 'number'));

			$district = ANTI_SPAM($_GET['district'], array('minValue' => 0, 'maxValue' => 20, 'type' => 'number', 'empty' => true));

			echo MES_Order::shipping_fee_cal($city, $district);
		} else if ($action == 'if_address_need_fee') {

			//计算配一个地址id是否需要加收配送费
			$address_id = ANTI_SPAM($_GET['address_id'], array('minLength' => 1, 'maxLength' => 12, 'type' => 'number', ));
			echo MES_Order::if_address_need_fee($address_id);
		} else if ($action == 'get_total_price_in_cart') {

			//计算购物车里面的商品总价
			echo MES_Order::get_total_price_in_cart();
		}  else if ($action == 'done_page') {
			$smarty->display('done_v2.dwt');
		} else {
			header("Location: 404.html");
		}

		break;

	//账户相关的请求
	case 'account' :
		//
		require_once (ROOT_PATH . 'includes/lib_order.php');

		//JSON
		require_once (ROOT_PATH . 'includes/cls_json.php');

		//lang files
		require_once (ROOT_PATH . 'languages/' . $_CFG['lang'] . '/user.php');

		//login using ajax
		if ($action == 'login') {
			$username = !empty($_POST['username']) ? json_str_iconv(trim($_POST['username'])) : '';
			$password = !empty($_POST['password']) ? trim($_POST['password']) : '';
			$username = ANTI_SPAM($username);
			$password = ANTI_SPAM($password);
			echo MES_User::ajax_login($username,$password);
		} else if ($action == 'check_login') {

			//检测用户是否已经登录
			echo MES_User::check_login();
		} else if ($action == 'logout') {

			//登出操作
			echo MES_User::logout();
		} else if ($action == 'signup_page') {
			//用户注册page
			 MES_User::signup_page();
			
		} else if ($action == 'signup_vaild_code') {
			 
			 //用户注册操作 
			 $mobile = ANTI_SPAM($_POST['mobile']);
			 echo MES_User::signup_vaild_code($mobile);
		} else if ($action == 'signup') {
			 
			 //用户注册操作 
			 $username = ANTI_SPAM($_POST['username']);
			 $password = ANTI_SPAM($_POST['password']);

			 //手机验证码
			 $vaild_code = ANTI_SPAM($_POST['vaild_code']);
			 echo MES_User::signup($username,$password,$vaild_code);
			
		} else if ($action == 'check_user_exsit') {

			//检测一个用户是否存在
			$username = ANTI_SPAM($_GET['username'], array('minLength' => 1, 'maxLength' => 20, ));
			echo MES_User::check_user_exsit($username);
		} else if ($action == 'auto_register') {

			//自动注册其实用的就是那个手机号码
			$username = ANTI_SPAM($_POST['username'], array(
				'minLength' => 1, 
				'maxLength' => 20, 
			));

			echo MES_User::auto_register($username);
		} else if ($action == "change_unregister_password") {

			//修改未注册但是曾经下单用户的密码
			$password = ANTI_SPAM($_POST['password'], array('minLength' => 6, 'maxLength' => 30, ));
			echo MES_User::change_unregister_password($password);
		} else if ($action == "get_user_order_detail") {

			//获得一个用户订单的详情
			$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
			$order_id = ANTI_SPAM($order_id, array('minLength' => 1, 'maxLength' => 12, 'type' => 'number'));
			echo MES_User::get_user_order_detail($order_id);
		} else if ($action == "get_user_order_list") {

			//获得一个用户所有的订单
			echo MES_User::get_user_order_list();
		} else if ($action == "del_one_order") {

			//删除一个订单
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
			$order_id = ANTI_SPAM($order_id, array('minLength' => 1, 'maxLength' => 12, 'type' => 'number'));
			echo MES_User::del_one_order($order_id);
		} else if ($action == "order_list") {

			//order list page
			$smarty -> display('order_list.dwt');
		} else if ($action == "order_detail") {

			//order detail page
			$smarty -> display('order_detail.dwt');
		} else if ($action == 'is_unset_password_user') {

			//check user if set password
			echo MES_User::is_unset_password_user();
		} else if ($action == 'set_password') {

			//check user if set password
			if ($_SESSION['user_auto_register'] == '11') {
				$smarty -> display('order_set_password.dwt');
			} else {
				echo 0;
			}
		} else if ($action == 'query_order') {

			//query your order by moblie
			$smarty -> display('order_query.dwt');
		} else if ($action == 'get_password_moblie') {

			//get password by your mobile
			$moblie = ANTI_SPAM($_POST['moblie']);

			echo MES_User::get_password_moblie($moblie);
		} else if ($action == 'query_login') {

			//login by moblie query
			$username = ANTI_SPAM($_POST['username']);
			$password = ANTI_SPAM($_POST['password']);
			echo MES_User::query_login($username, $password);
		} else if ($action == 'get_auto_register_mobile') {

			//获得自动注册的手机号码
			echo MES_User::get_auto_register_mobile();
		} else if ($action == 'account') {

			//个人账户管理页面
			$smarty -> display('account.dwt');
		} else if ($action == 'change_mobile_get_code') {

			//修改手机的验证码
			$mobile = ANTI_SPAM($_POST['mobile'], array('minLength' => 6, 'maxLength' => 30));
			echo MES_User::change_mobile_get_code($mobile);
		} else if ($action == 'change_mobile') {

			//chang your tel
			$mobile = ANTI_SPAM($_POST['mobile']);
			$code = ANTI_SPAM($_POST['code']);
			echo MES_User::change_mobile($mobile, $code);
		} else if ($action == 'get_user_mobile_number') {

			//get tel only
			echo MES_User::get_user_mobile_number();
		} else if ($action == 'change_password') {

			//change your password
			$old = ANTI_SPAM($_POST['old']);
			$new = ANTI_SPAM($_POST['new']);
			echo MES_User::change_password($old, $new);
		} else if ($action == 'forget_password_step1') {

			//change your password when your forget it
			$mobile = ANTI_SPAM($_POST['mobile']);
			$code = ANTI_SPAM($_POST['code']);
			echo MES_User::forget_password_step1($mobile, $code);
		} else if ($action == 'forget_password_step2') {

			//change your password when your forget it
			$mobile = ANTI_SPAM($_POST['mobile']);
			$password = ANTI_SPAM($_POST['password']);
			echo MES_User::forget_password_step2($mobile, $password);

		} else if ($action == 'forget_password_page') {
			$smarty -> display('change_password.dwt');
		} else if ($action == 'get_forget_password_code') {
			//change your password when your forget it
			$mobile = ANTI_SPAM($_POST['mobile']);
			echo MES_User::get_forget_password_code($mobile);
		} else if ($action == 'get_users_info') {

			//get name,tel and sex
			echo MES_User::get_users_info();
		} else if ($action == 'change_sex') {

			//change your sex
			$sex = ANTI_SPAM($_POST['sex']);
			echo MES_User::change_sex($sex);
		} else if ($action == 'change_real_name') {

			//change your real name
			$name = ANTI_SPAM($_POST['name']);
			echo MES_User::change_real_name($name);
		} else if ($action == 'get_order_count_by_sid') {
			echo MES_User::get_order_count_by_sid();
		} else if ($action == 'charge_vaild') {
			
			//获得充值验证码
			$mobile =  ANTI_SPAM($_POST['mobile']);
			echo MES_User::charge_vaild($mobile);
		} else if ($action == 'do_charge') {
			//充值操作
			$mobile =  ANTI_SPAM($_POST['mobile']);
			$card_num =  ANTI_SPAM($_POST['card_num']);
			$card_pwd =  ANTI_SPAM($_POST['card_pwd']);
			$vaild_code =  ANTI_SPAM($_POST['vaild_code']);

			echo MES_User::do_charge($card_num,$card_pwd,$mobile,$vaild_code);
		} else {
			header("Location: 404.html");
		}

		break;
	//账户相关的请求

	case 'huodong' :
		require_once (ROOT_PATH . 'lib/lover.php');
		//情人节活动
		if ($action == 'add') {
			$name = ANTI_SPAM($_POST['name']);
			$my_weibo = ANTI_SPAM($_POST['my_weibo']);
			$mobile = ANTI_SPAM($_POST['mobile']);
			$his_weibo = ANTI_SPAM($_POST['his_weibo']);
			$address = ANTI_SPAM($_POST['address']);
			$comment = ANTI_SPAM($_POST['comment']);
			echo MES_Lover::add($name, $my_weibo, $mobile, $his_weibo, $address, $comment);
		} else if ($action == 'get_all') {
			//情人节活动 已经下线
			die;
			echo MES_Lover::get_all();
		} else if ($action == 'page') {
			$smarty -> display('huodongpage.dwt');
		} else if ($action == 'admin') {
			//情人节活动 已经下线
			die;
			$smarty -> display('huodongadmin.dwt');
		} else if ($action == 'upload') {
			//猫爪活动 图片上传
			$ret = array();
			$file = $_FILES['images'];
			$images = $_POST['images'];
			$size = $file['size'];
			$type = $file['type'];
			if($size>1*1024*1024){
				 echo '<script>window.ret="'.json_encode(array('code'=>1,'msg'=>'文件体积过大,不能大于1MB')).'"</script>';
				 return;
			}
			
			
			if($type!='image/jpeg'&&$type!='image/jpg'&&$type!='image/png'&&$type!='image/gif'){
				 echo "<script>window.ret='".json_encode(array('code'=>2,'msg'=>'文件格式不支持'))."'</script>";
				 return;
			}

			$filename = date("YmdHis");
			$url = 'uploadimage/' . $filename . '.jpg';  
			$upfile = ROOT_PATH.$url;
			if(is_uploaded_file($file['tmp_name'])){  
			   if(!move_uploaded_file($file['tmp_name'], $upfile)){  
					 '<script>window.ret="'.json_encode(array('code'=>3,'msg'=>'server error')).'"</script>'; 
					 return;
				}  
				
			}
			echo "<script>window.ret=".json_encode(array('code'=>0,'msg'=>'success','url'=>$url))."</script>";
		} else if ($action == 'cat_page') {

			//weibo的认证
			include_once( ROOT_PATH .'weibo/config.php' );
			include_once( ROOT_PATH .'weibo/saetv2.ex.class.php' );
			$_AUTH=$_GET['auth'];
			session_start();
			if($_AUTH == 'true'||$_SESSION['weibotoken']['access_token']){
				$auth = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['weibotoken']['access_token'] );
				//$auth->follow_by_id('3477174474');
				$uid_get = $auth->get_uid();
				$uid = $uid_get['uid'];
				$user_message = $auth->show_user_by_id($uid);
				$smarty->assign('auth_url','#');
				$smarty->assign('uid',$uid);
				$smarty->assign('showupload','true');
			}else{
				$auth = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
				$auth_url = $auth->getAuthorizeURL( WB_CALLBACK_URL);
				$smarty->assign('auth_url',$auth_url);
				$smarty->assign('showupload','false');
			}
			
			$smarty->display('cat_page.dwt');
		} else if ($action == 'weibo_upload') {

			//weibo的图片上传
			$url = ANTI_SPAM($_POST['imageurl']);

			include_once( ROOT_PATH .'weibo/config.php' );
			include_once( ROOT_PATH .'weibo/saetv2.ex.class.php' );
			
			session_start();
			if($_SESSION['weibotoken']['access_token']){
				include_once( ROOT_PATH .'lib/cat_activity.php' );
				$weibo_url = 'http://huodong.mescake.com/route.php?mod=huodong&action=cat_page';
				$weibo_text = '#每实猫爪大晒#我家的猫爪，快来给我点个赞吧！'.$weibo_url;
				$auth = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['weibotoken']['access_token'] );
				$auth->upload($weibo_text,$url);
				$uid_get = $auth->get_uid();
				$uid = $uid_get['uid'];
				$user_message = $auth->show_user_by_id($uid);
				$weibo_name = $user_message['screen_name'];
				MES_Cat_activity::add($weibo_name,$url);
				echo json_encode(array('code'=>0,'msg'=>'success'));
			}else{
				echo json_encode(array('code'=>RES_FAIL,'msg'=>'fail'));
			}
		} else if ($action == 'weibo_upload_test') {
			//weibo猫爪活动 测试上传接口
			include_once( ROOT_PATH .'lib/cat_activity.php' );
			//weibo的图片上传
			$url = ANTI_SPAM($_POST['imageurl']);
			$weibo_name = "test";
			MES_Cat_activity::add($weibo_name,$url);

			echo json_encode(array('code'=>0,'msg'=>'success'));
		} else if ($action == 'cat_admin') {
			$smarty->display('cat_admin.dwt');
		} else if ($action == 'cat_gift') {
			$smarty->display('cat_gift.dwt');
		} else if ($action == 'cat_detail') {
			
			//大图页面
			include_once( ROOT_PATH .'lib/cat_activity.php' );
			$id = ANTI_SPAM($_GET['id']);
			$data = MES_Cat_activity::cat_get_by_id($id);
			$smarty->assign('data',$data);
			$smarty->display('cat_detail.dwt');
		} else if ($action == 'like') {
			//赞操作
			include_once( ROOT_PATH .'lib/cat_activity.php' );
			$id = ANTI_SPAM($_POST['id']);
			MES_Cat_activity::like($id);
		} else if ($action == 'cat_get_all') {
			
			//获得所有活动数据
			include_once( ROOT_PATH .'lib/cat_activity.php' );
			echo MES_Cat_activity::get_all();
		} else if ($action == 'cat_get_by_status') {
			
			//根据后台状态来获得数据
			include_once( ROOT_PATH .'lib/cat_activity.php' );

			$status = $_GET['status'];
			if($status!=1&&$status!=2&&$status!=0){
				die;
			}
			echo MES_Cat_activity::cat_get_by_status($status);

		}  else if ($action == 'cat_change_status') {

			include_once( ROOT_PATH .'lib/cat_activity.php' );
			$status = ANTI_SPAM($_POST['status']);
			$id = ANTI_SPAM($_POST['id']);
			echo MES_Cat_activity::cat_change_status($id,$status);
		} else if ($action == 'cat_like') {

			include_once( ROOT_PATH .'lib/cat_activity.php' );
			$id = ANTI_SPAM($_POST['id']);
			$has_liked = GET_REDIS(CLIENT_IP.$id,'cat_like');
			if($has_liked){
				echo json_encode(array('code'=>2));
				return;
			}else{
				SET_REDIS(CLIENT_IP.$id,'1','cat_like');
			}
			echo MES_Cat_activity::cat_like($id,$status);
		} else {
			header("Location: 404.html");
		}
		break;

	case 'test' :
	    //anti spam测试接口
		$str = ANTI_SPAM($_GET['str']);
		PARAM_VAILD($str, array('max' => 10, 'type' => 'number', 'values' => array(1, 2, 4)));
		break;
	case 'token' :
		//生成随机token测试接口
		echo GEN_MES_TOKEN();
		break;
	case 'goods' :
		require_once (ROOT_PATH . 'lib/goods.php');
		if ($action == 'get_price_by_weight') {
			$goods_id = ANTI_SPAM($_REQUEST['id']);
			$attr_id = isset($_REQUEST['attr']) ? explode(',', $_REQUEST['attr']) : array();
			$number = (isset($_REQUEST['number'])) ? intval($_REQUEST['number']) : 1;

			echo MES_Goods::get_price_by_weight($goods_id, $attr_id, $number);
		}else if ($action == 'goods_detail_page') {
			$goods_id = ANTI_SPAM($_GET['id']);
			MES_Goods::goods_detail_page($goods_id);
		}
		break;
	case 'page':
			if ($action == 'index') {
				//新版的首页
				function get_index_tpl(){
					global $smarty;
					require_once (ROOT_PATH . 'lib/catogary.php');
					$smarty -> assign('slider',$CAKE_SLIDER);
					$smarty -> assign('cato',$CAKE_CATO);
					return $smarty;
				}
				//为首页生成一个静态缓存页面
			    echo PAGE_CACHER('index','page','index_v2.dwt','get_index_tpl',true);
			}else if ($action == 'cato') {
				//蛋糕分类的页面
				$cato_id = ANTI_SPAM($_GET['id']);
				function get_cato_tpl(){
					global $smarty;
					global $cato_id;
		
					require_once (ROOT_PATH . 'lib/catogary.php');
					require_once (ROOT_PATH . 'lib/goods.php');
					$list = array();
					for($i=0;$i<count($CAKE_CATO[$cato_id]['cato']);$i++){
						$_cur = $CAKE_CATO[$cato_id]['cato'][$i];
						$_list = MES_Goods::get_goods_by_catogary($_cur['data']);
						array_push($list, array('data'=>$_list,'title'=>$_cur['title'],'name'=>$_cur['name']));
					}
		
					$smarty -> assign('data',$CAKE_CATO[$cato_id]);
					$smarty -> assign('list',$list);
					return $smarty;
				}
				//为首页生成一个静态缓存页面
			    echo PAGE_CACHER('catogary','page','catogary_v2.dwt','get_cato_tpl');
			}
		break;
	default :
		header("Location: 404.html");
		break;
}
?>