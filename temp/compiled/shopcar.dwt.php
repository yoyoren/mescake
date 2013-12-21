<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="mes,每实,蛋糕" />
<meta name="Description" content="MES每实是一个食物,生活方式,艺术和文化的综合体,它钟情于创造任何让生活产生乐趣,品质和格调的食物产品,3大系列,38款产品,让你尽享美好." />

<title>我的购物车</title>



<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="animated_favicon.gif" type="image/gif" />
<link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />
<?php echo $this->smarty_insert_scripts(array('files'=>'jquery.min.js')); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,shopping_flow.js')); ?>

<?php echo $this->smarty_insert_scripts(array('files'=>'header_spec.js')); ?>
	<style type="text/css">
		
		.rightborder{border-right:1px solid;padding-left:5px;width:200px;}
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
	 <div style='height:60px;'></div>	
	
	
	  <table  width='960' align="center"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FEF9D9" >
	  <tr >
		<td colspan="5" align="center" style="line-height:70px;position:relative;"><font size="6">我的购物车</font>
			<div style='position:absolute;right:0;top:55px;width:140px;cursor:pointer;'><img src="themes/default/images/flow/clearall.png" onclick="location.href='flow.php?step=clear2'" /><div></td>	
	  </tr>
	  <tr >
		<td colspan="5"><hr style="width:895px;height:2px;color:#D1D1D1;"/> </td>
	  </tr>
	  <tr style="line-height:30px;font-size:14px;">
		<td width="200" style="padding-left:24px;border-right:2px solid #D1D1D1;">品名</td>
		<td width="200" style="border-right:2px solid #D1D1D1;">&nbsp;数量</td>
		<td width="210" style="border-right:2px solid #D1D1D1;" >&nbsp;价格</td>
		<td width="230" style="border-right:2px solid #D1D1D1;">&nbsp;优惠</td>
		<!--<td width="250" style="border-right:2px solid #D1D1D1;">&nbsp;生日牌</td>-->
		<!--<td width="236">额外餐具0.5元/份<img src="themes/default/images/canju.png" align="absmiddle"/></td>-->
		<td width="100">&nbsp;取消</td>
	  </tr>
	 <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
            <tr height="40px">
              <td bgcolor="#FEF9D9" align="left"  style="padding-left:24px;">
                <?php if ($this->_var['goods']['goods_id'] > 0 && $this->_var['goods']['extension_code'] != 'package_buy'): ?>
                 <?php echo $this->_var['goods']['goods_name']; ?>[<?php echo nl2br($this->_var['goods']['goods_attr']); ?>]
                <?php endif; ?>
              </td>
              <td align="left" >
			  <img src="themes/default/images/flow/f_jian.png" align="absmiddle" style="cursor:pointer;vertical-align:middle;"  onclick="minus(<?php echo $this->_var['goods']['rec_id']; ?>);">&nbsp;<input type="text" name="goods_number[<?php echo $this->_var['goods']['rec_id']; ?>]" id="goods_number_<?php echo $this->_var['goods']['rec_id']; ?>" value="<?php echo $this->_var['goods']['goods_number']; ?>" size="4" class="inputBg" style="text-align:center " onkeydown="showdiv(this)"/>&nbsp;<img src="themes/default/images/flow/f_jia.png" align="absmiddle"  style="cursor:pointer;vertical-align:middle;" onclick="plus(<?php echo $this->_var['goods']['rec_id']; ?>);">
			  </td>
              <td align="left"  > <font  face="Arial"><?php echo $this->_var['goods']['goods_price']; ?></font> </td>
              <td align="left" ><label id="sub_<?php echo $this->_var['goods']['rec_id']; ?>"></label></td>
			  
			 
			  <script language="javascript">
			  function checkce(e)
			  {
				var cepattern=/[\u4e00-\u9fa5]/ ;
				var neirong=document.getElementById("birth"+e).value;
				if(!cepattern.test(neirong))
				{
					$("#birth"+e).attr('maxlength','16');
				}
				else
				{ 
			
					$("#birth"+e).attr('maxlength','8');
				}
			  }
				function minuscanju(n)
				{
					var num=document.getElementById("canju_number_"+n).value;
						num2=num-1;
						if(num2>=0)
						{
							document.getElementById("canju_number_"+n).value=num2;
							var cannum=document.getElementById("canjuyuan"+n).innerHTML;
							//alert(cannum);
							document.getElementById("canjuyuan"+n).innerHTML=cannum-0.5;
							var total=document.getElementById("total").innerHTML;
							total=total.substr(1);
							
							total=total-0.5;
							
							document.getElementById("total").innerHTML="￥"+total;
						//	alert(num2);
						}
				}
				function pluscanju(m)
				{
					var num=document.getElementById("canju_number_"+m).value;
					
						num++;
					document.getElementById("canju_number_"+m).value=num;
					var cannum=document.getElementById("canjuyuan"+m).innerHTML;
					//alert(cannum);
						document.getElementById("canjuyuan"+m).innerHTML=cannum-1+0.5+1;
					var total=document.getElementById("total").innerHTML;
					
					total=total.substr(1)-1+0.5+1;
					
					//total=total + 0.5;
					
					document.getElementById("total").innerHTML="￥"+total;
				}
			  </script>
              <td align="left" >
                <a href="javascript:if (confirm('确定从购物车中删除该商品')) location.href='flow.php?step=drop_shopcar&amp;id=<?php echo $this->_var['goods']['rec_id']; ?>'; " class="f6">&nbsp;<img src="themes/default/images/flow/f_del.png"></a>
              </td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		<tr >
			<td colspan="5"><hr style="width:895px;height:2px;color:#D1D1D1;"/> </td>
		</tr>
		<tr>
			<td colspan="5" >
			 <div class="total" style='background:#FEF9D9'>
			   <div class="clearcart" style='background:#FEF9D9'><font size='4'>总计/Total</font></div>
			   <div class="shopping_money" id="total"><?php echo $this->_var['shopping_money']; ?></div>
			 </div>
			</td>
		</tr>
		<tr >
			<td colspan="5"><hr style="width:895px;height:2px;color:#D1D1D1;"/></td>
		</tr>
		<tr>
			<td style='padding-left:26px;'><a href='index.php'><img src='themes/default/images/flow/f_to2.png'/></a></td><td colspan='2'>&nbsp;</td><td colspan='2' style='position:relative;'><a href='flow.php?step=cart' style='position:absolute;right:35px;top:0;'><img src='themes/default/images/flow/f_next2.png'/></a></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	<div style='height:60px;'></div>
	   <?php echo $this->fetch('library/page_footer.lbi'); ?>
   
   
</body>
</html>