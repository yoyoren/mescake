
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
      <h4 class="content-title">订单信息</h4>
	<?php echo $this->fetch('lib/order_list.lbi'); ?>
      <h4 class="content-title">送货信息<span class="ct-commit">*为保证送货准确性，请您填写正确无误的信息</span></h4>
      <p class="need-login-tip" id="login_tip" style="display:none">如果您之前注册购买过，请<a href="#" class="td-u user_login">登录</a>以读取地址信息，并积累积分</p>
      <div class="account-mes-area" id="address_container">
        <div class="ama-add-address">
           <a href="#" class="btn" id="add_new_address">+添加新地址</a>
        </div>
      </div>
      <form>
          <dl class="global-form-container clearfix" id="new_address_form" style="display:none">
            <dt class="l-title">送货地址：</dt>
            <dd class="r-con clearfix">
              北京市
              <em class="area-intro"></em>
              <select id="region_sel">
                <option value="0">请选择区域</option>
              </select><br>
              <div class="check-container big-check-container" style="margin-top:10px;">
                <input type="text" class="global-input" placeholder="详细地址" id="new_address">
                <span class="tips-container" style="display:none">输入错误</span>
              </div>
            </dd>
            <dt class="l-title lh-input">收货人：</dt>
            <dd class="r-con clearfix">
              <div class="check-container">
                <input type="text" class="global-input" placeholder="收货人姓名" id="new_contact">
                <span class="tips-container" style="display:none">输入错误</span>
              </div>
            </dd>
            <dt class="l-title lh-input">联系手机：</dt>
            <dd class="r-con clearfix">
              <div class="check-container">
                <input type="text" class="global-input" placeholder="联系人的手机号码" id="new_tel">
                <span class="tips-container" style="display:none">输入错误</span>
              </div>
            </dd>
            <dt class="l-title lh-input">&nbsp;</dt>
            <dd class="r-con clearfix" id="login_address_operate" style="display:none">
              <input class="btn green-btn" type="button" value="保存" id="save_address">
              <input class="btn green-btn" type="button" value="修改并保存" id="mod_address" style="display:none">
              <input class="btn" type="button" value="取消" id="cancel_address">
            </dd>
          </dl>
          <dl class="global-form-container clearfix">
            <dt class="l-title">送货时间：</dt>
            <dd class="r-con clearfix" style="margin-bottom:20px;">
              <input type="text" id="date_picker">
              <select id="hour_picker">
                <option value="0">小时</option>
              </select>
              <select id="minute_picker">
                <option value="0">分钟</option>
                <option value="0">0</option>
                <option value="30">30</option>
              </select>
            </dd>
            <dt class="l-title" style="display:none">开发票：</dt>
            <dd class="r-con clearfix" style="display:none;margin-bottom:20px;">
              <input type="checkbox" class="checkbox-item" id="fapiao_chk">
            </dd>
          </dl>
          <dl class="global-form-container clearfix" style="margin-left:80px;display:none" id="fapiao_form">
            <dt class="l-title">发票类型：</dt>
            <dd class="r-con clearfix" style="margin-bottom:20px;">
              普通发票
            </dd>
            <dt class="l-title">发票抬头：</dt>
            <dd class="r-con clearfix" style="margin-bottom:20px;">
              <label for="personal" class="space"><input type="radio" name="invoice" id="personal" class="radio-item">个人</label>
              <label for="company"><input type="radio" name="invoice" id="company" class="radio-item">公司</label>
            </dd>
            <dt class="l-title lh-input">顾客姓名：</dt>
            <dd class="r-con clearfix">
              <div class="check-container">
                <input type="password" class="global-input" placeholder="请填写发票打印抬头">
                <span class="tips-container" style="display:none">输入错误</span>
              </div>
            </dd>
            <dt class="l-title lh-input">公司名称：</dt>
            <dd class="r-con clearfix">
              <div class="check-container">
                <input type="password" class="global-input" placeholder="请填写发票打印抬头">
                <span class="tips-container" style="display:none">输入错误</span>
              </div>
            </dd>
            <dt class="l-title">发票内容：</dt>
            <dd class="r-con clearfix" style="margin-bottom:20px;">
              <label for="cake" class="space"><input type="radio" name="invoiceCon" id="cake" class="radio-item">蛋糕</label>
              <label for="food"><input type="radio" name="invoiceCon" id="food" class="radio-item">食品</label>
            </dd>
          </dl>
          <h4 class="content-title">付款方式</h4>
          <div class="payStyle">
            <label for="cashon"><input type="radio" name="payStyle" id="cashon" class="radio-item">货到付款</label>
            <select style="margin:0 10px;"><option>现金付款</option><option>POS机刷卡</option></select>
            <label for="alipay" class="space"><input type="radio" name="payStyle" id="alipay" class="radio-item">支付宝</label>
            <label for="kuaiqian"><input type="radio" name="payStyle" id="kuaiqian" class="radio-item">快钱</label>
          </div>
          <div class="check-money-area clearfix">
            <div class="money-style">
            <label for="voucher"><input type="checkbox" id="voucher" class="checkbox-item">使用代金券</label><br>
            <div style="margin-left:20px;">
              请输入代金券密码:
              <style type="text/css">
.more-input-area .global-input{width: 40px; margin-bottom: 10px;}
              </style>
              <div class="more-input-area">
                <input type="text" class="global-input"> -
                <input type="text" class="global-input"> -
                <input type="text" class="global-input"> -
                <input type="text" class="global-input"> 
                <em class="error-ico">x</em>
                <em class="suc-ico">v</em>
              </div>
            </div>
            <label for="balance"><input type="checkbox" id="balance" class="checkbox-item">使用账户余额（0元）</label><br>
            </div>
            <div class="total-money">
              <p><em>商品总计：</em><b class="total order_total">0元</b></p>
              <p><em>配送费：</em><b>0元</b></p>
              <p><em>优惠折扣：</em><b>0元</b></p>
              <p class="total-p"><em>您总共需要支付：</em><b class="total">0元</b></p>
            </div>
          </div>
          <div class="tl-r">
            <span class="total-sub-tip">请输入验证码：</span>
            <input type="text" class="global-input" style="width:80px;">
            <img src="" class="check-code">
          </div>
          <div class="tl-r">
            <span class="total-sub-tip">请确认信息无误后下单</span>
            <input id="submit_order_btn" class="btn green-btn big-btn" type="button" style="margin-right:0;" value="提交订单">
          </div>
        </form>
        <form action="flow.php" method="post" id="submit_form">
          <input type="hidden" name="pay_id" value="3" id="pay_id">
          <input type="hidden" name="bonus_sn" value="请输入10位现金券券号"  id="bonus_sn">
          <input type="hidden" name="shipping_fee" value="￥0.00"  id="shipping_fee">
          <input type="hidden" name="x" value="97">
          <input type="hidden" name="y" value="32">
          <input type="hidden" name="step" value="done">
        </form>
    </div>
  </div>

  <script src="js/jquery.json-1.3.js"></script>
  <script src="script/page/shoppingcar.js"></script>
  <script src="script/page/order.js"></script>
  <script src="script/datepicker/WdatePicker.js"></script>
  <?php echo $this->fetch('lib/footer_new.lbi'); ?>
</div>
</body>
</html>