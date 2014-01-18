(function(){
   window.MES = window.MES||{};
   MES.request = function(opt,method){
	   var mod = opt.mod||'';
	   var action = opt.action||'';
	   var callback = opt.callback;
	   var onsuccess = opt.onsuccess||function(){};
	   var onerror = opt.onerror||function(){};
	   var param = opt.param||{};
	   param['_tc'] = Math.random();
	   $[method||'get']('route.php?mod='+mod+'&action='+action,param,function(d){
		    if(callback){
				callback(d);
				return;
			}
			if(d.code == 0){
			   onsuccess(d);
			}else{
			   onerror(d);
			}
	   },'json');
   }
   
   MES.get=function(opt){
		return MES.request(opt,'get');
   }
   
   MES.post=function(opt){
		return MES.request(opt,'post');
   }
   
   MES.reload = function(url){
	    return  location.href=url+"&_tc="+Math.random();
   }

   MES.checkLogin = function(success,fail){
	   MES.get({
		mod:'account',
		action:'check_login',
		callback:function(d){
				if(!d.res){
					fail&&fail();
				}else{
					success&&success(d.uname);
				}
			}
	   });
   }
   
   
   MES.checkLogout = function(callback){
	   MES.get({
		mod:'account',
		action:'logout',
		callback:function(d){
				callback&&callback();
			}
	   });
   }

   //一部分header的逻辑，从后端放到前段控制
   $(window).ready(function(){
	   
	   //进入购物车给一个
	   $('#header_shopcar').click(function(){
		    MES.reload("route.php?mod=order&action=step1");
	   });


	   $('#header_login').click(function(){
			MES.checkLogin(function(){},function(){
				require(["ui/login"], function(login) {login.show();});
			});
	   });
	
	   $('#header_logout').click(function(){
			MES.checkLogout(function(){
				location.reload();
			});
	   });

	   MES.checkLogin(function(uname){
			$('#header_login').hide();
			$('#header_logout').show();
			$('#header_logout').before('<a href="route.php?mod=account&action=account">'+uname+', </a>');
	   },function(){ });
   
   });
})();