<div id="container">
    <?php echo $this->fetch('library/user/user_top_menu.lbi'); ?>
	
	<div id="content">
		<p class="wane_left"></p>
		<p class="wane_right"></p>
		<div class="indent">
			<table >
				<tbody>
					<tr>
						<th>订单号</th>
						<th>下单时间</th>
						<th>商品名称</th>
						<th>订单总金额</th>
						<th>送货时间</th>
						<th>订单状态</th>
						<th>操作</th>
					</tr>
                    <?php $_from = $this->_var['orders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
					<tr align="center">
						<td class="w167px"><a href="user.php?act=order_detail&order_id=<?php echo $this->_var['item']['order_id']; ?>" target="_blank"><?php echo $this->_var['item']['order_sn']; ?></a></td>
						<td class="w98px"><?php echo $this->_var['item']['order_time']; ?></td>
						<td class="w119px" ><?php $_from = $this->_var['item']['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'gitem');if (count($_from)):
    foreach ($_from AS $this->_var['gitem']):
?><?php if ($this->_var['gitem']['goods_price'] > 100): ?><?php echo $this->_var['gitem']['goods_name']; ?> <?php endif; ?><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?></td>
						<td class="w118px"><?php echo $this->_var['item']['total_fee']; ?></td>
						<td class="w173px"><?php echo $this->_var['item']['best_time']; ?></td>
						<td class="w123px"><?php echo $this->_var['item']['order_status']; ?></td>
						<td class="w72px" ><a href="user.php?act=order_detail&order_id=<?php echo $this->_var['item']['order_id']; ?>" target="_blank">查看</a></td>
					</tr>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>	
					<tr>
						
						<td colspan='7'>
						<div style="text-align:center;"><?php $_from = $this->_var['pager']['page_number']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['item']):
?>
							<a href="<?php echo $this->_var['item']; ?>">&nbsp;[<?php echo $this->_var['k']; ?>]&nbsp;</a>&nbsp;
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
    <div class="blank5"></div>
    <?php echo $this->fetch('library/pages.lbi'); ?>
    <div class="blank5"></div>
</div>