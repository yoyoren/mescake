<?php
include_once '../util/Config.php';
include_once ("Adenter.php");	
	$yqf_src = $_GET ['source'];
	$yqf_channel = $_GET ['channel'];
	$yqf_cid = $_GET ['cid'];
	$yqf_wi = $_GET ['wi'];
	$target_url = $_GET ['target'];
	$write_cookie = new Adenter();
	$write_cookie->jump ($yqf_src, $yqf_channel, $yqf_cid, $yqf_wi, $target_url );
?>