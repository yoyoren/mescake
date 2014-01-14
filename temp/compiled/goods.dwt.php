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
<link rel="stylesheet" type="text/css" href="css/home.css" />

<?php echo $this->smarty_insert_scripts(array('files'=>'jquery.min.js,jquery.json-1.3.js,login_register.js,transport.js,common.js,utils.js')); ?>
<script src="script/require.js"></script>
<script src="script/page/common.js"></script>
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
   <div class="blank60"></div>
   <div class="AreaL">
   <form action="shopcar.php?goods_id=<?php echo $this->_var['goods']['goods_id']; ?>)" method="post" name="ECS_FORMBUY" id="ECS_FORMBUY" >
    <div class="center-all-h">
       <div class="gbanner">
		<ul>
		<li><img src="themes/default/images/p/<?php echo $this->_var['goods']['goods_sn']; ?>-1.jpg"   /> </li>
		<li><img src="themes/default/images/p/<?php echo $this->_var['goods']['goods_sn']; ?>-2.jpg"   /> </li>
		<?php if ($this->_var['goods']['brand_id'] == 3): ?><li><img src="themes/default/images/p/<?php echo $this->_var['goods']['goods_sn']; ?>-3.jpg"   /> </li><?php endif; ?>
		</ul>
        <ol>
        <li class="active"></li>
        <li ></li>
		<?php if ($this->_var['goods']['brand_id'] == 3): ?><li ></li><?php endif; ?>
        </ol>
	   </div>
    </div>
    <div class="cen-body clearfix" >
	
		<div style="width:200px;float:left;border:0 solid green;">
			<div class="kouwei" >
					<p style="margin-bottom:10px;">Flavor Type 口味</p>
					<span><?php echo $this->_var['kouwei']; ?></span>
					<p style="margin-top:20px;margin-bottom:10px;">Food Materials 原材料</p>
					<span><?php echo $this->_var['ycl']; ?></span> 
			</div>
				
			<div class="songhuo">
					<p>1.北京市五环内免费送货。</p>
					<p>2.蛋糕在收到后2-3小时内食用最佳。</p>
					<p>3.如对本产品有过敏经历者，请选择其它款蛋糕。</p>
					<p>&nbsp;</p><br/><br/>
			</div>
		</div>
	
		<div style="width:380px;float:left;border-left:1px solid black;border-right:1px solid black;">
				<div class="size">
						   <h1>Size  尺寸</h1>
						   <ul  class="goodsattr" id="goodsattr">
					
					<?php $_from = $this->_var['specification']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('spec_key', 'spec');if (count($_from)):
    foreach ($_from AS $this->_var['spec_key'] => $this->_var['spec']):
?>
					<li style="text-align:left;">
					  <?php $_from = $this->_var['spec']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['value']):
