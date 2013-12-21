<?php
include_once ("Product.php");
/**
 *	订单信息类
 *  =======================================================================================
 *  ---------------------------------------------------------------------------------------
 * 	类属性：	
 * 		1.订单编号：    orderNo
 * 		2.下单时间：    orderTime
 		3.订单更新时间: updateTime
 * 		4.活动ID：	    campaignId，从cookie中读取
 * 		5.站点标识feedback:	feedback,从cookie中读取
 * 		6.运费：		fare
 * 		7.优惠金额：	favorable
 * 		8.优惠码：	favorableCode
 * 		9.订单状态：  orderStatus，订单状态类请看
 * 		10.商品：	products,商品类请看
 * 		11.订单状态：orderStatus
 *		12.支付状态：paymentStatus
 * 		13.支付方式：paymentType
 * 		<p>
 *  ---------------------------------------------------------------------------------------
 *  =======================================================================================
 * @author LSJ
 * @see advertiser.Product
 * @see advertiser.Sender#send() 
 * @version 0.2
 *
 */
class Order {	
	public $orderNo; 
	public $orderTime;
	public $updateTime;
	public $campaignId; 
	public $feedback; 
	public $fare; 
	public $favorable; 
	public $favorableCode; 
	public $products = null;
	public $orderStatus; 
	public $paymentStatus; 
	public $paymentType;
	
	


	public function getProducts() {
		return $this->products;
	}

	public function getOrderNo() {
		return $this->orderNo;
	}

	public function getOrderTime() {
		return $this->orderTime;
	}

	public function getCampaignId() {
		return $this->campaignId;
	}

	public function getFeedback() {
		return $this->feedback;
	}

	public function getFare() {
		return $this->fare;
	}

	public function getFavorable() {
		return $this->favorable;
	}

	public function getFavorableCode() {
		return $this->favorableCode;
	}

	public function getOrderStatus() {
		return $this->orderstatus;
	}

	public function getPaymentStatus() {
		return $this->paymentStatus;
	}

	public function getPaymentType() {
		return $this->paymentType;
	}


	public function setOrderNo($orderNo) {
		$this->orderNo = $orderNo;
	}

	public function setOrderTime($orderTime) {
		$this->orderTime = $orderTime;
	}

	public function setCampaignId($campaignId) {
		$this->campaignId = $campaignId;
	}

	public function setFeedback($feedback) {
		$this->feedback = $feedback;
	}

	public function setFare($fare) {
		$this->fare = $fare;
	}

	public function setFavorable($favorable) {
		$this->favorable = $favorable;
	}

	public function setFavorableCode($favorableCode) {
		$this->favorableCode = $favorableCode;
	}
	
	
	public function getUpdateTime() {
		return $this->updateTime;
	}

	public function setUpdateTime($updateTime) {
		$this->updateTime = $updateTime;

	}
	public function setProducts($products){
		$this->products = $products;
	}

	public function setOrderStatus($orderstatus){
		$this->orderStatus = $orderstatus;
	}
	public function setPaymentStatus($paymentStatus) {
		$this->paymentStatus = $paymentStatus;
	}

	public function setPaymentType($paymentType) {
		$this->paymentType = $paymentType;
	}
	
}
?>