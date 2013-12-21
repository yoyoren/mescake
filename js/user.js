/* $Id : user.js 4865 2007-01-31 14:04:10Z paulgao $ */

/* *
 * 修改会员信息
 */
function userEdit()
{
  var frm = document.forms['formEdit'];
  var email = frm.elements['email'].value;
  var msg = '';
  var reg = null;
  var passwd_answer = frm.elements['passwd_answer'] ? Utils.trim(frm.elements['passwd_answer'].value) : '';
  var sel_question =  frm.elements['sel_question'] ? Utils.trim(frm.elements['sel_question'].value) : '';

  if (email.length == 0)
  {
    msg += email_empty + '\n';
  }
  else
  {
    if ( ! (Utils.isEmail(email)))
    {
      msg += email_error + '\n';
    }
  }

  if (passwd_answer.length > 0 && sel_question == 0 || document.getElementById('passwd_quesetion') && passwd_answer.length == 0)
  {
    msg += no_select_question + '\n';
  }

  for (i = 7; i < frm.elements.length - 2; i++)	// 从第七项开始循环检查是否为必填项
  {
	needinput = document.getElementById(frm.elements[i].name + 'i') ? document.getElementById(frm.elements[i].name + 'i') : '';

	if (needinput != '' && frm.elements[i].value.length == 0)
	{
	  msg += '- ' + needinput.innerHTML + msg_blank + '\n';
	}
  }

  if (msg.length > 0)
  {
    alert(msg);
    return false;
  }
  else
  {
    return true;
  }
}

/* 会员修改密码 */
function editPassword()
{
  var frm              = document.forms['formPassword'];
  var old_password     = frm.elements['old_password'].value;
  var new_password     = frm.elements['new_password'].value;
  var confirm_password = frm.elements['comfirm_password'].value;

  var msg = '';
  var reg = null;

  if (old_password.length == 0)
  {
    msg += old_password_empty + '\n';
  }

  if (new_password.length == 0)
  {
    msg += new_password_empty + '\n';
  }

  if (confirm_password.length == 0)
  {
    msg += confirm_password_empty + '\n';
  }

  if (new_password.length > 0 && confirm_password.length > 0)
  {
    if (new_password != confirm_password)
    {
      msg += both_password_error + '\n';
    }
  }

  if (msg.length > 0)
  {
    alert(msg);
    return false;
  }
  else
  {
    return true;
  }
}

/* *
 * 对会员的留言输入作处理
 */
function submitMsg()
{
  var frm         = document.forms['formMsg'];
  var msg_title   = frm.elements['msg_title'].value;
  var msg_content = frm.elements['msg_content'].value;
  var msg = '';

  if (msg_title.length == 0)
  {
    msg += msg_title_empty + '\n';
  }
  if (msg_content.length == 0)
  {
    msg += msg_content_empty + '\n'
  }

  if (msg_title.length > 200)
  {
    msg += msg_title_limit + '\n';
  }

  if (msg.length > 0)
  {
    alert(msg);
    return false;
  }
  else
  {
    return true;
  }
}

/* *
 * 会员找回密码时，对输入作处理
 */
function submitPwdInfo()
{
  var frm = document.forms['getPassword'];
  var user_name = frm.elements['user_name'].value;
  var email     = frm.elements['email'].value;

  var errorMsg = '';
  if (user_name.length == 0)
  {
    errorMsg += user_name_empty + '\n';
  }

  if (email.length == 0)
  {
    errorMsg += email_address_empty + '\n';
  }
  else
  {
    if ( ! (Utils.isEmail(email)))
    {
      errorMsg += email_address_error + '\n';
    }
  }

  if (errorMsg.length > 0)
  {
    alert(errorMsg);
    return false;
  }

  return true;
}

/* *
 * 会员找回密码时，对输入作处理
 */
function submitPwd()
{
  var frm = document.forms['getPassword2'];
  var password = frm.elements['new_password'].value;
  var confirm_password = frm.elements['confirm_password'].value;

  var errorMsg = '';
  if (password.length == 0)
  {
    errorMsg += new_password_empty + '\n';
  }

  if (confirm_password.length == 0)
  {
    errorMsg += confirm_password_empty + '\n';
  }

  if (confirm_password != password)
  {
    errorMsg += both_password_error + '\n';
  }

  if (errorMsg.length > 0)
  {
    alert(errorMsg);
    return false;
  }
  else
  {
    return true;
  }
}

