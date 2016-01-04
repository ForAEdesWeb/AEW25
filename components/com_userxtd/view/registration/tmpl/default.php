<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

JHtmlBehavior::framework(true);
JHtmlBehavior::formvalidation();

$doc = JFactory::getDocument();
$params	= $data->params;
$user   = JFactory::getUser();
$uri    = JUri::getInstance();

$fieldsets = $data->form->getFieldsets();
?>

<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		var v = document.formvalidator;
		var validate = v.validate;
		v.parentValidate = validate;
		v.scroll = 0;

		v.validate = function(el)
		{
			var result = this.parentValidate(el);

			if( result == false)
			{
				if(this.scroll == 0)
				{
					// @TODO: Decouple mootools
					var sc = new Fx.Scroll(document).toElement(el);

					this.scroll = 1;
				}
			}

			return result;
		};


		$('#adminForm').on('submit', function(e)
		{
			if(!v.isValid($('#adminForm')))
			{
				v.scroll = 0;

				e.preventDefault();
			}
		});
	});

</script>

<form action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post"
	name="adminForm" id="adminForm" class="form-horizontal" enctype="multipart/form-data">

	<div id="userxtd-wrap" class="container-fluid registration">
		<div id="userxtd-wrap-inner">

			<div class="registration">
				<div class="registration-inner row-fluid">

					<?php foreach( $fieldsets as $fieldset ): ?>

						<fieldset>
							<legend>
								<?php echo JText::_($fieldset->label); ?>
							</legend>

							<?php
							$fields = $data->form->getFieldset($fieldset->name) ;
							?>

							<?php foreach( $fields as $field ): ?>
								<div class="control-group">
									<div class="control-label">
										<?php echo $field->label; ?>
									</div>
									<div class="controls">
										<?php echo $field->input; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</fieldset>
					<?php endforeach; ?>

				</div>
			</div>

		</div>
	</div>

	<div class="form-actions">
		<button type="submit" class="btn btn-primary button">
			<?php echo JText::_('JREGISTER');?>
		</button>

		<a class="btn button" href="<?php echo JUri::root();?>" title="<?php echo JText::_('JCANCEL');?>">
			<?php echo JText::_('JCANCEL');?>
		</a>

		<input type="hidden" name="task" value="registration.register" />
		<input type="hidden" name="return" value="<?php echo base64_encode($uri->toString()); ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
