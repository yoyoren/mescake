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


<?php if ($this->_var['action'] == 'profile'): ?>
<?php echo $this->fetch('library/user/user_profile.lbi'); ?>
<?php endif; ?>

     


<?php if ($this->_var['action'] == 'order_list'): ?>
<?php echo $this->fetch('library/user/user_order_list.lbi'); ?>
<?php endif; ?>


<?php if ($this->_var['action'] == 'order_detail'): ?>
<?php echo $this->fetch('library/user/user_order_detail.lbi'); ?>
<?php endif; ?>

<?php if ($this->_var['action'] == 'charge'): ?>
<?php echo $this->fetch('library/user/user_charge.lbi'); ?>
<?php endif; ?>

<?php if ($this->_var['action'] == 'charge_finish'): ?>
<?php echo $this->fetch('library/user/user_charge_finish.lbi'); ?>
<?php endif; ?>

<?php if ($this->_var['action'] == 'account_detail'): ?>
<?php echo $this->fetch('library/user/user_account_detail.lbi'); ?>
<?php endif; ?>

<div class="blank"></div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
  
</body>
<script type="text/javascript">
<?php $_from = $this->_var['lang']['clips_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</script>
</html>
