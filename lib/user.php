<?php

require_once('lib/fee.php');
class MES_User{

	//获得街道的地址
	private static function _get_distruct_name($city,$district){
		$hash = MES_Fee::get_fee_region();
		if($hash[$city]&&$city){
			if($hash[$city][$district]){
				return  $hash[$city][$district]['name'];
			}
		}
		return '';
	}

	public static function ajax_login($username,$password){
		global $db;
		global $_LANG;
		global $user;// = user_info($_SESSION['user_id']);
		$username = addslashes($username);
		$password = addslashes($password);

		$json = new JSON;
		$result   = array(
			'code' => RES_SUCCSEE, 
			'content' => 'login successs'
		);

		$username=$db->getOne("select user_name from". $GLOBALS['ecs']->table("users")."where email='$username' or mobile_phone='$username'");

		if(empty($username)){
			$result['code'] = RES_FAIL;
			$result['content'] = $_LANG['login_failure'];
			return $json->encode($result);
		}
		if ($user->login11($username, $password)){
			$ucdata = empty($user->ucdata)? "" : $user->ucdata;
			$result['ucdata'] = $ucdata;

			$_SESSION['usermsg']= get_user_info();
		}else{
			$_SESSION['login_fail']++;
			$result['error'] = 1;
			$result['code'] = RES_FAIL;
			$result['content'] = $_LANG['login_failure'];
		}
		return $json->encode($result);
	}

	public static function get_user_info($user_id){
		global $db;
		$user_id = addslashes($user_id);
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
		$uuid = $_COOKIE['uuid'];
		DEL_REDIS($uuid,'user');
		$user->logout();
		return json_encode(array('code'=>RES_SUCCSEE,'msg'=>'success'));
	}

	public static function check_login(){
		$token = $_COOKIE['serviceToken'];
		$uuid = $_COOKIE['uuid'];
		$res = false;

		if($token&&GET_REDIS($uuid,'user') == $token){
			$res = true;
			if($_SESSION['user_auto_register_moblie']){
				$uname = $_SESSION['user_auto_register_moblie'];
			}else{
				$uname = $uuid;
			}
		}

		return json_encode(array(
			'code'=>RES_SUCCSEE,
			'msg'=>'success',
			'res'=>$res,
			'uname'=>$uname
		));
	}

	//服务器端用于检查是否登录的方法
	public static function server_check_login(){
		global $db;
		$token = $_COOKIE['serviceToken'];
		$uuid = $_COOKIE['uuid'];

		$res = false;
		if($token&&GET_REDIS($uuid,'user')== $token){
			$res = true;
		}
		return $res;
	}

	//检查一个用户是否存在
	public static function check_user_exsit($username,$serverside=false){
		//global $user;
		global $db;
		$res = array('code'=>RES_SUCCSEE);
		$username = addslashes($username);
		$user = $db->getAll("select * from". $GLOBALS['ecs']->table("users")."where email='$username' or mobile_phone='$username'");
		$user = $user[0];
		if($user['mobile_phone']==$username&&$user!=null){
			if($user['user_type']==11){
				$res['exsit'] = false;
				$res['autoregister'] = true;
			}else{
				$res['exsit'] = true;
			}
			 
		}else{
			 $res['exsit'] = false;
		}
		if($serverside){
			return $res;
		}
		return json_encode($res);
	}

	//帮用户自动注册
	public static function auto_register($mobile){
		global $db;
		include_once(ROOT_PATH . 'includes/lib_passport.php');
		$check_user = MES_User::check_user_exsit($mobile);
		//if($check_user['autoregister']==true){
		//}

		$mobile = addslashes($mobile);
        $msg='';

		//这个密码实际上会在注册后立即被更新成一个随机密码
        $password = '123456';
		$f_email= 'W' . $mobile . "@fal.com";

		$email = $f_email;
		$username = $f_email;
        $other['mobile_phone'] = $mobile;
		$other['rea_name'] = '';

        $back_act = '';
        if (register($username, $password, $email,$other) !== false){
        	//user type 写成11 自动注册
			//密码必须名文 因为这个要发送给用户
			$rand_password = MES_User::rand_num();
			$db->query("update ecs_users set user_type=11,password='$rand_password' where user_name='$username'");//用户类型设置bisc
			$_SESSION['user_msg'] = $username;
		}

		//标记一下这个用户是这次自动注册的
		$_SESSION['user_auto_register'] = '11';
		$_SESSION['user_auto_register_moblie'] = $mobile;
		return json_encode(array(
			'code' =>RES_SUCCSEE,
			'msg'=>$msg
		));
	}

