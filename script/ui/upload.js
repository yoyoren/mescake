define(['ui/dialog'],function(Dialog){

	var body = '<div class="dialog-con">\
				<div class="up-img-area" id="upload_image"></div>\
				<form action="route.php?action=upload&mod=huodong"  enctype="multipart/form-data" method="post" id="mes_form">\
					<input type="file" name="images"  class="up-input" id="mes_input"/>\
					<span id="upload_tip"></span>\
				 </form>\
				<div class="single-btn-area">\
				  <input class="btn" type="button" value="取消" id="upload_cancle_weibo">\
				  <input class="btn status1-btn" type="button" value="发布" id="upload_to_weibo">\
				</div>\
			  </div>'
	var single;
	var upload = {
		init:function(){
			if(single){
				single.show();
			}else{
				 single = new Dialog({
					    width:460,
						title:'',
						body:body,
						bottom:' ',
						afterRender:function(){
							$('#upload_cancle_weibo').click(function(){
								single.hide();
							});

							require(['widget/upload'],function(upload){
								new upload({
									inputId:'mes_input',
									formId:'mes_form',
									url:'route.php?action=upload&mod=huodong',
									upload:function(){
										$('#upload_tip').html('图片正在上传中...');
									},
									callback:function(d){
										$('#upload_tip').html('图片上传成功');
										var url = d.url;
										$('#upload_image').append('<img src="'+url+'" width="180"/>');
										$('#upload_to_weibo').click(function(){
											MES.post({
												mod:'huodong',
												action:'weibo_upload',
												param:{
													imageurl:url
												},
												callback:function(d){
													if(d.code == 0){
														require(['ui/confirm'],function(confirm){
															new confirm('发布成功，内容正在审核中！');
														});
													}else{
														require(['ui/confirm'],function(confirm){
															new confirm('服务器错误！');
														});
													}
													single.hide();
												}
											});
											
										});
									}
								});
							});
						}
					});
			}
		}
	}

	upload.init();
	return single;
})