<?php
class Mes_AddressHandler {

	//只是单一获得省份数据
	public static function get_province_list(){
		$data = file_get_contents(ADDRESS_DATA_PATH);
		$data = json_decode($data);
		$res = array();
		for($i=0;$i<count($data);$i++){
			array_push($res,array(
				'code'=>$data[$i]->region->code,
				'name'=>$data[$i]->region->name,
			));
		}
		return $res;
	}
	
	public static function get_province_info($province){
		$data = file_get_contents(ADDRESS_DATA_PATH);
		$data = json_decode($data);
		$res = array();
		for($i=0;$i<count($data);$i++){
			if($data[$i]->region->code == $province){
				$res = $data[$i];
				return $res->region;
			}
		}
	}
	

	public static function get_city_info($province,$city){
		$data = file_get_contents(ADDRESS_DATA_PATH);
		$data = json_decode($data);
		$res = array();
		for($i=0;$i<count($data);$i++){
			if($data[$i]->region->code == $province){
				$res = $data[$i]->region->state;
				for($k=0;$k<count($res);$k++){
					if($res[$k]->code == $city){
						return $res[$k];
					}
				}
				
			}
		}
	}
	/*
	$temp = $res[$k]->city;
						
						*/
	
	public static function get_district_info($province,$city,$district){
		$data = file_get_contents(ADDRESS_DATA_PATH);
		$data = json_decode($data);
		$res = array();
		for($i=0;$i<count($data);$i++){
			if($data[$i]->region->code == $province){
				$res = $data[$i]->region->state;
				for($k=0;$k<count($res);$k++){
					if($res[$k]->code == $city){
						$temp = $res[$k]->city;
						for($j=0;$j<count($temp);$j++){
							if($temp[$j]->code == $district){
								return $temp[$j];
							}
						}
					}
				}
				
			}
		
		}
	}
}
?>