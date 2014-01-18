define(['ui/dialog'],function(Dialog){
	var Confirm = function(text,cb){
		var me = this;
		var opt= {};
		var cb = cb||function(){};
		opt.body = '<p class="dia-st-tip">'+text+'</p>';
		opt.title = '提示';
		opt.onconfirm = function(){
			cb();
			me.close();
		};
		Dialog.call(this,opt);
		this.el.addClass('dia-sub-tip');
	}
	Confirm.prototype = Dialog.prototype;
	Confirm.prototype.constructor = Confirm;
	return Confirm;
})