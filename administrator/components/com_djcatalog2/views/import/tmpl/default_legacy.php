<?php
/**
 * @version $Id: default_legacy.php 143 2013-10-02 14:36:44Z michal $
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
JHtml::_('behavior.calendar');

$producers_first_option = new stdClass();
$producers_first_option->id = '';
$producers_first_option->name = '- '.JText::_('JNONE').' -';
$producers_first_option->published = null;
$producers = count($this->producers) ? array_merge(array($producers_first_option),$this->producers) : array($producers_first_option);

$groups_first_option = new stdClass();
$groups_first_option->id = '';
$groups_first_option->name = '- '.JText::_('JNONE').' -';
$groups_first_option->published = null;
$fieldgroups = count($this->fieldgroups) ? array_merge(array($groups_first_option),$this->fieldgroups) : array($groups_first_option);

$users = $this->users;

$user = JFactory::getUser();

?>

<form action="<?php echo JRoute::_('index.php?option=com_djcatalog2&view=import'); ?>" method="post" name="adminForm" id="items-import-form" class="form-validate" enctype="multipart/form-data">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_DJCATALOG2_ITEMS_IMPORT'); ?></legend>
			<ul class="adminformlist">
				<li>
					<label for="csvfile">
						<?php echo JText::_('COM_DJCATALOG2_CSV_FILE'); ?>
					</label>
					<input type="file" name="csvfile" id="csvfile-items" value="" />
				</li>
				<li>
					<label for="i_enclosure">
						<?php echo JText::_('COM_DJCATALOG2_CSV_ENCLOSURE'); ?>
					</label>
					<select name="enclosure" id="i_enclosure">
						<option value="0"><?php echo htmlspecialchars("\""); ?></option>
						<option value="1"><?php echo htmlspecialchars("'"); ?></option>
					</select>
				</li>
				<li>
					<label for="i_separator">
						<?php echo JText::_('COM_DJCATALOG2_CSV_SEPARATOR'); ?>
					</label>
					<select name="separator" id="i_separator">
						<option value="0"><?php echo htmlspecialchars(","); ?></option>
						<option value="1"><?php echo htmlspecialchars(";"); ?></option>
					</select>
				</li>
				<li>
					<label for="i_cat_id">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_CATEGORY'); ?>
					</label>
					<?php echo JHTML::_('select.genericlist', $this->categories, 'cat_id', 'class="inputbox"', 'value', 'text', 0, 'i_cat_id'); ?>
				</li>
				<li>
					<label for="i_producer_id">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_PRODUCER'); ?>
					</label>
					<?php 
						echo JHTML::_('select.genericlist', $producers, 'producer_id', 'class="inputbox"', 'id', 'name', 0, 'i_producer_id');
					?>
				</li>
				<li>
					<label for="i_group_id">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_FIELD_GROUP'); ?>
					</label>
					<?php 
						echo JHTML::_('select.genericlist', $fieldgroups, 'group_id', 'class="inputbox"', 'id', 'name', 0, 'i_group_id');
					?>
				</li>
				<li>
					<label for="i_published">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_STATE'); ?>
					</label>
					<select name="published" id="i_published">
						<option value="0"><?php echo JText::_('JUNPUBLISHED'); ?></option>
						<option value="1"><?php echo JText::_('JPUBLISHED'); ?></option>
					</select>
				</li>
				<li>
					<label for="i_price">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_PRICE'); ?>
					</label>
					<input type="text" class="inputbox" name="price" id="i_price" value="0.00" />
				</li>
				<li>
					<label for="i_special_price">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_SPECIAL_PRICE'); ?>
					</label>
					<input type="text" class="inputbox" name="special_price" id="i_special_price" value="0.00" />
				</li>
				<li>
					<label for="i_created_by">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_AUTHOR'); ?>
					</label>
					<?php 
						echo JHTML::_('select.genericlist', $users, 'created_by', 'class="inputbox"', 'id', 'name', $user->id, 'i_created_by');
					?>
				</li>
				<li>
					<label for="i_created">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_CREATED_DATE'); ?>
					</label>
					<?php echo JHtml::_('calendar', strftime('%Y-%m-%d'), 'created', 'i_created', '%Y-%m-%d %H:%M:%S'); ?>
				</li>
				<li>
					<div class="clr"></div>
					<div class="button2-left">
						<div class="blank">
							<a href="#" onclick="javascript:Joomla.submitbutton('items.import')">
								<?php echo JText::_('COM_DJCATALOG2_IMPORT_BUTTON'); ?>
							</a>
						</div>
					</div>
				</li>
			</ul>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<form action="<?php echo JRoute::_('index.php?option=com_djcatalog2&view=import'); ?>" method="post" name="adminForm" id="categories-import-form" class="form-validate" enctype="multipart/form-data">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_DJCATALOG2_CATEGORIES_IMPORT'); ?></legend>
			<ul class="adminformlist">
				<li>
					<label for="csvfile">
						<?php echo JText::_('COM_DJCATALOG2_CSV_FILE'); ?>
					</label>
					<input type="file" name="csvfile" id="csvfile-categories" value="" />
				</li>
				<li>
					<label for="c_enclosure">
						<?php echo JText::_('COM_DJCATALOG2_CSV_ENCLOSURE'); ?>
					</label>
					<select name="enclosure" id="c_enclosure">
						<option value="0"><?php echo htmlspecialchars("\""); ?></option>
						<option value="1"><?php echo htmlspecialchars("'"); ?></option>
					</select>
				</li>
				<li>
					<label for="c_separator">
						<?php echo JText::_('COM_DJCATALOG2_CSV_SEPARATOR'); ?>
					</label>
					<select name="separator" id="c_separator">
						<option value="0"><?php echo htmlspecialchars(","); ?></option>
						<option value="1"><?php echo htmlspecialchars(";"); ?></option>
					</select>
				</li>
				<li>
					<label for="c_parent_id">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_PARENT'); ?>
					</label>
					<?php echo JHTML::_('select.genericlist', $this->categories, 'parent_id', 'class="inputbox"', 'value', 'text', 0, 'c_parent_id'); ?>
				</li>
				<li>
					<label for="c_published">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_STATE'); ?>
					</label>
					<select name="published" id="c_published">
						<option value="0"><?php echo JText::_('JUNPUBLISHED'); ?></option>
						<option value="1"><?php echo JText::_('JPUBLISHED'); ?></option>
					</select>
				</li>
				<li>
					<label for="c_created_by">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_AUTHOR'); ?>
					</label>
					<?php 
						echo JHTML::_('select.genericlist', $users, 'created_by', 'class="inputbox"', 'id', 'name', $user->id , 'c_created_by');
					?>
				</li>
				<li>
					<label for="c_created">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_CREATED_DATE'); ?>
					</label>
					<?php echo JHtml::_('calendar', strftime('%Y-%m-%d'), 'created', 'c_created', '%Y-%m-%d %H:%M:%S'); ?>
				</li>
				<li>
					<div class="clr"></div>
					<div class="button2-left">
						<div class="blank">
							<a href="#" onclick="javascript:Joomla.submitbutton('categories.import')">
								<?php echo JText::_('COM_DJCATALOG2_IMPORT_BUTTON'); ?>
							</a>
						</div>
					</div>
				</li>
			</ul>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<form action="<?php echo JRoute::_('index.php?option=com_djcatalog2&view=import'); ?>" method="post" name="adminForm" id="producers-import-form" class="form-validate" enctype="multipart/form-data">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_DJCATALOG2_PRODUCERS_IMPORT'); ?></legend>
			<ul class="adminformlist">
				<li>
					<label for="csvfile">
						<?php echo JText::_('COM_DJCATALOG2_CSV_FILE'); ?>
					</label>
					<input type="file" name="csvfile" id="csvfile-producers" value="" />
				</li>
				<li>
					<label for="p_enclosure">
						<?php echo JText::_('COM_DJCATALOG2_CSV_ENCLOSURE'); ?>
					</label>
					<select name="enclosure" id="p_enclosure">
						<option value="0"><?php echo htmlspecialchars("\""); ?></option>
						<option value="1"><?php echo htmlspecialchars("'"); ?></option>
					</select>
				</li>
				<li>
					<label for="ipseparator">
						<?php echo JText::_('COM_DJCATALOG2_CSV_SEPARATOR'); ?>
					</label>
					<select name="separator" id="p_separator">
						<option value="0"><?php echo htmlspecialchars(","); ?></option>
						<option value="1"><?php echo htmlspecialchars(";"); ?></option>
					</select>
				</li>
				<li>
					<label for="p_published">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_STATE'); ?>
					</label>
					<select name="published" id="p_published">
						<option value="0"><?php echo JText::_('JUNPUBLISHED'); ?></option>
						<option value="1"><?php echo JText::_('JPUBLISHED'); ?></option>
					</select>
				</li>
				<li>
					<label for="p_created_by">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_AUTHOR'); ?>
					</label>
					<?php 
						echo JHTML::_('select.genericlist', $users, 'created_by', 'class="inputbox"', 'id', 'name', $user->id, 'p_created_by');
					?>
				</li>
				<li>
					<label for="p_created">
						<?php echo JText::_('COM_DJCATALOG2_IMPORT_DEFAULT_CREATED_DATE'); ?>
					</label>
					<?php echo JHtml::_('calendar',  strftime('%Y-%m-%d'), 'created', 'c_created', '%Y-%m-%d %H:%M:%S'); ?>
				</li>
				<li>
					<div class="clr"></div>
					<div class="button2-left">
						<div class="blank">
							<a href="#" onclick="javascript:Joomla.submitbutton('producers.import')">
								<?php echo JText::_('COM_DJCATALOG2_IMPORT_BUTTON'); ?>
							</a>
						</div>
					</div>
				</li>
			</ul>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'items.import' && document.getElementById('csvfile-items').value != '') {
			Joomla.submitform(task, document.getElementById('items-import-form'));
		}
		else if (task == 'producers.import' && document.getElementById('csvfile-producers').value != '') {
			Joomla.submitform(task, document.getElementById('producers-import-form'));
		}
		else if (task == 'categories.import' && document.getElementById('csvfile-categories').value != '') {
			Joomla.submitform(task, document.getElementById('categories-import-form'));
		}
	}
</script>