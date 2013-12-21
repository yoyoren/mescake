
<?php echo $this->smarty_insert_scripts(array('files'=>'utils.js')); ?>
<script type="text/javascript">
  <?php $_from = $this->_var['lang']['profile_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
	var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</script>
<div id="container">
    <?php echo $this->fetch('library/user/user_top_menu.lbi'); ?>
	
	<div id="content">
		<p class="wane_left"></p>
		<p class="wane_right"></p>
		<div class="data">
        <form name="formEdit" action="user.php" method="post" onSubmit="return userEdit()">
			<input name="submit" type="submit" class="data_but" value=""/>
			<h2>修改我的信息</h2>
			<div class="item">
				<label>用户名：</label>
				<?php echo $this->_var['profile']['user_name']; ?>
			</div>
			<div class="item item1">
				<label>出生日期：</label>
				<?php echo $this->html_select_date(array('field_order'=>'YMD','prefix'=>'birthday','start_year'=>'-60','end_year'=>'+1','display_days'=>'true','month_format'=>'%m','day_value_format'=>'%02d','time'=>$this->_var['profile']['birthday'])); ?>
				<p>
					<label>性别：</label>
					<select>
                        <option value="0" <?php if ($this->_var['profile']['sex'] == 0): ?>selected<?php endif; ?>><?php echo $this->_var['lang']['secrecy']; ?></option>
						<option value="1" <?php if ($this->_var['profile']['sex'] == 1): ?>selected<?php endif; ?>><?php echo $this->_var['lang']['male']; ?></option>
						<option value="2" <?php if ($this->_var['profile']['sex'] == 2): ?>selected<?php endif; ?>><?php echo $this->_var['lang']['female']; ?></option>
					</select>
				</p>
			</div>
			<div class="item">
				<label>电子邮件：</label>
				<input name="email" value="<?php echo $this->_var['profile']['email']; ?>" type="text" class="w138px" />
				<p>
					<label>所在地区：</label>
                    <select name="province" id="province">
                    <option value="">请选择...</option>
                    <?php $_from = $this->_var['profile']['province_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'province');if (count($_from)):
    foreach ($_from AS $this->_var['province']):
?>
                 <option value="<?php echo $this->_var['province']['region_id']; ?>" <?php if ($this->_var['province']['region_id'] == $this->_var['profile']['province']): ?> selected<?php endif; ?>><?php echo $this->_var['province']['region_name']; ?></option>
                 <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </select>
				</p>
			</div>
			<div class="item">
				<label>移动电话：</label>
				<input name="mobile_phone" id="mobile_phone" value="<?php echo $this->_var['profile']['mobile_phone']; ?>" type="text" class="w138px" />
			</div>
			<div class="item">
				<label>家庭地址：</label>
				<input name="homeaddress" id="homeaddress" value="<?php echo $this->_var['profile']['homeaddress']; ?>" type="text" class="w472px" />
			</div>
            <input name="act" type="hidden" value="act_edit_profile" />
            </form>
		</div>
		<div class="data pt30px">
        <form name="formPassword" action="user.php" method="post" onSubmit="return editPassword()" >
			<input name="submit" type="submit" class="data_but" value=""/>
			<h2>修改密码</h2>
			<div class="item">
				<label class="pr20px">原密码：</label>
				<input name="old_password" type="password" class="w138px" value="" />
			</div>
			<div class="item">
				<label class="pr20px">新密码：</label>
				<input name="new_password" type="password" class="w138px"  value=""/>
			</div>
			<div class="item">
				<label>确认密码：</label>
				<input name="comfirm_password" type="password" class="w138px"  value=""/>
			</div>
            <input name="act" type="hidden" value="act_edit_password" />
         </form>
		</div>
	</div>
</div>