	public static function get_auto_register_mobile(){
		return json_encode(array(
			'code' =>RES_SUCCSEE,
			'msg'=>$_SESSION['user_auto_register_moblie']
		));
	}

	//帮没有设置密码的用户
	//帮没有设置密码的用户自动设置密码
	public static function change_unregister_password($password){
		global $db;
		
		$password = addslashes($password);
		//密码验证
		if(empty($password)||strlen($password)<6){
			return json_encode(array('code'=>RES_FAIL,'msg'=>'fail'));
		}

		
		$password = md5($password);
		//$password = md5($password.'0');
		$mobile = $_SESSION['user_auto_register_moblie'];
		$username = 'W' . $mobile . "@fal.com";
		


		if($_SESSION['user_auto_register'] == '11'){
			$db->query("update ecs_users set user_type=0,password='$password' where user_name='$username'");
			unset($_SESSION['user_auto_register']);
			unset($_SESSION['user_auto_register_moblie']);
			return json_encode(array('code'=>RES_SUCCSEE,'msg'=>'success'));
		}else{
			return json_encode(array('code'=>RES_FAIL,'msg'=>'fail'));
		}
		
	}

	//检测用户是否可以重新设置密码
	public static function is_unset_password_user(){
		if($_SESSION['user_auto_register'] == '11'){
			return json_encode(array('code'=>RES_SUCCSEE,'msg'=>true));
		}else{
			return json_encode(array('code'=>RES_SUCCSEE,'msg'=>false));
		}
	}
	