/* *
 * 处理会员提交的缺货登记
 */
function addBooking()
{
  var frm  = document.forms['formBooking'];
  var goods_id = frm.elements['id'].value;
  var rec_id  = frm.elements['rec_id'].value;
  var number  = frm.elements['number'].value;
  var desc  = frm.elements['desc'].value;
  var linkman  = frm.elements['linkman'].value;
  var email  = frm.elements['email'].value;
  var tel  = frm.elements['tel'].value;
  var msg = "";

  if (number.length == 0)
  {
    msg += booking_amount_empty + '\n';
  }
  else
  {
    var reg = /^[0-9]+/;
    if ( ! reg.test(number))
    {
      msg += booking_amount_error + '\n';
    }
  }

  if (desc.length == 0)
  {
    msg += describe_empty + '\n';
  }

  if (linkman.length == 0)
  {
    msg += contact_username_empty + '\n';
  }

  if (email.length == 0)
  {
    msg += email_empty + '\n';
  }
  else
  {
    if ( ! (Utils.isEmail(email)))
    {
      msg += email_error + '\n';
    }
  }

  if (tel.length == 0)
  {
    msg += contact_phone_empty + '\n';
  }

  if (msg.length > 0)
  {
    alert(msg);
    return false;
  }

  return true;
}

/* *
 * 会员登录
 */
function userLogin()
{
  var frm      = document.forms['formLogin'];
  var username = frm.elements['username'].value;
  var password = frm.elements['password'].value;
  var msg = '';

  if (username.length == 0)
  {
    msg += username_empty + '\n';
  }

  if (password.length == 0)
  {
    msg += password_empty + '\n';
  }

  if (msg.length > 0)
  {
    alert(msg);
    return false;
  }
  else
  {
    return true;
  }
}

function chkstr(str)
{
  for (var i = 0; i < str.length; i++)
  {
    if (str.charCodeAt(i) < 127 && !str.substr(i,1).match(/^\w+$/ig))
    {
      return false;
    }
  }
  return true;
}

function check_password( password )
{
	document.getElementById("password").style.border='1px solid grey';
    if ( password.length < 6 )
    {
        document.getElementById('password_notice').innerHTML = password_shorter;
		document.getElementById('password_notice1').innerHTML ='';
    }
    else
    {
        document.getElementById('password_notice').innerHTML = '';
		document.getElementById('password_notice1').innerHTML ="<font color='green'>√</font>";
    }
}
function check_capt(capt)
{
    if ( capt == '' )
    {
        document.getElementById('captmes').innerHTML ='验证码不能为空';
    }
    else
    {
        document.getElementById('captmes').innerHTML ='' ;
    }
}
function get_capt(){//0为注册
    if(document.getElementById('mobile_phone')) var mobile = document.getElementById('mobile_phone').value;
	if(mobile.length==11&&!isNaN(mobile))
	{
		Ajax.call( 'user.php?act=got_verify', 'mobile=' + mobile, get_capt_callback , 'GET', 'TEXT', true, true );
		//document.getElementById("getCaptcha").disabled=true;
	}else{
		document.getElementById("mobile_notice").innerHTML="请核对手机号";
	}
}
function get_capt_callback(result){
	if(result!=0){
		
		//document.getElementById("checkword").value=result;
		curCount = count;
		InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
		document.getElementById("send_status").innerHTML="验证码已发送，请注意查收，"+"<font color='red'>"+curCount +"</font>"+ "秒后可重点击新发送";
	}else{
		document.getElementById("send_status").innerHTML="短信发送失败，重新发送";
		document.getElementById("getCaptcha").disabled=false;
	}
}
/*倒计时*/
function SetRemainTime() {
    if (curCount == 0) {   
        window.clearInterval(InterValObj);//停止计时器
        document.getElementById("getCaptcha").disabled='';//启用按钮
        document.getElementById("send_status").innerHTML="&nbsp;";
    }
    else {
        curCount--;
		document.getElementById("getCaptcha").disabled=true;//启用按钮
		document.getElementById("send_status").innerHTML="验证码已发送，请注意查收，"+"<font color='red'>"+curCount+"</font>"+ "秒后可重新点击发送";
    }
}
function check_conform_password( conform_password )
{
	document.getElementById("conform_password").style.border='1px solid grey';
    password = document.getElementById('password').value;
    
    if ( conform_password.length < 6 )
    {
        document.getElementById('conform_password_notice').innerHTML = '确认密码不能少于六个字符';
        return false;
    }
    if ( conform_password != password )
    {
        document.getElementById('conform_password_notice').innerHTML = confirm_password_invalid;
    }
    else
    {
        document.getElementById('conform_password_notice').innerHTML = '';
		document.getElementById('conform_password_notice1').innerHTML = "<font color='green'>√</font>";
    }
}

