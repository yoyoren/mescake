<html>
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
<link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />

<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,jquery-1.4.js,shopping_flow.js')); ?>
</head>

<body style="background:#F2F2F2;">

<table width="900" align="center" border="0" >
			<tr>
				<td colspan="5"><hr/></td>
			</tr>
			<tr><td colspan="5">&nbsp;</td></tr>
			<tr>
				<td width="190"><input type="radio" name="dizhi" style="border:none;" onclick="addtoship(1)"/>
					<select name="country1" id="country1" class="city">
						<option value="441" selected>北京市</option>
					</select>&nbsp;&nbsp;&nbsp;
						<select name="city1" class="district" id="city1">
						<?php if ($this->_var['address1']['qu']): ?>
							<option value="<?php echo $this->_var['address1']['city']; ?>"><?php echo $this->_var['address1']['qu']; ?></option>
						<?php else: ?>
							<option value="0">选择区域</option>
						<?php endif; ?>
						<?php $_from = $this->_var['district_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'district');if (count($_from)):
    foreach ($_from AS $this->_var['district']):
?>
							<option value="<?php echo $this->_var['district']['region_id']; ?>"><?php echo $this->_var['district']['region_name']; ?></option>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</select>
				</td>
				<td width="291"><input class="lishiaddress" type="text" name="address1" id="address1" value="<?php echo $this->_var['address1']['address']; ?>" /></td>
				<td width="150">收货人:&nbsp;<input class="historyman" type="text" name="contact1" id="contact1" value="<?php echo $this->_var['address1']['consignee']; ?>"/></td>
				<td width="180">联系电话:&nbsp;<input class="historytel" type="text" name="tel1" id="tel1" value="<?php echo $this->_var['address1']['mobile']; ?>"/><input type="hidden" id="hidden1" value="1"/></td>
				<td><b onclick="changead1(<?php echo $this->_var['address1']['address_id']; ?>)"><img src="themes/default/images/save22.png"/></b></td>
			</tr>
			<tr>
				<td><input type="radio" name="dizhi" style="border:none;" onclick="addtoship(2)"/>
					<select name="country2" id="country2" class="city">
						<option value="441" selected>北京市</option>
					</select>&nbsp;&nbsp;&nbsp;
						<select name="city2" class="district" id="city2">
						<?php if ($this->_var['address2']['qu']): ?>
						<option value="<?php echo $this->_var['address2']['city']; ?>"><?php echo $this->_var['address2']['qu']; ?></option>
						<?php else: ?>
							<option value="0">选择区域</option>
						<?php endif; ?>
						<?php $_from = $this->_var['district_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'district');if (count($_from)):
    foreach ($_from AS $this->_var['district']):
?>
							<option value="<?php echo $this->_var['district']['region_id']; ?>" ><?php echo $this->_var['district']['region_name']; ?></option>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</select>
				</td>
				<td><input class="lishiaddress" type="text" name="address2" id="address2" value="<?php echo $this->_var['address2']['address']; ?>" /></td>
				<td>收货人:&nbsp;<input class="historyman" type="text" name="contact2" id="contact2" value="<?php echo $this->_var['address2']['consignee']; ?>"/></td>
				<td>联系电话:&nbsp;<input class="historytel" type="text" name="tel2" id="tel2" value="<?php echo $this->_var['address2']['mobile']; ?>"/><input type="hidden" id="hidden2" value="2"/></td>
				<td><b onclick="changead2(<?php echo $this->_var['address2']['address_id']; ?>)"><img src="themes/default/images/save22.png"/></b></td>
			</tr>
			<tr>
				<td><input type="radio" name="dizhi" style="border:none;" onclick="addtoship(3)" />
					<select name="country3" id="country3" class="city">
				
						<option value="441" selected>北京市</option>
					</select>&nbsp;&nbsp;&nbsp;
						<select name="city3" class="district" id="city3">
						<?php if ($this->_var['address3']['qu']): ?>
							<option value="<?php echo $this->_var['address3']['city']; ?>"><?php echo $this->_var['address3']['qu']; ?></option>
						<?php else: ?>
							<option value="0">选择区域</option>
						<?php endif; ?>
						<?php $_from = $this->_var['district_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'district');if (count($_from)):
    foreach ($_from AS $this->_var['district']):
?>
							<option value="<?php echo $this->_var['district']['region_id']; ?>" ><?php echo $this->_var['district']['region_name']; ?></option>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</select>				
				</td>
				<td><input class="lishiaddress" type="text" name="address3" id="address3"  value="<?php echo $this->_var['address3']['address']; ?>" /></td>
				<td>收货人:&nbsp;<input class="historyman" type="text" name="contact3" id="contact3" value="<?php echo $this->_var['address3']['consignee']; ?>"/></td>
				<td>联系电话:&nbsp;<input class="historytel" type="text" name="tel3" id="tel3" value="<?php echo $this->_var['address3']['mobile']; ?>"/><input type="hidden" id="hidden3" value="3"/></td>
				<td><b onclick="changead3(<?php echo $this->_var['address3']['address_id']; ?>)"><img src="themes/default/images/save22.png"/></b></td>
			</tr>	
		</table>
		<script language="javascript">
		function addtoship(a)
		{
		    var address=" ";
			var contact=" ";
			var tel=" ";
			var country='';
			var city='';
			var text='';
			
			var address1=$("#address1").val();
			var contact1=$("#contact1").val();
			var tel1=$("#tel1").val();
			var country1=$("#country1").val();
			
			//var city1=$("#city1").val();
			var c1=document.getElementById('city1');
			var index1 = c1.selectedIndex;
			var text1 = c1.options[index1].text;
			var city1 = c1.options[index1].value;

			var address2=$("#address2").val();
			var contact2=$("#contact2").val();	
			var tel2=$("#tel2").val();
			var country2=$("#country2").val();
			//var city2=$("#city2").val();
			var c2=document.getElementById('city2');
			var index2 = c2.selectedIndex;
			var text2 = c2.options[index2].text;
			var city2 = c2.options[index2].value;
			
			var address3=$("#address3").val();
			var contact3=$("#contact3").val();	
			var tel3=$("#tel3").val();
			var country3=$("#country3").val();
			//var city3=$("#city3").val();
			var c3=document.getElementById('city3');
			var index3 = c3.selectedIndex;
			var text3 = c3.options[index3].text;
			var city3 = c3.options[index3].value;
			
			if(a==1)
			{
			     address=address1;
				 contact=contact1;
				 tel=tel1;
				 country=country1;
				 city=city1;
				 text=text1;
				 var beijing='北京市';
				 var addressall=beijing + text;
				 if(address1=='')
				{
					alert("地址不能为空");
				}
				else
				{
					parent.document.getElementById('shipping_consignee').value=contact;
					parent.document.getElementById('shipping_tel').value=tel;
					parent.document.getElementById('addressxu').value=addressall;
					parent.document.getElementById('shipping_address').value=address;
					parent.document.getElementById("shipping_district").value=city;
				}
				 alert('送货地址已修改，请再次保存送货信息');
			}
			if(a==2)
			{
			     address=address2;
				 contact=contact2;
				 tel=tel2;
				 country=country2;
				 city=city2;
				 text=text2;
				 var beijing='北京市';
				 var addressall=beijing + text;
				 if(address2=='')
				{
					alert("地址不能为空");
				}
				else
				{
					parent.document.getElementById('shipping_consignee').value=contact;
					parent.document.getElementById('shipping_tel').value=tel;
					parent.document.getElementById('addressxu').value=addressall;
					parent.document.getElementById('shipping_address').value=address;
					parent.document.getElementById("shipping_district").value=city;
				}
				alert('送货地址已修改，请再次保存送货信息');
			}
			if(a==3)
			{
			     address=address3;
				 contact=contact3;
				 tel=tel3;
				 country=country3;
				 city=city3;
				 text=text3;
				 var beijing='北京市';
				 var addressall=beijing + text;
				 if(address3=='')
				{
					alert("地址不能为空");
				}
				else
				{
					parent.document.getElementById('shipping_consignee').value=contact;
					parent.document.getElementById('shipping_tel').value=tel;
					parent.document.getElementById('addressxu').value=addressall;
					parent.document.getElementById('shipping_address').value=address;
					parent.document.getElementById("shipping_district").value=city;
				}	
				alert('送货地址已修改，请再次保存送货信息');
				
			}
			
			
			
				
		}
		//历史地址
		function changead1(n)
      {
		var address1=$("#address1").val();
		var contact1=$("#contact1").val();
		var tel1=$("#tel1").val();
		var country1=$("#country1").val();
		var city1=$("#city1").val();
		if(!isNaN(tel1)){
			if(tel1.length !=11)
			{
				alert("手机号码长度不对");
			}
			else
			{
					$.ajax({
					  type:"post",
					  url:"historyadr.php",
					  data:"id="+new Date()+"&step=historyad1"+"&address_id="+n+"&address1="+address1+"&contact1="+contact1+"&tel1="+tel1+"&country1="+country1+"&city1="+city1,
					  dataType:"html",
					  success:function(msg){
						  //接收服务器的响应结果
						 //alert(msg);
						  window.location.reload();
						  //$("#div1").html(msg);
					  }
				  });
			}
		}else{
			alert("请输入手机号码");
		}
         
      }
	  function changead2(m)
      {
		var address2=$("#address2").val();
		var contact2=$("#contact2").val();	
		var tel2=$("#tel2").val();
		var country2=$("#country2").val();
		var city2=$("#city2").val();
		if(!isNaN(tel2)){
			if(tel2.length !=11)
			{
				alert("手机号码长度不对");
			}
			else
			{
					$.ajax({
					  type:"post",
					  url:"historyadr.php",
					  data:"id="+new Date()+"&step=historyad2"+"&address_id="+m+"&address2="+address2+"&contact2="+contact2+"&tel2="+tel2+"&country2="+country2+"&city2="+city2,
					  dataType:"html",
					  success:function(msg){
						  //接收服务器的响应结果
						  //alert(msg);
						   window.location.reload();
						  //$("#div1").html(msg);
					  }
				  });
			}
		}else{
			alert("请输入手机号码");
		}
      }
	   function changead3(k)
      {
	 
		var address3=$("#address3").val();
		var contact3=$("#contact3").val();	
		var tel3=$("#tel3").val();
		var country3=$("#country3").val();
		var city3=$("#city3").val();
		if(!isNaN(tel3)){
			if(tel3.length !=11)
			{
				alert("手机号码长度不对");
			}
			else
			{
				  $.ajax({
					  type:"post",
					  url:"historyadr.php",
					  data:"id="+new Date()+"&step=historyad3"+"&address_id="+k+"&address3="+address3+"&contact3="+contact3+"&tel3="+tel3+"&country3="+country3+"&city3="+city3,
					  dataType:"html",
					  success:function(msg){
						  //接收服务器的响应结果
						 // alert(msg);
						   window.location.reload();
						  //$("#div1").html(msg);
					  }
				  });
			}
		}else{
			alert("请输入手机号码");
		}
      }
		
		</script>
	</body>
</html>