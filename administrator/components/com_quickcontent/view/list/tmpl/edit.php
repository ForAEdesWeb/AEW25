<?php
/**
 * Part of Component Quickcontent files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtmlBootstrap::tooltip();
JHtmlFormbehavior::chosen('select');
JHtmlBehavior::formvalidation();

/**
 * Prepare data for this template.
 *
 * @var $container Windwalker\DI\Container
 * @var $data      Windwalker\Data\Data
 * @var $item      \stdClass
 */
$container = $this->getContainer();
$form      = $data->form;
$item      = $data->item;

// Setting tabset
$tabs = array(
	'tab_basic',
	'tab_advanced',
	'tab_rules'
)
?>
<!-- Validate Script -->
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'list.edit.cancel' || document.formvalidator.isValid(document.id('adminForm')))
		{
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	}
</script>

<div id="quickcontent" class="windwalker list edit-form row-fluid">
	<form action="<?php echo JURI::getInstance(); ?>"  method="post" name="adminForm" id="adminForm"
		class="form-validate" enctype="multipart/form-data">

		<div class="row-fluid">
			<div class="span7">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_QUICKCONTENT_LEGEND_FORM'); ?></legend>

					<?php echo $data->form->renderField('id'); ?>
					<?php echo $data->form->renderField('title'); ?>

				</fieldset>
				<fieldset class="adminform">
					<div><?php echo $data->form->getLabel('content'); ?></div>
					<div><?php echo $data->form->getInput('content'); ?></div>

				</fieldset>
			</div>

			<div class="span5 form-horizontal">
				<!-- Tab Content -->

				<?php echo JHtmlBootstrap::startTabSet('quickcontent', array( 'active' => 'basic' )  ); ?>

				<?php echo JHtmlBootstrap::addTab('quickcontent', 'basic', JText::_('COM_QUICKCONTENT_FORM_OPTION_BASIC')); ?>


				<?php echo JHtmlBootstrap::startAccordion('basic-slides', array('active' => 'basic-basic-options')); ?>
				<?php echo JHtmlBootstrap::addSlide('basic-slides', JText::_('COM_QUICKCONTENT_FORM_OPTION_BASIC') , 'basic-basic-options'); ?>
				<fieldset class="panelform">
					<div class="control-group">
						<?php echo $data->form->getLabel('menutype'); ?>
						<div class="controls">
							<?php echo $data->form->getInput('menutype'); ?>
						</div>
					</div>

					<div class="control-group">
						<?php echo $data->form->getLabel('delete_existing'); ?>
						<div class="controls">
							<?php echo $data->form->getInput('delete_existing'); ?>
						</div>
					</div>

					<div class="control-group">
						<?php echo $data->form->getLabel('category_menutype'); ?>
						<div class="controls">
							<?php echo $data->form->getInput('category_menutype'); ?>
						</div>
					</div>

				</fieldset>
				<?php echo JHtmlBootstrap::endSlide(); ?>
				<?php echo JHtmlBootstrap::endAccordion(); ?>


				<?php echo JHtmlBootstrap::endTab(); ?>


				<?php echo JHtmlBootstrap::addTab('quickcontent', 'category-list', JText::_('COM_QUICKCONTENT_FORM_OPTION_LIST')); ?>

				<!-- CATEGORY LIST PARAMS -->
				<?php echo $data->listParams; ?>
				<!-- CATEGORY LIST PARAMS -->

				<?php echo JHtmlBootstrap::endTab(); ?>


				<?php echo JHtmlBootstrap::addTab('quickcontent', 'category-blog', JText::_('COM_QUICKCONTENT_FORM_OPTION_BLOG')); ?>

				<!-- CATEGORY BLOG PARAMS -->
				<?php echo $data->blogParams; ?>
				<!-- CATEGORY BLOG PARAMS -->
				<?php echo JHtmlBootstrap::endTab(); ?>


				<?php echo JHtmlBootstrap::addTab('quickcontent', 'article', JText::_('COM_QUICKCONTENT_FORM_OPTION_ARTICLE')); ?>

				<!-- CONTENT PARAMS -->
				<?php echo $data->articleParams; ?>
				<!-- CONTENT PARAMS -->

				<?php echo JHtmlBootstrap::endTab(); ?>

				<?php echo JHtmlBootstrap::endTabSet(); ?>
			</div>
		</div>

		<!-- Hidden Inputs -->
		<div id="hidden-inputs">
			<input type="hidden" name="option" value="com_quickcontent" />
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>

