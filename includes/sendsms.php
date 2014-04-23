<?php
function sms_send($mobile,$type,$c='')
{
    $sendtime=date("Y-m-d H:i:s",time()+8*3600);
	if($type==1) 
	$c="尊敬的用户，您在每实订购的蛋糕已经确认，我们将按时为您送达。如有问题请与每实客服中心联系，电话4000 600 700，再次感谢您对每实蛋糕的钟爱与支持！";
	$cont=urlencode($c);
	echo file_get_contents('http://sdk.kuai-xin.com:8888/sms.aspx?action=send&userid=4333&account=s120018&password=wangjianming123&mobile='.$mobile.'&content='.$cont.'&sendTime=');
}

function sms_send2($mobile,$type,$c='')
{
    $sendtime=date("Y-m-d H:i:s",time()+8*3600);
	if($type==1) 
	$c="尊敬的用户，您在每实订购的蛋糕已经确认，我们将按时为您送达。如有问题请与每实客服中心联系，电话4000 600 700，再次感谢您对每实蛋糕的钟爱与支持！";
	$cont=urlencode($c);
	 file_get_contents('http://sdk.kuai-xin.com:8888/sms.aspx?action=send&userid=4333&account=s120018&password=wangjianming123&mobile='.$mobile.'&content='.$cont.'&sendTime=');
}

//已经支付订单发送短信
function sms_send_pay_success($mobile,$type,$c='')
{
    $sendtime=date("Y-m-d H:i:s",time()+8*3600);
	if($type==1) 
	$c="尊敬的用户，您在每实订购的蛋糕已经支付成功，我们将按时为您送达。如有问题请与每实客服中心联系，电话4000 600 700，再次感谢您对每实蛋糕的钟爱与支持！";
	$cont=urlencode($c);
	file_get_contents('http://sdk.kuai-xin.com:8888/sms.aspx?action=send&userid=4333&account=s120018&password=wangjianming123&mobile='.$mobile.'&content='.$cont.'&sendTime=');
}

//未支付订单发送短信
function sms_send_unpay($mobile,$type,$c='')
{
    $sendtime=date("Y-m-d H:i:s",time()+8*3600);
	if($type==1) 
	$c="尊敬的用户，您在每实订购的蛋糕订单已经提交，请尽快完成支付。如有问题请与每实客服中心联系，电话4000 600 700，再次感谢您对每实蛋糕的钟爱与支持！";
	$cont=urlencode($c);
	file_get_contents('http://sdk.kuai-xin.com:8888/sms.aspx?action=send&userid=4333&account=s120018&password=wangjianming123&mobile='.$mobile.'&content='.$cont.'&sendTime=');
}