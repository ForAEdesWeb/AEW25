<?php
/**
 * @version $Id: files.php 218 2014-01-16 08:57:21Z michal $
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
defined('_JEXEC') or die();

JHtml::_('behavior.framework');

$app = JFactory::getApplication();

$id = $app->input->get('id');

if (!$id) {
	JError::raiseError(404);
}

$plugin = $app->input->get('extension');

$lang = JFactory::getLanguage();
if ($plugin) {
	$lang = JFactory::getLanguage();
	$plugin_parts = explode('_', $plugin, 3);
	if (count($plugin_parts) == 3) {
		$lang->load($plugin, JPATH_ROOT.DS.'plugins'.DS.$plugin_parts[1].DS.$plugin_parts[2], null, true, false);
		$lang->load($plugin, JPATH_ROOT.DS.'plugins'.DS.$plugin_parts[1].DS.$plugin_parts[2], 'en-GB', false, false);
	}
	$lang->load($plugin, JPATH_ADMINISTRATOR, null, true, false);
	$lang->load($plugin, JPATH_ADMINISTRATOR, 'en-GB', false, false);
}

$db = JFactory::getDbo();
$db->setQuery('select id, caption as text from #__djc2_files where type='.$db->quote('item').' and item_id='.(int)$id.' order by ordering asc');
$files = $db->loadObjectlist('id');

if (empty($files)) {
	echo JText::_('PLG_EDITORS-XTD_DJCATALOG2FILE_ERROR_FILES_MISSING');
} else {
	require_once (str_replace('/',DIRECTORY_SEPARATOR, JPATH_ROOT.'/components/com_djcatalog2/helpers/route.php'));
	
	$file_selector = JHTML::_('select.genericlist', $files, 'file_id', 'class="inputbox input input-medium"', 'id', 'text', null, 'djcatalog2file_selector');
	
	?>
	<div>
		<fieldset class="adminform">
			<legend><?php echo JText::_('PLG_EDITORS-XTD_DJCATALOG2FILE_LEGEND') ?></legend>
			<div class="control-group">
				<div class="control-label">
					<label>
					<?php echo JText::_('PLG_EDITORS-XTD_DJCATALOG2FILE_LABEL') ?>: 
					</label>
				</div>
				<div class="controls">
					<?php echo $file_selector ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<label>
					<?php echo JText::_('PLG_EDITORS-XTD_DJCATALOG2FILELABEL_LABEL') ?>: 
					</label>
				</div>
				<div class="controls">
					<input type="text" value="" id="djcatalog2file_label" class="inputbox input-medium input" />
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<label>
					<?php echo JText::_('PLG_EDITORS-XTD_DJCATALOG2FILECLASSNAME_LABEL') ?>: 
					</label>
				</div>
				<div class="controls">
					<input type="text" value="button btn" id="djcatalog2file_classname" class="inputbox input-medium input" />
				</div>
			</div>
			<div class="control-group">
				<span class="faux-label"></span>
				<div class="controls">
					<button class="button btn" id="djcatalog2file_attach_button" onclick="DJCatalog2FileAttach();"><?php echo JText::_('PLG_EDITORS-XTD_DJCATALOG2FILE_ATTACHBUTTON') ?></button>
				</div>
			</div>
		</fieldset>
		<script type="text/javascript">
			window.addEvent('domready', function(){
				this.djcatalog2file_selector = document.id('djcatalog2file_selector');
				this.djcatalog2file_label = document.id('djcatalog2file_label');
				this.djcatalog2file_classname = document.id('djcatalog2file_classname');
				
				djcatalog2file_label.value = djcatalog2file_selector.getSelected().get('text');
				
				djcatalog2file_selector.addEvent('change', function(evt){
					if (djcatalog2file_selector.value != '') {
						djcatalog2file_label.value = djcatalog2file_selector.getSelected().get('text');
					}
				});
				this.djcatalog2file_link = 'index.php?option=com_djcatalog2&amp;format=raw&amp;task=download&amp;fid=';
				
				this.DJCatalog2FileAttach= function() {
					if (window.parent) {
						var id = djcatalog2file_selector.value; 
						if (!id) return;

						var label = djcatalog2file_label.value; 

						if (label == '') {
							label = 'Download';
						}
						var link = '<a href="'+djcatalog2file_link+id+'" target="_blank" class="'+djcatalog2file_classname.value+'"><span>'+label+'</span></a>';
						window.parent.jInsertDJCatalog2Attachment(link);
					}
				}
			});
		</script>
	</div>
	<?php
}

