(function(){
   window.MES = window.MES||{};
   window.CANDLE = 61;
   window.NUM_CANDLE = 67;
   window.CAT_CAKE = 68;
   window.FORK = 60;
   window.IS_MOBILE = /^1[3|4|5|8|9]\d{9}$/;
   MES.IS_MOBILE = function(num){
	return IS_MOBILE.test(num);
   }
   MES.request = function(opt,method){
	   var mod = opt.mod||'';
	   var action = opt.action||'';
	   var callback = opt.callback;
	   var onsuccess = opt.onsuccess||function(){};
	   var onerror = opt.onerror;
	   var param = opt.param||{};
	   var method = method||'get';
	   var host = location.host;
	   param['_tc'] = Math.random();
	   $[method]('https://'+host+'/route.php?mod='+mod+'&action='+action,param,function(d){
		    //如果有自己的错误处理 就用自己的
			if(!onerror){
				if(d.code == 10005){
					require(["ui/login"], function(login) {login.show();});
				}else if(d.code == 10006){
					require(["ui/confirm"], function(confirm) {
						new confirm('服务器内部错误！可能是您提交了非法格式的数据！');
					});
				}
			}
			if(callback){
				callback(d);
				return;
			}
			if(d.code == 0){
			   onsuccess(d);
			}else{
			   onerror&&onerror(d);
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

   //检测用户动作是否要登录
   MES.actionCheckLogin = function(success){
	MES.get({
		mod:'account',
		action:'check_login',
		callback:function(d){
				if(!d.res){
					require(["ui/login"], function(login) {login.show();});
				}else{
					success&&success();
				}
			}
	   });
  }
   
   MES.getGoodsCount = function(callback){
	   MES.get({
		mod:'account',
		action:'get_order_count_by_sid',
		callback:function(d){
				if(callback){
					callback(d);
				}else{
					var count = d.count;
					if(count>0){
						$('#goods_count').html(count).show();
					}
				}
			}
	   });
   }

   MES.checkLogout= function(callback){
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
	MES.inputError = function(id){
		var el = $('#'+id);
		el.show();
		setTimeout(function(){
			el.hide();
		},2000);
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
	    MES.getGoodsCount();
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
				//location.href = "index.php";
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