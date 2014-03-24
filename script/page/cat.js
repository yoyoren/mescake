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
})();