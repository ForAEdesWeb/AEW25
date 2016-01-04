<?php
/**
 * @name		Modules Manager CK
 * @package		com_modulesmanagerck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;
$doc   = JFactory::getDocument();

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('bootstrap.framework');
JHtmlBootstrap::loadCss($includeMaincss = true, $doc->direction);
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.combobox');

$lang	= JFactory::getLanguage();
$lang->load('com_modulesmanagerck');
$lang->load('com_modules',JPATH_ADMINISTRATOR);
$lang->load('',JPATH_ADMINISTRATOR);

$hasContent = empty($this->item->module) || $this->item->module == 'custom' || $this->item->module == 'mod_custom';

// Get Params Fieldsets
$this->fieldsets = $this->form->getFieldsets('params');


$script = "cksubmitbutton = function(task)
	{
		if (task == 'module.cancel') {
				window.parent.ckCloseModuleOptions();
				// ckCloseModuleOptions();
			} else if (document.formvalidator.isValid(document.id('module-form'))) {";
if ($hasContent)
{
	$script .= $this->form->getField('content')->save();
}
$script .= "	Joomla.submitform(task, document.getElementById('module-form'));
				// if (self != top)
				// {
					// window.top.setTimeout('window.parent.SqueezeBox.close()', 1000);
				// }
			}
	}";

JFactory::getDocument()->addScriptDeclaration($script);
//echo the top toolbar in modal popup
//echo JToolbar::getInstance('toolbar')->render('toolbar');

?>
<style>
html, body {
    height: inherit;
	padding-top: 0;
}

.radio.btn-group input[type="radio"] {
    display: block;
}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_modulesmanagerck&view=module&layout=message&tmpl=component&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="module-form" class="form-validate form-horizontal">
	<fieldset>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('JDETAILS'); ?></a></li>
			<li><a href="#options" data-toggle="tab"><?php echo JText::_('JOPTIONS'); ?></a></li>

			<?php if ($hasContent) : ?>
				<li><a href="#custom" data-toggle="tab"><?php echo JText::_('COM_MODULES_CUSTOM_OUTPUT'); ?></a></li>
			<?php endif; ?>
			<?php if ($this->item->client_id == 0) : ?>
				<li><a href="#assignment" data-toggle="tab"><?php echo JText::_('COM_MODULES_MENU_ASSIGNMENT'); ?></a></li>
			<?php endif; ?>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="details">
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('showtitle'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('showtitle'); ?>
							</div>
						</div>
						<hr />
						<?php if ((string) $this->item->xml->name != 'Login Form') : ?>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('published'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('published'); ?>
								</div>
							</div>
						<?php endif; ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('access'); ?>
							</div>
						<div class="controls">
								<?php echo $this->form->getInput('access'); ?>
							</div>
						</div>
						<?php if ((string) $this->item->xml->name != 'Login Form') : ?>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('publish_up'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('publish_up'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('publish_down'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('publish_down'); ?>
								</div>
							</div>
						<?php endif; ?>

						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('language'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('language'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('note'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('note'); ?>
							</div>
						</div>
					</div>
					<div class="span6">
						<?php if ($this->item->xml) : ?>
							<?php if ($text = trim($this->item->xml->description)) : ?>
								<blockquote>
									<h4>
										<?php echo JText::_('COM_MODULES_MODULE_DESCRIPTION'); ?>
										<?php if ($this->item->id) : ?>
											<span class="label label-info"><?php echo JText::_('JGRID_HEADING_ID'); ?> : <?php echo $this->item->id; ?></span>
										<?php endif; ?>
									</h4>
									<hr />
									<div>
										<?php echo JText::_($text); ?>
									</div>
									<hr />
									<div>
										<span class="label"><?php echo $this->item->client_id == 0 ? JText::_('JSITE') : JText::_('JADMINISTRATOR'); ?></span> / <span class="label"><?php if ($this->item->xml) echo ($text = (string) $this->item->xml->name) ? JText::_($text) : $this->item->module;else echo JText::_('COM_MODULES_ERR_XML');?></span>
									</div>
								</blockquote>
							<?php endif; ?>
						<?php else : ?>
							<div class="alert alert-error"><?php echo JText::_('COM_MODULES_ERR_XML'); ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="options">
				<?php echo $this->loadTemplate('j3_options'); ?>
			</div>

			<?php if ($hasContent) : ?>
			<div class="tab-pane" id="custom">
				<?php echo $this->form->getInput('content'); ?>
			</div>
			<?php endif; ?>
			<?php if ($this->item->client_id == 0) : ?>
				<div class="tab-pane" id="assignment">
					<?php echo $this->loadTemplate('j3_assignment'); ?>
				</div>
			<?php endif; ?>
		</div>

		<input id="jform_title" type="hidden" value="<?php echo $this->item->title ?>" name="jform[title]" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
		<?php echo JHtml::_('form.token'); ?>
		<?php echo $this->form->getInput('module'); ?>
		<?php echo $this->form->getInput('client_id'); ?>
	</fieldset>
</form>
