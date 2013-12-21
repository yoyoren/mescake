<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

 $mobile=$_POST["cellNumber"];
 $checkCode=$_POST["checkCode"];
 $trueCode=$_POST["checkword"];

if($checkCode==$trueCode)
{
	$word=456789;
	
	if($mobile && $word)
	{
		for($i=0;$i<6;$i++)
				{
					$r = rand(0,9);
					$salt .= $r;
				}
        $salt = $salt;
		//$salt = salt;
	//$sql="select ec_salt from ecs_users where mobile_phone={$mobile}";
	//$salt=$db->getRow($sql);
	//$salt=$salt["ec_salt"];
	$password = md5(md5($word).$salt);
	$db->query("update ecs_users set password='{$password}',ec_salt='{$salt}' where mobile_phone='{$mobile}'");
	
	}
		require_once 'includes/cls_randNum2.php';	
		$randNum=new randNum();
	//	$word=$randNum->generate_word();
	//	$randNum->record_word($word);
		
		if(!empty($mobile) && !empty($word)){
			$randNum->sendsms($mobile, $word);					
		}	
	
		  header("Location: ./index.php");

	

}

?>