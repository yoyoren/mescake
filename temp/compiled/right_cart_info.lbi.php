<div class="new_indent">
<script type="text/javascript">
//fixed
$(function(){
	$(window).scroll( function() {
		if($(window).scrollTop()>=746){
			$("div.new_indent").css({
                 top:$(window).scrollTop()-200
             });
		}else{
			$("div.new_indent").css({top:550});
		}
		
	});
	$("div.close").click(function(rec_id){
		
	});
});
function delgoods2(rec_id){
	Ajax.call('flow.php?step=drop_goods', 'ajax=1&id='+rec_id , delResponse, 'GET', 'JSON');
}
function delResponse(result)
{
		document.getElementById('ECS_CARTINFO').innerHTML = result.content;
}
</script>
		<h3>我的购物车<br /><span>My order</span><p><label><?php echo empty($this->_var['cart_goods_number']) ? '0' : $this->_var['cart_goods_number']; ?></label></p></h3>
		<p class="menu"><img src="themes/default/images/menu.gif" /></p>
		<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['cart_goods_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cart_goods_list']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['cart_goods_list']['iteration']++;
?>
		<ul>
			<li style="width:60px"><?php echo $this->_var['goods']['goods_name']; ?></li>
			<li style="width:15px"><?php echo $this->_var['goods']['goods_number']; ?></li>
			<li style="width:55px"><?php echo $this->_var['goods']['goods_price']; ?></li>
			<li><a href="javascript:delgoods2(<?php echo $this->_var['goods']['rec_id']; ?>);"><img src="themes/default/images/cancel.gif" width="12" height="12" /></a></li> 
		</ul>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		<p class="amount">总计/Total<span><?php echo $this->_var['total']['goods_price']; ?></span></p>
		<h4><a href="flow.php?step=cart">去付款 Next</a></h4>
	</div>






