<?php
class Mes_self_station {
	//获得地区列表
	public static function get_by_page($page=0){
		GLOBAL $db;
		$page_size = 50;
		$page_begin = $page * $page_size;
		$page_end = ($page + 1) * $page_size;
		$data = $db->getAll('SELECT * FROM mes_self_station LIMIT '.$page_begin.','.$page_end);
		return json_encode($data);
	}
	public static function get_by_city($province_id,$city_id){
		GLOBAL $db;
		$data = $db->getAll("SELECT * FROM mes_self_station WHERE station_province_id=$povince_id AND city_id=$city_id");
		return json_encode($data);
	}
	
	public static function get_by_stationid($station_id){
		GLOBAL $db;
		$data = $db->getAll("SELECT * FROM mes_self_station WHERE station_id=$station_id");
		return $data[0];
	}
	
	public static function remove($station_id){
		GLOBAL $db;
		$res = $db->query("DELETE FROM mes_self_station WHERE station_id=$station_id");
		return json_encode(array('code'=>0));
	}
	
	public static function add(
		$station_name,
		$station_address,
		$station_province_id,
		$station_province_name,
		$station_city_id,
		$station_city_name,
		$station_district_id,
		$station_district_name,
		$station_lat,
		$station_lng){
			
		GLOBAL $db;
		$db->query("INSERT INTO mes_self_station(station_name,station_address,station_province_id,station_province_name,station_city_id,station_city_name,station_district_id,station_district_name,station_lat,station_lng) VALUES('$station_name','$station_address','$station_province_id','$station_province_name','$station_city_id','$station_city_name','$station_district_id','$station_district_name','$station_lat','$station_lng')");
		return json_encode(array('code'=>0));
	}
}
?>