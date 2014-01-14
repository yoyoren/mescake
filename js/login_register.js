  function checklogin()
	{
	var username = document.getElementById('username').value;
	var password = document.formLogin.password.value;
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
		$('#notice12').html(msg);
		
	  }
	  else
	  {
		Ajax.call( 'user.php?act=signin', 'username=' + username +'&password='+password, login_callback , 'POST', 'JSON', true, true );
	  }
	}
	function login_callback($result)
	{
		if($result.error)
		{
			$('#notice12').html('用户名密码错误');
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
				window.location.reload();
				//window.location="index.php";
				//$('#dengludiv').html($result.content);
				
				//$('#vip,#logindiv,#fullbg').hide();
			}
		}
	}
	  function checklogin2()
	{
	var username = document.getElementById('username').value;
	var password = document.formLogin.password.value;
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
		$('#notice12').html(msg);
		
	  }
	  else
	  {
		Ajax.call( 'user.php?act=signin', 'username=' + username +'&password='+password, login_callback2 , 'POST', 'JSON', true, true );
	  }
	}
	function login_callback2($result)
	{
		if($result.error)
		{
			$('#notice12').html('用户名密码错误');
		}else{
			//window.location.reload();
			window.location="index.php";
			//$('#dengludiv').html($result.content);
			
			//$('#vip,#logindiv,#fullbg').hide();
			
		}
	}
function showBg(){ 
	var obj = document.getElementById('logindiv');
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
	$('#fullbg').height(yScroll+H);
	obj.style.left= (W/2-350) + "px";
	obj.style.top= (H/2 -90 - 225　+　yScroll) + "px";
	document.getElementById("fullbg").style.display="block";
	obj.style.display="block"; 
	$("html").css("overflow-y","hidden");
	$("html").css("overflow-x","hidden");
}
function closeBg(){
	$("#logindiv,#fullbg").hide();
	$("html").css("overflow-y","");
	$("html").css("overflow-x","");
}
//长时间无动作后出现的登陆页面点关闭小叉子跳到首页
function closeBg1()
{
	window.location='./index.php';
}
function scrolls(){
//取浏览器类型 
var temp_h1 = document.body.clientHeight; 
var temp_h2 = document.documentElement.clientHeight; 
var isXhtml = (temp_h2<=temp_h1&&temp_h2!=0)?true:false; 
var htmlbody = isXhtml?document.documentElement:document.body;
return htmlbody; 
}
//忘记密码提交验证
function forgetpd()
{
	if(document.getElementById('cell_phone').value=="")
	{
		alert("请输入手机号");	
		return false;
	}
	if(document.getElementById('checkCode').value=="")
	{
		alert("请输入验证码");
		return false;
	}
	if(document.getElementById('checkCode').value==document.getElementById('checkword').value)
	{
		alert("您的新密码已发送到手机，请注意查收");
	}
	else
	{
		alert("验证码输入错误！");
		document.getElementById('checkCode').value="";
		return false;
	}
}
//忘记密码
function showForget(){ 
	document.getElementById("fullbg").style.display="none";
	document.getElementById('logindiv').style.display="none";
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
	document.getElementById("forgetbg").style.width=W+"px";
	document.getElementById("forgetbg").style.left=0+"px";
	document.getElementById("forgetbg").style.top=0+"px";
	document.getElementById("forgetbg").style.height=(H+yScroll)+"px";
	document.getElementById("forgetbg").style.display="block";
	document.getElementById("showForget1").style.display="block";
	document.getElementById("showForget1").style.left= (W/2-350)+"px";
	document.getElementById("showForget1").style.top= (137+yScroll)+ "px";

}

//长时间不动作登陆页的忘记密码
function showForget1(){ 
	document.getElementById("logindiv1").style.display="none";
	document.getElementById('showForget2').style.display="block";
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
	/*document.getElementById("forgetbg1").style.width=W+"px";
	document.getElementById("forgetbg1").style.left=0+"px";
	document.getElementById("forgetbg1").style.top=0+"px";
	document.getElementById("forgetbg1").style.height=(H+yScroll)+"px";
	document.getElementById("forgetbg1").style.display="block";*/
	document.getElementById("showForget2").style.display="block";
	document.getElementById("showForget2").style.left= (W/2-200)+"px";
	document.getElementById("showForget2").style.top= (150+yScroll)+ "px";

}


//关闭忘记密码框
function closeForget()
{
	document.getElementById("forgetbg").style.display="none";
	document.getElementById("showForget1").style.display="none";
	$("html").css("overflow-y","");
	$("html").css("overflow-x","");
}
//关闭长时间无动作后的忘记密码框
function closeForget1()
{
	document.getElementById("logindiv1").style.display="block";
	document.getElementById("showForget2").style.display="none";
}
//90秒后重发验证码

