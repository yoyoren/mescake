<?php

//引入一个加密算法的工程
include_once(ROOT_PATH . '../security/main.php');
$T_USER = 'ecs_users';

//sec安全数据操作类
class MES_Sec{

    //User表相关的操作
	public static function get_decode_username_by_mobile($mobile){
		global $db;
		$sql ="select user_name from". $GLOBALS['ecs']->table("users")."where email='$mobile' or mobile_phone='$mobile'";
		$username = $db->getOne($sql);
		return $username;
	}

	public static function get_decode_userinfo_by_mobile($mobile){
		global $db;
		$sql ="select * from". $GLOBALS['ecs']->table("users")."where email='$mobile' or mobile_phone='$mobile'";
		$user = $db->getRow($sql);
		return $user;
	}

	public static function get_userinfo_by_username($username){
		global $db;
		$sql = "select * from". $GLOBALS['ecs']->table("users")."where user_name='$username'";
		$user = $db->getRow($sql);
		return $user;
	}


	public static function get_userinfo_by_userid($userid){
		global $db;
		
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('users') .
				" WHERE user_id = '$userid'";
		$user = $db->getRow($sql);
		return $user;
	}

    //获得用户名
	public static function get_userid_by_username($username){
		global $db;
		$sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users')." WHERE user_name='" . $username . "'";
        return $db->getOne($sql);
	}

	public static function get_user_type_by_mobile($mobile){
		global $db;
		$user_type = $db->getOne("select user_type from". $GLOBALS['ecs']->table("users")."where email='$mobile' or mobile_phone='$mobile'");
		return $user_type;
	}

	public static function get_password_by_mobile($mobile){
	    global $db;
		$password = $db->getOne("select password from". $GLOBALS['ecs']->table("users")."where email='$mobile' or mobile_phone='$mobile'");
		return $password;
	}

	public static function get_password_by_username($user_name){
	    global $db;
		$password = $db->getOne("select password from". $GLOBALS['ecs']->table("users")."where user_name='$user_name'");
		return $password;
	}

	public static function get_salt_by_mobile($mobile){
	    global $db;
		$salt = $db->getOne("select ec_salt from". $GLOBALS['ecs']->table("users")."where mobile_phone='$mobile'");
		return $salt;
	}

	public static function get_salt_by_username($user_name){
	    global $db;
		$salt = $db->getOne("select ec_salt from". $GLOBALS['ecs']->table("users")."where user_name='$user_name'");
		return $salt;
	}

	public static function get_mobile_by_username($user_name){
	    global $db;
		$mobile_phone = $db->getOne("select mobile_phone from". $GLOBALS['ecs']->table("users")."where user_name='$user_name'");
		return $mobile_phone;
	}


	//注册时检查用户是否存在
	public static function check_email_exsit($email){
		global $db;
		$sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users').
               " WHERE email = '$email'";
        if ($db->getOne($sql, true) > 0){
            return true;
        }
		return false;
	}


	//注册时检查用户是否存在
	public static function check_mobile_exsit($mobile){
		global $db;
		$mobile_phone = $db->getOne("select mobile_phone from". $GLOBALS['ecs']->table("users")."where mobile_phone='$mobile'");
		return $mobile_phone;
		
	}

    //注册时添加用户
	public static function add_user($username,$email,$password){
	    global $db;
		$fields = array('user_name','email','password');
        $values = array($username, $email, $password);
        $sql = "INSERT INTO " . $GLOBALS['ecs']->table('users').
               " (" . implode(',', $fields) . ")".
               " VALUES ('" . implode("', '", $values) . "')";

        $db->query($sql);
	
	}
	public static function update_auto_regsiter_password($username,$password){
		global $db;
		$db->query("update ".$GLOBALS['ecs']->table('users')." set user_type=11,password='$password' where user_name='$username'");
		return 1;
	}

	public static function update_password_and_salt($username,$password,$salt){
		global $db;
		$sql = "update ".$GLOBALS['ecs']->table('users')."SET password= '" .$password."',ec_salt='".$salt."'".
                   " where user_name='$post_username'";
		$db->query($sql);
		return 1;
	}

    //给自动注册的用户设置一个密码
	public static function update_password_for_unregitser_user($user_name,$password){
		global $db;
		$sql = "update ".$GLOBALS['ecs']->table('users')." set user_type=0,password='$password' where user_name='$user_name'";
		$db->query($sql);
		return 1;
	}

	public static function update_password_by_username($password,$user_name){
	    global $db;
		$db->query("update ".$GLOBALS['ecs']->table('users')." set password='$password' where user_name='$user_name'");
		return 1;
	}

	public static function update_password_by_mobile($password,$mobile){
	    global $db;
		$db->query("update ".$GLOBALS['ecs']->table('users')." set password='$password' where mobile_phone='$mobile'");
		return 1;
	}

	public static function update_salt_by_mobile($salt,$mobile){
	    global $db;
		$db->query("update ".$GLOBALS['ecs']->table('users')." set ec_salt='$salt' where mobile_phone='$mobile'");
		return 1;
	}

	public static function update_mobile_by_username($mobile,$user_name){
	    global $db;
		$db->query("update ".$GLOBALS['ecs']->table('users')." set mobile_phone='$mobile' where user_name='$user_name'");
		return 1;
	}

	public static function update_usertype_by_username($user_name){
	    global $db;
		$db->query("update ".$GLOBALS['ecs']->table('users')." set user_type=0 where user_name='$user_name'");
		return 1;
	}



	//根据条件更新usertable的字段
	public static function update_usertable_by_condition($update_col_arr,$update_condition_col){
	
	}
	




    //ADDRESS相关的操作
	public static function get_address_by_userid($user_id){
		global $db;
		$sql="select * from ecs_user_address where user_id={$user_id}";	
		$address=$db->getAll($sql);
		return $address;
	}

	public static function get_address_by_userid_and_addressid($user_id,$address_id){
		global $db;
		$sql="select * from ecs_user_address where user_id={$user_id} and address_id={$address_id}";	
		$address=$db->getRow($sql);
		return $address;
	}

	public static function get_address_by_userid_and_address($user_id,$address){
		global $db;
		$sql="select * from ecs_user_address where user_id={$user_id} and address='{$address}' limit 0,1";	
		$address=$db->getRow($sql);
		return $address;
	}

	public static function update_address_by_userid_and_addressid($country,$city,$district,$contact,$address,$tel,$user_id,$address_id){
		global $db;
		$db->query("update ecs_user_address set country={$country},city={$city},district={$district},consignee='{$contact}',address='{$address}',mobile='{$tel}'
			where user_id={$user_id} and address_id={$address_id}");
		return 1;
		
	}

	public static function add_address($contact,$country,$city,$district,$address,$tel,$user_id){
		global $db;
		$db->query("INSERT INTO ecs_user_address(address_name, user_id, consignee, country, province, city, district, address, tel, mobile, money_address, route_id, ExchangeState, ExchangeState2)
		VALUES('',{$user_id},'{$contact}','{$country}','0', '{$city}', '{$district}', '{$address}', '', '{$tel}', NULL, '0', '0', '0')");
		return 1;
		
	}


	public static function add_order($order){
		$res = $GLOBALS['db'] -> autoExecute($GLOBALS['ecs'] -> table('order_info'), $order, 'INSERT');
		return $res;
	}
	
}

?>