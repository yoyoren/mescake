<style type="text/css">
.history_title{width:165px; overflow:hidden; margin:0 auto; padding-top:6px;padding-left:8px;}
.history_title h3{color:#292526; font-size:14px; font-weight:bold; border-bottom:2px solid #B3B3B3; padding-bottom:5px;}
</style>
<div class="box history" id='history_div'>
  <div class="history_title"><h3>浏览记录<br />Browsing history</h3></div>  
    <ul id='history_list'>
        	<?php $_from = $this->_var['history_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_0_97017800_1389719452');$this->_foreach['history_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['history_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods_0_97017800_1389719452']):
        $this->_foreach['history_goods']['iteration']++;
?>
            <li>
            <span><?php echo $this->_foreach['history_goods']['iteration']; ?></span>
           <a href="goods.php?id=<?php echo $this->_var['goods_0_97017800_1389719452']['goods_id']; ?>"><img src="themes/default/images/sgoods/<?php echo $this->_var['goods_0_97017800_1389719452']['goods_sn']; ?>.png" width="40" height="30" />
            <img src="themes/default/images/price_name.gif" width="24" height="24" /></a>
            <div>
            <p style="color:#000;"><?php echo price_format_new($this->_var['goods_0_97017800_1389719452']['shop_price']); ?></p>
			<p><?php echo $this->_var['goods_0_97017800_1389719452']['goods_name']; ?></p>
            </div>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>           
        </ul>    
</div>
<script type="text/javascript">
if (document.getElementById('history_list').innerHTML.replace(/\s/g,'').length<1)
{
    document.getElementById('history_div').style.display='none';
}
else
{
    document.getElementById('history_div').style.display='block';
}
function clear_history()
{
Ajax.call('user.php', 'act=clear_history',clear_history_Response, 'GET', 'TEXT',1,1);
}
function clear_history_Response(res)
{
document.getElementById('history_list').innerHTML = '<?php echo $this->_var['lang']['no_history']; ?>';
}
</script>