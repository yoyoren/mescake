<?php

//引入一个加密算法的工程
include_once(ROOT_PATH . '../security/main.php');

//sec安全数据操作类
class MES_Sec{	
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

	public static function get_decode_userinfo_by_username($username){
		global $db;
		$sql = "select * from". $GLOBALS['ecs']->table("users")."where user_name='$username'";
		$user = $db->getRow($sql);
		return $user;
	}

	public static function get_decode_userinfo_by_userid($userid){
		global $db;
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('users') .
				" WHERE user_id = '$user_id'";
		$user = $db->getRow($sql);
		return $user;
	}

    //获得用户名
	public static function get_userid_by_username($username){
		global $db;
		$sql = "SELECT user_id  FROM " . $GLOBALS['ecs']->table('users')." WHERE user_name='" . $username . "'";
        return $db->getOne($sql);
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
	
	//根据条件更新usertable的字段
	public static function update_usertable_by_condition($update_col_arr,$update_condition_col){
	
	}
	
	public static function update_by_username($decode_username,$update_col=array()){
	
	}


	public static function get_decode_useraddress(){
		
	}

	public static function get_decode_usermobile(){
		
	}

	
}

?>