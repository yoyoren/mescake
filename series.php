<?php

/**
 * ECSHOP 首页文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: index.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

//ajax无刷新系列页面

	$brand_id=$_POST['brand_id'];
//三个系列页面
		if($brand_id==1)
		{
			$goods_list=$db->getAll("select * from ecs_goods  where goods_id in (1,5,6,7,8,9,11) and is_on_sale = 1");
			//print_r($goods_list);
			echo "<div class='clearfix'  style='width:804px;border:0px solid;padding-left:0px;'>";
					
				foreach($goods_list as $goods)
				{
					echo"	<div style='float:left;width:200px;height:200px;border:0px solid;padding-left:0px;margin-top:0px;'><br/>";
					echo"	<a href='goods.php?id={$goods['goods_id']}'><img src='themes/default/images/all/{$goods['goods_id']}.jpg' width='175' height='175'/></a>";
					echo"	</div>";
					
					//$smarty->assign('goods_list',      $goods_list);
				}
			echo "</div>";
		}
		if($brand_id==2)
		{
			$goods_list=$db->getAll('select * from ecs_goods where goods_id in(27,22,26,25,19,17,16,15,20,18,21,30,32) and is_on_sale=1');
			echo "<div class='clearfix'  style='width:804px;border:0px solid red;padding-left:0px;'><br/>";
					
				foreach($goods_list as $goods)
				{
					echo"	<div style='float:left;width:200px;height:200px;border:0px solid;padding-left:0px;margin-top:0px;'>";
					echo"	<a href='goods.php?id={$goods['goods_id']}'><img src='themes/default/images/all/{$goods['goods_id']}.jpg' width='175' height='175'/></a>";
					echo"	</div>";
					
					//$smarty->assign('goods_list',      $goods_list);
				}
			echo "</div>";
			//$smarty->assign('goods_list',      $goods_list);
		}
		if($brand_id==3)
		{
			$goods_list=$db->getAll('select * from ecs_goods where goods_id in(35,37,38,33) and is_on_sale=1');
			echo "<div class='clearfix'  style='width:804px;border:0px solid red;padding-left:0px;'><br/>";
					
				foreach($goods_list as $goods)
				{
					echo"	<div style='float:left;width:200px;height:200px;border:0px solid;padding-left:0px;margin-top:0px;'>";
					echo"	<a href='goods.php?id={$goods['goods_id']}'><img src='themes/default/images/all/{$goods['goods_id']}.jpg' width='175' height='175'/></a>";
					echo"	</div>";
					
					//$smarty->assign('goods_list',      $goods_list);
				}
			echo "</div>";
			//$smarty->assign('goods_list',      $goods_list);
		}
		if($brand_id==4)
		{

			//all页面
			$all_goods=$db->getAll(' select * from ecs_goods where goods_id>0 and goods_id<39 and goods_id<>2 and goods_id<> 34 and is_on_sale=1');
			echo "<div class='clearfix'  style='width:804px;border:0px solid red;padding-left:0px;'><br/>";
					
				foreach($all_goods as $goods)
				{
					echo"	<div style='float:left;width:200px;height:200px;border:0px solid;padding-left:0px;margin-top:0px;'>";
					echo"	<a href='goods.php?id={$goods['goods_id']}'><img src='themes/default/images/all/{$goods['goods_id']}.jpg' width='175' height='175'/></a>";
					echo"	</div>";
					
					//$smarty->assign('goods_list',      $goods_list);
				}
			echo "</div>";
	
			//print_r($all_goods);
			//$smarty->assign('all_goods',$all_goods);
		}


?>