
<!DOCTYPE html>
<html>
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>您的购物车中没有商品</title>
  <?php echo $this->fetch('lib/head_script.lbi'); ?>
  <script src="script/placeholder.js"></script>
</head>
<body>
<div class="layout">
  <?php echo $this->fetch('lib/header_new.lbi'); ?>
  <div class="head-line"></div>
  <div class="content">
    <div class="order-area">
      <h4 class="content-title">您的购物车中没有商品</h4>
      <div class="has-no-order">
        <a href="brand.php?id=4" class="btn big-btn green-btn">赶紧去选购吧</a>
      </div>
    </div>
  </div>
  <?php echo $this->fetch('lib/footer_new.lbi'); ?>
</div>
</body>
</html>