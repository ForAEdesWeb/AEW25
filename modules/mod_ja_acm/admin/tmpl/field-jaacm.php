<?php
// add css
$fields = $displayData['fields'];
$group_types = $displayData['group_types'];

// add button to toolbar
$bar = JToolbar::getInstance('toolbar');
// Add an apply button
$bar->appendButton('Standard', 'advanced', 'Advanced', 'advanced', false);
?>

<div class="ja-acm-admin joomla<?php echo substr(JVERSION, 0, 1) ?>">

	<div class="control-group jatools-header ">

		<div class="control-label">
			<label id="jatools-type-lbl" for="jatools-type" class="hasTip" title="<?php echo JText::_('MOD_JA_ACM_TYPE_DESC') ?>"><?php echo JText::_('MOD_JA_ACM_TYPE_LABEL') ?></label>
		</div>

		<div class="controls">
			<select id="jatools-type" name="jatools-type" class="required">
				<option value="" selected="selected"><?php echo JText::_('MOD_JA_ACM_LAYOUT_DEFAULT') ?></option>
				<?php foreach ($group_types as $tpl => $types) : ?>
					<optgroup id="jatools-type-<?php echo $tpl ?>" label="<?php if ($tpl=='_'): ?>---From Module---<?php else: ?>---From <?php echo $tpl ?> Template---<?php endif ?>">
						<?php foreach ($types as $type => $title): ?>
							<option value="<?php echo $tpl ?>:<?php echo $type ?>"><?php echo $title ?></option>
						<?php endforeach ?>
					</optgroup>
				<?php endforeach ?>
			</select>
		</div>

	</div>

	<?php foreach ($fields as $type => $fieldsets) : ?>
	<div id="jatools-<?php echo $type ?>" class="jatools-layout-config hide">
		<?php echo $fieldsets ?>
	</div>
	<?php endforeach ?>

</div>

<div id="acm-advanced-form" class="hide" title="<?php echo JText::_('MOD_JA_ACM_ADVANCED_FORM_TITLE') ?>">
	<textarea id="acm-advanced-input"></textarea>
</div>

<div id="acm-dialog-confirm" class="hide" title="<?php echo JText::_('MOD_JA_ACM_CONFIRM_DELETE_TITLE') ?>">
	<?php echo JText::_('MOD_JA_ACM_CONFIRM_DELETE_MSG') ?>
</div>

<script>
	jaToolsInit(jQuery);
</script>
