<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$sql02="select * from ecs_user_address where user_id={$_SESSION['user_id']} limit 0,1";	
$address1=$db->getRow($sql02);
if($address1['city'])
{
	$city1=$db->getOne("select region_name from ship_region where region_id={$address1['city']}");
	$address1['qu']=$city1;
}
$address2=$db->getRow("select * from ecs_user_address where user_id={$_SESSION['user_id']} limit 1,1");
if($address2['city'])
{
	$city2=$db->getOne("select region_name from ship_region where region_id={$address2['city']}");
	$address2['qu']=$city2;
}
$address3=$db->getRow("select * from ecs_user_address where user_id={$_SESSION['user_id']} limit 2,1");
if($address3['city'])
{
	$city3=$db->getOne("select region_name from ship_region where region_id={$address3['city']}");
	$address3['qu']=$city3;
}
//print_r($address1);
//print_r($address2);
//print_r($address3);
$rowss=$db->getRow("select * from ecs_user_address where user_id={$_SESSION['user_id']} limit 1");
$smarty->assign('accepter', $rowss);
$district_list=$db->getAll('select * from ship_region where parent_id=501');
$smarty->assign('district_list', $district_list);
$smarty->assign('address1',$address1);
$smarty->assign('address2',$address2);
$smarty->assign('address3',$address3);
$smarty->display('historyaddress.dwt');



?>