;(function ($){
    $.fn.miPlaceholder=function(color){
      var color=color || "#999";
      //Placeholder Ability
      if( 'placeholder' in document.createElement('input') ){
        return false;
     }   
      return this.each(function(i,obj){
        var miPid=(this.name || this.id)+"_miPid_"+i;
        var me=$(this);
        var className=me.attr("class");
        var placeholderText=me.attr("placeholder");
        me.before("<input type='text' id="+miPid+" class='"+className+"' value='"+placeholderText+"'>");
        $("#"+miPid).hide().css("color",color);
        if(this.value==""){
          me.hide();
          $("#"+miPid).show();
        }   
        $("#"+miPid).focus(function(){
            $(this).hide();
            me.show();
            me.focus();
        }); 
        me.blur(function(){
          if(this.value==""){
            me.hide();
            $("#"+miPid).show();
          }   
        }); 
      }); 
    }   
  })(jQuery);