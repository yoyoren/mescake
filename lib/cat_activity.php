<?php

class MES_Cat_activity{

	//获得街道的地址
	public static function get_all(){
		GLOBAL $db;
		$sql= "select * from cat_activity";
		$data = $db->getAll($sql);
		for($i=0;$i<count($data);$i++){
			 $data[$i]['format_date'] = date('Y-m-d H:i:s', $data[$i]['add_time']);
		}
		return json_encode(array('code'=>'0','data'=>$data));
	}

	//获得街道的地址
	public static function cat_get_by_status($status=0){
		GLOBAL $db;
		$sql= "select * from cat_activity where status={$status}";
		$data = $db->getAll($sql);
		for($i=0;$i<count($data);$i++){
			 $data[$i]['format_date'] = date('Y-m-d H:i:s', $data[$i]['add_time']);
		}
		return json_encode(array('code'=>'0','data'=>$data));
	}

	public static function add($weibo_name,$img){
		GLOBAL $db;
		$add_time = time();
		$sql= "insert into cat_activity (weibo_name,img,status,times,add_time) values('$weibo_name','$img',0,0,'$add_time');";
		$db->query($sql);
		return 1;
	}

	public static function cat_like($id){
		GLOBAL $db;
		$sql1= "select times from cat_activity  where id={$id}";
		$times = $db->getOne($sql1);
		$times+=1;
		$sql= "update cat_activity set times={$times} where id={$id}";
		$db->query($sql);
		return 1;
	}

	//审核
	public static function cat_change_status($id,$status=0){
		GLOBAL $db;
		$sql= "update cat_activity set status={$status} where id={$id}";
		$db->query($sql);
		return 1;
	}
	
	
}

?> 