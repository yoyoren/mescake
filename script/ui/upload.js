define(['ui/dialog'],function(Dialog){

	var body = '<div class="dialog-con">\
				<div class="up-img-area" id="upload_image"></div>\
				<form action="route.php?action=upload&mod=huodong"  enctype="multipart/form-data" method="post" id="mes_form">\
					<input type="file" name="images" id="mes_input"/>\
				 </form>\
				<div class="single-btn-area">\
				  <input class="btn" type="button" value="取消">\
				  <input class="btn status1-btn" type="button" value="上传" id="upload_to_weibo">\
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
						onshow:function(d){

						},
						onconfirm:function(){
							
						},
						afterRender:function(){
							require(['widget/upload'],function(upload){
								new upload({
									inputId:'mes_input',
									formId:'mes_form',
									url:'route.php?action=upload&mod=huodong',
									callback:function(d){
										$('#upload_image').append('<img src="'+d.url+'" width="100"/>');
										$('#upload_to_weibo').click(function(){
											MES.post({
												mod:'huodong',
												action:'weibo_upload',
												param:{
													imageurl:'https://test.mescake.com/'+d.url
												},
												callback:function(){
												
												}
											});
											single.close();
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