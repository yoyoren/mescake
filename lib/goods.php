<?php

//商品详情相关
class MES_Goods {
	private static function get_attr($attr_id) {
		$sql = "SELECT  ga.attr_value " . "FROM " . $GLOBALS['ecs'] -> table('goods_attr') . " AS ga " . "WHERE  " . db_create_in($attr_id, 'goods_attr_id');
		$attr = $GLOBALS['db'] -> getOne($sql);
		$attr = substr($attr, 0, 3);
		if ($attr == '1.0')
			$num['cj'] = 5;
		if ($attr == '2.0')
			$num['cj'] = 10;
		if ($attr == '3.0')
			$num['cj'] = 15;
		if ($attr == '5.0')
			$num['cj'] = 20;
		if ($attr == '10.')
			$num['cj'] = 40;
		if ($attr == '15.')
			$num['cj'] = 50;
		if ($attr == '20.')
			$num['cj'] = 80;
		if ($attr == '25.')
			$num['cj'] = 100;
		if ($attr == '30.')
			$num['cj'] = 120;
		if ($attr == '1.0')
			$num['cd'] = 1;
		if ($attr == '2.0')
			$num['cd'] = 1;
		if ($attr == '3.0')
			$num['cd'] = 1;
		if ($attr == '5.0')
			$num['cd'] = 2;
		if ($attr == '10.')
			$num['cd'] = 2;
		if ($attr == '15.')
			$num['cd'] = 3;
		if ($attr == '20.')
			$num['cd'] = 4;
		if ($attr == '25.')
			$num['cd'] = 5;
		if ($attr == '30.')
			$num['cd'] = 6;
		return $num;
	}

	public static function get_price_by_weight($goods_id, $attr_id, $number) {
		global $_LANG;
		$res = array('code' => '0', 'result' => '', 'qty' => 1);
		if ($goods_id == 0) {
			$res['code'] = 1;
			$res['err_msg'] = $_LANG['err_change_attr'];
			$res['err_no'] = 1;
		} else {
			if ($number == 0) {
				$res['qty'] = $number = 1;
			} else {
				$res['qty'] = $number;
			}

			$shop_price = get_final_price($goods_id, $number, true, $attr_id);
			$res['result'] = $shop_price * $number;
			//$res['result'] .="<font size=\"6px\" style=\"vertical-align:top;\">00</font>";
			$free = MES_Goods::get_attr($attr_id);
			$res['cd'] = $free['cd'];
			$res['cj'] = $free['cj'];
		}

		return json_encode($res);

	}

