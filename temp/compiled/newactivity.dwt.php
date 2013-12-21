<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?php echo $this->_var['keywords']; ?>" />
<meta name="Description" content="<?php echo $this->_var['description']; ?>" />

<title>Mes每实—精灵旅社</title>

<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="animated_favicon.gif" type="image/gif" />
<link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />
<?php if ($this->_var['cat_style']): ?>
<link href="<?php echo $this->_var['cat_style']; ?>" rel="stylesheet" type="text/css" />
<?php endif; ?>

<?php echo $this->smarty_insert_scripts(array('files'=>'jquery.min.js,jquery.json-1.3.js,common.js')); ?>
<style type="text/css">
.searchg{width:774px;height:250px;margin-top:10px;position:relative;overflow:hidden;}
.moremore{position:absolute;bottom:10px;right:40px;cursor:pointer;}
</style>
</head>
<body>
<?php echo $this->fetch('library/page_header.lbi'); ?>
<?php 
$k = array (
  'name' => 'member_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
<div class="block box">
<div class="blank60"></div>
  
   <div class="AreaL" id="search_goods_list">   
	<a href="goods.php?id=27">
		<div class="searchg" style="background:url(themes/default/images/zhiai.jpg)">
			<div class="moremore"><font color="white" size="4">立即订购</font>&nbsp;</div> 
		</div>
	</a>
  <a href="goods.php?id=11">
		<div class="searchg" style="background:url(themes/default/images/xiehou.jpg)">
			<div class="moremore"><font color="white" size="4">立即订购</font>&nbsp;</div> 
		</div>
  </a>
   <a href="goods.php?id=21">
		<div class="searchg" style="background:url(themes/default/images/hawana.jpg)">
			<div class="moremore"><font color="white" size="4">立即订购</font>&nbsp;</div> 
		</div>
  </a>
 <a href="goods.php?id=38">
		<div class="searchg" style="background:url(themes/default/images/napolun.jpg)">
			<div class="moremore"><font color="white" size="4">立即订购</font>&nbsp;</div> 
		</div>
  </a>
  
  </div>  
  
   <div class="top-right">
 
  <?php echo $this->fetch('library/search_form2.lbi'); ?>	
 <?php 
$k = array (
  'name' => 'right_history',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
  <div id="ECS_CARTINFO">
 <?php 
$k = array (
  'name' => 'right_cart_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
 </div>
 </div>
<div class="blank20"></div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
<script>
var select_spe = "<?php echo $this->_var['lang']['select_spe']; ?>";
var btn_buy = "<?php echo $this->_var['lang']['btn_buy']; ?>";
var is_cancel = "<?php echo $this->_var['lang']['is_cancel']; ?>";
</script>
  
   <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1000160405'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1000160405%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script>
</body>
</html>