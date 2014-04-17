<?php
$CAKE_CATO = file_get_contents("goodsconfig.json");
$CAKE_CATO = json_decode($CAKE_CATO,true);

$CAKE_SLIDER = array(
  array('img'=>'img/luckman.png',title=>'','desc'=>'','link'=>'http://www.mescake.com/cake/32'),
  array('img'=>'img/banner-huodong.jpg',title=>'','desc'=>'','link'=>'http://huodong.mescake.com/cat'),
  array('img'=>'img/banner-cat.jpg',title=>'<h2 style="color:#f59e21;">像猫一样享受生活</h2>','desc'=>'<p style="width:340px;">晒太阳是特长，看风景是爱好，<br>快乐随性是我的天分；<br>得意洋洋的享受时间，<br>让整个世界做我的游乐场。</p>','link'=>'/catcake'),
  
  array('img'=>'img/banner-for-love.jpg',title=>'<h2 style="color:#f2a8a8;">我愿意</h2>','desc'=>'<p style="width:340px;">人的一生，会遇到约2920万人<br>千万分之一的驻足<br>致爱，致唯一</p>','link'=>'/cake/27'),
);

?>
