<?php
/**
 * @version $Id: edit.php 211 2013-11-25 09:02:55Z michal $
 * @package DJ-Catalog2
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
 *
 * DJ-Catalog2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Catalog2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Catalog2. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$app = JFactory::getApplication();

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'category.cancel' || document.formvalidator.isValid(document.id('category-form'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task, document.getElementById('category-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_djcatalog2&view=category&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="category-form" class="form-validate" enctype="multipart/form-data">
	<div class="row-fluid">
		<div class="span12 form-horizontal">
			<fieldset>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#details" data-toggle="tab"><?php echo empty($this->item->id) ? JText::_('COM_DJCATALOG2_NEW') : JText::_('COM_DJCATALOG2_EDIT'); ?></a></li>
					<li>
						<a href="#publishing" data-toggle="tab"><?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING');?></a>
					</li>
					<li>
						<a href="#images" data-toggle="tab"><?php echo JText::_('COM_DJCATALOG2_IMAGES'); ?></a>
					</li>
					<?php $fieldSets = $this->form->getFieldsets('params'); ?>
					<?php foreach ($fieldSets as $name => $fieldSet) { ?>
						<li>
							<a href="#params-<?php echo $name; ?>" data-toggle="tab"><?php echo JText::_($fieldSet->label); ?></a>
						</li>
					<?php } ?>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="details">
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
						</div>
						
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
						</div>
						
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('parent_id'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('parent_id'); ?></div>
						</div>
						
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('published'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('published'); ?></div>
						</div>
						
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
						</div>
						
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('created'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('created'); ?></div>
						</div>
						
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
						</div>
						
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
						</div>
						
					</div>
					
					<div class="tab-pane" id="publishing">
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('metatitle'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('metatitle'); ?></div>
						</div>
						
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('metadesc'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('metadesc'); ?></div>
						</div>
						
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('metakey'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('metakey'); ?></div>
						</div>
					</div>
					
					<div class="tab-pane" id="images">
						<?php echo DJCatalog2ImageHelper::renderInput('category',$app->input->get('id', null, 'int'), (bool)JComponentHelper::getParams('com_djcatalog2')->get('multiple_image_upload', true)); ?>
					</div>
					<?php echo $this->loadTemplate('params'); ?>
				</div>
			</fieldset>
		</div>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>