	//获得一个订单的详情
	public static function get_user_order_detail($order_id){
		include_once(ROOT_PATH . 'includes/lib_transaction.php');
	    include_once(ROOT_PATH . 'includes/lib_payment.php');
	    include_once(ROOT_PATH . 'includes/lib_order.php');
	    include_once(ROOT_PATH . 'includes/lib_clips.php');
		
		GLOBAL $db;

		$order_id = addslashes($order_id);
	    $user_id = addslashes($_SESSION['user_id']);
	    $res = array('code' =>RES_SUCCSEE);
	    /* 订单详情 */
	    $order = get_order_detail($order_id, $user_id);

	    if ($order === false){
	    	$res['code'] = RES_FAIL;
	        return json_encode($res);
	    }

	    /* 订单商品 */
	    $goods_list = order_goods($order_id);

		//true 只返回支付的地址 不需要返回给我们其他代码
	    $order_detail = get_order_detail($order_id,$user_id,true);
	   
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
					$order['allow_edit_surplus'] = 1;
					$order['max_surplus'] = sprintf($_LANG['max_surplus'], $user['user_money']);
	            }
	        }
	    }



	    //订单 支付 配送 状态语言项
	    //$order['order_status'] = $_LANG['os'][$order['order_status']];
	    //$order['pay_status'] = $_LANG['ps'][$order['pay_status']];
	    //$order['shipping_status'] = $_LANG['ss'][$order['shipping_status']];

		$city_name=$db->getOne("select region_name from ship_region where region_id={$order['city']}");

		$order['cityName'] = $city_name;
		$order['districtName'] = MES_User::_get_distruct_name($order['city'],$order['district']);
		
		if($order['fork_message']){
		   $order['fork_message'] = json_decode($order['fork_message']);
		}
		
		return json_encode(array(
			'code' =>RES_SUCCSEE,
			'order'=>$order,
			'goods_list'=>$goods_list,
			'pay_online'=>$order_detail['pay_online']
		));		
	}


	//获得一个用户所有的订单
	public static function get_user_order_list(){
		include_once(ROOT_PATH . 'includes/lib_transaction.php');
		include_once(ROOT_PATH . 'includes/lib_payment.php');
	    include_once(ROOT_PATH . 'includes/lib_order.php');
	    include_once(ROOT_PATH . 'includes/lib_clips.php');
		global $db;
		global $ecs;
		$user_id = $_SESSION['user_id'];
		if(!$user_id){
			return json_encode(array('code' =>RES_FAIL,'msg'=>'user_id not exsit'));
		}

	    $orders = $db->getAll("SELECT * FROM " .$ecs->table('order_info'). " WHERE user_id = '$user_id' order by order_id DESC");
		$res=array();
		foreach($orders as $v){
			$order_id = $v['order_id'];
			$order_detail = $db->getAll("SELECT * FROM " .$ecs->table('order_goods'). " WHERE order_id = '$order_id'");

			$v['detail'] = $order_detail;
			$pay_online = get_order_detail($order_id,$user_id,true);
			$v['pay_online']=$pay_online;
			array_push($res,$v);
		}
		
	    return json_encode(array('code' =>RES_SUCCSEE,'orders'=>$res));
	}

	public static function del_one_order($order_id){
		include_once(ROOT_PATH . 'includes/lib_transaction.php');
		include_once(ROOT_PATH . 'includes/lib_common.php');
		global $db;
		global $ecs;
		$user_id = $_SESSION['user_id'];
		
		//只有没有确认的订单才可以取消
		//$current_order = $db->getRow("select order_status,pay_status from ecs_order_info where user_id = '$user_id' and order_id = '$order_id'");
		$current_order = $db->getRow("select order_status,pay_status,bonus,surplus,bonus_id,order_sn from ecs_order_info where user_id = '$user_id' and order_id = '$order_id'");
		if($current_order['order_status']==OS_UNCONFIRMED&&$current_order['pay_status']!=PS_PAYED){
  			$sql = "update ecs_order_info set order_status =".OS_CANCELED." where user_id = '$user_id' and order_id = '$order_id'";
  			$orders = $db->query($sql);
 			
			if($orders){	
 				$change_desc="订单号:".$current_order['order_sn'];
 				//取消礼金卡支付的订单记录礼金卡账户变动
 				if($current_order['surplus']>0){
 					log_mcard_change($user_id, $current_order['surplus'], $change_desc,0,$order_id,3);
 				}
 				//取消现金券支付的订单改变现金券的使用状态
 				else if($current_order['bonus']>0){
 					unuse_bonus(trim($current_order['bonus_id']));
				}
 				else{
 					log_account_change($user_id,0,0,0, 0, $change_desc);
 				}
 			}
  			return json_encode(array('code' =>RES_SUCCSEE));
  		}else{
  			return json_encode(array('code' =>RES_FAIL));
  		}
	}
	
	public static function get_password_moblie($mobile){
	   global $db;
	   $mobile = addslashes($mobile);
	   if(strlen($mobile)<5){
		   return json_encode(array('code' =>RES_FAIL));
	   }
	   $user_type=$db->getOne("select user_type from". $GLOBALS['ecs']->table("users")."where email='$mobile' or mobile_phone='$mobile'");
	   if($user_type == 11){
		   //重新生成随机的密码
		   $rand_password = MES_User::rand_num();
		   //组合出这个地址
		   $user_name= 'W' . $mobile . "@fal.com";
		   $db->query("update ecs_users set password='$rand_password' where user_name='$user_name'");

		   $password = $db->getOne("select password from". $GLOBALS['ecs']->table("users")."where email='$mobile' or mobile_phone='$mobile'");	  
		   
		   $c = "尊敬的用户，您在每实官网手机校验码为".$password."。如有问题请与每实客服中心联系，电话4000 600 700。";
		   $cont=urlencode($c);
		  
		   $url = 'http://sdk.kuai-xin.com:8888/sms.aspx?action=send&userid=4333&account=s120018&password=wangjianming123&mobile='.$mobile.'&content='.$cont.'&sendTime=';
		   
		   file_get_contents($url);
		   
		   return json_encode(array('code' =>RES_SUCCSEE));
	   }else{
		   return json_encode(array('code' =>RES_FAIL,'user_type' =>$user_type));
	   }
	}

	private static function rand_num(){
		srand((double)microtime()*1000000);//create a random number feed.
		$ychar="0,1,2,3,4,5,6,7,8,9,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z";
		$list=explode(",",$ychar);
		for($i=0;$i<6;$i++){
			$randnum=rand(0,35);
			$authnum.=$list[$randnum];
		}
		return $authnum;
	}

	//使用手机号码查询验证码的登录
	public static function query_login($username,$password){
		global $db;
		global $_LANG;
		global $user;
		$username = addslashes($username);
		$password = addslashes($password);
		$mobile = $username;
		$json = new JSON;
		$res  = array('code' =>RES_SUCCSEE, 'content' => 'login successs');

		$username=$db->getOne("select user_name from". $GLOBALS['ecs']->table("users")."where mobile_phone='$username'");

		if(empty($username)||empty($password)){
			$res['code']   = RES_FAIL;
			$res['content'] = $_LANG['login_failure'];
			return $json->encode($result);
		}

		if ($user->login_for_auto_register($username, $password)){
			$_SESSION['usermsg']= get_user_info();
			//这种用户都是自动注册登录 要区别对待
			$_SESSION['user_auto_register'] = '11';
			$_SESSION['user_auto_register_moblie'] = $mobile;
		}else{
			$res['code']   = RES_FAIL;
			$res['content'] = $_LANG['login_failure'];
		}
		return $json->encode($res);
	}

	public static function change_mobile($mobile,$code){
		global $db;
		if($_SESSION['change_mobile']!=$mobile||$_SESSION['change_mobile_code']!=$code){
			return json_encode(array('code' =>RES_FAIL));
		}else{
			$user_name = $_SESSION['uuid'];
			$db->query("update ecs_users set mobile_phone='$mobile' where user_name='$user_name'");
			unset($_SESSION['change_mobile']);
			unset($_SESSION['change_mobile_code']);
			return json_encode(array('code' =>RES_SUCCSEE,'mobile'=>$mobile));
		}
	}

	public static function change_mobile_get_code($mobile){
		$rand_password = MES_User::rand_num();
		$c = "尊敬的用户，您在每实官网手机校验码为".$rand_password."。如有问题请与每实客服中心联系，电话4000 600 700。";
		$content=urlencode($c);
	    $url = 'http://sdk.kuai-xin.com:8888/sms.aspx?action=send&userid=4333&account=s120018&password=wangjianming123&mobile='.$mobile.'&content='.$content.'&sendTime=';
		file_get_contents($url);
		$_SESSION['change_mobile'] = $mobile;
		$_SESSION['change_mobile_code'] = $rand_password;
		return json_encode(array('code' =>RES_SUCCSEE));
	}

	//获得用户正在使用的电话号码
	public static function get_user_mobile_number(){
		global $db;
		$user_name = addslashes($_SESSION['uuid']);
		$mobile=$db->getOne("select mobile_phone from". $GLOBALS['ecs']->table("users")."where user_name='$user_name'");
		return json_encode(array(
			'code' =>RES_SUCCSEE,
			'mobile'=>$mobile
		));
	}

	//获得用户正在使用的电话号码
	public static function get_users_info(){
		global $db;
		$user_name = addslashes($_SESSION['uuid']);
		$info=$db->getAll("select mobile_phone,rea_name,sex,user_money from". $GLOBALS['ecs']->table("users")."where user_name='$user_name'");
		return json_encode(array(
			'code' =>RES_SUCCSEE,
			'info'=>$info
		));
	}


	
	//已经登录用户修改密码
	public static function change_password($old,$new){
		global $db;
		$user_name = addslashes($_SESSION['uuid']);
		
		$old = md5($old);
		$new = md5($new);
		//$salt_password = md5($old.'ec_salt');
		//var_dump($salt_password);
		$password_in_db =$db->getOne("select password from". $GLOBALS['ecs']->table("users")."where user_name='$user_name'");
		$salt_in_db =$db->getOne("select ec_salt from". $GLOBALS['ecs']->table("users")."where user_name='$user_name'");
		$salt_password = md5($old.$salt_in_db);


		if($password_in_db==$salt_password){
			$new_password = md5($new.$salt_in_db);
			$db->query("update ecs_users set password='$new_password' where user_name='$user_name'");
			return json_encode(array('code' =>RES_SUCCSEE));
		}else{
			return json_encode(array('code' =>RES_FAIL));
		}
	}
   
    //忘记密码找回第一步验证这个是否可以
	public static function forget_password_step1($mobile,$code){
		if($_SESSION['forget_mobile_code'] == $code&&$_SESSION['forget_mobile'] == $mobile){
			$_SESSION['forget_mobile_vaild'] = true;
			return json_encode(array('code' =>RES_SUCCSEE));
		}else{
			$_SESSION['forget_mobile_vaild'] = false;
			return json_encode(array('code' =>RES_FAIL));
		}
	}

	//忘记密码找回第一步验证这个是否可以
	public static function forget_password_step2($mobile,$password){
		global $db;
		if($_SESSION['forget_mobile_vaild']&&$_SESSION['forget_mobile'] == $mobile){
			$salt_in_db = $db->getOne("select ec_salt from". $GLOBALS['ecs']->table("users")."where mobile_phone='$mobile'");
			if(!$salt_in_db){
				$salt_in_db = '8801';
				$db->query("update ecs_users set ec_salt='$salt_in_db' where mobile_phone='$mobile'");
			}
			$password = md5($password);
			$new_password = md5($password.$salt_in_db);
			$db->query("update ecs_users set password='$new_password' where mobile_phone='$mobile'");
			unset($_SESSION['forget_mobile']);
			unset($_SESSION['forget_mobile_code']);
			return json_encode(array('code' =>RES_SUCCSEE));
		}else{
			return json_encode(array('code' =>RES_FAIL));
		}
	}

	public static function get_forget_password_code($mobile){
		
		global $db;
		$mobile_phone = $db->getOne("select mobile_phone from". $GLOBALS['ecs']->table("users")."where mobile_phone='$mobile'");
		if($mobile_phone){
			$rand_password = MES_User::rand_num();
			$c = "尊敬的用户，您在每实官网手机校验码为".$rand_password."。如有问题请与每实客服中心联系，电话4000 600 700。";
			$content=urlencode($c);
			$url = 'http://sdk.kuai-xin.com:8888/sms.aspx?action=send&userid=4333&account=s120018&password=wangjianming123&mobile='.$mobile.'&content='.$content.'&sendTime=';
			file_get_contents($url);
			$_SESSION['forget_mobile'] = $mobile;
			$_SESSION['forget_mobile_code'] = $rand_password;
			return json_encode(array('code' =>RES_SUCCSEE));
		}else{
			return json_encode(array('code' =>RES_FAIL));
		}
	}


	public static function change_sex($sex){
		global $db;
		if($sex!=0&&$sex!=1){
			return json_encode(array('code' =>RES_FAIL));
		}
		$user_name = addslashes($_SESSION['uuid']);
		$sex = addslashes($sex);
		$db->query("update ecs_users set sex='$sex' where user_name='$user_name'");
		return json_encode(array('code' =>RES_SUCCSEE));
	}

	public static function change_real_name($name){
		global $db;
		$user_name = addslashes($_SESSION['uuid']);
		$name = addslashes($name);
		$db->query("update ecs_users set rea_name='$name' where user_name='$user_name'");
		return json_encode(array('code' =>RES_SUCCSEE));
	}


	public static function get_order_count_by_sid(){
		global $db;
		$sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE session_id= "' . SESS_ID.'"';
		$shipping_count =$db->getOne($sql);
		return json_encode(array('code' =>RES_SUCCSEE,'count'=>$shipping_count));
	}
	
}

?>