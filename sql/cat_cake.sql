INSERT INTO ecs_goods (goods_id,cat_id,goods_sn,goods_name,goods_name_style,click_count,brand_id,provider_name,goods_number,goods_weight,market_price,shop_price,promote_price,promote_start_date,promote_end_date,warn_number,keywords,goods_brief,goods_desc,goods_thumb,goods_img,original_img,is_real,extension_code,is_on_sale,is_alone_sale,is_shipping,integral,add_time,sort_order,is_delete,is_best,is_new,is_hot,is_promote,bonus_type_id,last_update,goods_type,seller_note,give_integral,rank_integral,suppliers_id,is_check,is_sugar) values(68,6,' A0188','猫爪蛋糕','+',0,4,'',0,'0.000','0.00','0.00','0.00',0,0,0,'','','','','','',1,'',1,1,0,0,1375728111,100,0,0,0,0,0,0,1375728337,2,'',-1,-1,0,NULL,1);
INSERT INTO ecs_goods_attr (goods_id,attr_id,attr_value,attr_price) values('68','6','1套：适合4-5人使用','188.00');
INSERT INTO ecs_goods_attr (goods_id,attr_id,attr_value,attr_price) values('68','9','乳脂、巧克力、树莓、草莓、枇杷','0');



这个问题真正的原因已经查到了，用户确实有过注册下单行为，但是用户通过购买的链接是首页的一个luckman活动Banner，这个banner指向的域名是huodong.mescake.com/cake/32，但是这个域名连接的是用于活动的数据库，不是和客服系统打通的线上数据库，所以用户下单是无法被查询到的。

现在这个问题已经resolve，以后不会再发生此类事件。
同时为了严格避免类似事件，我们将会采取一些更加严谨的方式上线
1.之后的网站banner上线不会再使用开发的人肉修改代码的方式上线的方式。同时我们正在为市场和运营同学开发一套UI的CMS系统，可以安全的更新网站banner
2.huodong域名只保留活动的数据表和huodong页面，其他页面将不能访问。
3.所有非运营环境服务器的网站，会留一个特殊页脚标识页面，便于问题发现和环境的区分。