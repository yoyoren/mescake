<?php
include_once 'Order.php';
include_once 'OrderStatus.php';
include_once 'Product.php';
include_once '../util/Config.php';
include_once 'Dto.php';

class Service{
	private $config = null;
	
	
	function Service(){
		$this->config = new Config();
	}
	public $dto = null;
	
///////////////////////////////////JSON格式的返回////////////////////////////////////
	/**************************************************
	 * 根据活动id、下单时间查询订单信息,返回的格式是JSON
	 * @param 活动id $campaignId
	 * @param 下单时间 $date
	 **************************************************/
	public function getOrderInfoByJSON($campaignId,$orderStartTime,$orderEndTime){
		if (empty($campaignId) || empty($orderStartTime)||empty($orderEndTime)){
			throw new Exception("campaignId ,orderStartTime or orderEndTime is null!", 119, "");
		}
		$this->dto = new Dto();
		$orderlist = 0;
		$orderlist = $this -> dto -> getOrderByOrderTime($campaignId,$orderStartTime,$orderEndTime);
		if(!$orderlist == 0){
			echo '{"orders":';
			echo $this->JSON($this->object_to_array($orderlist));
			echo '}';
		}else{
			echo 'no data!';
		}
	
	
	}

	 /**************************************************************
	 * 根据活动id、订单更新时间查询订单状态，返回的格式是JSON
	 * @param 活动id $campaignId
	 * @param 订单更新时间 $date
	 *************************************************************/
public function getOrderStatusByJSON($campaignId, $updateStartTime,$updateEndTime){
		if (empty($campaignId) || empty($updateStartTime)||empty($updateEndTime)){
			throw new Exception("campaignId ,updateStartTime or updateEndTime is null!", 119);
		}
		$this->dto = new Dto();
		$orderstatuslist = 0;
		$orderstatuslist =	$this -> dto -> getOrderByUpdateTime($campaignId,$updateStartTime,$updateEndTime);
		
		if(!$orderstatuslist == 0){
			echo '{"orderStatus":';
			echo $this->JSON($this->object_to_array($orderstatuslist));
			echo '}';
		}else{
			echo 'no data!';
		}
	              	
	}
	/***************************************************
	*  对象转化成数组 
	*
	***************************************************/

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
	


}
?>