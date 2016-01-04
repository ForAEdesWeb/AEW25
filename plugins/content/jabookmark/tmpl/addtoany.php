<?php
/**
 * ------------------------------------------------------------------------
 * JA Bookmark plugin for J2.5 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

/**
 * JA Bookmark Plugin is a module using for Display icons for your online social networking sites.
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

$language = $this->params->get('system-addtoany-language', 'en-US');
$customApps = $this->params->get('addthis_custom_apps');

if (trim($customApps) != '') {
    $apps = '"' . implode('","', split(',', $customApps)) . '"';
}

?>
<a class="a2a_dd" href="http://www.addtoany.com/share_save">
	<?php if ($this->params->get('addthis_button') != 'none') : ?>
	<?php if (!$this->params->get('used_custom_button')) : ?>
		<img src="http://static.addtoany.com/buttons/<?php echo $this->params->get('system-addtoany-button', 'share_save_256_24.png');?>" border="0" alt="Share/Save/Bookmark" />
	<?php else :?>
		<?php echo $this->params->get('custom_button');?>
		<?php echo $this->params->get('widget_text');?>
    <?php endif;?>
    <?php endif; ?>
</a>
<script type="text/javascript">

	a2a_color_main="<?php echo $this->params->get('system-addtoany-maincolor', 'D7E5ED');?>";
	a2a_color_border="<?php echo $this->params->get('system-addtoany-border', '414c53');?>";
	a2a_color_link_text="<?php echo $this->params->get('system-addtoany-linktext', '333333');?>";
	a2a_color_link_text_hover="<?php echo $this->params->get('system-addtoany-linktext_hover', '333333');?>";
	a2a_color_bg="<?php echo $this->params->get('system-addtoany-background', 'FFFFFF');?>";
	a2a_show_title=<?php echo (int) $this->params->get('system-addtoany-showpagetitle', '0');?>;
	a2a_orientation="<?php echo $this->params->get('system-addtoany-orientation', 'down');?>";
	<?php if (isset($apps)) : ?>
	a2a_prioritize=[<?php echo $apps;?>];
	
	<?php endif;?>
</script>

<script type="text/javascript" src="http://static.addtoany.com/menu/locale/<?php echo $language;?>.js" charset="utf-8"></script>
<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>