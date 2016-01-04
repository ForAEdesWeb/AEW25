<?php
/**
 * @name		Modules Manager CK
 * @package		com_templateck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_templateck/assets/css/templateck.css');
?>
<style>
	.aboutversion {
		margin: 10px;
		padding: 10px;
		font-size: 20px;
		font-color: #000;
	}

	.stepnumberck {
		background: #333;
		color: #e3e3e3;
		border-radius: 3px 0 0 3px;
		display: inline-block;
		padding: 5px;
		margin: 5px;
	}
</style>
<div class="aboutversion"><img src="../components/com_modulesmanagerck/images/logo_modulesmanagerck_110.png" alt="modules manager joomla" style="margin-right:10px;"/><?php echo JText::_('COM_MODULESMANAGERCK_VERSION') . ' ' . $this->ckversion; ?></div>

<div><?php echo JText::_('COM_MODULESMANAGERCK_DESC'); ?></div>
<br />
<div>
	<span class="stepnumberck"><?php echo JText::_('J1'); ?></span><a href="index.php?option=com_plugins&view=plugins&filter_folder=system"><?php echo JText::_('CK_ACTIVATE_PLUGIN'); ?></a>
</div>
<div>
	<span class="stepnumberck"><?php echo JText::_('J2'); ?></span><?php echo JText::_('CK_LOGIN_FRONTEND'); ?>
</div>