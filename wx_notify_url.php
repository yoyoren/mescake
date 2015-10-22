<?php
 define('IN_ECS', true);
 require (dirname(__FILE__) . '/includes/init.php');
 require (dirname(__FILE__) . '/includes/lib_payment.php');
 $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
 $p = xml_parser_create();
 xml_parse_into_struct($p, $postStr, $vals, $index);
 xml_parser_free($p);
 for($i=0;$i<count($vals);$i++){
	$obj = $vals[$i];
	if($obj['tag'] == 'OUT_TRADE_NO'){
		$log_id = $obj['value'];
		order_paid($log_id);
		file_put_contents('wxpay.log',$obj['value']);
	}
  }
?>