	public static function goods_detail_page($goods_id) {
		function get_tpl(){
			global $_CFG;
			global $db;
			global $smarty;
			global $ecs;
			global $goods_id;
			$cache_id = $goods_id . '-' . $_SESSION['user_rank'] . '-' . $_CFG['lang'];
			$cache_id = sprintf('%X', crc32($cache_id));
				$smarty -> assign('image_width', $_CFG['image_width']);
				$smarty -> assign('image_height', $_CFG['image_height']);
				$smarty -> assign('helps', get_shop_help());
				// 网店帮助
				$smarty -> assign('id', $goods_id);
				$smarty -> assign('type', 0);
				$smarty -> assign('cfg', $_CFG);
				$smarty -> assign('promotion', get_promotion_info($goods_id));
				//促销信息
				$smarty -> assign('promotion_info', get_promotion_info());
				$fileContent = file_get_contents("./tmpl/cake_".$goods_id.".htm");
				$smarty -> assign('staff_html', $fileContent);

				/* 获得商品的信息 */
				$goods = get_goods_info($goods_id);

				if ($goods === false) {
					/* 如果没有找到任何记录则跳回到首页 */
					ecs_header("Location: ./\n");
					exit ;
				} else {
					if ($goods['brand_id'] > 0) {
						$goods['goods_brand_url'] = build_uri('brand', array('bid' => $goods['brand_id']), $goods['goods_brand']);
					}

					$shop_price = $goods['shop_price'];
					//$linked_goods = get_linked_goods($goods_id);
					$goods['goods_sn'] = substr($goods['goods_sn'], 0, 3);
					$goods['goods_style_name'] = add_style($goods['goods_name'], $goods['goods_name_style']);

					/* 购买该商品可以得到多少钱的红包 */
					if ($goods['bonus_type_id'] > 0) {
						$time = gmtime();
						$sql = "SELECT type_money FROM " . $ecs -> table('bonus_type') . " WHERE type_id = '$goods[bonus_type_id]' " . " AND send_type = '" . SEND_BY_GOODS . "' " . " AND send_start_date <= '$time'" . " AND send_end_date >= '$time'";
						$goods['bonus_money'] = floatval($db -> getOne($sql));
						if ($goods['bonus_money'] > 0) {
							$goods['bonus_money'] = price_format($goods['bonus_money']);
						}
					}

					$smarty -> assign('goods', $goods);
					$smarty -> assign('goods_id', $goods['goods_id']);
					$_SESSION['goods_ids'] = $goods['goods_id'];
					$smarty -> assign('promote_end_time', $goods['gmt_end_time']);
					$smarty -> assign('categories', get_categories_tree($goods['cat_id']));
					// 分类树

					/* meta */
					$smarty -> assign('keywords', htmlspecialchars($goods['keywords']));
					$smarty -> assign('description', htmlspecialchars($goods['goods_brief']));

					$catlist = array();
					foreach (get_parent_cats($goods['cat_id']) as $k => $v) {
						$catlist[] = $v['cat_id'];
					}

					assign_template('c', $catlist);

					//上一个商品下一个商品 
					$prev_gid = $db -> getOne("SELECT goods_id FROM " . $ecs -> table('goods') . " WHERE cat_id=" . $goods['cat_id'] . " AND goods_id > " . $goods['goods_id'] . " AND is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0 LIMIT 1");
					if (!empty($prev_gid)) {
						$prev_good['url'] = build_uri('goods', array('gid' => $prev_gid), $goods['goods_name']);
						$smarty -> assign('prev_good', $prev_good);
						//上一个商品
					}

					$next_gid = $db -> getOne("SELECT max(goods_id) FROM " . $ecs -> table('goods') . " WHERE cat_id=" . $goods['cat_id'] . " AND goods_id < " . $goods['goods_id'] . " AND is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0");
					if (!empty($next_gid)) {
						$next_good['url'] = build_uri('goods', array('gid' => $next_gid), $goods['goods_name']);
						$smarty -> assign('next_good', $next_good);
						//下一个商品
					}

					$position = assign_ur_here($goods['cat_id'], $goods['goods_name']);

					/* current position */
					$smarty -> assign('page_title', $position['title']);
					// 页面标题
					$smarty -> assign('ur_here', $position['ur_here']);
					// 当前位置
					$wine_list = array(1, 3, 5, 10, 11, 16, 18, 24, 27, 28, 29, 30, 31, 33, 35, 36);
					$nut_list = array(6, 7, 8, 17, 26, 35, 39, 40);
					$qkl_list = array(6, 8, 11, 19, 21, 22, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37);
					if (in_array($goods_id, $wine_list))
						$smarty -> assign('wine', 1);
					if (in_array($goods_id, $nut_list))
						$smarty -> assign('nut', 1);
					if (in_array($goods_id, $qkl_list))
						$smarty -> assign('qkl', 1);
					$properties = get_goods_properties($goods_id);
					// 获得商品的规格和属性
					//print_r($properties['pro']);
					$kouwei = $properties['pro']['商品属性'][11]['value'];
					$ycl = $properties['pro']['商品属性'][9]['value'];

					$smarty -> assign('kouwei', $kouwei);
					$smarty -> assign('ycl', $ycl);
					$smarty -> assign('properties', $properties['pro']);
					// 商品属性
					$specification = $properties['spe'][6]['values'];
					$specification_more = array();
					$specification_display = array();
					$specification_count = count($specification);

					$specification_display = array_slice($specification,0,3);

					if($specification_count>2){
						$specification_more = array_slice($specification,3,$specification_count);
					}


					$smarty -> assign('specification', $properties['spe']);
					$smarty -> assign('specification_more', $specification_more);
					$smarty -> assign('specification_display', $specification_display);
					//var_dump($properties);
					// 商品规格
					
					$smarty -> assign('attribute_linked', get_same_attribute_goods($properties));
					// 相同属性的关联商品
					$smarty -> assign('related_goods', $linked_goods);
					// 关联商品
					//$smarty -> assign('goods_article_list', get_linked_articles($goods_id));
					// 关联文章
					$smarty -> assign('fittings', get_goods_fittings(array($goods_id)));
					// 配件
					//$smarty -> assign('rank_prices', get_user_rank_prices($goods_id, $shop_price));
					// 会员等级价格
					$smarty -> assign('pictures', get_goods_gallery($goods_id));
					// 商品相册
					//$smarty -> assign('bought_goods', get_also_bought($goods_id));
					// 购买了该商品的用户还购买了哪些商品
					//$smarty -> assign('goods_rank', get_goods_rank($goods_id));
					// 商品的销售排名

					//获取tag
					$tag_array = get_tags($goods_id);
					$smarty -> assign('tags', $tag_array);
					// 商品的标记
					//获取关联礼包
					//$package_goods_list = get_package_goods_list($goods['goods_id']);
					$smarty -> assign('package_goods_list', $package_goods_list);
					// 获取关联礼包

					assign_dynamic('goods');
					
					$volume_price_list = get_volume_price_list($goods['goods_id'], '1');
					$smarty -> assign('volume_price_list', $volume_price_list);
					// 商品优惠价格区间
				}
			

			//更新点击次数
			$db -> query('UPDATE ' . $ecs -> table('goods') . " SET click_count = click_count + 1 WHERE goods_id = '$goods_id'");
			$usermsg = $_SESSION['usermsg'];
			$user_msg = $_SESSION['user_msg'];

			$smarty -> assign('u_name', $usermsg['user_name']);
			$smarty -> assign('u_name2', $user_msg['user_msg']);
			
			// 当前系统时间
			$smarty -> assign('now_time', gmtime());
			return $smarty;
		}
		echo PAGE_CACHER($goods_id,'goods_page','goods_v2.dwt','get_tpl');

	}

	public static function get_goods_by_catogary($id_arrays){
		global $db;	
		$sql = 'SELECT * from '.$GLOBALS['ecs']->table('goods').'where is_on_sale=1 AND is_alone_sale = 1 AND is_delete = 0';
        $goods_res = $GLOBALS['db']->getAll($sql);
		$total = count($goods_res);
		$res = array();
		for($i=0;$i<$total;$i++){
			if(in_array($goods_res[$i]['goods_id'], $id_arrays)){
				$goods_res[$i]['goods_desc'] = strip_tags($goods_res[$i]['goods_desc']);
				$goods_res[$i]['goods_image'] ='themes/default/images/sgoods/'.substr($goods_res[$i]['goods_sn'],0,3).'.png';
				array_push($res,$goods_res[$i]);
			}
			
		}
		return $res;
		//var_dump($goods_res);
	}

}
?>