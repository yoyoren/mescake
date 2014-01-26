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
        var placeholderText=me.attr("placeholder");       
        me.before("<i class='place-item' id="+miPid+">"+placeholderText+"</i>");
        $("#"+miPid).hide().css("color",color);
        if(this.value==""){
          $("#"+miPid).show();
        }   
        $("#"+miPid).click(function(){
            $(this).hide();
            me.focus();
        }); 
        me.focus(function(){
          $("#"+miPid).hide();
        });
        me.blur(function(){
          if(this.value==""){
            $("#"+miPid).show();
          }   
        }); 
      }); 
    }   
  })(jQuery);