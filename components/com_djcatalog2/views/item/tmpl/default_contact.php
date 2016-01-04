<?php
/**
 * @version $Id: default_contact.php 272 2014-05-21 10:25:49Z michal $
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

defined ('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');

if (isset($this->error)) { ?>
	<div class="djc_contact-error">
		<?php echo $this->error; ?>
	</div>
<?php } ?>

<div class="djc_contact_form">
	<form id="djc_contact_form" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate">
		<fieldset>
			<legend><?php echo JText::_('COM_DJCATALOG2_FORM_LABEL'); ?></legend>
				<div class="control-group">
					<div class="control-label"><?php echo $this->contactform->getLabel('contact_name'); ?></div>
					<div class="controls"><?php echo $this->contactform->getInput('contact_name'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->contactform->getLabel('contact_email'); ?></div>
					<div class="controls"><?php echo $this->contactform->getInput('contact_email'); ?></div>
				</div>
				
				<?php if ((int)$this->params->get('contact_company_name_field', '0') > 0) { ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->contactform->getLabel('contact_company_name'); ?></div>
						<div class="controls"><?php echo $this->contactform->getInput('contact_company_name'); ?></div>
					</div>
				<?php } ?>
				<?php if ((int)$this->params->get('contact_street_field', '0') > 0) { ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->contactform->getLabel('contact_street'); ?></div>
						<div class="controls"><?php echo $this->contactform->getInput('contact_street'); ?></div>
					</div>
				<?php } ?>
				<?php if ((int)$this->params->get('contact_city_field', '0') > 0) { ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->contactform->getLabel('contact_city'); ?></div>
						<div class="controls"><?php echo $this->contactform->getInput('contact_city'); ?></div>
					</div>
				<?php } ?>
				<?php if ((int)$this->params->get('contact_zip_field', '0') > 0) { ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->contactform->getLabel('contact_zip'); ?></div>
						<div class="controls"><?php echo $this->contactform->getInput('contact_zip'); ?></div>
					</div>
				<?php } ?>
				<?php if ((int)$this->params->get('contact_country_field', '0') > 0) { ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->contactform->getLabel('contact_country'); ?></div>
						<div class="controls"><?php echo $this->contactform->getInput('contact_country'); ?></div>
					</div>
				<?php } ?>
				<?php if ((int)$this->params->get('contact_phone_field', '0') > 0) { ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->contactform->getLabel('contact_phone'); ?></div>
						<div class="controls"><?php echo $this->contactform->getInput('contact_phone'); ?></div>
					</div>
				<?php } ?>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->contactform->getLabel('contact_subject'); ?></div>
					<div class="controls"><?php echo $this->contactform->getInput('contact_subject'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->contactform->getLabel('contact_message'); ?></div>
					<div class="controls"><?php echo $this->contactform->getInput('contact_message'); ?></div>
				</div>
				<?php if ((int)$this->params->get('contact_copy_field', '1') > 0) { ?>
				<div class="control-group">
					<div class="control-label"><?php echo $this->contactform->getLabel('contact_email_copy'); ?></div>
					<div class="controls"><?php echo $this->contactform->getInput('contact_email_copy'); ?></div>
				</div>
				<?php } ?>
			<?php //Dynamically load any additional fields from plugins. ?>
			     <?php foreach ($this->contactform->getFieldsets() as $fieldset): ?>
			          <?php if ($fieldset->name != 'contact'):?>
			               <?php $fields = $this->contactform->getFieldset($fieldset->name);?>
			               <?php foreach($fields as $field): ?>
			                    <?php if ($field->hidden): ?>
			                         <?php echo $field->input;?>
			                    <?php else:?>
			                    	<div class="control-group">
			                         <div class="control-label">
			                            <?php echo $field->label; ?>
			                            <?php if (!$field->required && $field->type != "Spacer"): ?>
			                               <span class="optional"><?php echo JText::_('COM_DJCATALOG2_OPTIONAL');?></span>
			                            <?php endif; ?>
			                         </div>
			                         <div class="controls"><?php echo $field->input;?></div>
			                         </div>
			                    <?php endif;?>
			               <?php endforeach;?>
			          <?php endif ?>
			     <?php endforeach;?>
				<div class="controls">
					<button class="btn button validate" type="submit"><?php echo JText::_('COM_DJCATALOG2_CONTACT_SEND'); ?></button>
					<button id="djc_contact_form_button_close" class="btn button"><?php echo JText::_('COM_DJCATALOG2_CONTACT_FORM_CLOSE')?></button>
					<input type="hidden" name="option" value="com_djcatalog2" />
					<input type="hidden" name="task" value="item.contact" />
					<input type="hidden" name="id" value="<?php echo $this->item->slug; ?>" />
					<?php echo JHtml::_( 'form.token' ); ?>
				</div>
		</fieldset>
	</form>
</div>
