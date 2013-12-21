<style type="text/css">
.search{width:165px;  margin:0 auto;}
.search input{display:none;}
.search ul{width:165px; overflow:hidden; padding:5px 0; margin:0 auto; border-bottom:2px solid #B4B4B4;}
.search .option li{width:36px; font-size:11px; float:left; line-height:20px; padding-left:4px; position:relative;}
.search .option1 li{padding-left:4px; font-size:11px; float:left; width:78px; overflow:hidden; line-height:14px;height:14px;position:relative;}
.search  .option li span{position:absolute; right:0px; top:2px;}
.search  .option1 li span{position:absolute; right:0px; top:0px;}
.search .option2 li{font-size:11px; width:160px; padding-left:4px; line-height:15px; position:relative;}
.search  .option2 li span{position:absolute; right:0px; top:3px;}
.search1{width:160px; height:27px; margin:5px auto ; line-height:27px;font-size:14px; font-weight:bold;}
.history{width:170px; overflow:hidden; margin:0 auto; padding-top:35px;}

</style>

<form id="attr_searchForm" name="attr_searchForm" method="get" action="search.php">
<div class="search">
    
	<ul class="option" id="jieri" onclick="kuang2('jieri')">
<li><input  type="checkbox" id="shengri" name="oc" value="1" class="hide1" <?php if ($this->_var['flag_oc']['1']): ?>checked<?php endif; ?>/>生日<span <?php if ($this->_var['flag_oc']['1']): ?>class="checked"<?php endif; ?> name="shengri" >&nbsp;</span></li>
<li><input type="checkbox" id="juhui" name="oc" value="2" class="hide1" <?php if ($this->_var['flag_oc']['2']): ?>checked<?php endif; ?>/>聚会<span name="juhui"  <?php if ($this->_var['flag_oc']['2']): ?>class="checked"<?php endif; ?>>&nbsp;</span></li>
<li><input type="checkbox" id="ganen" name="oc" value="3" class="hide1" <?php if ($this->_var['flag_oc']['3']): ?>checked<?php endif; ?>/>感恩<span name="ganen"  <?php if ($this->_var['flag_oc']['3']): ?>class="checked"<?php endif; ?>>&nbsp;</span></li>
<li><input type="checkbox" id="qingzhu" name="oc" value="4" class="hide1" <?php if ($this->_var['flag_oc']['4']): ?>checked<?php endif; ?> />庆祝<span name="qingzhu"  <?php if ($this->_var['flag_oc']['4']): ?>class="checked"<?php endif; ?> >&nbsp;</span></li>
<li><input type="checkbox" id="huodong" name="oc" value="5" class="hide1"  <?php if ($this->_var['flag_oc']['5']): ?>checked<?php endif; ?>/>活动<span name="huodong" <?php if ($this->_var['flag_oc']['5']): ?>class="checked"<?php endif; ?>>&nbsp;</span></li>
<li><input type="checkbox" id="shangwu" name="oc" value="6" class="hide1"  <?php if ($this->_var['flag_oc']['6']): ?>checked<?php endif; ?>/>商务<span name="shangwu"   <?php if ($this->_var['flag_oc']['6']): ?>class="checked"<?php endif; ?>>&nbsp;</span></li>
<li><input type="checkbox" id="kuanghuan" name="oc" value="7" class="hide1"  <?php if ($this->_var['flag_oc']['7']): ?>checked<?php endif; ?>/>狂欢<span name="kuanghuan" <?php if ($this->_var['flag_oc']['7']): ?>class="checked"<?php endif; ?>>&nbsp;</span></li>
</ul>
       
    <div style="height:8px"></div>
    <ul class="option1 " id="kouwei" onclick="kuang2('kouwei')">
<li><input type="checkbox" id="qiaokeli" name="cat" value="2" class="hide1" rel="巧克力蛋糕" <?php if ($this->_var['flag_cat']['2']): ?>checked<?php endif; ?>/>巧克力蛋糕<span name="qiaokeli" <?php if ($this->_var['flag_cat']['2']): ?>class="checked"<?php endif; ?>>&nbsp;</span></li>
<li><input type="checkbox" id="naiyou" name="cat" value="1" class="hide1" rel="乳脂奶油蛋糕" <?php if ($this->_var['flag_cat']['1']): ?>checked<?php endif; ?>/>乳脂奶油蛋糕<span name="naiyou" <?php if ($this->_var['flag_cat']['1']): ?>class="checked"<?php endif; ?>>&nbsp;</span></li>
<li><input type="checkbox" id="musi" name="cat" value="3" class="hide1" rel="慕斯蛋糕"  <?php if ($this->_var['flag_cat']['3']): ?>checked<?php endif; ?>/>慕斯蛋糕<span name="musi" <?php if ($this->_var['flag_cat']['3']): ?>class="checked"<?php endif; ?>>&nbsp;</span></li>
<li><input type="checkbox" id="zhishi" name="cat" value="5" class="hide1" rel="芝士蛋糕"   <?php if ($this->_var['flag_cat']['5']): ?>checked<?php endif; ?>/>芝士蛋糕<span name="zhishi" <?php if ($this->_var['flag_cat']['5']): ?>class="checked"<?php endif; ?>>&nbsp;</span></li>
<li><input type="checkbox" id="ice" name="cat" value="4" class="hide1" rel="水果蛋糕"  <?php if ($this->_var['flag_cat']['4']): ?>checked<?php endif; ?>/>水果蛋糕<span name="ice" <?php if ($this->_var['flag_cat']['4']): ?>class="checked"<?php endif; ?>>&nbsp;</span></li>           
    </ul>      
    <div style="clear:both"></div>
     <ul class="option2" id="han"  onclick="kuang2('han')">
<li><input type="checkbox" id="jianguo" name="cf" value="1" class="hide1" <?php if ($this->_var['flag_cf']['1']): ?>checked<?php endif; ?>/>含坚果<span name="jianguo" <?php if ($this->_var['flag_cf']['1']): ?>class="checked"<?php endif; ?>>&nbsp;</span></li>
<li><input type="checkbox" id="jiu" name="cf" value="2" class="hide1" <?php if ($this->_var['flag_cf']['2']): ?>checked<?php endif; ?>/>含酒<span name="jiu" <?php if ($this->_var['flag_cf']['2']): ?>class="checked"<?php endif; ?>>&nbsp;</span></li>

     </ul>
    <div class="blank5"></div>
   <div class="search1"><a href="javascript:submit_search();">点击搜索&nbsp;<img src="themes/default/images/sico.gif" style="margin-left:65px;" align="absmiddle"/></a></div>
</div>
</form>

<script >
function kuang2(id){
  labels = document.getElementById(id).getElementsByTagName('span');
  radios = document.getElementById(id).getElementsByTagName('input');
    for(i=0,j=labels.length ; i<j ; i++)
	{
		labels[i].onclick=function() 
		{   
		   if(this.className==''){
			 this.className='checked';
			 try{
				document.getElementById(this.getAttribute('name')).checked = true;
			 } catch (e) {}
		   }
		   else
		   {
			  this.className='';
			  try{
				document.getElementById(this.getAttribute('name')).checked = false;
			 } catch (e) {} ;		   
		   
		   }
			
			
		}
	}
		
		
}
function submit_search()
{
	var oc = "", cat = "", cf = "";
	
	$("#jieri input:checked").each(function(){
	   oc += oc == ""? $(this).val():"."+$(this).val();
	 });  
    $("#kouwei input:checked").each(function(){
	   cat += cat == ""? $(this).val() : "." + $(this).val();
    });
    $("#han input:checked").each(function(){
	   cf += cf == ""? $(this).val() : "." + $(this).val();
    }); 
	
	if($("#search_goods_list").length >0)
	{
	   $("#search_goods_list").html('loading');
	   $.ajax({
			   type:"POST",
			   url:"search.php",
			   data:{"oc":oc,"cat":cat, "cf":cf,"is_ajax":"1"},
			   dataType:'json',
			   success:function(result){
				   if(result.error >0)
				   {
					  alert(result.message);  
				   }
				   else
				   {
					  $("#search_goods_list").html(result.content);      
				   }
			  }			   
		});		
	}
	else
	{
	    var pam = "";
		pam += oc != "" ? "&oc=" + oc:"";
		pam += cat != "" ? "&cat=" + cat:"";
		pam += cf != "" ? "&cf=" + cf:"";
		pam = pam.length >0 ? "?" + pam.substr(1):"";		
		location.href ="search.php"+ pam;		       	
	}
}
</script>