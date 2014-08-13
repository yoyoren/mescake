<?php

require_once('lib/fee.php');
require_once('model/sec.php');


class MES_User{

	//获得街道的地址
	private static function _get_distruct_name($city,$district){
		$hash = MES_Fee::get_fee_region();
		if($hash[$city]&&$city){
			if($hash[$city][$district]){
				return  $hash[$city][$district]['name'];
			}
		}
		return '';
	}
	
	
	
	private static function _send_sms($c,$mobile){
		$cont=urlencode($c);
		$url = 'http://sdk.kuai-xin.com:8888/sms.aspx?action=send&userid=4333&account=s120018&password=wangjianming123&mobile='.$mobile.'&content='.$cont.'&sendTime=';
		file_get_contents($url);
	}

    private static function _set_login_session ($username=''){
            $sql = "SELECT user_id, password, email FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_name='$username' LIMIT 1";
            $row = $GLOBALS['db']->getRow($sql);
            if ($row){
                $_SESSION['user_id']   = $row['user_id'];
                $_SESSION['user_name'] = $username;
                $_SESSION['email']     = $row['email'];
				$time_lasts=3600*24;
				//redis里共享这个userid;
				SETEX_REDIS($username,$row['user_id'],$time_lasts,'user_id');
            }
    }

    //密码加密加盐
	private static function compile_password ($cfg){

       if (isset($cfg['password'])){
            $cfg['md5password'] = md5($cfg['password']);
       }

       if (empty($cfg['type'])){
            $cfg['type'] = PWD_MD5;
       }

       switch ($cfg['type']){
           case PWD_MD5 :
               if(!empty($cfg['ec_salt'])){
			       return md5($cfg['md5password'].$cfg['ec_salt']);
		       }else{
                    return $cfg['md5password'];
			   }

           case PWD_PRE_SALT :
               if (empty($cfg['salt'])){
                    $cfg['salt'] = '';
               }

               return md5($cfg['salt'] . $cfg['md5password']);

           case PWD_SUF_SALT :
               if (empty($cfg['salt'])){
                    $cfg['salt'] = '';
               }

               return md5($cfg['md5password'] . $cfg['salt']);

           default:
               return '';
       }
    }

	 private static function check_user($username, $password = null){
		global $db;
		$user_table = $GLOBALS['ecs']->table("users");
        $post_username = $username;
        if ($password === null){
			//test pass
            $data = MES_Sec::get_userid_by_username($username);
            return $data;
        }else{
			//test pass
			$row = MES_Sec::get_userinfo_by_username($username);
			$ec_salt=$row['ec_salt'];
            if (empty($row)){
                return 0;
            }

            if (empty($row['salt']))
            {
                if ($row['password'] != MES_User::compile_password(array('password'=>$password,'ec_salt'=>$ec_salt))){
                    return 0;
                }else{
					//对于没有加盐的注册用户
					if(empty($ec_salt)){
						$ec_salt=rand(1,9999);
						$new_password=md5(md5($password).$ec_salt);
						MES_Sec::update_password_and_salt($post_username,$new_password,$ec_salt);
					}
                    return $row['user_id'];
                }
            } else {
                /* 如果salt存在，使用salt方式加密验证，验证通过洗白用户密码 */
                $encrypt_type = substr($row['salt'], 0, 1);
                $encrypt_salt = substr($row['salt'], 1);

                /* 计算加密后密码 */
                $encrypt_password = '';
                switch ($encrypt_type)
                {
                    case ENCRYPT_ZC :
                        $encrypt_password = md5($encrypt_salt.$password);
                        break;
                    case ENCRYPT_UC :
                        $encrypt_password = md5(md5($password).$encrypt_salt);
                        break;

                    default:
                        $encrypt_password = '';

                }

                if ($row['password'] != $encrypt_password) {
                    return 0;
                }

                $sql = "UPDATE " . $user_table .
                       " SET password = '".  MES_User::compile_password(array('password'=>$password)) . "', salt=''".
                       " WHERE user_id = '$row[user_id]'";
                $db->query($sql);
                return $row['user_id'];
            }
        }
    }

	private static function _login($username, $password, $remember = null){
        $cookie_path  = '/';
		if (MES_User::check_user($username, $password) > 0){
            MES_User::_set_login_session($username);
			$time_lasts=3600*24;
			$time = time()+$time_lasts;
			$token = md5($username.$password.'_mescake');
			SETEX_REDIS($username,$token,$time_lasts,'user');
            setcookie("serviceToken",$token, $time, $cookie_path);            
            setcookie("uuid",$username, $time, $cookie_path);
            return true;
        }else{
            return false;
        }
	}

