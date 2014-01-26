
<!DOCTYPE html>
<html>
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>我的订单详情</title>
  <?php echo $this->fetch('lib/head_script.lbi'); ?>
  <script src="script/placeholder.js"></script>
</head>
<body>
<div class="layout">
  <?php echo $this->fetch('lib/header_new.lbi'); ?>
  <div class="head-line"></div>
  <div class="content">
    <div class="my-order">
      <p class="need-login-tip" style="display:none">您还没有设置访问密码，建议<a href="" class="td-u">设置</a>以完成注册，方便查看订单信息。</p>
      <h4 class="content-title"><a href="route.php?mod=account&action=order_list">我的订单</a> &gt; 订单详情</h4>
      <div class="order-detail-container" id="order_container">

      </div>
    </div>
  </div>
  <script src="script/page/orderdetail.js"></script>
  <?php echo $this->fetch('lib/footer_new.lbi'); ?>
</div>
</body>
</html>