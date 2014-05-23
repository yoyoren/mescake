ALTER TABLE `ecs_goods` ADD `best_temp` VARCHAR(10) NOT NULL DEFAULT '10';
update ecs_goods set best_temp = 5 where goods_id = 68;