<?php
function sms_send($mobile,$type,$c='')
{
    $sendtime=date("Y-m-d H:i:s",time()+8*3600);
	if($type==1) 
	$c="尊敬的用户，您在每实订购的蛋糕已经确认，我们将按时为您送达。如有问题请与每实客服中心联系，电话4000 600 700，再次感谢您对每实蛋糕的钟爱与支持！";
	$cont=urlencode($c);
	echo file_get_contents('http://sdk.kuai-xin.com:8888/sms.aspx?action=send&userid=4333&account=s120018&password=wangjianming123&mobile='.$mobile.'&content='.$cont.'&sendTime=');
}
//$mobile='13811000692,13488724577';
//sms_send($mobile,1);
function sms_send2($mobile,$type,$c='')
{
    $sendtime=date("Y-m-d H:i:s",time()+8*3600);
	if($type==1) 
	$c="尊敬的用户，您在每实订购的蛋糕已经确认，我们将按时为您送达。如有问题请与每实客服中心联系，电话4000 600 700，再次感谢您对每实蛋糕的钟爱与支持！";
	$cont=urlencode($c);
	 file_get_contents('http://sdk.kuai-xin.com:8888/sms.aspx?action=send&userid=4333&account=s120018&password=wangjianming123&mobile='.$mobile.'&content='.$cont.'&sendTime=');
}