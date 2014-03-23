define(['ui/dialog'],function(Dialog){
	var Tip = function(text,cb){
		var me = this;
		var opt= {};
		var cb = cb||function(){};
		opt.body = '<p class="dia-st-tip">'+text+'</p><div class="single-btn-area"><input id="tip_confirm" class="btn status1-btn" type="submit" value="确定"></div>';
		opt.title = '提示';
		opt.bottom = ' ';
		opt.textStyle = 'padding:40px;';
		opt.onconfirm = function(){
			cb();
			me.close();
		};
		opt.afterRender = function(){
			$('#tip_confirm').click(function(){
				me.close();
			});
		}
		Dialog.call(this,opt);
		this.el.addClass('dia-sub-tip');
	}
	Tip.prototype = Dialog.prototype;
	Tip.prototype.constructor = Tip;
	return Tip;
})