<?php


class MES_User{
	public static function ajax_login($username,$password){
		global $db;
		global $_LANG;
		global $user;// = user_info($_SESSION['user_id']);
		
		$json = new JSON;
		$result   = array('code' => 0, 'content' => 'login successs');
		$username=$db->getOne("select user_name from". $GLOBALS['ecs']->table("users")."where email='$username' or mobile_phone='$username'");

		if(empty($username)){
			$result['code']   = 1;
			$result['content'] = $_LANG['login_failure'];
			return $json->encode($result);
		}

		if ($user->login11($username, $password)){
			$ucdata = empty($user->ucdata)? "" : $user->ucdata;
			$result['ucdata'] = $ucdata;
			$_SESSION['usermsg']= get_user_info();
		}else{
			$_SESSION['login_fail']++;
			$result['error']   = 1;
			$result['code']   = 1;
			$result['content'] = $_LANG['login_failure'];
		}
		return $json->encode($result);
	}

	public static function get_user_info($user_id){
		global $db;
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('users') .
				" WHERE user_id = '$user_id'";
		$user = $db->getRow($sql);

		unset($user['question']);
		unset($user['answer']);

		/* 格式化帐户余额 */
		if ($user){
			$user['formated_user_money'] = price_format($user['user_money'], false);
			$user['formated_frozen_money'] = price_format($user['frozen_money'], false);
		}

		return $user;
	}

	public static function logout(){
		global $user;
		$user->logout();
		return json_encode(array('code'=>'0','msg'=>'success'));
	}

	public static function check_login(){
		$token = $_COOKIE['serviceToken'];
		$res = false;
		if($token&&$_SESSION['serviceToken'] == $token){
			$res = true;
		}
		return json_encode(array('code'=>'0','msg'=>'success','res'=>$res));
	}

	//服务器端用于检查是否登录的方法
	public static function server_check_login(){
		$token = $_COOKIE['serviceToken'];
		$res = false;
		if($token&&$_SESSION['serviceToken'] == $token){
			$res = true;
		}
		return $res;
	}

	//检查一个用户是否存在
	public static function check_user_exsit($username){
		//global $user;
		global $db;
		$res = array('code'=>0);
		$username=$db->getOne("select user_name from". $GLOBALS['ecs']->table("users")."where email='$username' or mobile_phone='$username'");
		if($username==''){
		   $res['exsit'] = false;
		}else{
		   $res['exsit'] = true;
		}
		return json_encode($res);
	}

	//帮用户自动注册
	public static function auto_register($mobile){
		global $db;
		include_once(ROOT_PATH . 'includes/lib_passport.php');
        $msg='';
        $password = '123456';
		$f_email= 'W' . $mobile . "@fal.com";

		$email    = $f_email;
		$username = $f_email;
        $other['mobile_phone'] = $mobile;
		$other['rea_name'] = '';

        $back_act = '';
        if (register($username, $password, $email,$other) !== false){
        	//user type 写成11 自动注册
			$db->query("update ecs_users set user_type=11 where user_name='$username'");//用户类型设置bisc
			$_SESSION['user_msg']=$username;
		}

		//标记一下这个用户是这次自动注册的
		$_SESSION['user_auto_register'] = '11';
		return json_encode(array('code' =>'0','msg'=>$msg));
	}

	//帮没有设置密码的用户
	//帮没有设置密码的用户自动设置密码
	public static function change_unregister_password($password){
		global $db;
		$password = md5($password);
		$mobile = $_SESSION['user_auto_register_moblie'];
		$username = 'W' . $mobile . "@fal.com";
		if(strlen($password)<7){
			return json_encode(array('code'=>'1','msg'=>'fail'));
		}
		if($_SESSION['user_auto_register'] == '11'){
			$db->query("update ecs_users set user_type=0,password='$password' where user_name='$username'");
			unset($_SESSION['user_auto_register']);
			unset($_SESSION['user_auto_register_moblie']);
			return json_encode(array('code'=>'0','msg'=>'success'));
		}else{
			return json_encode(array('code'=>'2','msg'=>'fail'));
		}
		
	}
	public static function is_unset_password_user(){
		if($_SESSION['user_auto_register'] == '11'){
			return json_encode(array('code'=>'0','msg'=>true));
		}else{
			return json_encode(array('code'=>'0','msg'=>false));
		}
	}

	public static function get_user_order_detail($order_id){
		include_once(ROOT_PATH . 'includes/lib_transaction.php');
	    include_once(ROOT_PATH . 'includes/lib_payment.php');
	    include_once(ROOT_PATH . 'includes/lib_order.php');
	    include_once(ROOT_PATH . 'includes/lib_clips.php');

	    $user_id = $_SESSION['user_id'];
	    $res = array('code' =>'0');
	    /* 订单详情 */
	    $order = get_order_detail($order_id, $user_id);

	    if ($order === false){
	    	$res['code'] = 1;
	        return json_encode($res);
	    }

	    /* 订单商品 */
	    $goods_list = order_goods($order_id);
	    foreach ($goods_list AS $key => $value){
	        $goods_list[$key]['market_price'] = price_format($value['market_price'], false);
	        $goods_list[$key]['goods_price']  = price_format($value['goods_price'], false);
	        $goods_list[$key]['subtotal']     = price_format($value['subtotal'], false);
	    }

	     /* 设置能否修改使用余额数 */
	    if ($order['order_amount'] > 0)
	    {
	        if ($order['order_status'] == OS_UNCONFIRMED || $order['order_status'] == OS_CONFIRMED)
	        {
	            $user = user_info($order['user_id']);
	            if ($user['user_money'] + $user['credit_line'] > 0){
	                //$smarty->assign('allow_edit_surplus', 1);
	                //$smarty->assign('max_surplus', sprintf($_LANG['max_surplus'], $user['user_money']));
	            }
	        }
	    }



	    /* 订单 支付 配送 状态语言项 */
	    $order['order_status'] = $_LANG['os'][$order['order_status']];
	    $order['pay_status'] = $_LANG['ps'][$order['pay_status']];
	    $order['shipping_status'] = $_LANG['ss'][$order['shipping_status']];
		return json_encode(array('code' =>'0','order'=>$order,'goods_list'=>$goods_list));		
	}


	//获得一个用户所有的订单
	public static function get_user_order_list(){
		include_once(ROOT_PATH . 'includes/lib_transaction.php');
		global $db;
		global $ecs;
		$user_id = $_SESSION['user_id'];
	    $orders = $db->getAll("SELECT * FROM " .$ecs->table('order_info'). " WHERE user_id = '$user_id'");
	    return json_encode(array('code' =>'0','orders'=>$orders));
	}

	public static function del_one_order($order_id){
		include_once(ROOT_PATH . 'includes/lib_transaction.php');
		global $db;
		global $ecs;
		$user_id = $_SESSION['user_id'];
	    $orders = $db->query("delete FROM " .$ecs->table('order_info'). " WHERE user_id = '$user_id' and order_id = '$order_id'");
	    return json_encode(array('code' =>'0'));
	}
}

?>