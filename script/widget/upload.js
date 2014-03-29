define(function(Dialog){
	var Upload = function(opt){
		this.opt = {
			formId:opt.formId,
			inputId:opt.inputId,
			iframeId:opt.iframeId||'mes_upload',
			url:opt.url,
			callback:opt.callback||function(){}
			upload:opt.upload||function(){}
		}

		this.jqForm = $('#'+this.opt.formId);
		this.jqInput = $('#'+this.opt.inputId);
		this.init();
		
	}
	
	Upload.prototype = {
		init:function(){
			var me = this;
			var iframeId = this.opt.iframeId;
			var jqForm = this.jqForm;
			jqForm.attr('target',iframeId);
			jqForm.attr('action',this.opt.url);
			jqForm.attr('enctype','multipart/form-data');
			jqForm.attr('method','post');
			jqForm.after('<iframe name="'+iframeId+'" id="'+iframeId+'" width="0" height="0"></iframe>');
			this.jqInput.change(function(){
				me.opt.upload();
				jqForm.submit();
			});
			setTimeout(function(){
				var iframe = $('#'+iframeId)[0];
				iframe.onload = iframe.onreadystatechange = function(){
					var ret = iframe.contentWindow.ret;
					me.opt.callback(ret);
				}
			},0);
		}
	}
	return Upload;
});