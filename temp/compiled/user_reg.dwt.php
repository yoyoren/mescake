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

<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,user.js,transport.js,utils.js')); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'login_register.js')); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'jquery.min.js,jquery.json-1.3.js')); ?>
<body>
<div id="fullbg"></div>
<?php echo $this->fetch('library/page_header.lbi'); ?>

<form action="user.php" method="post" name="formUser" id="formUser">
<input name="act" type="hidden" value="act_register" >
	<div class="block box">
	 <div class="regmain">
			<span style="position:absolute;right:35px;top:10px;cursor:pointer;"><img src="themes/default/images/closelogin2.png" onclick="closeregister2()"/></span>
			<div class="regc" style='padding-top:45px;'>
				<table  border="0" id="regtable" cellspacing="0" cellpadding="0" onKeydown="checkenter3()">
					
					<tr>
						<td>手<span style='Visibility:hidden;'>手</span>机<span style='Visibility:hidden;'>手</span>号:</td><td colspan="2"><input name="mobile_phone" type="text" style='width:170px;' onfocus="kuangcolor(1)" onblur="check_mobile(this.value);" maxlength="11" id="mobile_phone" /><font color="red">*</font><span id="mobile_notice1"></span></td>
					</tr>
					<tr>
						<td>&nbsp; </td><td  colspan="2" id="mobile_notice" class='zcolor'>&nbsp;</td>
					</tr>
					<!--<tr>
						<td>短信验证码: </td><td><input type="text" name="captcha" id="capt" style="width:75px;" />&nbsp;&nbsp;<input type="button" name="get_captcha" id="getCaptcha" value="&nbsp;"  onclick="getCode3()" /><font color="red">*</font></td><td><span id="send_status"></span></td>
					</tr>
					<tr>
						<td colspan="3" id="captmes">&nbsp;</td>
					</tr>
					<tr>
						<td colspan='3'><font size='2' color='grey'>如验证码送达延时,可直接致电客服4000 600 700注册订购。</font></td>
					</tr>-->
					<tr style='display:none;'>
						<td id="regtd">邮箱: </td><td colspan="2"><input name="email" type="text" id="email" value='1111@ffalse.com' onfocus="kuangcolor(2)" onblur="checkEmail(this.value);" style='width:170px;' /><font color="red">*</font><span id="email_notice1"></span></td>
					</tr>
					<tr style='display:none;'>
						<td>&nbsp;</td><td  colspan="2" id="email_notice" class='zcolor'>&nbsp;</td>
					</tr>
					<tr>
						<td>请设置密码: </td><td style="width:220px;"><input name="password" type="password" id="password" onkeyup="mimaqiangdu()" onfocus="kuangcolor(3)" onblur="check_password(this.value);"  style='width:170px;' /><font color="red">*</font><span  id="password_notice1"></span></td><td><span id="qiangdu"></span></td>
					</tr>
					<tr>
						<td>&nbsp;</td><td colspan="2" id="password_notice" class='zcolor'>&nbsp;</td>
					</tr>
					<tr>
						<td>请确认密码:</td><td colspan="2"><input name="confirm_password" type="password" id="conform_password" onfocus="kuangcolor(4)" onblur="check_conform_password(this.value);" style='width:170px;' /><font color="red">*</font><span  id="conform_password_notice1"></span></td>
					</tr>
					<tr>
						<td>&nbsp;</td><td colspan="2" id="conform_password_notice" class='zcolor'>&nbsp;</td>
					</tr>
					<tr>
						<td>昵<span style='Visibility:hidden;'>昵称呢</span>称:</td><td><input name="rea_name" type="text" style='width:170px;' id='rea_name' onfocus="kuangcolor(5)" onblur="check_rea_name()"/><span></span></td>
						<td rowspan='2'>
							<table style="margin-left:280px;visibility:hidden;" border="0" >
								<tr>
									<td><input type="button" name="Submit" class="bt"  value="&nbsp;" onclick="register();" style="width:120px;"/></td>
									<td>&nbsp;&nbsp;&nbsp;</td>
									<td><img align="absmiddle" src="themes/default/images/shutdown.png" style="cursor:pointer;width:85px;height:30px;" onclick="closeregister2()"/></td>
									<!--<td><img src="themes/default/images/wangjimima.png" align="absmiddle" style="cursor:pointer;width:100px;height:25px;" onclick="showForget()" /></td>
									-->
								</tr>
							</table>
						</td>
					</tr>
						<tr><td>&nbsp;</td><td><div style="font-size:13px;color:grey;">欢迎注册，带&nbsp;<font color="red">*</font>&nbsp;的为必填项</div></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr>
							<td>&nbsp;</td><td colspan='2' style='padding-left:0px;'><input type="button" name="Submit" class="bt"  value="&nbsp;" onclick="register();" style="width:174px;height:30px;cursor:pointer;border:0;"/></td>
						</tr>
						<tr>
							<td>&nbsp;</td><td colspan='2' style='padding-left:250px;font-size:12px;color:black;'>金额有效期至2014年1月31日</td>
						</tr>
				</table>
			</div>	 
		</div>
	</div>
