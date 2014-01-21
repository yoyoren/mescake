<?php

class MES_Lover{

	//获得街道的地址
	public static function get_all(){
		GLOBAL $db;
		$sql= "select * from love_motive";
		$data = $db->getAll($sql);
		return json_encode(array('data'=>$data));
	}

	public static function add($name,$my_weibo,$mobile,$his_weibo,$address,$comment){
		GLOBAL $db;
		$sql= "insert into love_motive (name,my_weibo,mobile,his_weibo,address,comment) values('$name','$my_weibo','$mobile','$his_weibo','$address','$comment');";
		$db->query($sql);
		return 1;
	}
	
	
}

?> 