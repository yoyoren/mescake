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

<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,jquery-1.4.js,shopping_flow.js')); ?>

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
 <div id="ur_here">
  <?php echo $this->fetch('library/flow_here.lbi'); ?>
 </div>
</div>

<div class="blank"></div>
<div class="blank"></div>
<div class="block">
  <?php if ($this->_var['step'] == "cart"): ?>
  
  
  
  <script type="text/javascript">
  <?php $_from = $this->_var['lang']['password_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
    var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  function kuang(id){
		  labels = document.getElementById(id).getElementsByTagName('span');
		  for(i=0,j=labels.length ; i<j ; i++)
		  {
		   labels[i].onclick=function() 
		   {   
		  		
			if(this.className == 'kuang1') {
			 for(k=0,l=labels.length ; k<l ; k++)
			 {
			  labels[k].className='kuang1';			  
			 }
			 this.className='checked1';
			 try{
			  if(id=="fujian1") addToCart(60);		   
			  if(id=="fujian2") addToCart(61);
			  
			 } catch (e) {}
			}
		   }
		  }
	}
  </script>
  <script language="javascript">
	function disappear(a)
	{
		document.getElementById("birth"+a).value='';
		document.getElementById("birth"+a).style.color='black';
	}
	function yess(a)
	{
		document.getElementById("birth"+a).disabled=false;
		document.getElementById("birth"+a).value='';
		document.getElementById("birth"+a).focus();
		document.getElementById("birth"+a).style.color='black';		
	}
	function nono(a)
	{
	document.getElementById("birth"+a).disabled=true;
		document.getElementById("birth"+a).value='文字内容(八个字以内)';
		document.getElementById("birth"+a).style.color='grey';
	}
  </script>
  <div class="flowBox">
  <div class="myorder">
   <form id="formCart" name="formCart" method="post" action="flow.php" onsubmit="return checkOrderForm(this)">
  <table  align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#F2F2F2" >
	  <tr >
		<td colspan="6" align="center" style="height:70px;padding-top:10px;"><font size="6">我的订单 MY ORDER</font></td>	
	  </tr>
	  <tr >
		<td colspan="6"><hr style="width:895px;height:2px;color:#D1D1D1;"/> </td>
	  </tr>
	  <tr style="line-height:30px;">
		<td width="180" style="padding-left:24px;border-right:2px solid #D1D1D1;">品名</td>
		<td width="150" style="border-right:2px solid #D1D1D1;">&nbsp;数量</td>
		<td width="100" style="border-right:2px solid #D1D1D1;" >&nbsp;价格</td>
		<td width="100" style="border-right:2px solid #D1D1D1;">&nbsp;优惠</td>
		<td width="250" style="border-right:2px solid #D1D1D1;">&nbsp;生日牌</td>
		<!--<td width="236">额外餐具0.5元/份<img src="themes/default/images/canju.png" align="absmiddle"/></td>-->
		<td width="100">&nbsp;取消</td>
	  </tr>
	 <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_0_97594800_1389699171');if (count($_from)):
    foreach ($_from AS $this->_var['goods_0_97594800_1389699171']):
?>
            <tr height="40px">
              <td bgcolor="#F2F2F2" align="left"  style="padding-left:24px;">
                <?php if ($this->_var['goods_0_97594800_1389699171']['goods_id'] > 0 && $this->_var['goods_0_97594800_1389699171']['extension_code'] != 'package_buy'): ?>
                 <?php echo $this->_var['goods_0_97594800_1389699171']['goods_name']; ?>[<?php echo nl2br($this->_var['goods_0_97594800_1389699171']['goods_attr']); ?>]
                <?php endif; ?>
              </td>
              <td align="left" >
			  <img src="themes/default/images/flow/f_jian.png" align="absmiddle" style="cursor:pointer;vertical-align:middle;"  onclick="minus(<?php echo $this->_var['goods_0_97594800_1389699171']['rec_id']; ?>);">&nbsp;<input type="text" name="goods_number[<?php echo $this->_var['goods_0_97594800_1389699171']['rec_id']; ?>]" id="goods_number_<?php echo $this->_var['goods_0_97594800_1389699171']['rec_id']; ?>" value="<?php echo $this->_var['goods_0_97594800_1389699171']['goods_number']; ?>" size="4" class="inputBg" style="text-align:center " onkeydown="showdiv(this)"/>&nbsp;<img src="themes/default/images/flow/f_jia.png" align="absmiddle"  style="cursor:pointer;vertical-align:middle;" onclick="plus(<?php echo $this->_var['goods_0_97594800_1389699171']['rec_id']; ?>);">
			  </td>
              <td align="left"  > <font  face="Arial"><?php echo $this->_var['goods_0_97594800_1389699171']['goods_price']; ?></font> </td>
              <td align="left" ><label id="sub_<?php echo $this->_var['goods_0_97594800_1389699171']['rec_id']; ?>"></label></td>
			  <?php if ($this->_var['goods_0_97594800_1389699171']['goods_id'] == 61): ?>
			  <td>&nbsp;</td>
			 
			  <?php elseif ($this->_var['goods_0_97594800_1389699171']['goods_id'] == 60): ?>
			  <td>&nbsp;</td>
			 
			  <?php else: ?>
			  <td width="">&nbsp;
				
				是<input type="radio" maxlength="5" name="birthcard<?php echo $this->_var['goods_0_97594800_1389699171']['goods_id']; ?>" value="1" style="border:0;" onclick="yess(<?php echo $this->_var['goods_0_97594800_1389699171']['goods_id']; ?>)" checked  />
				否<input  type="radio" name="birthcard<?php echo $this->_var['goods_0_97594800_1389699171']['goods_id']; ?>" value="0" style="border:0;" onclick="nono(<?php echo $this->_var['goods_0_97594800_1389699171']['goods_id']; ?>)" />
				<input type="text" maxlength="8" name="card_message[]" id="birth<?php echo $this->_var['goods_0_97594800_1389699171']['goods_id']; ?>" onkeyup="checkce(<?php echo $this->_var['goods_0_97594800_1389699171']['goods_id']; ?>)" value="文字内容(八个字内)"  style="color:grey;width:120px;" onclick="disappear(<?php echo $this->_var['goods_0_97594800_1389699171']['goods_id']; ?>)"/>
			  </td>
			 <!-- <td>
				<img src="themes/default/images/flow/f_jian.png" align="absmiddle" style="cursor:pointer;vertical-align:middle;"  onclick="minuscanju(<?php echo $this->_var['goods_0_97594800_1389699171']['rec_id']; ?>);">
				<input type="text" name="canju_number[<?php echo $this->_var['goods_0_97594800_1389699171']['rec_id']; ?>]" id="canju_number_<?php echo $this->_var['goods_0_97594800_1389699171']['rec_id']; ?>" value="0" size="4" class="inputBg" style="text-align:center " readonly />&nbsp;
				<img src="themes/default/images/flow/f_jia.png" align="absmiddle"  style="cursor:pointer;vertical-align:middle;" onclick="pluscanju(<?php echo $this->_var['goods_0_97594800_1389699171']['rec_id']; ?>);">&nbsp;<span id="canjuyuan<?php echo $this->_var['goods_0_97594800_1389699171']['rec_id']; ?>"> 0</span>&nbsp;元
			  </td>-->
			  <?php endif; ?>
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
                <a href="javascript:if (confirm('<?php echo $this->_var['lang']['drop_goods_confirm']; ?>')) location.href='flow.php?step=drop_goods&amp;id=<?php echo $this->_var['goods_0_97594800_1389699171']['rec_id']; ?>'; " class="f6">&nbsp;<img src="themes/default/images/flow/f_del.png"></a>
              </td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  
		<tr>
			<td colspan="6">
			<div class="fujian">
				 <div id="fujian1">
					<table border="0">
						<tr>
							<td width="420">除按蛋糕规格免费赠送的餐具数量外，是否额外需要餐具？</td>
							<td width="240">&nbsp;单价:&nbsp;0.5元/份<img src="themes/default/images/canju.png" align="absmiddle"/></td>
							<td><?php if ($this->_var['canju'] == 1): ?>
								<span class="kuang1 checked" ></span>		
								<?php else: ?> 
								<span class="kuang1" onclick="kuang('fujian1')" ></span>
								<?php endif; ?>是的，我需要     
							</td>
						</tr>
					</table>				   
				 </div>
				 <div id="fujian2">
					<table border="0">
						<tr>
							<td width="420">特制的生日蜡烛需要单独订购，是否需要生日蜡烛？</td>
							<td width="240">&nbsp;单价:&nbsp;5.0元/个</td>
							<td><?php if ($this->_var['lazhu'] == 1): ?>
								<span class="kuang1 checked"></span>	
								<?php else: ?> 
								<span class="kuang1" onclick="kuang('fujian2')"></span>	
								<?php endif; ?> 
								是的，我需要
							</td>
						</tr>
					</table>
				 </div>
				   <!--生日牌定制  <input type="text" name="" />8个字以内&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;单价免费-->
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="6">
			 <div class="total">
			   <div class="clearcart"><img src="themes/default/images/flow/f_clear2.png" onclick="location.href='flow.php?step=clear'" /></div>
			   <div class="shopping_money" id="total"><?php echo $this->_var['shopping_money']; ?></div>
			 </div>
			</td>
		</tr>
	</table>
	 </div>
	 <div class="shipping">
	 <?php echo $this->fetch('library/consignee.lbi'); ?><br/>
	 
	 <div style="width:901px;margin:auto;background:#F2F2F2;"><iframe src="historyaddr.php" width="900" scrolling="no" frameborder="0"></iframe></div>
	 </div>
	 <!--<div class="orderinfo">
	 
	 <input type="text" name="orderman" id="orderman" />
	 <input type="text" name="ordertel" id="ordertel" />
	 <input type="text" name="orderemail" id="orderemail" />
	 
	 </div>-->
	 <div class="anniu clearfix">
	    <a href="index.php"><img src="themes/default/images/flow/f_to.jpg" /></a>
		<input type="image" src="themes/default/images/flow/f_next.jpg" />
        <input type="hidden" value="<?php echo $this->_var['user_info']['user_id']; ?>" id="login" name="login" />
        <input type="hidden" value="0" id="save" name="save"/>
		<input type="hidden" value="checkout" name="step" />
	 </div>
	 <div style="display:none;">
        <?php echo $this->fetch('library/recommend_new.lbi'); ?>
	 </div>	
   </div>   
  </div> 
  </form>
    <div class="blank5"></div>
  <?php endif; ?>
  </form>
        <?php if ($this->_var['step'] == "consignee"): ?>
        
        <?php echo $this->smarty_insert_scripts(array('files'=>'region.js,utils.js')); ?>
        <script type="text/javascript">
          region.isAdmin = false;
          <?php $_from = $this->_var['lang']['flow_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
          var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

          
          onload = function() {
            if (!document.all)
            {
              document.forms['theForm'].reset();
            }
          }
          
        </script>
        
        <?php $_from = $this->_var['consignee_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('sn', 'consignee');if (count($_from)):
    foreach ($_from AS $this->_var['sn'] => $this->_var['consignee']):
?>
        <form action="flow.php" method="post" name="theForm" id="theForm" onsubmit="return checkConsignee(this)">
        <?php echo $this->fetch('library/consignee.lbi'); ?>
        </form>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        <?php endif; ?>

        <?php if ($this->_var['step'] == "checkout"): ?>
        <form action="flow.php" method="post" name="theForm" id="theForm" >
        <script type="text/javascript">
        var flow_no_payment = "<?php echo $this->_var['lang']['flow_no_payment']; ?>";
        var flow_no_shipping = "<?php echo $this->_var['lang']['flow_no_shipping']; ?>";
		function kuang(id){
		  labels = document.getElementById(id).getElementsByTagName('span');
		  radios = document.getElementById(id).getElementsByTagName('input');
		  for(i=0,j=labels.length ; i<j ; i++)
		  {
		   labels[i].onclick=function() 
		   {   
		  
			if(this.className == '') {
			 for(k=0,l=labels.length ; k<l ; k++)
			 {
			  labels[k].className='';
			  radios[k].checked = false;
			 }
			 this.className='checked';
			 try{
				document.getElementById(this.getAttribute('name')).checked = true;
			 } catch (e) {}
			}
		   }
		  }
	}
	function kuangfu(id){
		  labels = document.getElementById(id).getElementsByTagName('span');
		  radios = document.getElementById(id).getElementsByTagName('input');
		  for(i=0,j=labels.length ; i<j ; i++)
		  {
		   labels[i].onclick=function() 
		   {   
		  
			if(this.className == '') {
			  for(k=0,l=labels.length ; k<l ; k++)
			  {
			    labels[k].className='';
			    radios[k].checked = false;
			  }
			  this.className='checked';
			  try{
				document.getElementById(this.getAttribute('name')).checked = true;
				if(this.getAttribute('name')=='bonus_id')
				{
					document.getElementById('qmsg').className="";
					document.getElementById('lmsg').className="hide1";
					changecard(0);
				}
				else
				{
					document.getElementById('lmsg').className="";
					document.getElementById('qmsg').className="hide1";
					changecard(1);
				}
			 } catch (e) {}
			}else{
			  for(k=0,l=labels.length ; k<l ; k++)
			 {
			  labels[k].className='';
			  radios[k].checked = false;
			 }
			 this.className='';
			 try{
				document.getElementById(this.getAttribute('name')).checked = false;
				if(this.getAttribute('name')=='bonus_id')
				{
					document.getElementById('qmsg').className="hide1";
					document.getElementById('lmsg').className="hide1";
					
					changecard(0);
				}
				else 
				{
					document.getElementById('lmsg').className="hide1";
					document.getElementById('qmsg').className="hide1";
					
					changecard(0);
				}
				
			 } catch (e) {}
			}
		   }
		  }
	}
	//
	function lijinka()
	{
		var a=document.getElementById('bonus_id').value;
		if(a!=0)
		{
			document.getElementById('bonus_id').value=0;
		}
	}
        </script>
		<div  id="dv1">
		
			<div id="cheader" >
			<div id="ctable" >
			<table border="0" width="914px" align="center" >
			<tr>
				<td colspan='4' style='line-height:90px;font-size:25px;text-align:center;'>我的订单 MY ORDER</td>
			</tr>
            <tr >
			     <td width="90px" align="left" id="accepter_name">收件人姓名</td>
			     <td width="500px" align="left" id="accepter_add">送货地址</td>
			     <td width="162px" align="left" id="accepter_tel">收件人联系电话</td>
			     <td>送货时间</td>
			   </tr> 
			   <tr >
			     <td width="90px" align="left"><?php echo htmlspecialchars($this->_var['consignee']['consignee']); ?></td>
			     <td width="500px" align="left"><?php echo htmlspecialchars($this->_var['consignee']['addressname']); ?></td>
			     <td width="162px" align="left"><?php echo htmlspecialchars($this->_var['consignee']['mobile']); ?></td>
			     <td><?php echo $this->_var['order']['best_time']; ?></td>
			   </tr> 
			</table>
			</div>
			<div id="corder" >
			<table border="0" width="914px" align="center">
               <tr height="45px">
			     <td width="140px" align="left">付款方式</td>
			     <td width="125px" align="left">订货人姓名</td>
			     <td width="500px" align="left">订货人联系电话</td>		 
			   </tr>
			   <tr >
			     <td width="140px" align="left">货到付款（现金）</td>
			     <td width="125px" align="left"><?php echo $this->_var['order']['orderman']; ?></td>
			     <td width="500px" align="left"><?php echo $this->_var['order']['mobile']; ?></td>		 
			   </tr> 
			</table>
			</div>		
			</div>
		
			<div id="middle" >
			        <div class="flowBox" >
                    
            <table width="100%" id="ttable" align="center" border="0" cellpadding="5" cellspacing="0" bgcolor="#F2F2F2">
       	      <tr>
                  <td width="250px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;产品清单<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name
                  </td>
                  <td width="195px"></td>
                  <td width="157px" align="left">数量<br>Quantity</td>
                  <td width="150px" align="left">价格<br>Unit Price</td>
                  <td  align="left">优惠<br>Discount</td>
              </tr>
            </table>
        <table  width="100%" align="center" border="0" cellpadding="5" cellspacing="0" bgcolor="#F2F2F2">
            <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_0_97683300_1389699171');if (count($_from)):
    foreach ($_from AS $this->_var['goods_0_97683300_1389699171']):
?>
            <tr>
              <td width="250px">&nbsp;&nbsp;&nbsp;
          <a href="goods.php?id=<?php echo $this->_var['goods_0_97683300_1389699171']['goods_id']; ?>" target="_blank" class="fc"><?php echo $this->_var['goods_0_97683300_1389699171']['goods_name']; ?>[<?php echo nl2br($this->_var['goods_0_97683300_1389699171']['goods_attr']); ?>]</a>
              </td>
              <td width="195px"></td>
			  <td width="157px" align="left"><?php echo $this->_var['goods_0_97683300_1389699171']['goods_number']; ?></td>
              <td width="150px" align="left"><?php echo $this->_var['goods_0_97683300_1389699171']['formated_goods_price']; ?></td>
              <td  align="left"><?php echo empty($this->_var['goods_0_97683300_1389699171']['discount']) ? '0' : $this->_var['goods_0_97683300_1389699171']['discount']; ?></td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          </table>
      </div>
			</div>
		
			<div id="cfooter">
			  <div id="ctotal"><?php echo $this->_var['shopping_money']; ?></div>
			</div>
		
          <div class="payway clearfix" id="payid"  onclick="kuang('payid')">
		  <div style="text-align:center;line-height:85px;margin-left:0px;font-size:25px;">付款方式 TYPE OF PAYMENT</div>
		  <div style="padding-left:100px;">
<div class="paywidth1 ">
<input type="radio" id="cash" name="pay_id" value="1" class="hide1" checked="checked"/>货到付款（现金）&nbsp;<span name="cash" class="checked">&nbsp;</span>&nbsp;&nbsp;
</div>
<div class="paywidth2">
<input type="radio" id="pos" name="pay_id" value="2" class="hide1"/>货到付款（POS机）&nbsp;<span name="pos" >&nbsp;</span>
&nbsp;&nbsp;
</div>
<div class="paywidth3">
<input type="radio" id="alipay" name="pay_id" value="3" class="hide1" />支付宝&nbsp;<span name="alipay" >&nbsp;</span>
&nbsp;&nbsp;
</div>
<div class="paywidth4">
<input type="radio" id="bill" name="pay_id" value="4" class="hide1" />快钱&nbsp;<span name="bill" >&nbsp;</span>
</div></div>
     </div>
	 <div class="used" id="used" onclick="kuangfu('used');lijinka()" >
	 <div style="height:50px;text-align:center;font-size:25px;">优惠券/礼金卡 GIFT & MES CARD</div>
	 <div style='padding-left:25px;'>
		<input type="radio" id="surplus" name="surplus" value="0" class="hide1" />活动赠送/礼金卡&nbsp;<span name="surplus" >&nbsp;</span><label id="lmsg" class="">&nbsp;<input type='text'  readonly style='border:0;background:#F2F2F2;margin-left:2px;' value='(可用余额:<?php echo $this->_var['your_surplus']; ?>)'/>&nbsp;&nbsp;<a href="user.php?act=charge"><img src="themes/default/images/chongzhix.png" align="absmiddle" /></a></label><br />
		<input type="radio" id="bonus_id" name="bonus_id" value="0" class="hide1" />现金券<b style='visibility:hidden;'>用现金券/</b></span><span name="bonus_id" >&nbsp;</span>&nbsp;<label id="qmsg" class="hide1" ><input type="text" name="bonus_sn" value="请输入10位现金券券号"  onfocus="javasrcipt:this.value='';" />&nbsp;&nbsp;<input type="button" name="validate_bonus" value="&nbsp;" onclick="validateBonus(document.forms['theForm'].elements['bonus_sn'].value)" style="vertical-align:middle;background:url(themes/default/images/yanzh.png);width:50px;border:0;cursor:pointer;height:20px;" /></label>
	 </div>
	 </div>
	
          <?php echo $this->fetch('library/order_total.lbi'); ?>
	 
		<div id="reDiv"><a href="flow.php"><img src="themes/default/images/flow/revise.jpg" id="revise"></a></div>
		<div id="nextDiv"><input type="image" src="themes/default/images/flow/f_next.jpg" id="cnext"></div>
      
     <input type="hidden" value="done" name="step"/>

       <!--<div></div>-->
    </form>
        <?php endif; ?>

        <?php if ($this->_var['step'] == "done"): ?>
        
		<script>
	$(function(){
		$('.imgShowSmall img').mouseover(function(){
			var thissrc = $(this).attr('src');
			$(this).parents('.imgShow').find('.imgShowBig img').attr('src',thissrc);
		});		   
	});
</script>
        <div class="flowBox">
		<div class="box1">
	<div class="imgShow">
    	<div class="imgShowBig">
		<img src="themes/default/images/sgoods/<?php echo $this->_var['goods']['img']; ?>.png" width="144" height="96" alt="" />
		</div>
        <div class="imgShowSmall">
		<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_0_97703100_1389699171');if (count($_from)):
    foreach ($_from AS $this->_var['goods_0_97703100_1389699171']):
?>
		<img src="themes/default/images/sgoods/<?php echo $this->_var['goods_0_97703100_1389699171']['goodsimg']; ?>.png" width="50" height="33" alt="" />
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</div>
    </div>
	<div class="donetext">
	<table style="text-align:left;margin:auto;font-size:12px;">
		<tr>
			<td>您选择的<?php echo $this->_var['goods']['name']; ?>,订单号为<?php echo $this->_var['order']['order_sn']; ?>，蛋糕正在制作。<br/>
			我们的特别派送员将在预订时间送达贵处，再次感谢您的预订。<br><br>
		<div style="text-align:center;">如需帮助请致电4000 600 700</div></td>
		</tr>
		
	</table>
	</div>
</div>
        </div><?php echo $this->_var['pay_online']; ?>
        <?php endif; ?>
        <?php if ($this->_var['step'] == "login"): ?>
        <?php echo $this->smarty_insert_scripts(array('files'=>'utils.js,user.js')); ?>
        <script type="text/javascript">
        <?php $_from = $this->_var['lang']['flow_login_register']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
          var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

        
        function checkLoginForm(frm) {
          if (Utils.isEmpty(frm.elements['username'].value)) {
            alert(username_not_null);
            return false;
          }

          if (Utils.isEmpty(frm.elements['password'].value)) {
            alert(password_not_null);
            return false;
          }

          return true;
        }

        function checkSignupForm(frm) {
          if (Utils.isEmpty(frm.elements['username'].value)) {
            alert(username_not_null);
            return false;
          }

          if (Utils.trim(frm.elements['username'].value).match(/^\s*$|^c:\\con\\con$|[%,\'\*\"\s\t\<\>\&\\]/))
          {
            alert(username_invalid);
            return false;
          }

          if (Utils.isEmpty(frm.elements['email'].value)) {
            alert(email_not_null);
            return false;
          }

          if (!Utils.isEmail(frm.elements['email'].value)) {
            alert(email_invalid);
            return false;
          }

          if (Utils.isEmpty(frm.elements['password'].value)) {
            alert(password_not_null);
            return false;
          }

          if (frm.elements['password'].value.length < 6) {
            alert(password_lt_six);
            return false;
          }

          if (frm.elements['password'].value != frm.elements['confirm_password'].value) {
            alert(password_not_same);
            return false;
          }
          return true;
        }
        
        </script>
        
        <div class="flowBox">
        <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
          <tr>
            <td width="50%" valign="top" bgcolor="#ffffff">
            <h6><span>用户登录：</span></h6>
            <form action="flow.php?step=login" method="post" name="loginForm" id="loginForm" onsubmit="return checkLoginForm(this)">
                <table width="90%" border="0" cellpadding="8" cellspacing="1" bgcolor="#B0D8FF" class="table">
                  <tr>
                    <td bgcolor="#ffffff"><div align="right"><strong><?php echo $this->_var['lang']['username']; ?></strong></div></td>
                    <td bgcolor="#ffffff"><input name="username" type="text" class="inputBg" id="username" /></td>
                  </tr>
                  <tr>
                    <td bgcolor="#ffffff"><div align="right"><strong><?php echo $this->_var['lang']['password']; ?></strong></div></td>
                    <td bgcolor="#ffffff"><input name="password" class="inputBg" type="password" /></td>
                  </tr>
                  <?php if ($this->_var['enabled_login_captcha']): ?>
                  <tr>
                    <td bgcolor="#ffffff"><div align="right"><strong><?php echo $this->_var['lang']['comment_captcha']; ?>:</strong></div></td>
                    <td bgcolor="#ffffff"><input type="text" size="8" name="captcha" class="inputBg" />
                    <img src="captcha.php?is_login=1&<?php echo $this->_var['rand']; ?>" alt="captcha" style="vertical-align: middle;cursor: pointer;" onClick="this.src='captcha.php?is_login=1&'+Math.random()" /> </td>
                  </tr>
                  <?php endif; ?>
                  <tr>
            <td colspan="2"  bgcolor="#ffffff"><input type="checkbox" value="1" name="remember" id="remember" /><label for="remember"><?php echo $this->_var['lang']['remember']; ?></label></td>
          </tr>
                  <tr>
                    <td bgcolor="#ffffff" colspan="2" align="center"><a href="user.php?act=qpassword_name" class="f6"><?php echo $this->_var['lang']['get_password_by_question']; ?></a>&nbsp;&nbsp;&nbsp;<a href="user.php?act=get_password" class="f6"><?php echo $this->_var['lang']['get_password_by_mail']; ?></a></td>
                  </tr>
                  <tr>
                    <td bgcolor="#ffffff" colspan="2"><div align="center">
                        <input type="submit" class="bnt_blue" name="login" value="<?php echo $this->_var['lang']['forthwith_login']; ?>" />
                        <?php if ($this->_var['anonymous_buy'] == 1): ?>
                        <input type="button" class="bnt_blue_2" value="<?php echo $this->_var['lang']['direct_shopping']; ?>" onclick="location.href='flow.php?step=consignee&amp;direct_shopping=1'" />
                        <?php endif; ?>
                        <input name="act" type="hidden" value="signin" />
                      </div></td>
                  </tr>
                </table>
              </form>

              </td>
            <td valign="top" bgcolor="#ffffff">
            <h6><span>用户注册：</span></h6>
            <form action="flow.php?step=login" method="post" name="formUser" id="registerForm" onsubmit="return checkSignupForm(this)">
               <table width="98%" border="0" cellpadding="8" cellspacing="1" bgcolor="#B0D8FF" class="table">
                  <tr>
                    <td bgcolor="#ffffff" align="right" width="25%"><strong><?php echo $this->_var['lang']['username']; ?></strong></td>
                    <td bgcolor="#ffffff"><input name="username" type="text" class="inputBg" id="username" onblur="is_registered(this.value);" /><br />
            <span id="username_notice" style="color:#FF0000"></span></td>
                  </tr>
                  <tr>
                    <td bgcolor="#ffffff" align="right"><strong><?php echo $this->_var['lang']['email_address']; ?></strong></td>
                    <td bgcolor="#ffffff"><input name="email" type="text" class="inputBg" id="email" onblur="checkEmail(this.value);" /><br />
            <span id="email_notice" style="color:#FF0000"></span></td>
                  </tr>
                  <tr>
                    <td bgcolor="#ffffff" align="right"><strong><?php echo $this->_var['lang']['password']; ?></strong></td>
                    <td bgcolor="#ffffff"><input name="password" class="inputBg" type="password" id="password1" onblur="check_password(this.value);" onkeyup="checkIntensity(this.value)" /><br />
            <span style="color:#FF0000" id="password_notice"></span></td>
                  </tr>
                  <tr>
                    <td bgcolor="#ffffff" align="right"><strong><?php echo $this->_var['lang']['confirm_password']; ?></strong></td>
                    <td bgcolor="#ffffff"><input name="confirm_password" class="inputBg" type="password" id="confirm_password" onblur="check_conform_password(this.value);" /><br />
            <span style="color:#FF0000" id="conform_password_notice"></span></td>
                  </tr>
                  <?php if ($this->_var['enabled_register_captcha']): ?>
                  <tr>
                    <td bgcolor="#ffffff" align="right"><strong><?php echo $this->_var['lang']['comment_captcha']; ?>:</strong></td>
                    <td bgcolor="#ffffff"><input type="text" size="8" name="captcha" class="inputBg" />
                    <img src="captcha.php?<?php echo $this->_var['rand']; ?>" alt="captcha" style="vertical-align: middle;cursor: pointer;" onClick="this.src='captcha.php?'+Math.random()" /> </td>
                  </tr>
                  <?php endif; ?>
                  <tr>
                    <td colspan="2" bgcolor="#ffffff" align="center">
                        <input type="submit" name="Submit" class="bnt_blue_1" value="<?php echo $this->_var['lang']['forthwith_register']; ?>" />
                        <input name="act" type="hidden" value="signup" />
                    </td>
                  </tr>
                </table>
              </form>
              </td>
          </tr>
          <?php if ($this->_var['need_rechoose_gift']): ?>
          <tr>
            <td colspan="2" align="center" style="border-top:1px #ccc solid; padding:5px; color:red;"><?php echo $this->_var['lang']['gift_remainder']; ?></td>
          </tr>
          <?php endif; ?>
        </table>
        </div>
        
        <?php endif; ?>




</div>
<div class="blank5"></div>
<div class="blank"></div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
  
</body>
<script type="text/javascript">
var process_request = "<?php echo $this->_var['lang']['process_request']; ?>";
<?php $_from = $this->_var['lang']['passport_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
var username_exist = "<?php echo $this->_var['lang']['username_exist']; ?>";
var compare_no_goods = "<?php echo $this->_var['lang']['compare_no_goods']; ?>";
var btn_buy = "<?php echo $this->_var['lang']['btn_buy']; ?>";
var is_cancel = "<?php echo $this->_var['lang']['is_cancel']; ?>";
var select_spe = "<?php echo $this->_var['lang']['select_spe']; ?>";
</script>
</html>
