<?php
/**
 * @copyright	Copyright (C) 2005 - 2010 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
if(version_compare(JVERSION,'3.0.0','>=')) {
    $css=array('dl.tabs {float:left;margin:10px 0 -1px 0;z-index:50;clear:both;}');
    $css[]='dl.tabs dt {float:left;padding:4px 10px;border:1px solid #ccc;margin-left:3px;background:#e9e9e9;color:#666;}';
    $css[]='dl.tabs dt.open {background:#fff;border-bottom:1px solid #fff;z-index:100;color:#000;}';
    $css[]='div.current {clear:both;border:1px solid #ccc;padding:10px 10px}';
    JFactory::getDocument()->addStyleDeclaration(implode("\n",$css));
    $version = 3;
}
?>
<table style="width:100%;">
<tr><td style="width:50%;vertical-align:top;"">
<form action="<?php echo JRoute::_('index.php?option=com_favicon'); ?>" name="adminForm" id="adminForm" method="post" enctype="multipart/form-data">
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="id" id="cfgname" value="" />
    <?php echo JHtml::_('form.token'); ?>
	<fieldset><legend><?php echo JText::_('COM_FAVICON_ADD_AN_IMAGE');?></legend>
		<?php echo JTEXT::_('COM_FAVICON_VALID_TYPES').': '.implode(", ",array_merge(array('ICO'),$this->model->getSupportedImageTypes()));?><br />
		<input type="file" name="ico_file">
                <button onclick="Joomla.submitbutton('favicon.addimage');return false;"><?php echo JText::_('COM_FAVICON_ADD_IMAGE');?></button><br />
		<label for="desired_bit_count"><?php echo JText::_('COM_FAVICON_FOR_UPLOADING_IMAGES_ONLY');?>:</label>
		<select name="desired_bit_count" id="desired_bit_count">
			<option value="1"><?php echo JText::_('COM_FAVICON_SELECT_LOWEST_POSSIBLE_BIT_COUNT');?></option>
			<option value="32"><?php echo JText::_('COM_FAVICON_RAISE_BIT_COUNT_TO_32_BITS');?></option>
			<option value="24"><?php echo JText::_('COM_FAVICON_RAISE_BIT_COUNT_TO_24_BITS');?></option>
			<option value="8"><?php echo JText::_('COM_FAVICON_RAISE_BIT_COUNT_TO_8_BITS');?></option>
			<option value="4"><?php echo JText::_('COM_FAVICON_RAISE_BIT_COUNT_TO_4_BITS');?></option>
		</select>
	</fieldset>
        <fieldset><legend><?php echo JText::_('COM_FAVICON_CURRENT_ICON_CONTENTS');?></legend>
	<?php
	if ($this->icon->countImages()) {
                echo '<button class="removebutton" onclick="Joomla.submitbutton(\'favicon.remove\');return false;">'.JText::_('COM_FAVICON_REMOVE_SELECTED_IMAGES').'</button>';
		// These are the generally supported bit counts:
		$supportedBitCounts = array(32, 24, 8, 4, 1);
		// These are the generally supported sizes:
		$standardSizes = array(128, 48, 32, 24, 16);

		// We will use that info to make a table:

		// I'm making an array of all the images called $nonStandard.  This will
		// be cleared out as each image is found to be standard size and bitcount.
		// What's left will be only nonStandard sizes that we can display at the end.
		$nonStandard = array();
		foreach (array_keys($this->icon->images) as $key) {
			$nonStandard[$key] = true;
		}

		?>
			<table class="icontable">
			<tr class="sizerow">
				<td></td>
				<?php
				// Printing labels:
				foreach ($standardSizes as $standardSize) {
					?>
					<th style="width:<?php echo $standardSize;?>px">
						<?=$standardSize?>x<?=$standardSize?>
					</th>
					<?php
				}
				?>
			</tr>
			<?php
			// Each table row:
			foreach ($supportedBitCounts as $supportedBitCount) {
				?>
				<tr class="bc<?php echo $supportedBitCount;?>">
					<th>
						<?php
						// Printing labels:
						?>
						<?php echo $supportedBitCount?> bit
					</th>
					<?php
					// Each table column:
					foreach ($standardSizes as $standardSize) {
						?>
						<td valign=top align=center>
							<?php
							// Check each image to see if it's this size/bitCount.
							foreach($this->icon->images as $key => $image) {
								$bitCount = $image->_entry["BitCount"]?$image->_entry["BitCount"]:$image->_header["BitCount"];
								if (
									$bitCount == $supportedBitCount and
									$image->_entry["Height"] == $standardSize and
									$image->_entry["Width"] == $standardSize
								) {
									// If the image fits here, print it and unset it from $nonStandard.
									echo '<div style="background-color: #FFFFFF" onMouseOver="this.style.backgroundColor=\'#316ac5\'" onMouseOut="this.style.backgroundColor=\'#FFFFFF\'"><input type="checkbox" name="remove_images[]" id="remove_image_'.$key.'" value="'.$key.'"><a href="javascript:void(document.getElementById(\'remove_image_'.$key.'\').click());"><img border=0 src="'.JURI::base(true).'/index.php?option=com_favicon&amp;task=favicon.image&amp;id=0&amp;key='.$key.'"></a></div>';
									unset($nonStandard[$key]);
								}
							}
							?>
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
		// Now, just spit out $nonStandard:
		if (count($nonStandard)) {
                    echo '<div class="nonstandard">';
                    echo '<h3>'.JText::_('COM_FAVICON_ICON_CONTAINS_NONSTANDARD_SIZES_BITCOUNTS').':</h3>';
                    echo '<button class="removebutton" onclick="Joomla.submitbutton(\'favicon.remove\');return false;">'.JText::_('COM_FAVICON_REMOVE_SELECTED_IMAGES').'</button>';
                    echo '<br style="clear:both" />';
                    foreach ($nonStandard as $key => $true) {
                        $image = $this->icon->images[$key];
                        echo '<div style="background-color: #FFFFFF" onMouseOver="this.style.backgroundColor=\'#316ac5\'" onMouseOut="this.style.backgroundColor=\'#FFFFFF\'"><input type="checkbox" name="remove_images[]" id="remove_image_'.$key.'" value="'.$key.'"><a href="javascript:void(document.getElementById(\'remove_image_'.$key.'\').click());\"><img border=0 src="'.JURI::base(true).'/index.php?option=com_favicon&amp;task=favicon.image&amp;id=0&amp;key='.$key.'"></a></div>';
                    }
                    echo '</div>';
                }
	} else {
		// If there are no images, say so.
		echo('<h3>'.JText::_('COM_FAVICON_CONTAINS_NO_IMAGES').'</h3>');
	}
	// Here's the form for uploading.
	?>
        </fieldset>
</td><td style="vertical-align:top;">
<?php if($this->iconid != 0): ?>
    <fieldset><legend><?php echo JText::_('COM_FAVICON_PUBLISH_TO_TEMPLATE');?></legend>
        <p><?php echo JText::_('COM_FAVICON_PUBLISH_TO_TEMPLATE_DESC');?></p>
        <?php if($this->model->getTemplate()):?>
        <label for="backup_favicon" class="checkbox"><input name="backup_favicon" id="backup_favicon" type="checkbox" value="1" /><?php echo JText::_('COM_FAVICON_BACKUP_FAVICON');?></label><br style="clear:both;"/>
        <?php endif; ?>
        <button onclick="Joomla.submitbutton('favicon.template');return false;"><?php echo JText::_('COM_FAVICON_REPLACE_TEMPLATE_FAVICON');?></button>
    </fieldset>
    <fieldset><legend><?php echo JText::_('COM_FAVICON_INSTRUCTIONS');?></legend>
<?php endif;
    echo JHtml::_('tabs.start','config-tabs-favicon_instructions', array('useCookie'=>1));
    echo JHtml::_('tabs.panel',JText::_('COM_FAVICON_ADDING_IMAGES'), 'addingimages');
    echo JText::_('COM_FAVICON_ADDING_IMAGES_DESC');
    echo JHtml::_('tabs.panel',JText::_('COM_FAVICON_REMOVING_IMAGES'), 'removingimages');
    echo JText::_('COM_FAVICON_REMOVING_IMAGES_DESC');
    if($this->iconid != 0) {
        echo JHtml::_('tabs.panel',JText::_('COM_FAVICON_APPLYING'), 'applying');
        echo JText::_('COM_FAVICON_APPLYING_DESC');
        echo JHtml::_('tabs.panel',JText::_('COM_FAVICON_PUBLISHING'), 'publishing');
        echo JText::_('COM_FAVICON_PUBLISHING_DESC');
    } else {
        echo JHtml::_('tabs.panel',JText::_('COM_FAVICON_SAVING'), 'saving');
        echo JText::_('COM_FAVICON_SAVING_DESC');
    }
    echo JHtml::_('tabs.panel',JText::_('COM_FAVICON_SIZES'), 'sizes');
    echo JText::_('COM_FAVICON_SIZES_DESC');
    echo JHtml::_('tabs.panel',JText::_('COM_FAVICON_MORE_INFO'), 'moreinfo');
    echo JText::_('COM_FAVICON_MORE_INFO_DESC');
    echo'<p><a class="modal" rel="{handler: \'iframe\', size: {x:600, y:400}}" href="'.JURI::base(true).'/components/com_favicon/helpers/floiconlicense.txt">'.JText::_('COM_FAVICON_LICENSELINK_TEXT').'</a></p>';
    echo JHtml::_('tabs.end'); 
?>
    </fieldset>
</td></tr></table>
</form>
