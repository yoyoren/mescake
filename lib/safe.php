<?php

function PARAM_VAILD($str='',$option=array()){
	$length = iconv_strlen($str,'utf-8');
	$res = json_encode(array('code'=>RES_PARAM_INVAILD));

	//有些字段可以被设置为空 则要通过验证
	if(!$option['empty']&&empty($str)){
		echo $res;
		exit;
	}
	
	//如果是可以为空且是空值 那么不验证
	if($option['empty']&&empty($str)){
		return true;
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

function GET_IP(){
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
	  $ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
	  $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	elseif(!empty($_SERVER["REMOTE_ADDR"])){
	  $ip = $_SERVER["REMOTE_ADDR"];
	}
	else{
	  $ip = "无法获取！";
	}
	return $ip;
}

function GEN_MES_TOKEN( $len = 32, $md5 = true ) {  
       # Seed random number generator  
          # Only needed for PHP versions prior to 4.2  
          mt_srand( (double)microtime()*1000000 );  
          # Array of characters, adjust as desired  
          $chars = array(  
              'Q', '@', '8', 'y', '%', '^', '5', 'Z', '(', 'G', '_', 'O', '`',  
              'S', '-', 'N', '<', 'D', '{', '}', '[', ']', 'h', ';', 'W', '.',  
              '/', '|', ':', '1', 'E', 'L', '4', '&', '6', '7', '#', '9', 'a',  
              'A', 'b', 'B', '~', 'C', 'd', '>', 'e', '2', 'f', 'P', 'g', ')',  
              '?', 'H', 'i', 'X', 'U', 'J', 'k', 'r', 'l', '3', 't', 'M', 'n',  
              '=', 'o', '+', 'p', 'F', 'q', '!', 'K', 'R', 's', 'c', 'm', 'T',  
              'v', 'j', 'u', 'V', 'w', ',', 'x', 'I', '$', 'Y', 'z', '*'  
          );  
          # Array indice friendly number of chars;  
          $numChars = count($chars) - 1; $token = '';  
          # Create random token at the specified length  
          for ( $i=0; $i<$len; $i++ )  
              $token .= $chars[ mt_rand(0, $numChars) ];  
          # Should token be run through md5?  
          if ( $md5 ) {  
              # Number of 32 char chunks  
              $chunks = ceil( strlen($token) / 32 ); $md5token = '';  
              # Run each chunk through md5  
              for ( $i=1; $i<=$chunks; $i++ )  
                  $md5token .= md5( substr($token, $i * 32 - 32, 32) );  
              # Trim the token  
              $token = substr($md5token, 0, $len);  
          } return $token;  
} 