<?php

function PARAM_VAILD($str='',$option=array()){
	$length = strlen($str);
	$res = json_encode(array('code'=>10006));

	//有些字段可以被设置为空 则要通过验证
	if(!$option['empty']&&empty($str)){
		echo $res;
		exit;
	}

	//最大长度
	if($option['maxLength']&&$length>$option['maxLength']){
		echo $res;
		exit;
	}

	//最小长度
	if($option['minLength']&&$length<$option['minLength']){
		echo $res;
		exit;
	}


	//最大值
	if($option['maxValue']&&$str>$option['maxValue']){
		echo $res;
		exit;
	}

	//最小值
	if($option['minValue']&&$str<$option['minValue']){
		echo $res;
		exit;
	}

	//必须是数字
	if($option['type']&&$option['type'] == 'number'){
		if(!is_numeric($str)){
			echo $res;
			exit;
		}
	}

	//必须是特定值
	if($option['values']&&is_array($option['values'])){
		if(!in_array($str,$option['values'])){
			echo $res;
			exit;
		}
	}
}


function ANTI_SPAM($str,$option=array()){
	if(isset($str)){
		$str = trim($str);  //清理空格
		$str = strip_tags($str);   //过滤html标签
		$str = htmlspecialchars($str);   //将字符内容转化为html实体
		$str = addslashes($str);
		PARAM_VAILD($str,$option);
		return $str;
	}else{
		PARAM_VAILD($str,$option);
		return '';
	}
}