function getCode2(){
	
	var get_verify		= document.getElementById("yzimg");
	var mobile          = document.getElementById("cell_phone").value;
	
	if(isNaN(mobile) || mobile.length!=11){
		alert("手机号格式不正确");
	}else{
		Ajax.call( 'user.php?act=got_verify', 'mobile=' + mobile, get_verify_callback , 'GET', 'TEXT', true, true );
	}
	
}
var InterValObj; //timer变量，控制时间
var count = 60; //间隔函数，1秒执行
var curCount;//当前剩余秒数
function get_verify_callback(result){
	if(result!=0){
		
		document.getElementById("checkword").value=result;
		curCount = count;
		InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
		document.getElementById("tdd").innerHTML="验证码已发送，请注意查收，"+"<font color='red'>"+curCount +"</font>"+ "秒后可重点击新发送";
	}else{
		document.getElementById("tdd").innerHTML="短信发送失败，请点击重新发送";
		document.getElementById("yzimg").disabled=true;
	}
}
/*倒计时*/
function SetRemainTime() {
    if (curCount == 0) {   
        window.clearInterval(InterValObj);//停止计时器
        document.getElementById("yzimg").disabled='';//启用按钮
        document.getElementById("tdd").innerHTML="&nbsp;";
    }
    else {
        curCount--;
		document.getElementById("yzimg").disabled=true;//启用按钮
		document.getElementById("tdd").innerHTML="验证码已发送，请注意查收，"+"<font color='red'>"+curCount +"</font>"+ "秒后可重点击新发送";
    }
}
//登录界面回车登录
function checkenter()
{
	
  if (event.keyCode == 13) { 
	document.getElementById("loginbt").click();
  }
}
	//找回密码回车登录
function checkenter2()
{
	
  if (event.keyCode == 13) { 
	document.getElementById("bt2").click();
  }
}

//90秒后重发验证码

function getCode3(){
	
	var get_verify		= document.getElementById("getCaptcha");
	var mobile          = document.getElementById("mobile_phone").value;
	
	if(isNaN(mobile) || mobile.length!=11){
		alert("手机号格式不正确");
		
	}else{
	    Ajax.call( 'user.php?act=check_mobile', 'mobile=' + mobile, check_mobile_callbacke , 'GET', 'TEXT', true, true );
		
	}
	
}
function check_mobile_callbacke(result)
{
	var get_verify		= document.getElementById("getCaptcha");
	var mobile          = document.getElementById("mobile_phone").value;
  if ( result == "ok" )
  {
		Ajax.call( 'user.php?act=got_verify', 'mobile=' + mobile, get_verify_callback2 , 'GET', 'TEXT', true, true );
	
  }
 
}
var InterValObj; //timer变量，控制时间
var count = 60; //间隔函数，1秒执行
var curCount;//当前剩余秒数
function get_verify_callback2(result){
	if(result!=0){
		
		document.getElementById("checkword").value=result;
		curCount = count;
		InterValObj = window.setInterval(SetRemainTime2, 1000); //启动计时器，1秒执行一次
		document.getElementById("captmes").innerHTML="验证码已发送，请注意查收，"+"<font color='red'>"+curCount +"</font>"+ "秒后可重点击新发送";
	}else{
		document.getElementById("captmes").innerHTML="短信发送失败，请点击重新发送";
		document.getElementById("getCaptcha").disabled=false;
	}
}
/*倒计时*/
function SetRemainTime2() {
    if (curCount == 0) {   
        window.clearInterval(InterValObj);//停止计时器
        document.getElementById("getCaptcha").disabled='';//启用按钮
        document.getElementById("captmes").innerHTML="&nbsp;";
    }
    else {
        curCount--;
		document.getElementById("getCaptcha").disabled=true;//启用按钮
		document.getElementById("captmes").innerHTML="验证码已发送，请注意查收，"+"<font color='red'>"+curCount +"</font>"+ "秒后可重点击新发送";
    }
}

//登录点击文本框变色
function kcolor(m)
{
		if(m==1)
		{document.getElementById("username").style.border='1px solid green';}
		if(m==2)
		{document.getElementById("password").style.border='1px solid green';}
		if(m==3)
		{document.getElementById("uname").style.border='1px solid green';}
		if(m==4)
		{document.getElementById("pwd").style.border='1px solid green';}
		if(m==5)
		{document.getElementById("cell_phone").style.border='1px solid green';}
		if(m==6)
		{document.getElementById("checkCode").style.border='1px solid green';}
}
function kcolor2(k)
{
		if(k==1)
		{document.getElementById("username").style.border='1px solid grey';}
		if(k==2)
		{document.getElementById("password").style.border='1px solid grey';}
		if(k==3)
		{document.getElementById("uname").style.border='1px solid grey';}
		if(k==4)
		{document.getElementById("pwd").style.border='1px solid grey';}
		if(k==5)
		{document.getElementById("cell_phone").style.border='1px solid grey';}
		if(k==6)
		{document.getElementById("checkCode").style.border='1px solid grey';}
}