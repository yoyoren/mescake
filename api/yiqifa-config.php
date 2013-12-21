<?php
	/**
	* 配置文件
	* ==========================================================================
	* 说明：
	* 		该文件中变量相对来说比较重要，里面的内容变量内容部分需要自己配置和设置
	* 
	* ==========================================================================
	*@author lsj
	*@version 0.2
	*/


	//默认活动编号(cid)
	define("default_campaign_id", "101");	
	
	//默认的目标地址(url)，一般默认的是首页
	//define("default_target", "http://localhost/mescake");
	define("default_target", "http://www.mescake.com/");
	
	//cookie保存域(建议保存这样的域名：.yiqifa.com)
	define("union_cookie_domain",".mescake.com");
	
	//默认活动类型
	define("default_channel", "cps");
	
	//编码方式(针对这一套sdk，都是GBK的编码，如果你们的环境是UTF-8，请修改这个编码)
	define("default_charset", "UTF-8");
	
	//cookie名字
	define("union_cookie_name","emar");
	
    //cookie有效期,单位为毫秒(30天)
    define("union_cookie_maxage",2592000);
    
    //其他合作伙伴cookie名字(与亿起发同类的公司)开头、结尾都是逗号
    define("clean_cookie_names",",yiqifa,linkt,");	
			
	//链接时间,单位为毫秒
	define("connect_timeout",3000);
	
	//响应时间,单位为毫秒
	define("read_timeout", 3000);
	
	//是否进行IP限制,true限制ip，false无限制ip
	define("limit_ip",false);

	//允许访问的ip地址
	define("ip_list","127.0.0.3,127.0.0.2");

	//是否进行签名验证,true签名验证，false不进行签名验证
	define("is_sign",false);

	//每一个接口都会存在这么一个值，需要跟相关的技术跟你沟通要得
	define("interId","52311e189497ca0284130d94");
?>