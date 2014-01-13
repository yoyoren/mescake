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

	
}

?>