<?php echo $this->smarty_insert_scripts(array('files'=>'jquery.min.js,jquery.json-1.3.js,transport.js')); ?>
<div id="fullbg"></div>
<div class="block clearfix index">
<div class="sidebar" id="dengludiv">
		<?php if ($this->_var['user_info']): ?><div class="sidebar-add1" id="logink"><?php else: ?><div class="sidebar-add" id="logink"><?php endif; ?>
		  <div class="sidebar-bj"><p>欢迎来到每实蛋糕官方网页，我们将为您提供最为优质的美食体验</p> </div>
		  <?php if ($this->_var['user_info']): ?><div class="sidebar-login2"><?php else: ?><div class="sidebar-login"><?php endif; ?>
		    <p>
			<?php if ($this->_var['user_info']): ?>
			<a href="user.php">我的MES信息中心</a>&nbsp;<img src="themes/default/images/ico.gif" align="absmiddle" />&nbsp;<a href="user.php?act=logout">退出</a>
			<?php else: ?>
			<a onclick="showBg();" href="javascript:void(0)">登陆</a><a href="user.php?act=register">注册</a>
			<?php endif; ?>
			<span class="lcolse" onClick="closel('logink')"></span>
			</p> 		          
		  </div>
		</div>
		 <?php if ($this->_var['user_info'] == NULL): ?>
		<div class="sidebar-vips" id="vip">
		 <div class="sidebar-vip"><p>VIP客户进入端口</p></div>
		 <div class="sidebar-vip-login"><p><a onclick="showBg();" href="javascript:void(0)">礼金卡客户登陆</a><span class="lcolse" onClick="closel('vip')"></span></p></div>
		</div>
		<?php endif; ?>
    </div>
	</div>
<div id="logindiv">
<span><img onclick="closeBg();" src="themes/default/images/closelogin2.png"></span>
<div class="logininput">
<form name="formLogin" action="user.php" method="post">
	<table border="0"  onKeydown="checkenter()"cellpadding="0" cellspacing="0">
	  <tr>
	    <td>手机号:</td>
	  </tr>	
	  <tr>
	    <td><input class="inp" type="text"  name="username" id="username" onfocus="kcolor(1)" onblur="kcolor2(1)" style="width:220px;height:26px;"/></td>
	  </tr>	
	  <tr>
	    <td>密码:</td>
	  </tr>	
	  <tr>
	    <td><input class="inp" type="password"  name="password" id="password" onfocus="kcolor(2)" onblur="kcolor2(2)" style="width:220px;height:26px;"/><img src="themes/default/images/wangjimima.jpg"  style="cursor:pointer;" id="forgetmima" onclick="showForget()" />
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
					
					<td><input type="button" onClick="checklogin()" value="&nbsp;" id="loginbt" style="line-height:30px;"></td>
					<td><a href="user.php?act=register" id="wozhuce">&nbsp;<img src="themes/default/images/zhuce3.jpg" align="absmiddle" style="cursor:pointer;border:0;margin-left:18px;width:100px;height:30px;"/></a>
					</td>
				</tr>
			</table>
		</td>
	  </tr>	
	</table>
	</form>
	</div></div>
	<?php echo $this->smarty_insert_scripts(array('files'=>'login_register.js')); ?>

<div id="forgetbg"></div>
	<div  id="showForget1">
		<div style="height:20px;margin-top:5px;width:660px;"><img style="margin-left:640px;cursor:pointer;" src="themes/default/images/closelogin2.png" onclick="closeForget()"/></div>
			<form action ="yanzhengma2.php" method="post" name="frmde" onsubmit="return forgetpd()">
				<input type="hidden" name="checkword" id="checkword" />
					<table id="forgetTable" border="0" style="line-height:30px;margin-left:300px;margin-top:30px;">
							<tr>
								<td ><span style='font-size:18px;'>手机号：</span></td><td><input type="text" onfocus="kcolor(5)" onblur='kcolor2(5)'id="cell_phone" name="cellNumber" style='height:26px;line-height:26px;'/></td>
							</tr>
							<tr>
								<td width='120'><span style='font-size:18px;'>短信验证码：</span></td><td><input type="text" onfocus="kcolor(6)" onblur='kcolor2(6)' name="checkCode" id="checkCode" style='height:26px;line-height:26px;'/></td>
							</tr>
							<tr>
								<td height="50"><input onclick="getCode2()" type="button" name="yzimg" id="yzimg" value="&nbsp;" style="width:86px;border:0;background:url(themes/default/images/yanzhengma.jpg) no-repeat;"/></td><td id="tdd" style="color:grey;"></td>
							</tr>
							<tr>
								<td>&nbsp;</td><td style=""><input type="submit" name="Submit" class="bt2" id="bt3" value="&nbsp;" style="width:194px;cursor:pointer;border:0;"/></td>
							</tr>
					</table>
			</form>
	</div>