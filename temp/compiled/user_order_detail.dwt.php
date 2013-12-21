<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?php echo $this->_var['keywords']; ?>" />
<meta name="Description" content="<?php echo $this->_var['description']; ?>" />

<title><?php echo $this->_var['page_title']; ?></title>

<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="animated_favicon.gif" type="image/gif" />
<link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />

<?php echo $this->smarty_insert_scripts(array('files'=>'jquery.min.js,jquery.json-1.3.js,transport.js,common.js,user.js')); ?>
</head>
<body>
<?php echo $this->fetch('library/page_header.lbi'); ?>
<div id="container">

<div id="content">
		<p class="wane_left"></p>
		<p class="wane_right"></p>
		<div class="indent_detail">
			<h2>订单状况：</h2>
			<table>
			<tr>
				<td style="border-bottom:none;width:70px;">订单号：</td><td><?php echo $this->_var['order']['order_sn']; ?></td>
			<tr>
				<td style="border-bottom:none;">订单状况：</td><td><?php echo $this->_var['order']['order_status']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_var['order']['confirm_time']; ?></td>
			</tr>
			<tr>
				<td style="border-bottom:none;">付款状况</td>
				<td><?php echo $this->_var['order']['pay_status']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($this->_var['order']['order_amount'] > 0): ?><?php echo $this->_var['order']['pay_online']; ?><?php endif; ?><?php echo $this->_var['order']['pay_time']; ?></td>
			</tr>
			</table>
		</div>
		<div class="indent_detail">
			<h2 class="pt20px">商品列表</h2>
			<table >
				<tbody align="center">
					<tr>
						<th>商品名称</th>
						<th>属性</th>
						<th>单价</th>
						<th>购买数量</th>
						<th>小计</th>
					</tr>
                     <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
					<tr>
						<td><?php if ($this->_var['goods']['goods_id'] > 0 && $this->_var['goods']['extension_code'] != 'package_buy'): ?>
                <a href="goods.php?id=<?php echo $this->_var['goods']['goods_id']; ?>" target="_blank" class="f6"><?php echo $this->_var['goods']['goods_name']; ?></a>
                <?php if ($this->_var['goods']['parent_id'] > 0): ?>
                <span style="color:#FF0000">（<?php echo $this->_var['lang']['accessories']; ?>）</span>
                <?php elseif ($this->_var['goods']['is_gift']): ?>
                <span style="color:#FF0000">（<?php echo $this->_var['lang']['largess']; ?>）</span>
                <?php endif; ?>
              <?php elseif ($this->_var['goods']['goods_id'] > 0 && $this->_var['goods']['extension_code'] == 'package_buy'): ?>
                <a href="javascript:void(0)" onclick="setSuitShow(<?php echo $this->_var['goods']['goods_id']; ?>)" class="f6"><?php echo $this->_var['goods']['goods_name']; ?><span style="color:#FF0000;">（礼包）</span></a>
                <div id="suit_<?php echo $this->_var['goods']['goods_id']; ?>" style="display:none">
                    <?php $_from = $this->_var['goods']['package_goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'package_goods_list');if (count($_from)):
    foreach ($_from AS $this->_var['package_goods_list']):
?>
                      <a href="goods.php?id=<?php echo $this->_var['package_goods_list']['goods_id']; ?>" target="_blank" class="f6"><?php echo $this->_var['package_goods_list']['goods_name']; ?></a><br />
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
              <?php endif; ?></td>
						<td><?php echo nl2br($this->_var['goods']['goods_attr']); ?></td>
						<td><?php echo $this->_var['goods']['goods_price']; ?></td>
						<td><?php echo $this->_var['goods']['goods_number']; ?></td>
						<td><?php echo $this->_var['goods']['subtotal']; ?></td>
					</tr>
                     <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</tbody>
			</table>
		</div>
		<div class="indent_detail">
			<h2 class="pt20px"><span style="float:right;margin-right:150px">商品总价：<?php echo $this->_var['order']['formated_goods_amount']; ?></span></h2>
			<table border="0">
				<tbody>
					<tr>
						<td style="width:600px;">费用总计</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>商品总金额：<?php echo $this->_var['order']['formated_total_fee']; ?></td>
					</tr>
					<tr>
						<td></td>
						<td>配送费：￥<?php echo $this->_var['order']['shipping_fee']; ?></td>
					</tr>
					<tr>
						<td></td>
						<td>现金券：<?php echo $this->_var['order']['formated_bonus']; ?> &nbsp;</td>
					</tr>
					<tr>
						<td></td>
						<td>礼金卡：<?php echo $this->_var['order']['formated_surplus']; ?> &nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td></td>
						<td>付款总金额：<?php echo $this->_var['order']['formated_order_amount']; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="indent_detail">
			<h2 class="pt20px">收款人信息：</h2>
			<table>
			  <tr>
				<td style="border-bottom:none;width:100px;">收款人姓名：</td><td><?php echo $this->_var['order']['consignee']; ?></td>
			</tr>
			<tr>
				<td style="border-bottom:none;">详细地址：</td><td><?php echo $this->_var['order']['address']; ?></td>
			</tr>
			<tr>
                <td style="border-bottom:none;">联系电话：</td><td><?php echo $this->_var['order']['mobile']; ?></td>
			</tr>
			</table>
		</div>
	</div>
</div>

  
</body>
</html>