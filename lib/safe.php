<?php
function ANTI_SPAM($str){
	if(isset($str)){
		$str = trim($str);  //清理空格
		$str = strip_tags($str);   //过滤html标签
		$str = htmlspecialchars($str);   //将字符内容转化为html实体
		$str = addslashes($str);
		return $str;
	}else{
		return '';
	}
}