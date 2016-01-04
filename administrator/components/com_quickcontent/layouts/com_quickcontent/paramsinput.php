<?php
/**
 * Part of joomla3303 project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

$fieldSets = $displayData['fieldsets'];
$keys = $displayData['keys'];
$id = $displayData['id'];

echo JHtmlBootstrap::startAccordion('menu-sliders-' . $id, array('active' => $id . '-' . $keys[0] . '-options'));

foreach ($fieldSets as $name => $fieldSet):

	$label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_MENUS_' . $name . '_FIELDSET_LABEL';

	echo JHtmlBootstrap::addSlide('menu-sliders-' . $id, JText::_($label), $id . '-' . $name . '-options');

	if (isset($fieldSet->description) && trim($fieldSet->description))
	{
		echo '<p class="tip">' . $this->escape(JText::_($fieldSet->description)) . '</p>';
	}
?>
	<div class="clr"></div>
	<fieldset class="panelform">

		<?php foreach ($displayData['formParams']->$id->getFieldset($name) as $field) : ?>
			<div class="control-group">
				<span class="control-label">
					<?php echo $field->label; ?>
				</span>

				<div class="controls">
					<?php echo $field->input; ?>
				</div>
			</div>
		<?php endforeach; ?>

	</fieldset>

	<?php
	echo JHtmlBootstrap::endSlide();
endforeach;

echo JHtmlBootstrap::endAccordion();