	public static function ajax_login($username,$password){
		global $db;
		global $_LANG;
		global $user;
		$username = addslashes($username);
		$password = addslashes($password);

		$json = new JSON;
		$result   = array(
			'code' => RES_SUCCSEE, 
			'content' => 'login successs'
		);
		//test pass
		$username = MES_Sec::get_decode_username_by_mobile($username);

		if(empty($username)){
			$result['code'] = RES_FAIL;
			$result['content'] = $_LANG['login_failure'];
			return $json->encode($result);
		}
		if (MES_User::_login($username, $password)){
			$ucdata = empty($user->ucdata)? "" : $user->ucdata;
			$result['ucdata'] = $ucdata;

			$_SESSION['usermsg']= get_user_info();
		}else{
			$_SESSION['login_fail']++;
			$result['error'] = 1;
			$result['code'] = RES_FAIL;
			$result['content'] = $_LANG['login_failure'];
		}
		return $json->encode($result);
	}

	public static function get_user_info($user_id){
		global $db;
		$user_id = addslashes($user_id);
		$user = MES_Sec::get_userinfo_by_userid($user_id);

		unset($user['question']);
		unset($user['answer']);

		/* 格式化帐户余额 */
		if ($user){
			$user['formated_user_money'] = price_format($user['user_money'], false);
			$user['formated_frozen_money'] = price_format($user['frozen_money'], false);
		}

		return $user;
	}


    private static function clear_cookie_for_logout(){
		$cookie_path  = '/';
		$time = time() - 3600;
        setcookie("ECS[user_id]",  '', $time, $cookie_path);            
        setcookie("ECS[password]", '', $time, $cookie_path);
	    setcookie("uuid", '', $time, $cookie_path);
		setcookie("serviceToken", '', $time, $cookie_path);
	}

	private static function clear_session(){
		 $GLOBALS['sess']->destroy_session();
	}

	private static function clear_redis_for_logout(){
		$uuid = $_COOKIE['uuid'];
		DEL_REDIS($uuid,'user');
		DEL_REDIS($uuid,'user_id');
	}

	public static function logout(){
		MES_User::clear_session();
		MES_User::clear_cookie_for_logout();
		MES_User::clear_redis_for_logout();
		return json_encode(array(
			'code'=>RES_SUCCSEE,
			'msg'=>'success'
		));
	}

	public static function check_login(){
		$token = $_COOKIE['serviceToken'];
		$uuid = $_COOKIE['uuid'];
		$res = false;

		if($token&&GET_REDIS($uuid,'user') == $token){
			$res = true;
			if($_SESSION['user_auto_register_moblie']){
				$uname = $_SESSION['user_auto_register_moblie'];
			}else{
				$uname = $uuid;
			}
		}

		return json_encode(array(
			'code'=>RES_SUCCSEE,
			'msg'=>'success',
			'res'=>$res,
			'uname'=>$uname
		));
	}

	//服务器端用于检查是否登录的方法
	public static function server_check_login(){
		global $db;
		$token = $_COOKIE['serviceToken'];
		$uuid = $_COOKIE['uuid'];

		$res = false;
		if($token&&GET_REDIS($uuid,'user')== $token){
			$res = true;
		}
		return $res;
	}


	//更新用户信息
	//原来是在lib_main中定义的一个全局方法 似乎是没有必要的
	public static function update_user_info(){
		if (!$_SESSION['user_id']){
			return false;
		}

		//查询会员信息
		$time = date('Y-m-d');
		$sql = 'SELECT u.user_money,u.email, u.pay_points, u.user_rank, u.rank_points, '.
				' IFNULL(b.type_money, 0) AS user_bonus, u.last_login, u.last_ip'.
				' FROM ' .$GLOBALS['ecs']->table('users'). ' AS u ' .
				' LEFT JOIN ' .$GLOBALS['ecs']->table('user_bonus'). ' AS ub'.
				' ON ub.user_id = u.user_id AND ub.used_time = 0 ' .
				' LEFT JOIN ' .$GLOBALS['ecs']->table('bonus_type'). ' AS b'.
				" ON b.type_id = ub.bonus_type_id AND b.use_start_date <= '$time' AND b.use_end_date >= '$time' ".
				" WHERE u.user_id = '$_SESSION[user_id]'";
		if ($row = $GLOBALS['db']->getRow($sql))
		{
			/* 更新SESSION */
			$_SESSION['last_time']   = $row['last_login'];
			$_SESSION['last_ip']     = $row['last_ip'];
			$_SESSION['login_fail']  = 0;
			$_SESSION['email']       = $row['email'];

			/*判断是否是特殊等级，可能后台把特殊会员组更改普通会员组*/
			if($row['user_rank'] >0)
			{
				$sql="SELECT special_rank from ".$GLOBALS['ecs']->table('user_rank')."where rank_id='$row[user_rank]'";
				if($GLOBALS['db']->getOne($sql)==='0' || $GLOBALS['db']->getOne($sql)===null)
				{   
					$sql="update ".$GLOBALS['ecs']->table('users')."set user_rank='0' where user_id='$_SESSION[user_id]'";
					$GLOBALS['db']->query($sql);
					$row['user_rank']=0;
				}
			}

			/* 取得用户等级和折扣 */
			if ($row['user_rank'] == 0)
			{
				// 非特殊等级，根据等级积分计算用户等级（注意：不包括特殊等级）
				$sql = 'SELECT rank_id, discount FROM ' . $GLOBALS['ecs']->table('user_rank') . " WHERE special_rank = '0' AND min_points <= " . intval($row['rank_points']) . ' AND max_points > ' . intval($row['rank_points']);
				if ($row = $GLOBALS['db']->getRow($sql))
				{
					$_SESSION['user_rank'] = $row['rank_id'];
					$_SESSION['discount']  = $row['discount'] / 100.00;
				}
				else
				{
					$_SESSION['user_rank'] = 0;
					$_SESSION['discount']  = 1;
				}
			}
			else
			{
				// 特殊等级
				$sql = 'SELECT rank_id, discount FROM ' . $GLOBALS['ecs']->table('user_rank') . " WHERE rank_id = '$row[user_rank]'";
				if ($row = $GLOBALS['db']->getRow($sql))
				{
					$_SESSION['user_rank'] = $row['rank_id'];
					$_SESSION['discount']  = $row['discount'] / 100.00;
				}
				else
				{
					$_SESSION['user_rank'] = 0;
					$_SESSION['discount']  = 1;
				}
			}
		}

		/* 更新登录时间，登录次数及登录ip */
		$sql = "UPDATE " .$GLOBALS['ecs']->table('users'). " SET".
			   " visit_count = visit_count + 1, ".
			   " last_ip = '" .real_ip(). "',".
			   " last_login = '" .gmtime(). "'".
			   " WHERE user_id = '" . $_SESSION['user_id'] . "'";
		$GLOBALS['db']->query($sql);
	}

