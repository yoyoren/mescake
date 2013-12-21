
<ul id="nav">
<li <?php if ($this->_var['action'] == 'profile'): ?>class="active"<?php endif; ?>><a href="user.php" onfocus="this.blur()">我的信息</a></li>
<li <?php if ($this->_var['action'] == 'order_list'): ?>class="active"<?php endif; ?>><a href="user.php?act=order_list" onfocus="this.blur()">我的订单</a></li>
<!--<li <?php if ($this->_var['action'] == 'order_detail'): ?>class="active"<?php endif; ?>><a href="#" onfocus="this.blur()">订单详情</a></li>-->
<li <?php if ($this->_var['action'] == 'charge'): ?>class="active"<?php endif; ?>><a href="user.php?act=charge" onfocus="this.blur()">礼金卡充值 </a></li>
<li <?php if ($this->_var['action'] == 'account_detail'): ?>class="active"<?php endif; ?>><a href="user.php?act=account_detail" onfocus="this.blur()">账户明细</a></li>
</ul>