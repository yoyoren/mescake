(function(){
	if(window.showupload){
		require(['ui/upload'],function(upload){
			upload.show();
		});

		$('#do_upload').click(function(){
			require(['ui/upload'],function(upload){
				upload.show();
			});
			return false;
		});
	}
	function get_by_status(status){
		MES.get({
			mod:'huodong',
			action:'cat_get_by_status',
			param:{
				status:status
			},
			callback:function(d){
			 var data =d.data;
			 var html = '';
			 for(var i=0;i<data.length;i++){
				html+='<li>\
					<a href="route.php?mod=huodong&action=cat_detail&id='+data[i].id+'"><img src="'+data[i].img+'" width="180"></a>\
					<div class="act-func-area"><span class="weibo-name">@'+data[i].weibo_name+'</span><span class="act-love like" data-id="'+data[i].id+'">'+data[i].times+'</span></div>\
				  </li>';
			 }
			 $('#image_container').html(html);
				
			}

		});
	}
	get_by_status(1);

	$(document).delegate('.like','click',function(){
	  var id = $(this).data('id');
	  MES.post({
			mod:'huodong',
			action:'cat_like',
			param:{
				id:id
			},
			callback:function(d){
				alert('您已经成功了赞了这货！');
			}

		});
	});
})();