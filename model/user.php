<?php
class MES_MUser{
	public static function get_username_from_user_by_userid($userid){
		global $db;
		$username=$db->getOne("select user_name from". $GLOBALS['ecs']->table("users")."where email='$userid' or mobile_phone='$userid'");
		return $username;
	}

	public static function get_all_from_user_by_userid($userid){
		global $db;
		$user_id = addslashes($userid);
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('users') .
				" WHERE user_id = '$user_id'";
		$user = $db->getRow($sql);
		return $user;
	}
}
?>