function is_registered( username )
{
    var submit_disabled = false;
	var unlen = username.replace(/[^\x00-\xff]/g, "**").length;

    if ( username == '' )
    {
        document.getElementById('username_notice').innerHTML = msg_un_blank;
        var submit_disabled = true;
    }

    if ( !chkstr( username ) )
    {
        document.getElementById('username_notice').innerHTML = msg_un_format;
        var submit_disabled = true;
    }
    if ( unlen < 3 )
    { 
        document.getElementById('username_notice').innerHTML = username_shorter;
        var submit_disabled = true;
    }
    if ( unlen > 14 )
    {
        document.getElementById('username_notice').innerHTML = msg_un_length;
        var submit_disabled = true;
    }
    if ( submit_disabled )
    {
        document.forms['formUser'].elements['Submit'].disabled = 'disabled';
        return false;
    }
    Ajax.call( 'user.php?act=is_registered', 'username=' + username, registed_callback , 'GET', 'TEXT', true, true );
}



function registed_callback(result)
{
  if ( result == "true" )
  {
    document.getElementById('username_notice1').innerHTML = "<font color='green'>√</font>";
    document.forms['formUser'].elements['Submit'].disabled = '';
  }
  else
  {
    document.getElementById('username_notice').innerHTML ='邮箱已注册';
    document.forms['formUser'].elements['Submit'].disabled = 'disabled';
  }
}
function check_mobile(mobile)
{document.getElementById("mobile_phone").style.border='1px solid grey';
	var submit_disabled = false;
	if ( mobile.length !=11 || isNaN(mobile) ){
        document.getElementById('mobile_notice').innerHTML = "请输入正确手机号码";
		document.getElementById('mobile_notice1').innerHTML =''
		 submit_disabled = true;
    }
	if (mobile == ''){
		document.getElementById('mobile_notice').innerHTML = " 请输入手机号";
		document.getElementById('mobile_notice1').innerHTML =''
		 submit_disabled = true;
	}
	if( submit_disabled )
	{		
		document.forms['formUser'].elements['Submit'].disabled = 'disabled';	
		return false;
	}
	Ajax.call( 'user.php?act=check_mobile', 'mobile=' + mobile, check_mobile_callback , 'GET', 'TEXT', true, true );//注册		
}

function check_mobile_callback(result)
{
  if ( result == "ok" )
  {
    document.getElementById('mobile_notice1').innerHTML = "<font color='green'>√</font>";
	document.getElementById('mobile_notice').innerHTML = ''
	document.forms['formUser'].elements['Submit'].disabled = '';
  }
  else
  {
    document.getElementById('mobile_notice').innerHTML = " 此手机号已经被注册，请重新输入";
	document.getElementById('mobile_notice1').innerHTML = '';
	document.forms['formUser'].elements['Submit'].disabled = 'disabled';
	
  }
}
function checkEmail(email)
{
  var submit_disabled = false;
 
  if (email == '')
  {
    document.getElementById('email_notice').innerHTML = '邮箱不能为空';
    submit_disabled = true;
  }
  else if (!Utils.isEmail(email))
  {
    document.getElementById('email_notice').innerHTML = '邮箱格式不正确';
    submit_disabled = true;
  }
 
  if( submit_disabled )
  {
    document.forms['formUser'].elements['Submit'].disabled = 'disabled';
    return false;
  }
  Ajax.call( 'user.php?act=check_email', 'email=' + email, check_email_callback , 'GET', 'TEXT', true, true );
}

