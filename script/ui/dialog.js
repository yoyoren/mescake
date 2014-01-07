define(function(){
	var zIndex = 1000;
	var index = 0;
	var _html = '<div id="dialog_<%=index%>" class="dialog" style="margin:0;display:none">\
      <div class="dialog-head">\
        <%if(title){%><p class="dia-title"><%=title%></p><% } %>\
        <em class="close-ico" id="close_<%=index%>">X</em>\
      </div>\
      <div class="dialog-con">\
        <form action="">\
		   <p><%=body%></p>\
			<input id="dialog_confirm_<%=index%>" class="btn green-btn" type="submit" value="确定">\
			<input id="dialog_cancel_<%=index%>" class="btn" type="reset" value="取消">\
		</form>\
      </div>\
    </div>';

    var _dialog = function(opt){
		opt = opt||{};
		this.opt = opt;
		var _index = index++;
		var html = mstmpl(_html,{
			title:opt.title,
			body:opt.body||'',
			index:_index
		});
		$('body').append(html);
		this.el = $('#dialog_'+_index);
		this.cancelButton = $('#dialog_cancel_'+_index);
		this.confirmButton = $('#dialog_confirm_'+_index);
		this.closeButton = $('#close_'+_index);
		this.bind().show();
	};

	_dialog.prototype = {
		bind:function(){
			var _self = this;
			this.cancelButton.click(function(){
				_self.hide();
			});

			this.closeButton.click(function(){
				_self.hide();
			});
			
			this.confirmButton.click(function(){
				_self.opt.onconfirm();
				return false;
			});
	
			$(window).resize(function(){
				_self._reposition();
			})
			return this;
		},
		
		show:function(){
			this.el.show();
			this._reposition();
		},
		_reposition:function(){
			this.el.css({
				top:($(window).height()-this.el.height())/2,
				left:($(window).width()-this.el.width())/2
			});
		},
		hide:function(){
			this.el.hide();
		},
		close:function(){
			this.el.remove();
		}
	}
	return _dialog;
})