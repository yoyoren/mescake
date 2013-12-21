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

<?php echo $this->smarty_insert_scripts(array('files'=>'jquery.min.js,common.js')); ?>
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
	<div class="blank20"></div><div class="blank20"></div><div class="blank20"></div>
   <?php if ($this->_var['id'] == 1): ?>
        <img src="themes/default/images/m1.png" border="0" usemap="#Map1" />      
<map name="Map1" id="Map1">
  <area shape="poly" coords="503,5,942,4,934,214,525,318" href="goods.php?id=22" />
  <area shape="poly" coords="522,330,525,541,945,586,946,223" href="goods.php?id=27" />
<area shape="poly" coords="525,548,517,789,936,798,943,594" href="goods.php?id=25" />

</map>  <?php elseif ($this->_var['id'] == 2): ?>
		   <img src="themes/default/images/m2.jpg" border="0" usemap="#Map2" />
<map name="Map2" id="Map2">
  <area shape="poly" coords="507,5,505,221,928,281,944,8" href="goods.php?id=15" />
<area shape="poly" coords="504,235,504,568,926,465,930,295" href="goods.php?id=21" />
<area shape="poly" coords="506,586,497,783,941,787,933,485" href="goods.php?id=20" />
</map>
   <?php else: ?>
      <img src="themes/default/images/m3.png" border="0" usemap="#Map3" />
<map name="Map3" id="Map3">
  <area shape="poly" coords="503,10,502,257,924,206,941,11" href="goods.php?id=1" />
<area shape="poly" coords="504,277,503,420,919,585,935,239" href="goods.php?id=4" />
<area shape="poly" coords="507,461,498,792,930,798,915,609" href="goods.php?id=3" />
</map>
   <?php endif; ?></div>
</body>
</html>
