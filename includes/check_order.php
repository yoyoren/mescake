<?php
function check_order($con,$goods)
{
	$msg = '';
	$btime="";
	
	$ntimestamp = gmtime()+32*3600; //24小时后时间戳
	$now = gmtime();
	$tmd = date('Y-m-d',$now+32*3600); 
	
	if(empty($con))
	{
	   $msg .= '请您填写收货信息|';
	}else{
		$btime=$con['best_time'];
	}
	$btimestamp = strtotime($btime);
	if(empty($goods))
	{
	   $msg .= '购物车没有商品|';
	}
	if(empty($btime)||strlen($btime)<19)
	{
	   $msg .= '请填写送货时间|';
	}
	if($btimestamp < gmtime() + 13.25*3600)
	{
	   $msg .= '送货时间不足5小时,重新填写送货时间';
	}
	    if((local_date('H',$now)>21)&&( substr($btime,0,13) <$tmd.' 14')&&$con['country']=='441')
    {
        $msg .='请注意，此时订货最早14点送货,请修改送货时间 ';
    }
    if((local_date('H',$now)<10)&&( substr($btime,0,13) < date('Y-m-d',$now+8*3600).' 14')&&$con['country']=='441')
    {
        $msg .='请注意，此时订货最早14点送货,请修改送货时间 ';
    }
	return $msg;
}

function check_login()
{
	session_start();
	$flag=($_SESSION['user_id']==0)?0:$_SESSION['user_id'];
	var_dump($_SESSION);
	//if($flag>0){
//		echo $flag;
//	}else{
//		echo "0";
//	}
	
	
}
if(isset($_POST['login']))
{
	if($_POST['login']==1) check_login();
}