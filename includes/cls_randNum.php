<?php
class randNum
{
    function check_word($word)
    {
        $recorded = isset($_SESSION['captcha']) ? $_SESSION['captcha'] : '';
        $given    = $this->encrypts_word($word);

        return (preg_match("/$given/", $recorded));
    }
    function encrypts_word($word)
    {
        return substr(md5(strtoupper($word)), 1, 10);
    }
    function record_word($word)
    {
        $_SESSION['captcha'] = $this->encrypts_word($word);
    }
    function generate_word($length = 6)
    {
        $chars = '1234567890';

        for ($i = 0, $count = strlen($chars); $i < $count; $i++)
        {
            $arr[$i] = $chars[$i];
        }

        mt_srand((double) microtime() * 1000000);
        shuffle($arr);

        return substr(implode('', $arr), 3, $length);
    }
	function sendsms($mobile,$captcha)
	{
	   $c = "尊敬的用户，您在每实官网手机校验码为".$captcha."。如有问题请与每实客服中心联系，电话4000 600 700。";
	   $cont=urlencode($c);
		file_get_contents('http://sdk.kuai-xin.com:8888/sms.aspx?action=send&userid=4333&account=s120018&password=wangjianming123&mobile='.$mobile.'&content='.$cont.'&sendTime=');
	}
}

?>