?>
					  <div class="clearfix" style="width:370px;margin-left:0;border:0px solid" >
						<label for="spec_value_<?php echo $this->_var['value']['id']; ?>">
							<div style="width:350px;float:left;border:0px solid green">
							<b><?php echo $this->_var['value']['label']; ?>&nbsp;[<?php echo $this->_var['value']['format_price']; ?>]</b> 
							</div>
						</label>
						<input class="hide1" style="display:none;" type="radio" name="spec_<?php echo $this->_var['spec_key']; ?>"  value="<?php echo $this->_var['value']['id']; ?>" id="spec_value_<?php echo $this->_var['value']['id']; ?>" <?php if ($this->_var['key'] == 0): ?>checked<?php endif; ?> onclick="changePrice()" /><!--<class="hide1">*-->
						<div style="float:left;"><span  id="_<?php echo $this->_var['value']['id']; ?>" ></span></div>
					  </div>
					  <br />
					  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					  <input type="hidden" name="spec_list" value="<?php echo $this->_var['key']; ?>" />                             
					  </li>    
					  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					  
					  </ul>
				<div class="text" >10磅~30磅产品可根据客户需要定制，<br/>&nbsp;详情咨询客服</div>   
					<div class="line1">
						<?php if ($this->_var['wine']): ?><img src="themes/default/images/wine.jpg" /><?php endif; ?>
						<?php if ($this->_var['nut']): ?><img src="themes/default/images/nuts.jpg" /><?php endif; ?>
						<?php if ($this->_var['qkl']): ?><img src="themes/default/images/cho.jpg" /><?php endif; ?>
					</div>      
				</div>	  	
				
				<div class="" >
					<div class="line2"><img src="themes/default/images/baoxian.jpg" /></div>
					<div class="line3"><img src="themes/default/images/wendu.jpg" /><img src="themes/default/images/cha.jpg" /><span id="cj" style="font-size:25px; font-weight:bold"></span><img src="themes/default/images/dao.jpg" /><span id="cd" style="font-size:25px; font-weight:bold"></span><img src="themes/default/images/cj.jpg" /></div>            
				</div>
		</div>
	
		<div style="width:180px;float:left;border:0 solid blue;">
			<div class="pinming">
					<h1 style="line-height:30px;margin-left:15px;"><?php echo $this->_var['goods']['goods_sn']; ?></h1>
		  			<h1 style="line-height:60px;font-size:40px;margin-left:15px;"><?php echo $this->_var['goods']['goods_name']; ?></h1>
		  			<div id="ECS_GOODS_AMOUNT" style="padding-left:15px;"></div>
                    <br><hr style="margin-left:5px;"><br/>
		  			<div class="shuliang">
                         
		  				 <div class="plus"></div>
                         <div class="num"><img src="themes/default/images/shuliang.jpg" align="absmiddle"/>
                            <img id="imgminus" src="themes/default/images/minus.jpg" onclick="changenumber1(0)">&nbsp;<input name="number" type="text" id="number" value="1"  size="2"   onchange="changePrice()" />&nbsp;<img id="imgplus" src="themes/default/images/plus.jpg" onclick="changenumber1(1)">
                         </div>
                         <div class="minus"></div> 
		  			</div>
			</div>
			<br/>
					<div id="login2" style="display:none;"><?php echo $this->_var['u_name']; ?><?php echo $this->_var['u_name2']; ?></div>
					<div class="addgou"><img src="themes/default/images/paynow.png" id="order_now_btn" style="cursor:pointer;"></div><br/>
					<div class="addgou"><img src="themes/default/images/addshopcar.png" id="add_to_cart_btn" style="cursor:pointer;"></div>
					
					
			
		</div>
<div id="fullbg2"></div>

<div id="logindiv12" >
<span><img onclick="closeBgs2();" src="themes/default/images/closelogin2.png"></span>
	<div class="logininput" >
	<form name="formLogin" action="user.php" method="post">
	<table border="0" height='240' style='margin-left:310px;margin-top:60px;' onKeydown="checkenter()"cellpadding="0" cellspacing="0" >
	  <tr>
	    <td>手机号:</td>
	  </tr>	
	  <tr>
	    <td><input class="inp" type="text"  name="username" id="uname" onfocus="kcolor(3)" onblur="kcolor2(3)"style="width:220px;height:25px;"/></td>
	  </tr>	
	  <tr>
	    <td>密码:</td>
	  </tr>	
	  <tr>
	    <td><input class="inp" type="password"  name="password" id="pwd" onfocus="kcolor(4)" onblur="kcolor2(4)" style="width:220px;height:25px;"/><img src="themes/default/images/wangjimima.jpg"  style="cursor:pointer;" id="forgetmima" onclick="showForget()" />
			<input type="hidden" name="act" value="signin" />
			<input type="hidden" name="back_act" value="<?php echo $this->_var['back_act']; ?>" />
		</td>
	  </tr>
	  <tr>
	  	<td ><label id="notice13">&nbsp;</label></td>
	  </tr>	
	  <tr >
	    <td >
			<table border="0" cellspacing="0" cellpadding="0" style="text-align:left;">
				<tr>
					
					<td><input type="button" onClick="checklogin3()" value="&nbsp;" id="loginbt" style="line-height:30px;"></td>
					<td id='jump'><a href="user.php?act=register" id="wozhuce">&nbsp;<img src="themes/default/images/zhuce3.jpg" align="absmiddle" style="cursor:pointer;border:0;margin-left:18px;width:100px;height:30px;"/></a>
					</td>
				</tr>
			</table>
		</td>
	  </tr>	
	  <tr><td>&nbsp;</td></tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr><td>&nbsp;</td></tr>
	</table>
	</form>
	</div>	
