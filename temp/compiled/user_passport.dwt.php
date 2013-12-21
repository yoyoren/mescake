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

<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,user.js,transport.js,login_register.js,jquery.min.js,jquery.json-1.3.js,')); ?>

<body>
<?php echo $this->fetch('library/page_header.lbi'); ?>

<div class="block box">
 <div id="ur_here">
  </div>
</div>

<div class="blank"></div>
<?php if ($this->_var['action'] == 'login'): ?>
<div class="usBox clearfix">
  <div id="logindiv1">

<span><img onclick="closeBg1();" src="themes/default/images/closelogin2.png"></span>
<div class="logininput" id="logininput">
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
	    <td><input class="inp" type="password"  name="password" id="password" style="width:220px;height:25px;"/><img src="themes/default/images/zhaohuimima.png"  style="cursor:pointer;" id="forgetmima" onclick="showForget1()" />
			<input type="hidden" name="act" value="act_login" />
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
					
					<td><input type="button" onClick="checklogin()" value="&nbsp;" id="loginbt" style="line-height:30px;"></td>
					<td><a href="user.php?act=register" id="wozhuce">&nbsp;<img src="themes/default/images/woyaozhuce1.png" align="absmiddle" style="cursor:pointer;border:0;width:120px;height:32px;"/></a>
					</td>
				</tr>
			</table>
		</td>
	  </tr>	
	</table>
	</form>
	</div>
    
    </div>
    
    <div  id="showForget2">
		<div style="height:20px;margin-top:5px;width:360px;"><img style="margin-left:345px;cursor:pointer;" src="themes/default/images/closelogin2.png" onclick="closeForget1()"/></div>
			<form action ="yanzhengma2.php" method="post" name="frmde" onsubmit="return forgetpd()">
				<input type="hidden" name="checkword" id="checkword" />
					<table id="forgetTable" border="0">
							<tr>
								<td >手机号：</td><td><input type="text" id="cell_phone" name="cellNumber" /></td>
							</tr>
							<tr>
								<td width='90'><span >短信验证码：</span></td><td><input type="text" name="checkCode" id="checkCode"/></td>
							</tr>
							<tr>
								<td height="50"><input onclick="getCode2()" type="button" name="yzimg" id="yzimg" value="&nbsp;" style="width:86px;border:0;background:url(themes/default/images/yanzhengma.jpg) no-repeat;"/></td><td id="tdd" style="color:grey;"></td>
							</tr>
							<tr>
								<td>&nbsp;</td><td style="padding-left:70px;"><input type="submit" name="Submit" class="bt2" id="bt2" value="&nbsp;" style="width:120px;cursor:pointer;"/></td>
							</tr>
					</table>
			</form>
	</div>
     
</div>
<?php endif; ?>


    <?php if ($this->_var['action'] == 'register'): ?>
    <?php if ($this->_var['shop_reg_closed'] == 1): ?>
    <div class="usBox">
      <div class="usBox_2 clearfix">
        <div class="f1 f5" align="center"><?php echo $this->_var['lang']['shop_register_closed']; ?></div>
      </div>
    </div>
    <?php else: ?>
    <?php echo $this->smarty_insert_scripts(array('files'=>'utils.js')); ?>
