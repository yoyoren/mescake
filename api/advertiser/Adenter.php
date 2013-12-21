<?php
	class Adenter{		 
		private $DEFAULT_CAMPAIGN_ID = "101";					// 活动id 即cid，上线时需要设置亿起发提供的cid
		private $DEFAULT_TARGET 	 = "http://www.baidu.com"; 	 // 着陆页面，默认是首页
		private $DEFAULT_CHANNEL     = "cps";					// 业务合作方式
		public 	$UNION_COOKIE_NAME 	 = "yiqifa";				// 保存的cookie名
		private $UNION_COOKIE_DOMAIN = ".mescake.com"; 			// 替换为自己网站的域
		private $UNION_COOKIE_MAXAGE = 2592000;					// cookie有效期，默认是30天
		private $CLEAN_COOKIE_NAMES  = ",sina,linkt,chengguo,";// 如果有多个来源的数据，且cookie名字不相同，请写在这里

		function Adenter(){
		
			$config = new Config();		
			//必要参数开始
			$cid = $config -> getString("default_campaign_id");
			if (!empty($cid)) {
				$this -> DEFAULT_CAMPAIGN_ID = $cid;
			}
			
			$target = $config -> getString("default_target");
			if (!empty($target)) {
				$this -> DEFAULT_TARGET = $target;
			}
			
			$cdomain = $config -> getString("union_cookie_domain");
			if (!empty($cdomain)) {
				$this -> UNION_COOKIE_DOMAIN = $cdomain;
			}
			
			$channel = $config -> getString("default_channel");
			if (!empty($channel)) {
				$this -> DEFAULT_CHANNEL = $channel;
			}
			
			$cname = $config -> getString("union_cookie_name");
			if (!empty($cname)) {
				$this -> UNION_COOKIE_NAME = $cname;
			}
			
			$cage = $config -> getString("union_cookie_maxage");
			if (!empty($cage)) {
				$this -> UNION_COOKIE_MAXAGE = $cage;
			}
			
			$cnames = $config -> getString("clean_cookie_names");
			if (!empty($cnames)) {
				$this -> CLEAN_COOKIE_NAMES = $cnames;
			}
		}
		
		function jump($source,$channel,$campagin_id,$yiqifa_wi,$target_url){	
			if (empty($this-> UNION_COOKIE_DOMAIN)) {	// 如果域名为空,则抛出异常
				throw new Exception("Cookie domain is null!",136);
			}

			if (empty($this -> DEFAULT_CAMPAIGN_ID) || !is_numeric($this->DEFAULT_CAMPAIGN_ID)) {// 如果活动id为空,则抛出异常
				throw new Exception();
			}
			if (empty($this -> DEFAULT_TARGET)) {		// 如果目标地址为空,则抛出异常
				throw new Exception();
			}

			/* =============================参数校验============================== */
			if (empty($target_url)) {					// 如果目标地址为空，设置为默认目标地址
				$target_url = $this-> DEFAULT_TARGET;
			} else if (!strcmp(substr($target_url, 0,6),"http://")
					&& !strcmp(substr($target_url, 0,7),"https://")) { // 如果目标地址不是以“http://”或“https://”开头，则加上"http://"
				$target_url = "http://" + $target_url;
			}
		   if(stripos($target_url,$this -> UNION_COOKIE_DOMAIN)===false){ //如果目标地址为其他域名的地址，替换成默认的跳转页面
				$target_url = $this-> DEFAULT_TARGET;
			}
			
			if (empty($source)) { 							// 如果无来源，不记录Cookie直接跳转到target指定的地址
				header('Location: '.$target_url.'/');
				return;
			}
			
			//请求域名
			$http_host  = $_SERVER["HTTP_HOST"];
			//请求的地址
			$requestUrl = $_SERVER["REQUEST_URI"] ;
			//获取地址里面的参数
			$visitParam = $_SERVER["QUERY_STRING"];
			//获取详细的请求地址
			$visitReferer = $_SERVER["HTTP_REFERER"];	
			$visit = "";
			if (!empty($visitParam)) { 				// 拼装详细的请求地址
				$visit = $http_host.$requestUrl;
			}		

			if (empty($visitReferer)){
				$visitReferer = "";
			}
			if (empty($channel)) {
				$channel = $this ->  DEFAULT_CHANNEL;
			}
	
			if (!is_numeric($campagin_id)) { // 如果活动ID缺失或不是数字，则替换为默认的活动ID，正确的活动ID由亿起发提供
				$cid = $this ->  DEFAULT_CAMPAIGN_ID;
			}
			if (empty($yiqifa_wi)) { // 如果站点标识为空，抛出异常
				throw new Exception("wi is null!");
			}
	
			// 查看是否有其他联盟的cookie，如果有清掉，防止给两家联盟结算
			if ($this -> CLEAN_COOKIE_NAMES != null) {
				$list = explode(",",$this -> CLEAN_COOKIE_NAMES);
				foreach ($list as $value){
					 if(strlen($_COOKIE[$value]) > 0){					 			
					 	setcookie($value,'',time() - $this -> UNION_COOKIE_MAXAGE,'/');
					 }
				}
			}

			
			// 写入最新来源的cookie
			$yiqifa_wi  = empty($yiqifa_wi) ? "" : $yiqifa_wi;
			$cookieValue = $source . ":" . $channel . ":" . $campagin_id . ":" . $yiqifa_wi ;
			$cookieValue = urlencode($cookieValue);	
			//setcookie($this->UNION_COOKIE_NAME,$cookieValue,time() + $this->UNION_COOKIE_MAXAGE,"/");
			setcookie($this->UNION_COOKIE_NAME,$cookieValue,time() + $this->UNION_COOKIE_MAXAGE,"/",$this -> UNION_COOKIE_DOMAIN);
			echo urldecode($cookieValue);	
			header("Location: ".$target_url);
		}
	}
?>