</div>

<script type="text/javascript">
var goods_id='';
var typenum='';	
function closeBgs2(){
	$("#logindiv12,#fullbg2").hide();
	$("html").css("overflow-y","");
	$("html").css("overflow-x","");
}

function showBgs3(){ 
	var obj = document.getElementById('logindiv12');
	var W = screen.width;//取得屏幕分辨率宽度 
	var H = screen.height;//取得屏幕分辨率高度 
	var yScroll;//取滚动条高度 
	if (self.pageYOffset) { 
	yScroll = self.pageYOffset; 
	} else if (document.documentElement && document.documentElement.scrollTop){ 
	yScroll = document.documentElement.scrollTop; 
	} else if (document.body) {
	yScroll = document.body.scrollTop; 
	}
	$('#fullbg2').height(yScroll+H);
	obj.style.left= (W/2-350) + "px";
	obj.style.top= (H/2 -90 - 225　+　yScroll) + "px";
	document.getElementById("fullbg2").style.display="block";
	obj.style.display="block"; 
	$("html").css("overflow-y","hidden");
	$("html").css("overflow-x","hidden");
}
  function checklogin3()
	{
	var username = document.getElementById('uname').value;
	var password = document.getElementById('pwd').value;
	var msg='';
	  if (username.length == 0)
	  {
		msg = '手机号必须填写';
	  }else if (password.length == 0)
	  {
		msg = ' 密码不能为空';
	  }
	  if (msg.length > 0)
	  {
		$('#notice13').html(msg);
		
	  }
	  else
	  {
		Ajax.call( 'user.php?act=signin', 'username=' + username +'&password='+password, login_callback3 , 'POST', 'JSON', true, true );
	  }
	}
	function login_callback3($result)
	{
		if($result.error)
		{
			$('#notice13').html('用户名密码错误');
		}
		else
		{
			var act=document.formLogin.act.value;
			 if(act=="act_login")
			 {
				 window.location='./index.php';
			 }
			else
			{
				
				if(typenum==2)
				{	
					//addToCart1(goods_id);	
					window.location='shopcar.php';
				}
				if(typenum==1)
				{	
					//addToCart2(goods_id);
					window.location='flow.php?step=cart';
				}
				
				
			}
		}
	}
</script>

  
    </div>
   </form>
   </div>
         <div class="top-right">
          <?php echo $this->fetch('library/search_form2.lbi'); ?>	
 <?php 
