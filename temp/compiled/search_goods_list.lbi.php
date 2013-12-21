<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['goods_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods_list']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['goods_list']['iteration']++;
?>
  <div class="searchg">
    <div class="content">
      <div class="gname"><b><?php echo $this->_var['goods']['goods_name']; ?></b><span class="dright"> <?php echo $this->_var['goods']['goods_sn']; ?></span></div><hr>
      <div class="gbrief"><?php echo $this->_var['goods']['goods_desc']; ?></div>
      <div class="img"><img src="themes/default/images/sgoods/<?php echo $this->_var['goods']['goods_sn']; ?>.png" /></div>
      <div class="price"><?php echo $this->_var['goods']['market_price']; ?></div>
      <div class="more"><a href="<?php echo $this->_var['goods']['url']; ?>">立即订购&nbsp;<img src="themes/default/images/more.jpg" align="absmiddle"></a></a></div>
    </div>
  </div>

<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>