function check_email_callback(result)
{
  if ( result == 'ok' )
  {
    document.getElementById('email_notice1').innerHTML = '√';
	document.getElementById('email_notice').innerHTML = '';
    document.forms['formUser'].elements['Submit'].disabled = '';
  }
  else
  {
    document.getElementById('email_notice').innerHTML = '邮箱已被注册';
	document.getElementById('email_notice1').innerHTML = '';
    document.forms['formUser'].elements['Submit'].disabled = 'disabled';
  }
}

/* *
 * 处理注册用户
 */
function register()
{
  var frm  = document.forms['formUser'];
  //var username  = Utils.trim(frm.elements['username'].value);
  var email  = frm.elements['email'].value;
  var password  = Utils.trim(frm.elements['password'].value);
  var confirm_password = Utils.trim(frm.elements['confirm_password'].value);
  // var captcha = document.getElementById("capt").value;
  var mobile_phone = frm.elements['extend_field5'] ? Utils.trim(frm.elements['extend_field5'].value) : '';
  var m_phone=document.getElementById("mobile_phone").value;


  var msg = "";
  // 检查输入
  var msg = '';
  if (email.length == 0)
  {
   document.getElementById('email_notice').innerHTML ='邮箱不能为空';
  }
  else
  {
    if ( ! (Utils.isEmail(email)))
    {
      document.getElementById('email_notice').innerHTML ='邮箱格式不正确';
    }
  }
  if(m_phone.length==0)
  {
	document.getElementById('mobile_notice').innerHTML = " 请输入手机号";return false;
  }
  if (password.length == 0)
  {
    document.getElementById('password_notice').innerHTML = '密码不能为空'; return false;
  }
  else if (password.length < 6)
  {
    document.getElementById('password_notice').innerHTML = '密码太短';
  }
  if (/ /.test(password) == true)
  {
	document.getElementById('password_notice').innerHTML = '密码含有空格';
  }
  if (confirm_password != password )
  {
    msg += '两次输入密码不一致' + '\n';
  }

  if (mobile_phone.length>0)
  {
    var reg = /^[\d|\-|\s]+$/;
    if (!reg.test(mobile_phone))
    {
      msg +=  '手机号无效' + '\n';
    }
  }
  for (i = 4; i < frm.elements.length - 4; i++)	// 从第五项开始循环检查是否为必填项
  {
	needinput = document.getElementById(frm.elements[i].name + 'i') ? document.getElementById(frm.elements[i].name + 'i') : '';

	if (needinput != '' && frm.elements[i].value.length == 0)
	{
	  msg += '- ' + needinput.innerHTML + msg_blank + '\n';
	}
  }
   /* if(captcha==""){
		document.getElementById("captmes").innerHTML="请输入验证码";
		return false;
    }*/
  if (msg.length > 0)
  {
    //alert(msg);
    return false;
  }
  else
  {document.getElementById('formUser').submit();return true;
	//Ajax.call( 'user.php?act=check_captcha','captcha='+captcha,check_capt_callback,'POST', 'TEXT' );
    //return false;
  }
}
function check_capt_callback(result){
	if(result=="1"){		
		document.getElementById('formUser').submit();		
		return true;		
	}else{
		document.getElementById("captmes").innerHTML="验证码错误";
		return false;
	}
}
/* *
 * 用户中心订单保存地址信息
 */
function saveOrderAddress(id)
{
  var frm           = document.forms['formAddress'];
  var consignee     = frm.elements['consignee'].value;
  var email         = frm.elements['email'].value;
  var address       = frm.elements['address'].value;
  var zipcode       = frm.elements['zipcode'].value;
  var tel           = frm.elements['tel'].value;
  var mobile        = frm.elements['mobile'].value;
  var sign_building = frm.elements['sign_building'].value;
  var best_time     = frm.elements['best_time'].value;

  if (id == 0)
  {
    alert(current_ss_not_unshipped);
    return false;
  }
  var msg = '';
  if (address.length == 0)
  {
    msg += address_name_not_null + "\n";
  }
  if (consignee.length == 0)
  {
    msg += consignee_not_null + "\n";
  }

  if (msg.length > 0)
  {
    alert(msg);
    return false;
  }
  else
  {
    return true;
  }
}

