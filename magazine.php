<?php
	header("Location: index.htm");
	die;

	define('IN_ECS', true);
	require(dirname(__FILE__) . '/includes/init.php');
	$id = isset($_REQUEST['id'])  ? intval($_REQUEST['id']) : 0;
	$smarty->assign('id',$id);
	$smarty->display('magazine.dwt');
	?>
