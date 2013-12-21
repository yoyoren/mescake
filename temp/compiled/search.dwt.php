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
<?php if ($this->_var['cat_style']): ?>
<link href="<?php echo $this->_var['cat_style']; ?>" rel="stylesheet" type="text/css" />
<?php endif; ?>

<?php echo $this->smarty_insert_scripts(array('files'=>'jquery.min.js,jquery.json-1.3.js,common.js')); ?>
<style type="text/css">
.searchg{width:774px;height:250px;padding-top:10px;margin-top:10px;background-color:#E3E0DB; position:relative;overflow:hidden;}
.searchg .content{margin-left:15px;width:740px;height:175px;}
.searchg .content .gbrief{height:155px}
.img{width:300px; height:220px; position:absolute; right:93px; top:50px;}
.img img{width:300px; height:220px;}
.dright{float:right;margin-right:10px}
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
  <?php echo $this->fetch('library/search_goods_list.lbi'); ?> 
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