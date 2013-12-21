<?php
/**
 * 实时接口类
 *
 * ==============================================================================================================================================
 * 说明：
 * 		客户点击亿起发推广的广告产生订单,此时需要及时调用该接口向亿起发push(推送)数据
 * 		部分属性暂时先定义下来，方便将来扩展。
 * ==============================================================================================================================================
 * 示例：
 * 		$sender = new Sender();
 * 		$sender -> send();
 * ==============================================================================================================================================
 * @author lsj
 * @package advertiser
 * @see advertiser.Order
 * @see advertiser.Product
 * @license 
 * @version 0.2
 */
 class Sender { 	
 	
 	/** 亿起发接口地址 */
	private $receiverPath = "http://o.yiqifa.com/servlet/handleCpsInterIn";
 	
	/** 订单发送结果,0,表示正常发送.*/
	private static $SEND_STATUS_SUCCESS = 0;
	 
	/** 订单发送结果, 1表示缺少必要的参数 */
	private static $SEND_STATUS_LACK_PARAMETERS = 1;
	
	/** 订单发送结果. 2表示发送地址错误.*/
	private static $SEND_STATUS_TCP_ERROR= 2;
	
	/**	订单发送结果,3表示链接超时.*/
	private static $SEND_STATUS_TIMEOUT  = 3;
		
	/** 订单发送结果,4表示URL格式错误. */
	private static $SEND_STATUS_URL_ERRO = 4;
	
	/** 订单发送结果, 5表示IO异常.*/	
	private static $SEND_STATUS_IO_ERRO  = 5;
	
	/** 订单发送结果,-1表示发送失败. 	*/
	private static $SEND_STATUS_OTHER_ERRO = -1;
	
	/** 链接超时的时间.	*/
	private static $CONNECT_TIMEOUT = 3000;
	
	/** 响应时间 */
	private static $READ_TIMEOUT 	= 3000;
		
	/** 工具类*/
	private $config = null;
	
	/** 订单*/
	private $order  = NULL;
	
 	function Sender(){
		 $this->config = new Config();
		 $this->order  = new Order();
		 $this->orderStatus  = new OrderStatus();
	}

	public function getOrder() {
		return $this->order;
	}

	public function setOrder($order) {
		$this->order = $order;
	}

	public function getOrderStatus() {
		return $this->orderStatus;
	}

	public function setOrderStatus($orderStatus) {
		$this->orderStatus = $orderStatus;
	}
	

	/***************************************************************************
	 * push实时数据方法,此方法将判断一些必须的参数是否为空，如果为空将被将被终止
	 * 否则，则读取cookie、调用sendParameters方法组合链接，向亿起发发送数据.
	 * @license 
	 * @see sendParameters
	 * @version 1.0.1
	 ****************************************************************************/	
 	function sendOrder() { 	
		if ($this->order == null) {
			return self::$SEND_STATUS_LACK_PARAMETERS;
		}		
		
		if (count($this -> order -> getProducts()) == 0) {
			return self::$SEND_STATUS_LACK_PARAMETERS;
		}

		if (strlen($this -> order -> getCampaignId())== 0) {
			$cid = $this -> config -> getString("default_campaign_id");
			$this -> order -> setCampaignId($cid);
		}
		$sendURL = $this -> sendURLByJSONOrder();
		// 创建一个curl回话
		$ch = curl_init();

		// 获取发送数据的地址
		

        curl_setopt($ch,CURLOPT_URL,$sendURL);
     	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// 设置POST方式发送数据
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $sendURL);
		$fs = curl_exec($ch);
		curl_close($ch);

		return $sendURL;

	
		/*if ($fs == 0){
			return self::$SEND_STATUS_SUCCESS;
		}else if ($fs == 1){
			return self::$SEND_STATUS_URL_ERRO;
		}else if ($fs == 2){
			return self::SEND_STATUS_LACK_PARAMETERS;
		}*/
 	}
    
	function sendOrderStatus(){
	
		
		// 创建一个curl回话
		//$ch = curl_init();

		// 获取发送数据的地址
		$sendURL = $this -> sendURLByJSONOrderStatus();
		//header("Location: ".$sendURL);
        //curl_setopt($ch,CURLOPT_URL,$sendURL);
     	//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// 设置POST方式发送数据
		//curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $sendURL);
		//$fs = curl_exec($ch);
		//curl_close($ch);

		return $sendURL;
	
 	}
	
    /*************************
	 *订单信息参数组装成为json的格式发送
	 **************************/
	function sendURLByJSONOrder(){


		$sb = $this->receiverPath . "?" ."interId=".$this ->config -> getString("interId"). "&json=".urlencode("{\"orders\":[" .$this->JSON($this->object_to_array($this->order))."]}")."&encoding=".$this->config->getString("default_charset");

		return $sb;
		//echo $sb;
		//exit;
	
	}
	 /*************************
	 *订单状态参数组装成为json的格式发送
	 **************************/
		function sendURLByJSONOrderStatus(){

		//$this->order->setEncoding($this->config->getString("default_charset"));
		
		$sb = $this->receiverPath . "?" ."interId=".$this ->config -> getString("interId"). "&json=".urlencode("{\"orderStatus\":[" .$this->JSON($this->object_to_array($this->orderStatus))."]}")."&encoding=".$this->config->getString("default_charset");

		//return $sb;
		echo $sb;
		exit;
	}
	function object_to_array($obj)
	{
	    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
		foreach ($_arr as $key => $val)
		{
			$val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
			$arr[$key] = $val;
		}
		return $arr;
	}
	/**************************************************************
	 *
	 *	使用特定function对数组中所有元素做处理
	 *	@param	string	&$array		要处理的字符串
	 *	@param	string	$function	要执行的函数
	 *	@return boolean	$apply_to_keys_also		是否也应用到key上
	 *	@access public
	 *
	 *************************************************************/
	function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
	{
		static $recursive_counter = 0;
		if (++$recursive_counter > 1000) {
			die('possible deep recursion attack');
		}
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
			} else {
				$array[$key] = $function($value);
			}
	 
			if ($apply_to_keys_also && is_string($key)) {
				$new_key = $function($key);
				if ($new_key != $key) {
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
		}
		$recursive_counter--;
	}
 
	/**************************************************************
	 *
	 *	将数组转换为JSON字符串（兼容中文）
	 *	@param	array	$array		要转换的数组
	 *	@return string		转换得到的json字符串
	 *	@access public
	 *
	 *************************************************************/
	function JSON($array) {
		$this->arrayRecursive($array, 'urlencode', true);
		$json = json_encode($array);
		return urldecode($json);
	}

	/********************************************************
	 * 获取与配置文件"yiqifa-config.php"中cookie名字相同的cookie。
	 * 
	 * @param cookieName
	 * @return 返回符合条件cookie的值
	 *******************************************************/
	function getCookieValue($cookieName) {							
		return $_COOKIE[$cookieName];
	}
 }
?>