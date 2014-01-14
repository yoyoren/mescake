<!DOCTYPE html>
<html>
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
  <title>购物车中的商品</title>
  <?php echo $this->fetch('lib/head_script.lbi'); ?>
</head>
<body>
<div class="layout">
  <?php echo $this->fetch('lib/header_new.lbi'); ?>
  <div class="head-line"></div>
  <div class="content">
    <div class="order-area">
      <h4 class="content-title">购物车中的商品</h4>
      <?php echo $this->fetch('lib/order_list.lbi'); ?>
      <div class="order-btn-area clearfix">
        <a href="index.php" id="continue_shopping" class="btn big-btn fl-l">继续购物</a>
        <input class="btn green-btn big-btn fl-r" type="button" value="提交订单" onclick="location.href='route.php?mod=order&action=step2'"/>
      </div>
    </div>
  </div>
  <script src="script/page/shoppingcar.js"></script>
  <?php echo $this->fetch('lib/footer_new.lbi'); ?>
</div>
</body>
</html>