$k = array (
  'name' => 'right_history',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
<div style='visibility:hidden;'> 
	<div id="ECS_CARTINFO">
	<?php 
$k = array (
  'name' => 'right_cart_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
	</div>
</div>
</div>
   <div class="blank20"></div>
   <div class="blank20"></div>
     <div style='display:none;'> 
    <?php echo $this->fetch('library/recommend_new.lbi'); ?>
	</div>
<div class="blank20"></div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>


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
        
        
        
        <?php endif; ?>
  
</body>
<script type="text/javascript">
var goods_id = <?php echo $this->_var['goods_id']; ?>;
var goodsattr_style = <?php echo empty($this->_var['cfg']['goodsattr_style']) ? '1' : $this->_var['cfg']['goodsattr_style']; ?>;
var gmt_end_time = <?php echo empty($this->_var['promote_end_time']) ? '0' : $this->_var['promote_end_time']; ?>;
<?php $_from = $this->_var['lang']['goods_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
var goodsId = <?php echo $this->_var['goods_id']; ?>;
var now_time = <?php echo $this->_var['now_time']; ?>;


onload = function(){
  changePrice();
  //fixpng();
  attrkuang();
  try {onload_leftTime();}
  catch (e) {}
}

/**
 * 点选可选属性或改变数量时修改商品价格的函数
 */
function changePrice()
{
  var attr = getSelectedAttributes(document.forms['ECS_FORMBUY']);
  var qty = document.forms['ECS_FORMBUY'].elements['number'].value;

  Ajax.call('goods.php', 'act=price&id=' + goodsId + '&attr=' + attr + '&number=' + qty, changePriceResponse, 'GET', 'JSON');
}

/**
 * 接收返回的信息
 */
function changePriceResponse(res)
{
  if (res.err_msg.length > 0)
  {
    alert(res.err_msg);
  }
  else
  {
    document.forms['ECS_FORMBUY'].elements['number'].value = res.qty;

    if (document.getElementById('ECS_GOODS_AMOUNT'))
      document.getElementById('ECS_GOODS_AMOUNT').innerHTML = res.result;
	if(document.getElementById('cj'))
	document.getElementById('cj').innerHTML = res.cj;
	if(document.getElementById('cd'))
	document.getElementById('cd').innerHTML = res.cd;
  }
}
function changenumber1(way)
{
	if(way=="0"){
		 document.forms['ECS_FORMBUY'].elements['number'].value=parseInt(document.forms['ECS_FORMBUY'].elements['number'].value)-1;
		 if(document.forms['ECS_FORMBUY'].elements['number'].value==0) document.forms['ECS_FORMBUY'].elements['number'].value==1;
		 changePrice();	 
	}else{
	 	document.forms['ECS_FORMBUY'].elements['number'].value=parseInt(document.forms['ECS_FORMBUY'].elements['number'].value)+1;
		changePrice();	
	}
}


function attrkuang(){
		  labels = document.getElementById('goodsattr').getElementsByTagName('span');
		  radios = document.getElementById('goodsattr').getElementsByTagName('input');
		  for(m=0;m<radios.length;m++){
		  	if(radios[m].checked == true) 
			{
				var aa=radios[m].id;
				document.getElementById(aa.substr(10)).className='checked';;
			}
		  }
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
			 var s=this.id;	 
			 try{
				document.getElementById('spec_value'+s).checked = true;
				changePrice();
			 } catch (e) {}
			}
		   }
		  }
	}
$(function (){
   var now=0;
   var picTimer;
   var ulwidth = $(".gbanner").width(); 
   var lilen=$(".gbanner ul li").length;
   $(".gbanner ul").css("width",ulwidth * (lilen)); //UL宽度
	
	//小按钮
	$('.gbanner ol li').click(function (){
		now=$(this).index();
		$('.gbanner ol li').removeClass('active');
		$(this).addClass('active');
		$('.gbanner ul').animate({left: -ulwidth*$(this).index()});
	});
	
	//箭头移入显示隐藏
	$('.gbanner').hover(function(){	
	//alert('dd');	
		$('.btn').stop().animate({opacity: 1});
		},function(){
		$('.btn').stop().animate({opacity: 0});		
			});
			
	//普通切换		
	function tab(now)
	{
		$('.gbanner ol li').removeClass('active');
		$('.gbanner ol li').eq(now).addClass('active');
		var nowLeft = -now*ulwidth;
		$(".gbanner ul").stop(true,false).animate({"left":nowLeft},300); 
	}
	   			
	//右按钮	
	$('.btn_r').click(function(){
	    now += 1;
		if(now == lilen) {now = 0;}
		tab(now);
	 })		
	 
	 //左按钮
	 $('.btn_l').click(function(){
		now -= 1;
		if(now == -1) {now = lilen - 1;}
	    tab(now);	
	 })		
	 
	 //鼠标滑上焦点图时停止自动播放，滑出时开始自动播放
	$(".gbanner").hover(function() {
		clearInterval(picTimer);
	},function() {
		picTimer = setInterval(function() {
			tab(now);
			now++;
			if(now == lilen) {now = 0;}
		},4000);
	}).trigger("mouseout");
});
</script>
<script>
	window.GOOD_ID = <?php echo $this->_var['goods']['goods_id']; ?>

</script>
<script src="script/page/goodsdetail.js"></script>


</html>
