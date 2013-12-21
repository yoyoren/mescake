<?php
/**
 *	订单状态类
 *  ==================================================================
 *  ------------------------------------------------------------------
 * 	类属性：	
 * 		1.订单编号：    orderNo
 * 		2.站点标识wi:	feedback
 * 		3.订单状态：orderStatus
 *		4.支付状态：paymentStatus
 * 		5.支付方式：paymentType
 *		6.订单跟新时间   updateTime
 *  ------------------------------------------------------------------
 *  ==================================================================
 * @author LSJ
 * 
 * @version 0.2
 *
 */
class OrderStatus {	
	public $orderNo; 
	public $feedback;
	public $updateTime;
	public $orderStatus; 
	public $paymentStatus; 
	public $paymentType; 
	

	public function getOrderNo() {
		return $this->orderNo;
	}

	public function getFeedback() {
		return $this->feedback;
	}
	
	public function getUpdateTime() {
		return $this->updateTime;
	}

	public function getOrderStatus() {
		return $this->orderStatus;
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

	public function setFeedback($feedback) {
		$this->feedback = $feedback;
	}

    public function setUpdateTime($updateTime) {
		$this->updateTime = $updateTime;
	}

	public function setOrderStatus($orderStatus) {
		$this->orderStatus = $orderStatus;
	}

	public function setPaymentStatus($paymentStatus) {
		$this->paymentStatus = $paymentStatus;
	}

	public function setPaymentType($paymentType) {
		$this->paymentType = $paymentType;
	}
	
}
?>