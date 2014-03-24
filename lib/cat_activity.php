<?php

class MES_Cat_activity{

	//获得街道的地址
	public static function get_all(){
		GLOBAL $db;
		$sql= "select * from cat_activity";
		$data = $db->getAll($sql);
		return json_encode(array('code'=>'0','data'=>$data));
	}

	public static function add($weibo_name,$img){
		GLOBAL $db;
		$sql= "insert into cat_activity (weibo_name,img,status,times) values('$weibo_name','$img',0,0);";
		$db->query($sql);
		return 1;
	}

	public static function like($id){
		GLOBAL $db;
		$sql= "update cat_activity set times=1 where id={$id}";
		$db->query($sql);
		return 1;
	}

	//审核
	public static function change_status($id,$status=0){
		GLOBAL $db;
		$sql= "update cat_activity set status={$status} where id={$id}";
		$db->query($sql);
		return 1;
	}
	
	
}

?> 