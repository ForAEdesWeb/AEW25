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
 * Supports an HTML select list of CCK fields
 *
 * @package     Windwalker.Framework
 * @subpackage  Form.CCK
 */
class JFormFieldFields extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var string
	 */
	public $type = 'Fields';

	/**
	 * @var mixed
	 */
	public $value;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var JForm
	 */
	public $form;

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
		static $form_setted = false;
		static $form;

		$this->getValues();
		$this->addFieldJs();

		$element = $this->element;
		$class   = (string) $element['class'];
		$nolabel = (string) $element['nolabel'];
		$nolabel = ($nolabel == 'true' || $nolabel == '1') ? true : false;

		/** @var $cck \Windwalker\CCK\CCKEngine */
		$cck = $this->container->get('cck');
		$input = $this->container->get('input');

		// Get Field Form
		// =============================================================
		if (!$form_setted)
		{
			// ParseValue
			$data = $cck->parseAttrs($this->value);

			$type = $input->get('field_type', 'text');
			$form = null;

			// Loading form
			// =============================================================
			JForm::addFormPath(__DIR__ . '/../Resource/Form');
			$form = null;

			// Event
			JFactory::getApplication()
				->triggerEvent('onCCKEngineBeforeFormLoad', array(&$form, &$data, &$this, &$element, &$form_setted));

			$form = JForm::getInstance('fields', $type, array('control' => 'attrs'), false, false);

			// Event
			JFactory::getApplication()
				->triggerEvent('onCCKEngineAfterFormLoad', array(&$form, &$data, &$this, &$element, &$form_setted));

			$form->bind($data);

			// Set Default for Options
			$default = JArrayHelper::getValue($data, 'default');
			$input->set('field_default', $default, 'method', true);
			$form_setted = true;
		}

		$fieldset = (string) $element['fset'];
		$fieldset = $fieldset ? $fieldset : 'attrs';
		$fields   = $form->getFieldset($fieldset);

		$html = '<div class="' . $class . ' ak-cck-' . $fieldset . '">';

		foreach ($fields as $field)
		{
			if (!$nolabel)
			{
				$html .= '<div class="control-group">';
				$html .= '    <div class="control-label">' . $field->getLabel() . '</div>';
				$html .= '            <div class="controls">' . $field->getInput() . '</div>';
				$html .= '</div>';
			}
			else
			{
				$html .= '<div class="control-group">';
				$html .= $field->getInput();
				$html .= '</div>';
			}
		}

		$html .= '</div>';

		return $html;

	}

	/**
	 * Get values from session.
	 *
	 * @return  bool
	 */
	public function getValues()
	{
		if ($this->value)
		{
			return true;
		}

		$attrs = JFactory::getApplication()->getUserState("lib_windwalker.cck.fields", array());

		if ($attrs)
		{
			$this->value = json_encode($attrs);
		}

		$input = $this->container->get('input');

		// Retain data
		$retain = $input->get('retain', 0);

		if ($retain)
		{
			$this->value = json_encode($input->getRaw('attrs'));
		}
	}

	/**
	 * Add JS to head.
	 *
	 * @return  void
	 */
	public function addFieldJs()
	{
		JHtmlBehavior::framework(true);

		$asset = $this->container->get('helper.asset');
		$input = $this->container->get('input');

		$asset->addJS('cck/fields.js', $input->get('option'));
	}
}