<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

JHtmlBehavior::formvalidation();
JHtmlBehavior::keepalive();
JHtmlBootstrap::tooltip();

$data = $this->data;
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'user.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
			Joomla.submitform(task, document.getElementById('user-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}

	window.addEvent('domready', function(){
		$$('#toolbar-apply a').addClass('btn btn-primary');
	});
</script>

<form action="<?php echo JRoute::_(JUri::getInstance()->toString()); ?>" method="post"
	name="adminForm" id="user-form" class="profile-edit form-validate form-horizontal" enctype="multipart/form-data">

	<div id="member-profile">

		<div class="top-toolbar text-right">
			<a type="submit" class="btn btn-primary" onclick="Joomla.submitbutton('user.edit.save')">
				<?php echo JText::_('JTOOLBAR_SAVE');?>
			</a>

			<a class="btn button" href="#" title="<?php echo JText::_('JCANCEL');?>" onclick="Joomla.submitbutton('user.edit.cancel')">
				<?php echo JText::_('JCANCEL');?>
			</a>
		</div>

		<?php foreach ($data->form->getFieldsets() as $group => $fieldset):// Iterate through the form fieldsets and display each one.?>
			<?php $fields = $data->form->getFieldset($group);?>
			<?php if (count($fields)):?>
				<fieldset>
					<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
						<legend><?php echo JText::_($fieldset->label); ?></legend>
					<?php endif;?>


					<?php foreach ($fields as $field):// Iterate through the fields in the set and display them.?>
						<?php if ($field->hidden):// If the field is hidden, just display the input.?>
							<div class="control-group">
								<div class="controls">
									<?php echo $field->input;?>
								</div>
							</div>
						<?php else:?>
							<div class="control-group">
								<div class="control-label">
									<?php echo $field->label; ?>
									<?php if (!$field->required && $field->type != 'Spacer') : ?>
										<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL'); ?></span>
									<?php endif; ?>
								</div>
								<div class="controls">
									<?php echo $field->input; ?>
								</div>
							</div>
						<?php endif;?>
					<?php endforeach;?>

				</fieldset>
			<?php endif;?>
		<?php endforeach;?>

		<div class="form-actions">
			<a type="submit" class="btn btn-primary" onclick="Joomla.submitbutton('user.edit.save')">
				<?php echo JText::_('JTOOLBAR_SAVE');?>
			</a>

			<a class="btn button" href="#" title="<?php echo JText::_('JCANCEL');?>" onclick="Joomla.submitbutton('user.edit.cancel')">
				<?php echo JText::_('JCANCEL');?>
			</a>
		</div>
	</div>

	<!-- Hidden Inputs -->
	<div id="hidden-inputs">
		<input type="hidden" name="return" value="<?php echo JRequest::getVar('return') ; ?>" />
		<input type="hidden" name="jform[id]" value="<?php echo $data->state->get('user.id');?>" />
		<input type="hidden" name="option" value="com_userxtd" />
		<input type="hidden" name="task" value="user.save" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<div class="clr"></div>
</form>
