<?php echo $this->smarty_insert_scripts(array('files'=>'transport.js,utils.js')); ?>
<div id="ECS_ORDERTOTAL">
<table width="99%" align="center" border="0" cellpadding="5" cellspacing="1">
  <tr>
    <td  bgcolor="#ffffff">
      本订单应付商品金额
 </td>
   <td align="right"><?php echo $this->_var['total']['goods_price_formated']; ?></td>
  </tr> 
  <tr>
   <td  bgcolor="#ffffff">配送费</td>
   <td align="right"><?php echo $this->_var['total']['shipping_fee_formated']; ?></td>
   <input type="hidden" name="shipping_fee" value="<?php echo $this->_var['total']['shipping_fee_formated']; ?>"/>
  </tr> 
  <tr>
   <td  bgcolor="#ffffff" style="border-bottom:1px solid">
      扣减 礼金卡/现金券金额<br />
 </td>
    <td align="right" bgcolor="#ffffff" style="height:30px;border-bottom:1px solid">
      <?php if ($this->_var['total']['surplus'] > 0): ?>
      -  <?php echo $this->_var['total']['surplus_formated']; ?>
      <?php endif; ?>
      <?php if ($this->_var['total']['bonus'] > 0): ?>
      - <?php echo $this->_var['total']['bonus_formated']; ?>
      <?php endif; ?> 
	   <?php if ($this->_var['total']['surplus'] == 0 && $this->_var['total']['bonus'] == 0): ?>
	   -￥0.00
	   <?php endif; ?>
	   <br />  </td>
  </tr>
  <tr style="line-height:50px;">
  <td  bgcolor="#ffffff"  ><font size="+3">您尚需支付</font></td>
    <td align="right" bgcolor="#ffffff"> <font size="+3"><?php echo $this->_var['total']['amount_formated']; ?></font>
	</td>
  </tr>
  <tr><td>&nbsp;</td></tr>
</table>
</div>