/* *
 * 会员余额申请
 */
function submitSurplus()
{
  var frm            = document.forms['formSurplus'];
  var surplus_type   = frm.elements['surplus_type'].value;
  var surplus_amount = frm.elements['amount'].value;
  var process_notic  = frm.elements['user_note'].value;
  var payment_id     = 0;
  var msg = '';

  if (surplus_amount.length == 0 )
  {
    msg += surplus_amount_empty + "\n";
  }
  else
  {
    var reg = /^[\.0-9]+/;
    if ( ! reg.test(surplus_amount))
    {
      msg += surplus_amount_error + '\n';
    }
  }

  if (process_notic.length == 0)
  {
    msg += process_desc + "\n";
  }

  if (msg.length > 0)
  {
    alert(msg);
    return false;
  }

  if (surplus_type == 0)
  {
    for (i = 0; i < frm.elements.length ; i ++)
    {
      if (frm.elements[i].name=="payment_id" && frm.elements[i].checked)
      {
        payment_id = frm.elements[i].value;
        break;
      }
    }

    if (payment_id == 0)
    {
      alert(payment_empty);
      return false;
    }
  }

  return true;
}

/* *
 *  处理用户添加一个红包
 */
function addBonus()
{
  var frm      = document.forms['addBouns'];
  var bonus_sn = frm.elements['bonus_sn'].value;

  if (bonus_sn.length == 0)
  {
    alert(bonus_sn_empty);
    return false;
  }
  else
  {
    var reg = /^[0-9]{10}$/;
    if ( ! reg.test(bonus_sn))
    {
      alert(bonus_sn_error);
      return false;
    }
  }

  return true;
}

/* *
 *  合并订单检查
 */
function mergeOrder()
{
  if (!confirm(confirm_merge))
  {
    return false;
  }

  var frm        = document.forms['formOrder'];
  var from_order = frm.elements['from_order'].value;
  var to_order   = frm.elements['to_order'].value;
  var msg = '';

  if (from_order == 0)
  {
    msg += from_order_empty + '\n';
  }
  if (to_order == 0)
  {
    msg += to_order_empty + '\n';
  }
  else if (to_order == from_order)
  {
    msg += order_same + '\n';
  }
  if (msg.length > 0)
  {
    alert(msg);
    return false;
  }
  else
  {
    return true;
  }
}

/* *
 * 订单中的商品返回购物车
 * @param       int     orderId     订单号
 */
function returnToCart(orderId)
{
  Ajax.call('user.php?act=return_to_cart', 'order_id=' + orderId, returnToCartResponse, 'POST', 'JSON');
}

function returnToCartResponse(result)
{
  alert(result.message);
}

/* *
 * 检测密码强度
 * @param       string     pwd     密码
 */
function checkIntensity(pwd)
{
  var Mcolor = "#FFF",Lcolor = "#FFF",Hcolor = "#FFF";
  var m=0;

  var Modes = 0;
  for (i=0; i<pwd.length; i++)
  {
    var charType = 0;
    var t = pwd.charCodeAt(i);
    if (t>=48 && t <=57)
    {
      charType = 1;
    }
    else if (t>=65 && t <=90)
    {
      charType = 2;
    }
    else if (t>=97 && t <=122)
      charType = 4;
    else
      charType = 4;
    Modes |= charType;
  }

  for (i=0;i<4;i++)
  {
    if (Modes & 1) m++;
      Modes>>>=1;
  }

  if (pwd.length<=4)
  {
    m = 1;
  }

  switch(m)
  {
    case 1 :
      Lcolor = "2px solid red";
      Mcolor = Hcolor = "2px solid #DADADA";
    break;
    case 2 :
      Mcolor = "2px solid #f90";
      Lcolor = Hcolor = "2px solid #DADADA";
    break;
    case 3 :
      Hcolor = "2px solid #3c0";
      Lcolor = Mcolor = "2px solid #DADADA";
    break;
    case 4 :
      Hcolor = "2px solid #3c0";
      Lcolor = Mcolor = "2px solid #DADADA";
    break;
    default :
      Hcolor = Mcolor = Lcolor = "";
    break;
  }
  if (document.getElementById("pwd_lower"))
  {
    document.getElementById("pwd_lower").style.borderBottom  = Lcolor;
    document.getElementById("pwd_middle").style.borderBottom = Mcolor;
    document.getElementById("pwd_high").style.borderBottom   = Hcolor;
  }


}

