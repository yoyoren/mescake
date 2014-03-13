define(function(){
	
	//默认的层级从1000开始
	var zIndex = 1000;
	
	//相当于id
	var index = 0;
	var jqWIN = $(window);
	var _html = '<div id="dialog_<%=index%>" class="dialog" style="margin:0;display:none;z-index:<%=zIndex%>">\
      <div class="dialog-head">\
        <%if(title){%><p class="dia-title"><%=title%></p><% } %>\
        <em class="close-ico" id="close_<%=index%>">X</em>\
      </div>\
      <div class="dialog-con" style="<%=textStyle%>">\
        <form action="">\
		   <p><%=body%></p>\
			<%if(!bottom) {%>\
				<input id="dialog_confirm_<%=index%>" class="v-btn green-btn" type="submit" value="确定">\
				<input id="dialog_cancel_<%=index%>" class="v-btn" type="reset" value="取消">\
			<% } else {%>\
				<%=bottom%>\
			<% } %>\
		</form>\
      </div>\
    </div>';

    var _dialog = function(opt){
		opt = opt||{};
		//opt.afterRender = opt.afterRender||function(){};

		this.opt = opt;
		var _index = index++;
		var html = mstmpl(_html,{
			title:opt.title,
			body:opt.body||'',
			bottom:opt.bottom||'',
			index:_index,
			zIndex:zIndex++,
			textStyle:opt.textStyle
		});
		$('body').append(html);
		$('body').append('<div class="gray-bg dialog_bg" style="z-index:100"></div>');
		this.el = $('#dialog_'+_index);
		
	    //没有底部的结构就不需要事件绑定
		if(!opt.bottom){
			this.cancelButton = $('#dialog_cancel_'+_index);
			this.confirmButton = $('#dialog_confirm_'+_index);
		}
		this.onshow = opt.onshow||function(){};
		this.onclose = opt.onclose||function(){};
		this.closeButton = $('#close_'+_index);
		this.bind().show();


		///渲染后可以自己定义一些自定义事件
		setTimeout(function(){
			opt.afterRender&&opt.afterRender();
		},0);
	};

	_dialog.prototype = {
		bind:function(){
			var _self = this;
			this.cancelButton&&this.cancelButton.click(function(){
				_self.hide();
			});

			this.closeButton.click(function(){
				_self.hide();
			});
			
			this.confirmButton&&this.confirmButton.click(function(){
				_self.opt.onconfirm();
				return false;
			});
	
			$(window).resize(function(){
				_self._reposition();
			})
			return this;
		},
		
		show:function(d){
			if(!$('body').find('.dialog_bg').length){
				$('body').append('<div class="gray-bg dialog_bg" style="z-index:100"></div>');
			}
			var bodyHeight = $(document).height(); 
			var windowHeight = $(window).height();
			function bgHeight(){
			  var bodyHeight = $(document).height(); 
			  var windowHeight = $(window).height();
			  if(windowHeight < bodyHeight){
			    $('body').find('.dialog_bg').height(bodyHeight);
			  }else{
			    $('body').find('.dialog_bg').height('100%');
			  }
			}
			bgHeight();
			$(window).resize(function() {
			  bgHeight(); 
			});
			//$('body').find('.dialog_bg').css('height',jqWIN.height()+jqWIN.scrollTop());
			this.el.show();
			this._reposition();
			this.onshow(d);
		},
		_reposition:function(){
			this.el.css({
				top:(jqWIN.height()-this.el.height())/2+jqWIN.scrollTop(),
				left:(jqWIN.width()-this.el.width())/2
			});
		},

		hide:function(){
			this.el.hide();
			$('body').find('.dialog_bg').remove();
		},

		close:function(){
			this.el.remove();
			this.onclose();
			$('body').find('.dialog_bg').remove();

		}
	}
	return _dialog;
});
