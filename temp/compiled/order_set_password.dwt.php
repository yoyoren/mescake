
<!DOCTYPE html>
<html>
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>设置登录密码完成注册</title>
   <?php echo $this->fetch('lib/head_script.lbi'); ?>
   <script src="script/placeholder.js"></script>
</head>
<body>
<?php echo $this->_var['pay_online']; ?>
<div class="layout">
  <?php echo $this->fetch('lib/header_new.lbi'); ?>
  <div class="head-line"></div>
  <div class="content">
    <div class="suc-container tl-c">
      <p class="suc-tip"><span id="moblie_number"></span>的用户您好！<br/>请设置一个<span class="error-tip">6位及以上</span>的登录密码以完成注册</p>
      <div class="suc-bg-area">
        <p class="suc-tip">今后凭手机号码和密码就可以直接登录查看订单，还可以获得积分积累</p>
        <div class="check-container">
          <input type="password" class="global-input" placeholder="请设置6位或以上密码" id="password_input"/>
          <span class="tips-container" style="display:none" id="set_warn">请设置6位或以上密码</span>
        </div>
        <input class="btn green-btn vert-btn" type="submit" value="确认" id="change_password"/>
      </div>
    </div>
  </div>
  <script src="script/page/shoppingend.js"></script>
  <?php echo $this->fetch('lib/footer_new.lbi'); ?>
</div>
</body>
<script type="text/javascript">
$("input[placeholder]").miPlaceholder("#ccc");
</script>
</html>