<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?php echo $this->_var['keywords']; ?>" />
<meta name="Description" content="<?php echo $this->_var['description']; ?>" />

<title>MES每时_尽享美好</title>

<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="animated_favicon.gif" type="image/gif" />
<link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />

<?php echo $this->smarty_insert_scripts(array('files'=>'jquery.min.js,common.js')); ?>

</head>
<body'>

<?php echo $this->fetch('library/page_header.lbi'); ?>
<?php 
$k = array (
  'name' => 'member_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
<div class="block box">
<div class="blank60"></div>
  <div class="brandleft">
    <div class="brandname"><div><img src="themes/default/images/a.png" onclick="hello(4)" style='cursor:pointer;'/><img src="themes/default/images/d.png" onclick="hello(1)" style='cursor:pointer;'/><img src="themes/default/images/s.png" onclick='hello(2)' style='cursor:pointer;'/><img src="themes/default/images/c.png" onclick='hello(3)' style='cursor:pointer;'></div><img src="<?php echo $this->_var['brand']['brand_logo']; ?>" id='brandlogo'/></div>
	<div class="brandlist<?php echo $this->_var['brand']['brand_id']; ?>">
<script language="javascript">
	function hello(n)
	{
	//alert('ddddd');
		$.ajax({
			type:"post",
			url:"series.php",
			data:"id="+new Date()+"&step=series"+"&brand_id="+n,
			dataType:"html",
			success:function(msg){
		    //接收服务器的响应结果
		//alert(msg);
		$("#series").html(msg);
			//window.location.reload();
			//$("#div1").html(msg);
			  }
			});
			if(n==1){
			document.getElementById('brandlogo').src='themes/default/images/days.jpg';
			document.title="DAYS_MES每时尽享美好";
			}
		if(n==2){
			document.getElementById('brandlogo').src='themes/default/images/special.jpg';
			document.title="SPECIAL_MES每时尽享美好";
		}
		if(n==3){
			document.getElementById('brandlogo').src='themes/default/images/classic.jpg';
			document.title="CLASSIC_MES每时尽享美好";
		}
		if(n==4){
			document.getElementById('brandlogo').src='themes/default/images/all.png';
			document.title="ALL_MES每时尽享美好";
		}
	}
</script>
	<div id="series">
		<?php if ($this->_var['brand_id'] == 4): ?>
	<div class="clearfix"  style="width:804px;border:0px solid;padding-left:0px;"><br/>
	<?php $_from = $this->_var['all_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'all');if (count($_from)):
    foreach ($_from AS $this->_var['all']):
?>
		<div style='float:left;width:200px;height:200px;padding-left:0px;margin-top:0px;'>
			<a href='goods.php?id=<?php echo $this->_var['all']['goods_id']; ?>'><img src='themes/default/images/all/<?php echo $this->_var['all']['goods_id']; ?>.jpg' width='175' height='175'/></a>
		</div>
	
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</div>
<?php else: ?>
		<div class="clearfix"  style="width:804px;border:0px solid;padding-left:0px;"><br/>
		<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
			<div style='float:left;width:200px;height:200px;border:0px solid;padding-left:0px;margin-top:0px;'>
				<a href='goods.php?id=<?php echo $this->_var['goods']['goods_id']; ?>'><img src='themes/default/images/all/<?php echo $this->_var['goods']['goods_id']; ?>.jpg' width='175' height='175'/></a>
			</div>
		
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</div>
<?php endif; ?>	
	</div>

	
	 
	 <!-- <div class="footer"></div>-->
	</div>
	
   </div>
    <div class="top-right">
     <?php echo $this->fetch('library/search_form2.lbi'); ?>	
 <?php 
$k = array (
  'name' => 'right_history',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
  <!--<div id="ECS_CARTINFO">
 <?php 
$k = array (
  'name' => 'right_cart_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
 </div>-->
 </div>
</div>

<?php echo $this->fetch('library/page_footer.lbi'); ?>
  
</body>
</html>
