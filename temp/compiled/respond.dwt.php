<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?php echo $this->_var['keywords']; ?>" />
<meta name="Description" content="<?php echo $this->_var['description']; ?>" />

<title><?php echo $this->_var['page_title']; ?></title>

<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="animated_favicon.gif" type="image/gif" />
<link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />

<?php echo $this->smarty_insert_scripts(array('files'=>'common.js')); ?>
<style type="text/css">
p a{color:#006acd; text-decoration:underline;}
</style>
</head>
<body>
<?php echo $this->fetch('library/page_header.lbi'); ?>

<div class="blank"></div>
<div class="block">
  <div class="box">
   <div class="box_1">
    <h3><span><?php echo $this->_var['lang']['system_info']; ?></span></h3>
    <div class="boxCenterList RelaArticle" align="center">
      <div style="margin:20px auto;">
      <p style="font-size: 14px; font-weight:bold; color: red;"><?php echo $this->_var['message']; ?></p>
        <div class="blank"></div>
        <?php if ($this->_var['virtual_card']): ?>
        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
        <?php $_from = $this->_var['virtual_card']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vgoods');if (count($_from)):
    foreach ($_from AS $this->_var['vgoods']):
?>
          <?php $_from = $this->_var['vgoods']['info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'card');if (count($_from)):
    foreach ($_from AS $this->_var['card']):
?>
            <tr>
            <td bgcolor="#FFFFFF"><?php echo $this->_var['vgoods']['goods_name']; ?></td>
            <td bgcolor="#FFFFFF">
            <?php if ($this->_var['card']['card_sn']): ?><strong><?php echo $this->_var['lang']['card_sn']; ?>:</strong><?php echo $this->_var['card']['card_sn']; ?><?php endif; ?>
            <?php if ($this->_var['card']['card_password']): ?><strong><?php echo $this->_var['lang']['card_password']; ?>:</strong><?php echo $this->_var['card']['card_password']; ?><?php endif; ?>
            <?php if ($this->_var['card']['end_date']): ?><strong><?php echo $this->_var['lang']['end_date']; ?>:</strong><?php echo $this->_var['card']['end_date']; ?><?php endif; ?>
            </td>
            </tr>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </table>
        <?php endif; ?>
        <a href="<?php echo $this->_var['shop_url']; ?>"><?php echo $this->_var['lang']['back_home']; ?></a>
      </div>
    </div>
   </div>
  </div>
</div>
<div class="blank5"></div>

<div class="block">
  <div class="box">
   <div class="helpTitBg clearfix">
    <?php echo $this->fetch('library/help.lbi'); ?>
   </div>
  </div>
</div>
<div class="blank"></div>


<?php if ($this->_var['img_links'] || $this->_var['txt_links']): ?>
<div id="bottomNav" class="box">
 <div class="box_1">
  <div class="links clearfix">
    <?php $_from = $this->_var['img_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'link');if (count($_from)):
    foreach ($_from AS $this->_var['link']):
?>
    <a href="<?php echo $this->_var['link']['url']; ?>" target="_blank" title="<?php echo $this->_var['link']['name']; ?>"><img src="<?php echo $this->_var['link']['logo']; ?>" alt="<?php echo $this->_var['link']['name']; ?>" border="0" /></a>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <?php if ($this->_var['txt_links']): ?>
    <?php $_from = $this->_var['txt_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'link');if (count($_from)):
    foreach ($_from AS $this->_var['link']):
?>
    [<a href="<?php echo $this->_var['link']['url']; ?>" target="_blank" title="<?php echo $this->_var['link']['name']; ?>"><?php echo $this->_var['link']['name']; ?></a>]
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <?php endif; ?>
  </div>
 </div>
</div>
<?php endif; ?>

<div class="blank"></div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body>
</html>
