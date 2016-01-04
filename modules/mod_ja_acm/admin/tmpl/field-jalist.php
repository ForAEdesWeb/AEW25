<?php
$field = $displayData['field'];
$items = $displayData['items'];
$value = htmlspecialchars($field->value, ENT_COMPAT, 'UTF-8');
$id = $field->id;
$name = $field->name;
$label = JText::_($field->element['label']);
$desc = JText::_($field->element['description']);

$width = 90/count ($items);

$doc = JFactory::getDocument();
$doc->addScript(JURI::root(true) . '/modules/mod_ja_acm/admin/assets/jalist.js');
$doc->addStyleSheet(JURI::root(true) . '/modules/mod_ja_acm/admin/assets/jalist.css');
?>
<div class="jaacm-list <?php echo $id ?>">
	<h4><?php echo $label ?></h4>
	<p><?php echo $desc ?></p>
	<table class="jalist" width="100%">
		<thead>
			<tr>
				<?php foreach ($items as $item) :
					$title = (string) $item->element['title'];
					if (!$title) $title = (string) $item->element['label'];
					?>
					<th width="<?php echo $width ?>%">
						<?php echo JText::_($title) ?>
					</th>
				<?php endforeach ?>
				<th width="10%">&nbsp;</th>
			</tr>
		</thead>

		<tbody>
			<tr class="first">
				<?php foreach ($items as $item) : ?>
					<td>
						<?php echo $item->getInput() ?>
					</td>
				<?php endforeach ?>
				<td>
					<span class="btn action btn-clone" data-action="clone_row" title="Clone Row"><i class="fa fa-plus"></i></span>
					<span class="btn action btn-delete" data-action="delete_row" title="Delete Row"><i class="fa fa-minus"></i></span>
				</td>
			</tr>
		</tbody>

	</table>

	<input type="hidden" name="<?php echo $name ?>" value="<?php echo $value ?>" class="acm-object" />
</div>
<script>
	// jaFieldList(jQuery, '.<?php echo $id ?>');
	jQuery('.<?php echo $id ?>').jalist();
</script>