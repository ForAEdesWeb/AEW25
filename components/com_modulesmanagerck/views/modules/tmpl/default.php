<?php
/**
 * @name		Modules Manager CK
 * @package		com_modulesmanagerck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;

// JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
// JHtml::_('behavior.tooltip');
// JHtml::_('behavior.formvalidation');
// JHtml::_('behavior.combobox');
// JHtml::_('formbehavior.chosen', 'select');

$lang	= JFactory::getLanguage();
$lang->load('com_templateck');
$lang->load('com_modules',JPATH_ADMINISTRATOR);

// Add Stylesheets
// $doc   = JFactory::getDocument();
// $doc->addStyleSheet('administrator/templates/isis/css/template.css');

// Load optional rtl bootstrap css and bootstrap bugfixes
// JHtmlBootstrap::loadCss($includeMaincss = false, $doc->getDirection());
?>
<script>
// window.parent.cksuccessmessage();
window.parent.ckCloseModuleOptions();
</script>