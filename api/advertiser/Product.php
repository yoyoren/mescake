<?php
/**
 * 商品信息类
 * 
 * ==================================================================================================
 * 属性如下：
 * 	1.商品编号：productNo
 * 	2.商品名称：name
 * 	3.商品数量：amount
 * 	4.商品价格：price
 * 	5.商品类别：category
 * 	6.佣金类型：commissionType
 * ==================================================================================================
 * @author lsj
 * @access public
 * @see advertiser.Order
 * @version 0.2.0
 */

class Product {
	
	public $productNo;
	public $name;
	public $amount;
	public $price;
	public $category;
	public $commissionType;
	
	

	function Product(){}
	
	public function setProductNo($productNo) {
		$this->productNo = $productNo;
	}
	
	public function getProductNo() {
		return $this->productNo;
	}

	public function getName() {
		return $this->name;
	}

	public function getAmount() {
		return $this->amount;
	}

	public function getPrice() {
		return $this->price;
	}

	public function getCategory() {
		return $this->category;
	}

	public function getCommissionType() {
		return $this->commissionType;
	}

	public function setName($name) {
		$this->name = $name;
	
	}

	public function setAmount($amount) {
		$this->amount = $amount;
	}

	public function setPrice($price) {
		$this->price = $price;
	}

	public function setCategory($category) {
		$this->category = $category;
	}

	public function setCommissionType($commissionType) {
		$this->commissionType = $commissionType;
	}
}

?>