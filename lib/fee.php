<?php

//运费相关
class MES_Fee{

	//计算一个区域是否需要算运费
	public static function cal_fee($city,$district){
		$hash = MES_Fee::get_fee_region();
		if($hash[$city]){
			if($hash[$city][$district]){
				return !$hash[$city][$district]['free'];
			}	  
		}else{
			return false;
		}
	}
	public static function get_fee_region(){
		$CITY_HASH = array(
			'544'=>array(
				'1'=>array('name'=>'五环内','free'=>true),
				'2'=>array('name'=>'管庄','free'=>true),
				'3'=>array('name'=>'东坝','free'=>true),
				'4'=>array('name'=>'常营','free'=>true),
				'5'=>array('name'=>'定福庄','free'=>true),
				'6'=>array('name'=>'双桥','free'=>true),
				'7'=>array('name'=>'*五环外，六环内其他区域','free'=>false)
			 ),
			'548'=>array(
				'1'=>array('name'=>'五环内','free'=>true),
				'2'=>array('name'=>'香山地区','free'=>true),
				'3'=>array('name'=>'*五环外，六环内其他区域','free'=>false)
			 ),
			'550'=>array(
				'1'=>array('name'=>'五环内','free'=>true),
				'2'=>array('name'=>'*五环外，六环内其他区域','free'=>false)
			 ),
			'551'=>array(
				'1'=>array('name'=>'五环内','free'=>true),
				'2'=>array('name'=>'八大处','free'=>true),
				'3'=>array('name'=>'石景山城区','free'=>true),
				'4'=>array('name'=>'八角街道','free'=>true),
				'5'=>array('name'=>'古城街道','free'=>true),
				'6'=>array('name'=>'苹果园街道','free'=>true),
				'7'=>array('name'=>'金顶街街道','free'=>true),
				'8'=>array('name'=>'*五环外，六环内其他区域','free'=>false)
			 ),
			'552'=>array(
				'1'=>array('name'=>'五环内','free'=>true),
				'2'=>array('name'=>'*五环外，六环内其他区域','free'=>false)
			 ),
			'569'=>array(
				'1'=>array('name'=>'空港街道','free'=>true),
				'2'=>array('name'=>'天竺地区','free'=>true),
				'3'=>array('name'=>'李桥镇','free'=>true),
				'4'=>array('name'=>'常营','free'=>true),
				'5'=>array('name'=>'仁和地区','free'=>true),
				'6'=>array('name'=>'胜利街道','free'=>true),
				'7'=>array('name'=>'石园街道','free'=>true),
				'8'=>array('name'=>'旺泉街道','free'=>true),
				'9'=>array('name'=>'双丰街道','free'=>true),
				'10'=>array('name'=>'光明街道','free'=>true),
				'11'=>array('name'=>'机场生活区','free'=>true),
				'12'=>array('name'=>'林河开发区','free'=>true),
				'13'=>array('name'=>'空港开发区A区','free'=>true),
				'14'=>array('name'=>'*五环外，六环内其他区域','free'=>false)
			 ),
			'572'=>array(
				'1'=>array('name'=>'长阳','free'=>false),
				'2'=>array('name'=>'良乡','free'=>false),
			 ),
			'571'=>array(
				'1'=>array('name'=>'*五环外六环内区域','free'=>false),
			
			),
		);
		return $CITY_HASH;
	}
	
}

?>