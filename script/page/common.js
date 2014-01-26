(function(){
   window.MES = window.MES||{};
   MES.request = function(opt,method){
	   var mod = opt.mod||'';
	   var action = opt.action||'';
	   var callback = opt.callback;
	   var onsuccess = opt.onsuccess||function(){};
	   var onerror = opt.onerror||function(){};
	   var param = opt.param||{};
	   var method = method||'get';

	   param['_tc'] = Math.random();
	   $[method]('route.php?mod='+mod+'&action='+action,param,function(d){
		    if(d.code == 10005){
				require(["ui/login"], function(login) {login.show();});
			}else if(d.code == 10006){
				require(["ui/confirm"], function(confirm) {
					new confirm('服务器内部错误！可能是您提交了非法格式的数据！');
				});
			}
			
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
					var name = d.uname||'';
					success&&success(name.replace(/W/,'').replace('@fal.com',''));
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
   
   MES.updateTotalPriceDisplay = function(d){
			if(d.order_total == false){
				location.href = 'route.php?mod=order&action=empty';
				return;
			}
			d = d.order_total;
			$('.order_total').html('￥'+(parseFloat(d.goods_price,10)+parseFloat(d.pack_fee,10)));
			$('#final_total').html(d.amount_formated);
	}
	
    var binded;
	/*
    MES.checkLogin(function(uname){
		if(('#header_login').length){
			binded = true;
			$('#header_login').hide();
			$('#header_logout').show();
			$('#header_logout').before('<a href="route.php?mod=account&action=account">'+uname+', </a>');
		}
	},function(){ });
	*/
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
		   if(binded){
				return;
		   }
			$('#header_login').hide();
			$('#header_logout').show();
			$('#header_logout').before('<a href="route.php?mod=account&action=account">'+uname+', </a>');
	   },function(){ });
   
   });
})();