<div id="container">
<?php echo $this->fetch('library/user/user_top_menu.lbi'); ?>
<div id="content">
		<p class="wane_left"></p>
		<p class="wane_right"></p>
		<div class="service">
			<h2>账户余额：<span class="fs28"><?php echo $this->_var['surplus_amount']; ?></span><span class="fs12">元</span><p><a href="user.php?act=charge">充 值</a></p></h2>
			<table>
				<tbody>
					<tr>
						<th class="w167px">日期时间</th>
						<th class="w98px">操作</th>
						<th class="w170px">金额（元）</th>
						<th class="w437px">备注</th>
					</tr>
                    <?php $_from = $this->_var['account_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
					<tr>
						<td><?php echo $this->_var['item']['change_time']; ?></td>
						<td><?php echo $this->_var['item']['type']; ?></td>
						<td><?php echo $this->_var['item']['amount']; ?></td>
						<td><?php echo $this->_var['item']['short_change_desc']; ?></td>
					</tr>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>					
				</tbody>
			</table>
            <?php echo $this->fetch('library/pages.lbi'); ?>
		</div>
	</div>
</div>