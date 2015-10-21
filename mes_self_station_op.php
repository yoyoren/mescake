<html>
<head>
<meta charset="utf-8">

<link rel="stylesheet" href="http://www.mescake.com/bootstrap/css/bootstrap.min.css"/>
<script src="http://s1.static.mescake.com/script/release/lib.min.js"></script>
</head>

<body>
<style>
body,table,tr{font-size:12px;}
table td {padding:10px;}
</style>
<div>
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">添加自提站点</div>

<table cellpadding="10" class="table">
<tr>
  <td>站点名称</td>
  <td><input  class="form-control" id="station_name" width="200"/></td>
</tr>
<tr>
  <td>站点地址</td>
  <td><input class="form-control" id="station_address" width="200"/></td>
</tr>
<tr>
  <td>站点所在省份</td>
  <td><select class="form-control" id="province_sel">
	<option value="0">请选择</option>
  </select></td>
</tr>
<tr>
    <td>站点所在城市</td>
    <td><select class="form-control"  id="city_sel">
	<option value="0">请选择</option>
	</select></td>
</tr>
<tr>
    <td>站点所在区域</td>
    <td><select class="form-control"  id="distirct_sel">
	<option value="0">请选择</option>
	</select></td>
</tr>
<tr>
    <td>站点经度</td>
    <td><input class="form-control"  id="station_lat"  width="200" value="0"/></td>
</tr>
<tr>
  <td>站点纬度</td>
  <td><input class="form-control" id="station_lng" width="200" value="0"/></td>
</tr>
<tr>
  <td></td>
  <td><button id="confirm_add" class="btn">确认添加</button></td>
</tr>
</table>

</div>
</div>
<div>
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">已添加的自提站点列表</div>
	<table id="data_container" class="table">
	<tr>
	  <td>站点名称</td>
	  <td>站点地址</td>
	  <td>站点所在省份</td>
	  <td>站点所在城市</td>
	  <td>站点所在区域</td>
	  <td>站点经度</td>
	  <td>站点纬度</td>
	  <td>操作</td>
	</tr>
	</table>
	</div>
</div>
<script>
$('#confirm_add').click(function(){
	var station_name = $('#station_name').val();
	var station_address = $('#station_address').val();
	var station_province = $('#province_sel').val();
	var station_city = $('#city_sel').val();
	var station_district = $('#distirct_sel').val();
	var station_lat = $('#station_lat').val();
	var station_lng = $('#station_lng').val();
	if(!station_name){
		alert('请填写自提点名称')
		return;
	}
	if(!station_address){
		alert('请填写自提点地址')
		return;
	}
	if(!station_province){
		alert('请填写自提点省份')
		return;
	}
	if(!station_city){
		alert('请填写自提点城市')
		return;
	}
	if(!station_district){
		alert('请填写自提点区域')
		return;
	}
	if(!station_lat){
		station_lat = '0';
	}
	if(!station_lng){
		station_lng = '0';
	}
	station_province_id = station_province.split('_')[0];
	station_province_name = station_province.split('_')[1];
	
	station_city_id = station_city.split('_')[0];
	station_city_name = station_city.split('_')[1];
	
	station_district_id = station_district.split('_')[0];
	station_district_name = station_district.split('_')[1];
	
	$.post('/route.php?mod=self_station&action=add',{
		mod:'self_station',
		action:'add',
		station_name:station_name,
		station_address:station_address,
		station_province_id:station_province_id,
		station_province_name:station_province_name,
		station_city_id:station_city_id,
		station_city_name:station_city_name,
		station_district_id:station_district_id,
		station_district_name:station_district_name,
		station_lat:station_lat,
		station_lng:station_lng
	},function(d){
		alert('添加成功');
		location.reload();
	},'json');
});
$.get('/route.php',{
	mod:'self_station',
	action:'list',
	page:0,
},function(d){
	var html = '';
	for(var i=0;i<d.length;i++){
		var _d = d[i];
		html += '<tr id="item_'+_d.station_id+'"><td>'+ _d.station_name +'</td>\
		<td>'+ _d.station_address +'</td>\
		<td>'+ _d.station_province_name +'</td>\
		<td>'+ _d.station_city_name +'</td>\
		<td>'+ _d.station_district_name +'</td>\
		<td>'+ _d.station_lat +'</td>\
		<td>'+ _d.station_lng +'</td>\
		<td><button class="btn" data-id="'+_d.station_id+'" class="del_op">删除</button></td>\
		</tr>';
	}
	$('#data_container').append(html);
},'json');
$('body').delegate('.del_op','click',function(e){
	var con = confirm('确认删除这个自提站?');
	if(!con){
		return;
	}
	var _id = $(this).data('id');
	$.post('/route.php?mod=self_station&action=remove',{
		id:_id
	},function(){
		alert('删除成功');
		$('#item_' + _id).remove();
	},'json');
});

$.get('/route.php',{
	mod:'address',
	action:'province'
},function(d){
	var container = $('#province_sel');
	var html = '';
	for(var i=0;i<d.length;i++){
		var _d = d[i];
		html += '<option value="'+_d.code+'_'+_d.name+'">'+_d.name+'</option>'
	}
	container.append(html);
},'json');

$('#province_sel').change(function(){
	var val =  $(this).val();
	if(val){
		window.province_id = val.split('_')[0];
		$.get('/route.php',{
			mod:'address',
			action:'city',
			province: province_id
		},function(d){
			var container = $('#city_sel');
			var html = '<option value="0">请选择</option>';
			for(var i=0;i<d.length;i++){
				var _d = d[i];
				html += '<option value="'+_d.code+'_'+_d.name+'">'+_d.name+'</option>'
			}
			container.html(html);
		},'json');
	}
});

$('#city_sel').change(function(){
	var val =  $(this).val();
	if(val){
		var city_id = val.split('_')[0];
		$.get('/route.php',{
			mod:'address',
			action:'district',
			province: province_id,
			city:city_id
		},function(d){
			var container = $('#distirct_sel');
			var html = '<option value="0">请选择</option>';
			for(var i=0;i<d.length;i++){
				var _d = d[i];
				html += '<option value="'+_d.code+'_'+_d.name+'">'+_d.name+'</option>'
			}
			container.html(html);
		},'json');
	}
});
</script>
</body>
</html>

