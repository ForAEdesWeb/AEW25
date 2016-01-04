<?php
/**
 * @package     Windwalker.Framework
 * @subpackage  Form.CCK
 * @author      Simon Asika <asika32764@gmail.com>
 * @copyright   Copyright (C) 2013 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
use Windwalker\Helper\XmlHelper;

defined('_JEXEC') or die;

JForm::addFieldPath(__DIR__);
JFormHelper::loadFieldType('List');

/**
 * Supports an HTML select list of Fieldtype.
 *
 * @package     Windwalker.Framework
 * @subpackage  Form.CCK
 */
class JFormFieldFieldtype extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	public $type = 'Fieldtype';

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
	 * @return    string    The field input markup.
	 */
	public function getOptions()
	{
		//$this->value = JRequest::getVar('field_type') ;
		$this->setFieldData();

		$input = $this->container->get('input');

		if (!$this->value)
		{
			$this->value = XmlHelper::get($this->element, 'default');
		}

		$input->set('field_type', $this->value, 'method', true);

		$element = $this->element;

		$types = JFolder::files(__DIR__ . '/../Resource/Form');

		JFactory::getApplication()
			->triggerEvent('onCCKEnginePrepareFieldtypes', array(&$types, &$this, &$element));

		$options = array();

		foreach ($types as $type)
		{
			$type = str_replace('.xml', '', $type);

			if ($type == 'index.html')
			{
				continue;
			}

			$options[] = JHtml::_(
				'select.option', (string) $type,
				JText::_('LIB_WINDWALKER_FIELDTYPE_' . strtoupper($type))
			);
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

	/**
	 * If default value exists.
	 */
	public function setFieldData()
	{
		$input = $this->container->get('input');

		if (! $input->get('id'))
		{
			$app = JFactory::getApplication();
			$app->setUserState('lib_windwalker.cck.fields', null);
		}

		$type = $input->get('field_type');

		$this->value = $type ? : $this->value;
	}
}