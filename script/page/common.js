(function(){
   window.MES = window.MES||{};
   
   MES.checkLogin = function(success,fail){
	$.get('route.php',{
		mod:'account',
		action:'check_login'
	},function(d){
		if(!d.res){
			fail&&fail();
		}else{
			success&&success(d.uname);
		}
	},'json');
   }
   
   
   MES.checkLogout = function(callback){
	$.get('route.php',{
		mod:'account',
		action:'logout'
	},function(d){
		callback&&callback();
	},'json');
   }

   //一部分header的逻辑，从后端放到前段控制
   $(window).ready(function(){
	   $('#header_shopcar').click(function(){
		 location.href="route.php?mod=order&action=step1";
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
	   },function(){
	   });
   
   });
})();