	//检查一个用户是否存在
	public static function check_user_exsit($mobile,$serverside=false){
		global $db;
		$res = array('code'=>RES_SUCCSEE);
		$mobile = addslashes($mobile);

		//test pass
		$user = MES_Sec::get_decode_userinfo_by_mobile($mobile);
		if($user['mobile_phone']==$mobile&&$user!=null){
			if($user['user_type']==11){
				$res['exsit'] = false;
				$res['autoregister'] = true;
			}else{
				$res['exsit'] = true;
			}
			 
		}else{
			 $res['exsit'] = false;
		}
		if($serverside){
			return $res;
		}
		return json_encode($res);
	}


    //添加用户username, $email,$password
    public static function add_user($username,$email,$password){
        /* 将用户添加到整合方 */
		
        if (MES_User::check_user($username) > 0)
        {
            $error = ERR_USERNAME_EXISTS;
            return false;
        }

        /* 检查email是否重复 */
        if (MES_Sec::check_email_exsit($email))
        {
            $error = ERR_EMAIL_EXISTS;
            return false;
        }

        $password = MES_User::compile_password(array('password'=>$password));

        MES_Sec::add_user($username,$email,$password);
        return true;
    } 

	//帮用户自动注册
	public static function auto_register($mobile){
		global $db;
		global $ecs;
		include_once(ROOT_PATH . 'includes/lib_passport.php');
        
		$msg='';
		$mobile = addslashes($mobile);
		$check_user = MES_User::check_user_exsit($mobile);

		//这个密码实际上会在注册后立即被更新成一个随机密码
        $password = '123456';
		$f_email= 'W' . $mobile . "@fal.com";

		$email = $f_email;
		$username = $f_email;
        $other['mobile_phone'] = $mobile;
		$other['rea_name'] = '';

        $back_act = '';
        if (MES_User::_register($username, $password, $email,$other) !== false){
        	//user type写成11代表自动注册
			//密码必须名文 因为这个要发送给用户
			$password = MES_User::rand_num();
			MES_Sec::update_auto_regsiter_password($username,$password);
			$_SESSION['user_msg'] = $username;
		}

		//标记一下这个用户是这次自动注册的
		$_SESSION['user_auto_register'] = '11';
		$_SESSION['user_auto_register_moblie'] = $mobile;
		return json_encode(array(
			'code' =>RES_SUCCSEE,
			'msg'=>$msg
		));
	}
	
	
	//检查管理员表
	private static function _admin_registered( $adminname ){
		//这个不需要加密
			$res = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('admin_user') .
										  " WHERE user_name = '$adminname'");
			return $res;
	}

     //之前定义在lib_passport内部的注册方法
	 public static function _register($username, $password, $email, $other = array()){
		
		//检查username
		if (empty($username)){
			$GLOBALS['err']->add($GLOBALS['_LANG']['username_empty']);
		}else{
			if (preg_match('/\'\/^\\s*$|^c:\\\\con\\\\con$|[%,\\*\\"\\s\\t\\<\\>\\&\'\\\\]/', $username)){
				$GLOBALS['err']->add(sprintf($GLOBALS['_LANG']['username_invalid'], htmlspecialchars($username)));
			}
		}

		//检查email
		if (empty($email)){
			return false;
		}

		if ($GLOBALS['err']->error_no > 0){
			return false;
		}

		//检查是否和管理员重名
		if (MES_User::_admin_registered($username)){
			return false;
		}

    
		if (!MES_User::add_user($username, $email,$password)){
			//注册失败
			return false;
		}else{
			//注册成功
			//设置成登录状态
			$GLOBALS['user']->set_session($username);
			$GLOBALS['user']->set_cookie($username);

			$time_lasts=3600*24;
			$time = time()+$time_lasts;
			$token = md5($username.$password.'_mescake');
			

			SETEX_REDIS($username,$token,$time_lasts,'user');

			//cookie的下发 要放到正确的路径下
			setcookie("serviceToken",$token, $time,'/');            
			setcookie("uuid",$username, $time,'/');
			
			//test pass
			$row = MES_Sec::get_userinfo_by_username($username);
			if ($row) {
				SETEX_REDIS($username,$row['user_id'],$time_lasts,'user_id');
			}

			
			//推荐处理
			$affiliate  = unserialize($GLOBALS['_CFG']['affiliate']);
			if (isset($affiliate['on']) && $affiliate['on'] == 1)
			{
				// 推荐开关开启
				$up_uid     = get_affiliate();
				empty($affiliate) && $affiliate = array();
				$affiliate['config']['level_register_all'] = intval($affiliate['config']['level_register_all']);
				$affiliate['config']['level_register_up'] = intval($affiliate['config']['level_register_up']);
				if ($up_uid){

					if (!empty($affiliate['config']['level_register_all'])){

						if (!empty($affiliate['config']['level_register_up'])){
							$rank_points = $GLOBALS['db']->getOne("SELECT rank_points FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_id = '$up_uid'");
							if ($rank_points + $affiliate['config']['level_register_all'] <= $affiliate['config']['level_register_up']){
								log_account_change($up_uid, 0, 0, $affiliate['config']['level_register_all'], 0, sprintf($GLOBALS['_LANG']['register_affiliate'], $_SESSION['user_id'], $username));
							}
						}else{
							log_account_change($up_uid, 0, 0, $affiliate['config']['level_register_all'], 0, $GLOBALS['_LANG']['register_affiliate']);
						}
					}

					//设置推荐人
					$sql = 'UPDATE '. $GLOBALS['ecs']->table('users') . ' SET parent_id = ' . $up_uid . ' WHERE user_id = ' . $_SESSION['user_id'];

					$GLOBALS['db']->query($sql);
				}
			}

			//定义other合法的变量数组
			$other_key_array = array('msn', 'qq', 'office_phone', 'home_phone', 'mobile_phone','rea_name');
			$update_data['reg_time'] = local_strtotime(local_date('Y-m-d H:i:s'));
			if ($other)
			{
				foreach ($other as $key=>$val)
				{
					//删除非法key值
					if (!in_array($key, $other_key_array))
					{
						unset($other[$key]);
					}
					else
					{
						$other[$key] =  htmlspecialchars(trim($val)); //防止用户输入javascript代码
					}
				}
				$update_data = array_merge($update_data, $other);
			}
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('users'), $update_data, 'UPDATE', 'user_id = ' . $_SESSION['user_id']);
			
			//注册的时候只写入了email和密码 至于手机号要单独更新
			update_user_info();      // 更新用户信息
			return true;
		}
	}



	public static function get_auto_register_mobile(){
		return json_encode(array(
			'code' =>RES_SUCCSEE,
			'msg'=>$_SESSION['user_auto_register_moblie']
		));
	}

	//帮没有设置密码的用户
	//帮没有设置密码的用户自动设置密码
	public static function change_unregister_password($password){
		global $db;
		
		$password = addslashes($password);
		//密码验证
		if(empty($password)||strlen($password)<6){
			return json_encode(array('code'=>RES_FAIL,'msg'=>'fail'));
		}

		
		$password = md5($password);
		$mobile = $_SESSION['user_auto_register_moblie'];
		$username = 'W' . $mobile . "@fal.com";

		if($_SESSION['user_auto_register'] == '11'){

			MES_Sec::update_password_for_unregitser_user($username,$password);
			unset($_SESSION['user_auto_register']);
			unset($_SESSION['user_auto_register_moblie']);
			return json_encode(array('code'=>RES_SUCCSEE,'msg'=>'success'));
		}else{
			return json_encode(array('code'=>RES_FAIL,'msg'=>'fail'));
		}
		
	}

	//检测用户是否可以重新设置密码
	public static function is_unset_password_user(){
		if($_SESSION['user_auto_register'] == '11'){
			return json_encode(array('code'=>RES_SUCCSEE,'msg'=>true));
		}else{
			return json_encode(array('code'=>RES_SUCCSEE,'msg'=>false));
		}
	}
	
	//获得一个订单的详情
	public static function get_user_order_detail($order_id){
		include_once(ROOT_PATH . 'includes/lib_transaction.php');
	    include_once(ROOT_PATH . 'includes/lib_payment.php');
	    include_once(ROOT_PATH . 'includes/lib_order.php');
	    include_once(ROOT_PATH . 'includes/lib_clips.php');
		
		GLOBAL $db;
		$order_id = addslashes($order_id);
	    $user_id = addslashes(GET_REDIS($_COOKIE['uuid'],'user_id'));
	    $res = array('code' =>RES_SUCCSEE);
	    /* 订单详情 */
	    $order = get_order_detail($order_id, $user_id);

	    if ($order === false){
	    	$res['code'] = RES_FAIL;
	        return json_encode($res);
	    }

	    /* 订单商品 */
	    $goods_list = order_goods($order_id);

		//true 只返回支付的地址 不需要返回给我们其他代码
	    $order_detail = get_order_detail($order_id,$user_id,true);
	   
	    foreach ($goods_list AS $key => $value){
	        $goods_list[$key]['market_price'] = price_format($value['market_price'], false);
	        $goods_list[$key]['goods_price']  = price_format($value['goods_price'], false);
	        $goods_list[$key]['subtotal']     = price_format($value['subtotal'], false);
	    }

	     /* 设置能否修改使用余额数 */
	    if ($order['order_amount'] > 0)
	    {
	        if ($order['order_status'] == OS_UNCONFIRMED || $order['order_status'] == OS_CONFIRMED)
	        {
	            $user = user_info($order['user_id']);
	            if ($user['user_money'] + $user['credit_line'] > 0){
					$order['allow_edit_surplus'] = 1;
					$order['max_surplus'] = sprintf($_LANG['max_surplus'], $user['user_money']);
	            }
	        }
	    }



	    //订单 支付 配送 状态语言项
	    //$order['order_status'] = $_LANG['os'][$order['order_status']];
	    //$order['pay_status'] = $_LANG['ps'][$order['pay_status']];
	    //$order['shipping_status'] = $_LANG['ss'][$order['shipping_status']];

		$city_name=$db->getOne("select region_name from ship_region where region_id={$order['city']}");

		$order['cityName'] = $city_name;
		$order['districtName'] = MES_User::_get_distruct_name($order['city'],$order['district']);
		
		if($order['fork_message']){
		   $order['fork_message'] = json_decode($order['fork_message']);
		}
		
		return json_encode(array(
			'code' =>RES_SUCCSEE,
			'order'=>$order,
			'goods_list'=>$goods_list,
			'pay_online'=>$order_detail['pay_online']
		));		
	}


	//获得一个用户所有的订单
	public static function get_user_order_list(){
		include_once(ROOT_PATH . 'includes/lib_transaction.php');
		include_once(ROOT_PATH . 'includes/lib_payment.php');
	    include_once(ROOT_PATH . 'includes/lib_order.php');
	    include_once(ROOT_PATH . 'includes/lib_clips.php');
		global $db;
		global $ecs;
		$user_id = GET_REDIS($_COOKIE['uuid'],'user_id');
		if(!$user_id){
			return json_encode(array('code' =>RES_FAIL,'msg'=>'user_id not exsit'));
		}

	    $orders = $db->getAll("SELECT * FROM " .$ecs->table('order_info'). " WHERE user_id = '$user_id' order by order_id DESC");
		$res=array();
		foreach($orders as $v){
			$order_id = $v['order_id'];
			$order_detail = $db->getAll("SELECT * FROM " .$ecs->table('order_goods'). " WHERE order_id = '$order_id'");

			$v['detail'] = $order_detail;
			$pay_online = get_order_detail($order_id,$user_id,true);
			$v['pay_online']=$pay_online;
			array_push($res,$v);
		}
		
	    return json_encode(array('code' =>RES_SUCCSEE,'orders'=>$res));
	}

	public static function del_one_order($order_id){
		include_once(ROOT_PATH . 'includes/lib_transaction.php');
		include_once(ROOT_PATH . 'includes/lib_common.php');
		global $db;
		global $ecs;
		$user_id = GET_REDIS($_COOKIE['uuid'],'user_id');
		
		//只有没有确认的订单才可以取消
		//$current_order = $db->getRow("select order_status,pay_status from ecs_order_info where user_id = '$user_id' and order_id = '$order_id'");
		$current_order = $db->getRow("select order_status,pay_status,bonus,surplus,bonus_id,order_sn from ecs_order_info where user_id = '$user_id' and order_id = '$order_id'");
		if($current_order['order_status']==OS_UNCONFIRMED&&$current_order['pay_status']!=PS_PAYED){
  			$sql = "update ecs_order_info set order_status =".OS_CANCELED." where user_id = '$user_id' and order_id = '$order_id'";
  			$orders = $db->query($sql);
 			
			if($orders){	
 				$change_desc="订单号:".$current_order['order_sn'];
 				//取消礼金卡支付的订单记录礼金卡账户变动
 				if($current_order['surplus']>0){
 					log_mcard_change($user_id, $current_order['surplus'], $change_desc,0,$order_id,3);
 				}
 				//取消现金券支付的订单改变现金券的使用状态
 				else if($current_order['bonus']>0){
 					unuse_bonus(trim($current_order['bonus_id']));
				}
 				else{
 					log_account_change($user_id,0,0,0, 0, $change_desc);
 				}
 			}
  			return json_encode(array('code' =>RES_SUCCSEE));
  		}else{
  			return json_encode(array('code' =>RES_FAIL));
  		}
	}
	
	public static function get_password_moblie($mobile){
	   global $db;
	   $mobile = addslashes($mobile);
	   if(strlen($mobile)<5){
		   return json_encode(array('code' =>RES_FAIL));
	   }
	   $user_type = MES_Sec::get_user_type_by_mobile($mobile);
	   if($user_type == 11){
		   //重新生成随机的密码
		   $rand_password = MES_User::rand_num();
		   //组合出这个地址
		   $user_name= 'W' . $mobile . "@fal.com";
		   MES_Sec::update_password_by_username($password,$user_name);
		   $password = MES_Sec::get_password_by_mobile($mobile);
  
		   
		   $c = "尊敬的用户，您在每实官网手机校验码为".$password."。如有问题请与每实客服中心联系，电话4000 600 700。";
		   MES_User::_send_sms($c,$mobile);
		   
		   return json_encode(array('code' =>RES_SUCCSEE));
	   }else{
		   return json_encode(array('code' =>RES_FAIL,'user_type' =>$user_type));
	   }
	}

    //产生一个随机的密码，用于手机找回密码
	private static function rand_num(){
		srand((double)microtime()*1000000);//create a random number feed.
		$ychar="0,1,2,3,4,5,6,7,8,9,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z";
		$list=explode(",",$ychar);
		for($i=0;$i<6;$i++){
			$randnum=rand(0,35);
			$authnum.=$list[$randnum];
		}
		return $authnum;
	}

	//使用手机号码查询验证码的登录
	public static function query_login($mobile,$password){
		global $db;
		global $_LANG;
		global $user;
		$mobile = addslashes($mobile);
		$password = addslashes($password);
		$json = new JSON;
		$res  = array('code' =>RES_SUCCSEE, 'content' => 'login successs');

		$username = MES_Sec::get_decode_username_by_mobile($mobile);

		if(empty($username)||empty($password)){
			$res['code']   = RES_FAIL;
			$res['content'] = $_LANG['login_failure'];
			return $json->encode($result);
		}

		if ($user->login_for_auto_register($username, $password)){
			$_SESSION['usermsg']= get_user_info();
			//这种用户都是自动注册登录 要区别对待
			$_SESSION['user_auto_register'] = '11';
			$_SESSION['user_auto_register_moblie'] = $mobile;
		}else{
			$res['code']   = RES_FAIL;
			$res['content'] = $_LANG['login_failure'];
		}
		return $json->encode($res);
	}

    //修改默认的手机号码
	public static function change_mobile($mobile,$code){
		global $db;
		if($_SESSION['change_mobile']!=$mobile||$_SESSION['change_mobile_code']!=$code){
			return json_encode(array('code' =>RES_FAIL));
		}else{
			$user_name = $_SESSION['uuid'];
			MES_Sec::update_mobile_by_username($mobile,$user_name);
			unset($_SESSION['change_mobile']);
			unset($_SESSION['change_mobile_code']);
			return json_encode(array('code' =>RES_SUCCSEE,'mobile'=>$mobile));
		}
	}

	public static function change_mobile_get_code($mobile){
		$rand_password = MES_User::rand_num();
		$c = "尊敬的用户，您在每实官网手机校验码为".$rand_password."。如有问题请与每实客服中心联系，电话4000 600 700。";
		MES_User::_send_sms($c,$mobile);
		$_SESSION['change_mobile'] = $mobile;
		$_SESSION['change_mobile_code'] = $rand_password;
		return json_encode(array('code' =>RES_SUCCSEE));
	}

	//获得用户正在使用的电话号码
	public static function get_user_mobile_number(){
		global $db;
		$user_name = addslashes($_SESSION['uuid']);
		$mobile = MES_Sec::get_mobile_by_username($user_name);
		return json_encode(array(
			'code' =>RES_SUCCSEE,
			'mobile'=>$mobile
		));
	}

	//获得用户正在使用的电话号码
	public static function get_users_info(){
		global $db;
		$user_name = addslashes($_COOKIE['uuid']);
		//test pass
		$info = MES_Sec::get_userinfo_by_username($user_name);
		return json_encode(array(
			'code' =>RES_SUCCSEE,
			'info'=>$info
		));
	}


	
	//已经登录用户修改密码
	public static function change_password($old,$new){
		global $db;
		$user_name = addslashes($_COOKIE['uuid']);
		
		$old = md5($old);
		$new = md5($new);

		$password_in_db = MES_Sec::get_password_by_username($user_name);
		$salt_in_db = MES_Sec::get_salt_by_username($user_name);
		
		$salt_password = md5($old.$salt_in_db);


		if($password_in_db==$salt_password){
			$new_password = md5($new.$salt_in_db);
			MES_Sec::update_password_by_username($new_password,$user_name);
			return json_encode(array('code' =>RES_SUCCSEE));
		}else{
			return json_encode(array('code' =>RES_FAIL));
		}
	}
   
    //忘记密码找回第一步验证这个是否可以
	public static function forget_password_step1($mobile,$code){
		if($_SESSION['forget_mobile_code'] == $code&&$_SESSION['forget_mobile'] == $mobile){
			$_SESSION['forget_mobile_vaild'] = true;
			return json_encode(array('code' =>RES_SUCCSEE));
		}else{
			$_SESSION['forget_mobile_vaild'] = false;
			return json_encode(array('code' =>RES_FAIL));
		}
	}

	//忘记密码找回第一步验证这个是否可以
	public static function forget_password_step2($mobile,$password){
		global $db;
		if($_SESSION['forget_mobile_vaild']&&$_SESSION['forget_mobile'] == $mobile){
			$salt_in_db = MES_Sec::get_salt_by_mobile($mobile);
			if(!$salt_in_db){
				$salt_in_db = '8801';
				MES_Sec::update_salt_by_mobile($salt_in_db,$mobile);
			}
			
			$password = md5($password);
			$new_password = md5($password.$salt_in_db);
			MES_Sec::update_password_by_mobile($new_password,$mobile);

			unset($_SESSION['forget_mobile']);
			unset($_SESSION['forget_mobile_code']);
			return json_encode(array('code' =>RES_SUCCSEE));
		}else{
			return json_encode(array('code' =>RES_FAIL));
		}
	}

	public static function get_forget_password_code($mobile){
		
		global $db;
		$mobile_phone = MES_Sec::check_mobile_exsit($mobile);
		if($mobile_phone){
			$rand_password = MES_User::rand_num();
			$c = "尊敬的用户，您在每实官网手机校验码为".$rand_password."。如有问题请与每实客服中心联系，电话4000 600 700。";
			MES_User::_send_sms($c,$mobile);
			$_SESSION['forget_mobile'] = $mobile;
			$_SESSION['forget_mobile_code'] = $rand_password;
			return json_encode(array('code' =>RES_SUCCSEE));
		}else{
			return json_encode(array('code' =>RES_FAIL));
		}
	}

   //新版暂时没有使用
   //可以先不改造
	public static function change_sex($sex){
		global $db;
		if($sex!=0&&$sex!=1){
			return json_encode(array('code' =>RES_FAIL));
		}
		$user_name = addslashes($_COOKIE['uuid']);
		$sex = addslashes($sex);
		$db->query("update ecs_users set sex='$sex' where user_name='$user_name'");
		return json_encode(array('code' =>RES_SUCCSEE));
	}
	
	//新版暂时没有使用
    //可以先不改造
	public static function change_real_name($name){
		global $db;
		$user_name = addslashes($_COOKIE['uuid']);
		$name = addslashes($name);
		$db->query("update ecs_users set rea_name='$name' where user_name='$user_name'");
		return json_encode(array('code' =>RES_SUCCSEE)); 
	}


	public static function get_order_count_by_sid(){
		global $db;
		//parent_id = 0 只有非配属品才算商品
		$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE session_id= "' . SESS_ID.'" and parent_id=0';
		$goods_count = 0;
		$goods =$db->getAll($sql);
		foreach($goods as $val){
			$goods_count += $val['goods_number'];
		}	
		return json_encode(array('code' =>RES_SUCCSEE,'count'=>$goods_count));
	}

	public static function signup_page(){
		global $smarty;
		$smarty->display('signup_v2.dwt');
	}
	
	
	//注册手机号码的获得验证码步骤
	public static function signup_vaild_code($mobile){
		
		 //重新生成随机的密码
		  $rand_password = MES_User::rand_num();  
		  $content = "尊敬的用户，您在每实官网手机校验码为".$rand_password."。如有问题请与每实客服中心联系，电话4000 600 700。";
		  MES_User::_send_sms($content,$mobile);
		  
		  //缓存验证码
		  SET_REDIS($mobile,$rand_password,'signup');
		  return json_encode(array(
			  'code' =>RES_SUCCSEE,
			  'msg'=>'success'
		  ));
	}
	
	//对外的使用手机号码的注册
	public static function signup($mobile,$password,$vaild_code){
		global $_CFG;
		global $db;
		global $_LANG;
		//增加是否关闭注册
		if ($_CFG['shop_reg_closed']){
			return json_encode(array('code' =>RES_FAIL,'msg'=>'close'));
		}else{

			$f_email= 'W' . $mobile . "@fal.com";
			$email = $f_email;
			$username = $f_email;
			$other['mobile_phone'] = $mobile;
			$other['rea_name'] = '';
			$back_act =  '';
			$redis_vaild_code = GET_REDIS($mobile,'signup');
			
			if($redis_vaild_code!=$vaild_code){
				return json_encode(array('code' =>10010,'msg'=>'vaild error for wrong vaild code'));
			}
			
			if (strlen($username) < 3){
				return json_encode(array('code' =>RES_FAIL,'msg'=>$_LANG['passport_js']['username_shorter']));
			}

			if (strlen($password) < 6){
				return json_encode(array('code' =>RES_FAIL,'msg'=>$_LANG['passport_js']['password_shorter']));
			}

			if (strpos($password, ' ') > 0){
				return json_encode(array('code' =>RES_FAIL,'msg'=>$_LANG['passwd_balnk']));
			}
			
			//register in lib_passport orginal;
			if (MES_User::_register($username, $password, $email,$other) !== false){
				
				MES_Sec::update_usertype_by_username($username);
				$_SESSION['user_msg'] = $username;			

				//删除注册状态验证的redis;
				DEL_REDIS($mobile,'signup');
				return json_encode(array('code' =>RES_SUCCSEE,'msg'=>'success'));
			}else{
				return json_encode(array('code' =>RES_FAIL,'msg'=>'failed'));
			}
		}
	}

    //充值验证码
	public static function charge_vaild($mobile){
		 //重新生成随机的密码
		  $rand_password = MES_User::rand_num();  
		  $content = "尊敬的用户，您在每实官网手机校验码为".$rand_password."。如有问题请与每实客服中心联系，电话4000 600 700。";
		  MES_User::_send_sms($content,$mobile);
		  
		  //缓存验证码
		  SET_REDIS($mobile,$rand_password,'charge');
		  return json_encode(array(
			  'code' =>RES_SUCCSEE,
			  'msg'=>'success'
		  ));
	}
	
	//充值操作
	public static function do_charge($card_num,$card_pwd,$mobile,$vaild_code){
		global $_CFG;
		global $db;
		global $_LANG;

		global $ecs;
		$user_id = GET_REDIS($_COOKIE['uuid'],'user_id');
		$time=gmtime();
		$result = array('code' =>0, 'message' => '');
		//接收卡号、密码

		//验证码
		$redis_vaild_code = GET_REDIS($mobile,'charge');
		if($redis_vaild_code!=$vaild_code){
			return json_encode(array(
				'code' =>10010,
				'msg'=>'验证码错误！'
			));
		}
		//验证卡号
		$record = $db->getRow('select mc.* from moneycards as mc where '. " mc.cardid='{$card_num}' and mc.cardpassword='{$card_pwd}'");
		
		if($record ==false){
		   $result['code'] = RES_FAIL;
		   $result['message'] ='卡号与密码不符合，请重新输入';
		   return json_encode($result);
	    }
	
		if($record['flag'] !=1){
		   $result['code'] = RES_FAIL;
		   $result['message'] ='您的储值卡还未生效，详情请联系客服';
		   return json_encode($result);
		}
	
		if($record['user_id']>0 ||$record['used_time'] > 0){
		   $result['code'] = RES_FAIL;
		   $result['message'] ='您的储值卡已使用';
		   return json_encode($result);
		}
	   
		if($time<$record['sdate'] && $time>$record['edate']){
		   $result['code'] = RES_FAIL;
		   $result['message'] ='您的储值卡已过期';
		   return json_encode($result);
		}
	

		//注册时间
		$us_id=GET_REDIS($_COOKIE['uuid'],'user_id');
		$regt=$db->getOne("select reg_time from ecs_users where user_id=$us_id ");

		$sql1="update moneycards set user_id=$us_id, used_time ='"
		. gmtime() ."' where cardid='$card_num'";

		$res=$db->query($sql1);
		
		if($res){
			$change_money = floatval($record['cardmoney']);
			log_mcard_change($user_id, $change_money,'储值卡：'.$card_num.'充值',0,0,2);
			$user_money = $GLOBALS['db']->getOne('SELECT user_money FROM ' . $ecs->table('users') ." WHERE user_id='$_SESSION[user_id]'");
			$result['user_money'] = $user_money;
			$result['change_money'] = $change_money;
			$result['message'] ='操作成功';
			DEL_REDIS($mobile,'charge');
		}else{
		   $result['code'] = RES_FAIL;
		   $result['message'] ='更新储值卡状态失败，请重试';
		}
		return json_encode($result);
	}
}

?>