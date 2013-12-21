<?php

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if($_POST['step']=='historyad1')
{
	 $user_id=$_SESSION['user_id'];
	 $address1=$_POST['address1'];
	 $contact1=$_POST['contact1'];
	 $tel1=$_POST['tel1'];
	$address_id=$_POST['address_id'];
	$country1=$_POST["country1"];
	$city1=$_POST["city1"];
	 if( $address_id=="undefined")
	 {
		$address_id=null;
	 }
		if($address_id==null)
		{
			$db->query("INSERT INTO ecs_user_address(address_name, user_id, consignee, country, province, city, district, address, tel, mobile, money_address, route_id, ExchangeState, ExchangeState2)
			VALUES('',{$user_id},'{$contact1}','{$country1}','0', '{$city1}', '0', '{$address1}', '', '{$tel1}', NULL, '0', '0', '0')");
			echo '0';
		}
		else
		{
		
			$db->query("update ecs_user_address set country={$country1},city={$city1},consignee='{$contact1}',address='{$address1}',mobile='{$tel1}'
			where address_id={$address_id}");
				echo '1';
		}
}
if($_POST['step']=='historyad2')
{
	 $user_id=$_SESSION['user_id'];
	 $address_id=$_POST['address_id'];
	 $address2=$_POST['address2'];
	 $contact2=$_POST['contact2'];
	 $tel2=$_POST['tel2'];
	 $address_id=$_POST['address_id'];
	 $country2=$_POST["country2"];
	$city2=$_POST["city2"];
	 if( $address_id=="undefined")
	 {
		$address_id=null;
	 }
		if($address_id==null)
		{
			$db->query("INSERT INTO ecs_user_address(address_name, user_id, consignee, country, province, city, district, address, tel, mobile, money_address, route_id, ExchangeState, ExchangeState2)
			VALUES('',{$user_id},'{$contact2}','{$country2}','0', '{$city2}', '0', '{$address2}', '', '{$tel2}', NULL, '0', '0', '0')");
			echo '2';
		}
		else
		{
		
			$db->query("update ecs_user_address set country={$country2},city={$city2},consignee='{$contact2}',address='{$address2}',mobile='{$tel2}'
			where address_id={$address_id}");
			echo '3';
		}
}
if($_POST['step']=='historyad3')
{
	 $user_id=$_SESSION['user_id'];
	 $address_id=$_POST['address_id'];
	 if( $address_id=="undefined")
	 {
		$address_id=null;
	 }
//	echo $address_id;
	 $address3=$_POST['address3'];
	 $contact3=$_POST['contact3'];
	 $tel3=$_POST['tel3'];
	 $country3=$_POST["country3"];
	$city3=$_POST["city3"];
		if( $address_id == null )
		{
			$db->query("INSERT INTO ecs_user_address(address_name, user_id, consignee, country, province, city, district, address, tel, mobile, money_address, route_id, ExchangeState, ExchangeState2)
			VALUES('',{$user_id},'{$contact3}','{$country3}','0', '{$city3}', '0', '{$address3}', '', '{$tel3}', NULL, '0', '0', '0')");
			echo '4';
		}
		else
		{
			$db->query("update ecs_user_address set country={$country3},city={$city3},consignee='{$contact3}',address='{$address3}',mobile='{$tel3}'
			where address_id={$address_id}");
			echo '5';
		}
}