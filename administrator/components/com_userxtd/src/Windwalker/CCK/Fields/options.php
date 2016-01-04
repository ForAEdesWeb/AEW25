<?php
/**
 * @package     Windwalker.Framework
 * @subpackage  Form.CCK
 * @author      Simon Asika <asika32764@gmail.com>
 * @copyright   Copyright (C) 2013 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JForm::addFieldPath(__DIR__);

/**
 * Supports an HTML grid for list option.
 *
 * @package     Windwalker.Framework
 * @subpackage  Form.CCK
 */
class JFormFieldOptions extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	public $type = 'Options';

	/**
	 * Property value.
	 *
	 * @var mixed
	 */
	public $value;

	/**
	 * Property name.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Property container.
	 *
	 * @var  \Windwalker\DI\Container
	 */
	protected $container = null;

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$input = \Windwalker\DI\Container::getInstance()->get('input');

		$this->container = \Windwalker\DI\Container::getInstance($input->get('option'));

		return parent::setup($element, $value, $group);
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	public function getInput()
	{
		$input   = $this->container->get('input');
		$element = $this->element;
		$doc     = JFactory::getDocument();
		$default = $input->get('field_default');

		// Is checkbox?
		$checkbox = \Windwalker\Helper\XmlHelper::get($this->element, 'ckeckbox');

		if ($checkbox == 'true' || $checkbox == '1')
		{
			$checkbox = true;
			$default  = explode(',', $default);
		}
		else
		{
			$checkbox = false;
		}

		// Set Default Vars
		$vars   = $this->value ? $this->value : array();
		$vars[] = array('text' => '', 'value' => '');

		// Prepare Grid
		$grid = new JGrid;

		$grid->setTableOptions(array('class' => 'adminlist table table-striped', 'id' => 'ak-attr-table'));
		$grid->setColumns(array('default', 'value', 'text', 'operate'));

		// Set TH
		$grid->addRow(array('class' => 'row1'));
		$grid->setRowCell('default', JText::_('LIB_WINDWALKER_ATTR_DEFAULT'));
		$grid->setRowCell('value', JText::_('LIB_WINDWALKER_ATTR_VALUE'));
		$grid->setRowCell('text', JText::_('LIB_WINDWALKER_ATTR_TEXT'));
		$grid->setRowCell('operate', JText::_('LIB_WINDWALKER_ATTR_OPERATE'));

		foreach ($vars as $key => $var)
		{
			$checked = '';

			if ($checkbox)
			{
				if (in_array($var['value'], $default))
				{
					$checked = 'checked';
				}
			}
			else
			{
				if ($var['value'] === $default)
				{
					$checked = 'checked';
				}
			}

			//Set Operate buttons
			$add_btn = '<a class="ak-delete-option btn" onclick="WindwalkerCCKList.addOption(this);"><i class="icon-save-new"></i></a>';

			$del_btn = '<a class="ak-delete-option btn" onclick="WindwalkerCCKList.deleteOption(this);"><i class="icon-delete"></i></a>';


			// Set TR
			$grid->addRow(array('class' => 'row' . $key % 2));

			// Set TDs
			if ($checkbox)
			{
				$grid->setRowCell('default', '<input type="checkbox" class="attr-default" id="option-' . $key . '" name="attrs[default][]" value="' . $var['value'] . '" ' . $checked . '/>');
			}
			else
			{
				$grid->setRowCell('default', '<input type="radio" class="attr-default" id="option-' . $key . '" name="attrs[default]" value="' . $var['value'] . '" ' . $checked . '/>');
			}

			$grid->setRowCell('value', '<input type="text" class="attr-value input-medium" name="attrs[options][value][]" value="' . $var['value'] . '" onfocus="WindwalkerCCKList.addAttrRow(this);" onblur="WindwalkerCCKList.setDefault(this)" />');
			$grid->setRowCell('text', '<input type="text" class="attr-text input-medium" name="attrs[options][text][]" value="' . $var['text'] . '" onfocus="WindwalkerCCKList.addAttrRow(this);" />');
			$grid->setRowCell('operate', $add_btn . $del_btn);
		}

		// Set Javascript
		$doc->addScriptDeclaration("\n\n var akfields_num = " . (count($vars) - 1) . ' ;');
		$this->addScript(count($vars) - 1);

		return (string) $grid;
	}

	/**
	 * Add JS to head.
	 */
	public function addScript($num = 0)
	{
		$script = <<<SCRIPT
;jQuery(document).ready(function($)
{
	window.WindwalkerCCKList.init({});
});
SCRIPT;

		$asset = $this->container->get('helper.asset');

		$asset->addJs('cck/list.js');
		$asset->internalJS($script);
	}
}