</form>

<div id="logindiv">
<span><img onclick="closeBg();" src="themes/default/images/closelogin2.png"></span>
<div class="logininput">
<form name="formLogin" action="user.php" method="post">
	<table border="0"  onKeydown="checkenter()"cellpadding="0" cellspacing="0">
	  <tr>
	    <td>邮箱/手机号:</td>
	  </tr>	
	  <tr>
	    <td><input class="inp" type="text"  name="username" id="username" style="width:220px;height:25px;"/></td>
	  </tr>	
	  <tr>
	    <td>密码:</td>
	  </tr>	
	  <tr>
	    <td><input class="inp" type="password"  name="password" id="password" style="width:220px;height:25px;"/><img src="themes/default/images/zhaohuimima.png"  style="cursor:pointer;" id="forgetmima" onclick="showForget()" />
			<input type="hidden" name="act" value="signin" />
			<input type="hidden" name="back_act" value="<?php echo $this->_var['back_act']; ?>" />
		</td>
	  </tr>
	  <tr>
	  	<td ><label id="notice12">&nbsp;</label></td>
	  </tr>	
	  <tr >
	    <td >
			<table border="0" cellspacing="0" cellpadding="0" style="text-align:left;">
				<tr>
					
					<td><input type="button" onClick="checklogin2()" value="&nbsp;" id="loginbt" style="line-height:30px;"></td>
					<td><a href="user.php?act=register" id="wozhuce">&nbsp;<img src="themes/default/images/woyaozhuce1.png" align="absmiddle" style="cursor:pointer;border:0;width:120px;height:32px;"/></a>
					</td>
				</tr>
			</table>
		</td>
	  </tr>	
	</table>
	</form>
	</div></div>


<div id="forgetbg"></div>
	<div  id="showForget1">
		<div style="height:20px;margin-top:5px;width:360px;"><img style="margin-left:345px;cursor:pointer;" src="themes/default/images/closelogin2.png" onclick="closeForget()"/></div>
			<form action ="yanzhengma2.php" method="post" name="frmde" onsubmit="return forgetpd()">
					<input type="hidden" name="checkword" id="checkword" />
						<table id="forgetTable" border="0">
							<tr>
								<td >手机号：</td><td><input type="text" id="cell_phone" name="cellNumber" /></td>
							</tr>
							<tr>
								<td width='110'><span >短信验证码：</span></td><td><input type="text" name="checkCode" id="checkCode"/></td>
							</tr>
							<tr>
								<td height="50"><input onclick="getCode2()" type="button" name="yzimg" id="yzimg" value="&nbsp;" style="width:86px;border:0;background:url(themes/default/images/yanzhengma.jpg) no-repeat;"/></td><td id="tdd" style="color:grey;"></td>
							</tr>
							<tr>
								<td>&nbsp;</td><td style="padding-left:75px;"><input type="submit" name="Submit" class="bt2" id="bt2" value="&nbsp;" style="width:120px;cursor:pointer;"/></td>
							</tr>
						</table>
			 </form>
	</div

<div style='margin:auto;width:954px;'>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</div>

</body>
</html>
<script type="text/javascript">
var process_request = "<?php echo $this->_var['lang']['process_request']; ?>";
<?php $_from = $this->_var['lang']['passport_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
var username_exist = "<?php echo $this->_var['lang']['username_exist']; ?>";
var InterValObj; //timer变量，控制时间
var count = 60; //间隔函数，1秒执行
var curCount;//当前剩余秒数
</script>
