<?php
/**
 * @name		Modules Manager CK
 * @package		com_modulesmanagerck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
// no direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();


?>
<style type="text/css">
#new-modules-list li {
	border-bottom: 1px solid #CCCCCC;
    list-style: none outside none;
    padding: 5px;
}

#new-modules-list li a {
	text-decoration: none;
	color: #336699;
	font-size: 1.3em;
}

#new-modules-list li a:hover {
	color: #000;
}

#new-modules-list li small {
	display: block;
	clear: both;
}
</style>
<h2><?php echo JText::_('COM_MODULES_TYPE_CHOOSE')?></h2>
<ul id="new-modules-list" class="list list-striped">
<?php foreach ($this->items as &$item) : ?>
	<?php
		// Prepare variables for the link.
		$link = 'javascript:void(0)';
		// $link	= 'index.php?option=com_modules&task=module.add&eid='. $item->extension_id;
		$name	= $this->escape($item->name);
		$desc	= JHTML::_('string.truncate', ($this->escape($item->desc)), 200);
		$short_desc	= JHTML::_('string.truncate', ($this->escape($item->desc)), 90);
	?>
	<?php if ($document->direction != "rtl") : ?>
	<li>
		<a href="<?php echo JRoute::_($link);?>" onclick="ckAddModule('<?php echo $item->extension_id;?>', '<?php echo $item->module;?>');">
			<strong><?php echo $name; ?></strong>
		</a>
		<small class="hasPopover" data-placement="right" title="<?php echo $name; ?>" data-content="<?php echo $desc; ?>"><?php echo $short_desc; ?></small>
	</li>
	<?php else : ?>
	<li>
		<small rel="popover" data-placement="left" title="<?php echo $name; ?>" data-content="<?php echo $desc; ?>"><?php echo $short_desc; ?></small>
		<a href="<?php echo JRoute::_($link);?>">
			<strong><?php echo $name; ?></strong>
		</a>
	</li>
	<?php endif?>
<?php endforeach; ?>
</ul>
<div class="clr"></div>