function changeType(obj)
{
  if (obj.getAttribute("min") && document.getElementById("ECS_AMOUNT"))
  {
    document.getElementById("ECS_AMOUNT").disabled = false;
    document.getElementById("ECS_AMOUNT").value = obj.getAttribute("min");
    if (document.getElementById("ECS_NOTICE") && obj.getAttribute("to") && obj.getAttribute('fee'))
    {
      var fee = parseInt(obj.getAttribute("fee"));
      var to = parseInt(obj.getAttribute("to"));
      if (fee < 0)
      {
        to = to + fee * 2;
      }
      document.getElementById("ECS_NOTICE").innerHTML = notice_result + to;
    }
  }
}

function calResult()
{
  var amount = document.getElementById("ECS_AMOUNT").value;
  var notice = document.getElementById("ECS_NOTICE");

  reg = /^\d+$/;
  if (!reg.test(amount))
  {
    notice.innerHTML = notice_not_int;
    return;
  }
  amount = parseInt(amount);
  var frm = document.forms['transform'];
  for(i=0; i < frm.elements['type'].length; i++)
  {
    if (frm.elements['type'][i].checked)
    {
      var min = parseInt(frm.elements['type'][i].getAttribute("min"));
      var to = parseInt(frm.elements['type'][i].getAttribute("to"));
      var fee = parseInt(frm.elements['type'][i].getAttribute("fee"));
      var result = 0;
      if (amount < min)
      {
        notice.innerHTML = notice_overflow + min;
        return;
      }

      if (fee > 0)
      {
        result = (amount - fee) * to / (min -fee);
      }
      else
      {
        //result = (amount + fee* min /(to+fee)) * (to + fee) / min ;
        result = amount * (to + fee) / min + fee;
      }

      notice.innerHTML = notice_result + parseInt(result + 0.5);
    }
  }
}
//关闭注册页
function closeregister2()
{
	window.location="index.php";
}
//密码强度检测
function mimaqiangdu()
{
	 var pattern1 = /^\d|\w{6,22}$/;
	 var pattern2=/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,22}$/;
	 var pattern3=/\@|\#|\$|\%|\^|\&|\*|\!|\_{1,22}/;
	 
     var pwd = document.formUser.password.value;
		if(pwd.length>=6)
		{	
			if(pattern1.test(pwd))
			{
				document.getElementById("password_notice").innerHTML ="密码强度：<font color='red'>弱</font>";
			}
			if(pattern2.test(pwd))
			{
				document.getElementById("password_notice").innerHTML ="密码强度：<font color='red'>中</font>";
			}
			if(pattern3.test(pwd) || pwd.length>=10 )
			{
				document.getElementById("password_notice").innerHTML ="密码强度：<font color='red'>强</font>";
			}
		}
		else
		{
				document.getElementById("password_notice").innerHTML ="密码太短";
		}
}
//获得焦点文本框颜色变化
function kuangcolor(n)
{
	if(n==1)
	{
		document.getElementById("mobile_phone").style.border='1px solid green';
	}
	if(n==3)
	{
		document.getElementById("password").style.border='1px solid green';
	}
	if(n==4)
	{
		document.getElementById("conform_password").style.border='1px solid green';
	}
	if(n==5)
	{
		document.getElementById("rea_name").style.border='1px solid green';
	}
}
function check_rea_name()
{
	document.getElementById("rea_name").style.border='1px solid grey';
}