<div class="usBox">
  <div class="usBox_2 clearfix">
   <div class="regtitle"></div>
    <form action="user.php" method="post" name="formUser" onsubmit="return register();">
      <table width="100%"  border="0" align="left" cellpadding="5" cellspacing="3">
        <tr>
          <td width="13%" align="right"><?php echo $this->_var['lang']['label_username']; ?></td>
          <td width="87%">
          <input name="username" type="text" size="25" id="username" onblur="is_registered(this.value);" class="inputBg"/>
            <span id="username_notice" style="color:#FF0000"> *</span>
          </td>
        </tr>
        <tr>
          <td align="right"><?php echo $this->_var['lang']['label_email']; ?></td>
          <td>
          <input name="email" type="text" size="25" id="email" onblur="checkEmail(this.value);"  class="inputBg"/>
            <span id="email_notice" style="color:#FF0000"> *</span>
          </td>
        </tr>
        <tr>
          <td align="right"><?php echo $this->_var['lang']['label_password']; ?></td>
          <td>
          <input name="password" type="password" id="password1" onblur="check_password(this.value);" onkeyup="checkIntensity(this.value)" class="inputBg" style="width:179px;" />
            <span style="color:#FF0000" id="password_notice"> *</span>
          </td>
        </tr>
        <tr>
          <td align="right"><?php echo $this->_var['lang']['label_password_intensity']; ?></td>
          <td>
            <table width="145" border="0" cellspacing="0" cellpadding="1">
              <tr align="center">
                <td width="33%" id="pwd_lower"><?php echo $this->_var['lang']['pwd_lower']; ?></td>
                <td width="33%" id="pwd_middle"><?php echo $this->_var['lang']['pwd_middle']; ?></td>
                <td width="33%" id="pwd_high"><?php echo $this->_var['lang']['pwd_high']; ?></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td align="right"><?php echo $this->_var['lang']['label_confirm_password']; ?></td>
          <td>
          <input name="confirm_password" type="password" id="conform_password" onblur="check_conform_password(this.value);"  class="inputBg" style="width:179px;"/>
            <span style="color:#FF0000" id="conform_password_notice"> *</span>
          </td>
        </tr>
        <?php $_from = $this->_var['extend_info_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'field');if (count($_from)):
    foreach ($_from AS $this->_var['field']):
?>

        <tr>
          <?php if ($this->_var['field']['is_need']): ?>id="extend_field<?php echo $this->_var['field']['id']; ?>i"<?php endif; ?>><?php echo $this->_var['field']['reg_field_name']; ?>
          <td align="right" <?php if ($this->_var['field']['is_need']): ?>id="extend_field<?php echo $this->_var['field']['id']; ?>i"<?php endif; ?>><?php echo $this->_var['field']['reg_field_name']; ?>
          <td>
          <input name="extend_field<?php echo $this->_var['field']['id']; ?>" type="text" size="25" class="inputBg" />
          </td>
        </tr>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        <tr>
          <td>&nbsp;</td>
          <td align="left">
          <input name="act" type="hidden" value="act_register" >
          <input type="hidden" name="back_act" value="<?php echo $this->_var['back_act']; ?>" />
          <input name="Submit" type="submit" value="" class="us_Submit_reg">
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php endif; ?>
<?php endif; ?>



    <?php if ($this->_var['action'] == 'get_password'): ?>
    <?php echo $this->smarty_insert_scripts(array('files'=>'utils.js')); ?>
    <script type="text/javascript">
    <?php $_from = $this->_var['lang']['password_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
      var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </script>
<div class="usBox">
  <div class="usBox_2 clearfix">
    <form action="user.php" method="post" name="getPassword" onsubmit="return submitPwdInfo();">
        <br />
        <table width="70%" border="0" align="center">
          <tr>
            <td colspan="2" align="center"><strong><?php echo $this->_var['lang']['username_and_email']; ?></strong></td>
          </tr>
          <tr>
            <td width="29%" align="right"><?php echo $this->_var['lang']['username']; ?></td>
            <td width="61%"><input name="user_name" type="text" size="30" class="inputBg" /></td>
          </tr>
          <tr>
            <td align="right"><?php echo $this->_var['lang']['email']; ?></td>
            <td><input name="email" type="text" size="30" class="inputBg" /></td>
          </tr>
          <tr>
            <td></td>
            <td><input type="hidden" name="act" value="send_pwd_email" />
              <input type="submit" name="submit" value="<?php echo $this->_var['lang']['submit']; ?>" class="bnt_blue" style="border:none;" />
              <input name="button" type="button" onclick="history.back()" value="<?php echo $this->_var['lang']['back_page_up']; ?>" style="border:none;" class="bnt_blue_1" />
	    </td>
          </tr>
        </table>
        <br />
      </form>
  </div>
</div>
<?php endif; ?>


    <?php if ($this->_var['action'] == 'qpassword_name'): ?>
<div class="usBox">
  <div class="usBox_2 clearfix">
    <form action="user.php" method="post">
        <br />
        <table width="70%" border="0" align="center">
          <tr>
            <td colspan="2" align="center"><strong><?php echo $this->_var['lang']['get_question_username']; ?></strong></td>
          </tr>
          <tr>
            <td width="29%" align="right"><?php echo $this->_var['lang']['username']; ?></td>
            <td width="61%"><input name="user_name" type="text" size="30" class="inputBg" /></td>
          </tr>
          <tr>
            <td></td>
            <td><input type="hidden" name="act" value="get_passwd_question" />
              <input type="submit" name="submit" value="<?php echo $this->_var['lang']['submit']; ?>" class="bnt_blue" style="border:none;" />
              <input name="button" type="button" onclick="history.back()" value="<?php echo $this->_var['lang']['back_page_up']; ?>" style="border:none;" class="bnt_blue_1" />
	    </td>
          </tr>
        </table>
        <br />
      </form>
  </div>
</div>
<?php endif; ?>


    <?php if ($this->_var['action'] == 'get_passwd_question'): ?>
<div class="usBox">
  <div class="usBox_2 clearfix">
    <form action="user.php" method="post">
        <br />
        <table width="70%" border="0" align="center">
          <tr>
            <td colspan="2" align="center"><strong><?php echo $this->_var['lang']['input_answer']; ?></strong></td>
          </tr>
          <tr>
            <td width="29%" align="right"><?php echo $this->_var['lang']['passwd_question']; ?>：</td>
            <td width="61%"><?php echo $this->_var['passwd_question']; ?></td>
          </tr>
          <tr>
            <td align="right"><?php echo $this->_var['lang']['passwd_answer']; ?>：</td>
            <td><input name="passwd_answer" type="text" size="20" class="inputBg" /></td>
          </tr>
          <?php if ($this->_var['enabled_captcha']): ?>
          <tr>
            <td align="right"><?php echo $this->_var['lang']['comment_captcha']; ?></td>
            <td><input type="text" size="8" name="captcha" class="inputBg" />
            <img src="captcha.php?is_login=1&<?php echo $this->_var['rand']; ?>" alt="captcha" style="vertical-align: middle;cursor: pointer;" onClick="this.src='captcha.php?is_login=1&'+Math.random()" /> </td>
          </tr>
          <?php endif; ?>
          <tr>
            <td></td>
            <td><input type="hidden" name="act" value="check_answer" />
              <input type="submit" name="submit" value="<?php echo $this->_var['lang']['submit']; ?>" class="bnt_blue" style="border:none;" />
              <input name="button" type="button" onclick="history.back()" value="<?php echo $this->_var['lang']['back_page_up']; ?>" style="border:none;" class="bnt_blue_1" />
	    </td>
          </tr>
        </table>
        <br />
      </form>
  </div>
</div>
<?php endif; ?>

<?php if ($this->_var['action'] == 'reset_password'): ?>
    <script type="text/javascript">
    <?php $_from = $this->_var['lang']['password_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
      var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </script>
<div class="usBox">
  <div class="usBox_2 clearfix">
    <form action="user.php" method="post" name="getPassword2" onSubmit="return submitPwd()">
      <br />
      <table width="80%" border="0" align="center">
        <tr>
          <td><?php echo $this->_var['lang']['new_password']; ?></td>
          <td><input name="new_password" type="password" size="25" class="inputBg" /></td>
        </tr>
        <tr>
          <td><?php echo $this->_var['lang']['confirm_password']; ?>:</td>
          <td><input name="confirm_password" type="password" size="25"  class="inputBg"/></td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input type="hidden" name="act" value="act_edit_password" />
            <input type="hidden" name="uid" value="<?php echo $this->_var['uid']; ?>" />
            <input type="hidden" name="code" value="<?php echo $this->_var['code']; ?>" />
            <input type="submit" name="submit" value="<?php echo $this->_var['lang']['confirm_submit']; ?>" />
          </td>
        </tr>
      </table>
      <br />
    </form>
  </div>
</div>
<?php endif; ?>

<div class="blank"></div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
  
   <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1000160405'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1000160405%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script>
</body>
<script type="text/javascript">
var process_request = "<?php echo $this->_var['lang']['process_request']; ?>";
<?php $_from = $this->_var['lang']['passport_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
var username_exist = "<?php echo $this->_var['lang']['username_exist']; ?>";
</script>
</html>
