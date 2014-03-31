<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {

	}
}
if ($token) {
	$_SESSION['weibotoken'] = $token;
	setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
	header("Location:../route.php?mod=huodong&action=cat_page&auth=true");
} else {
	echo "授权失败。";
}
?>