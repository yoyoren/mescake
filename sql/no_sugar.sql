ALTER TABLE `ecs_order_goods` ADD `goods_attr_id` VARCHAR( 200 ) NOT NULL DEFAULT '0';

//必须有这个字段 否则查询磅重会有问题
insert into ecs_attribute (attr_id,cat_id,attr_name,attr_input_type,attr_type,attr_values,attr_index,sort_order,is_linked,attr_group) values (23,5,'no sugar',1,0,'',0,0,0,0);