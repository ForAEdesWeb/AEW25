<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Windwalker\CCK;

use Windwalker\DI\Container;
use Windwalker\Helper\ArrayHelper;

// No direct access
defined('_JEXEC') or die;

/**
 * CCK Engine to handle fields and datas.
 *
 * @since 2.0
 */
class CCKEngine
{
	/**
	 * Property app.
	 *
	 * @var  \JApplicationCms
	 */
	protected $app = null;

	/**
	 * Property event.
	 *
	 * @var  \JEventDispatcher
	 */
	protected $event = null;

	/**
	 * Property container.
	 *
	 * @var  \Windwalker\DI\Container
	 */
	protected $container = null;

	/**
	 * Class init.
	 *
	 * @param \JApplicationCms         $app
	 * @param \JEventDispatcher        $event
	 * @param \Windwalker\DI\Container $container
	 */
	public function __construct(\JApplicationCms $app, \JEventDispatcher $event, Container $container)
	{
		$this->app   = $app;
		$this->event = $event;
		$this->container = $container;
	}

	/**
	 * Set element data into table format, called from JModelAdmin::perpareTable();
	 *
	 * @param    \JTable $table  The JTable object prepare save to DB.
	 * @param    array  $attrs  Field's attributes.
	 * @param    array  $option Some option.
	 */
	public function setFieldTable($table, $attrs = null, $option = array())
	{
		$field_type = $table->get('field_type', 'text');

		$input = $this->container->get('input');

		if (!is_array($attrs))
		{
			$attrs = $input->getVar('attrs', array());
		}

		$context = \JArrayHelper::getValue($option, 'context', 'lib_windwalker.field');

		$this->event->trigger('onCCKEngineBeforeSaveField', array($context, &$table, &$attrs, $option));

		// Check is table have all needed column
		// ==================================================================
		$this->checkTable($table, $context);

		// Filter Attrs
		// ==================================================================
		$attrs = $this->filterFields($field_type, $attrs);

		// Convert Name to uppercase and safe ID
		// ==================================================================
		$name = \JArrayHelper::getValue($attrs, 'name');
		$name = $this->regularizeName($name);
		$attrs['name'] = $name;

		// Set Name as Field ID
		if (isset($table->name))
		{
			$table->set('name', $name);
		}

		// Remove empty options
		// ==================================================================
		if (! empty($attrs['options']['value']))
		{
			foreach ($attrs['options']['value'] as $k => $val)
			{
				if (!$attrs['options']['value'][$k] && !$attrs['options']['text'][$k])
				{
					unset($attrs['options']['value'][$k]);
					unset($attrs['options']['text'][$k]);
				}
			}
		}

		// Build Element
		// ==================================================================
		$table->element  = $this->buildElement($field_type, $attrs, $option);
		$table->name     = $name;
		$table->label    = $attrs['label'];
		$table->attrs    = json_encode($attrs);
		$table->required = CCKHelper::isBool($attrs['required']);

		$this->event->trigger('onCCKEngineAfterSaveField', array($context, &$table, &$attrs, $option));
	}

	/**
	 * Convert a element data to JForm XML field string.
	 *
	 * @param    string $field_type Type of this field.
	 * @param    array  $attrs      Field's attributes.
	 * @param    array  $option     Some option.
	 *
	 * @return  string  Field XML element.
	 */
	public function buildElement($field_type = 'text', $attrs = null, $option = array())
	{
		$node_name  = !empty($option['node_name']) ? $option['node_name'] : 'field';
		$field_type = $field_type ? $field_type : 'text';
		$input = $this->container->get('input');

		$attrs = $this->convertJsonToArray($attrs);

		if (!is_array($attrs))
		{
			$attrs = $input->getVar('attrs', array());
		}

		$this->event->trigger('onCCKEngineBeforeBuildElement', array(\JArrayHelper::getValue($option, 'context'), &$field_type, &$attrs, $option));

		if (!is_array($attrs))
		{
			return '<' . $node_name . '/>';
		}

		// Rebuild options
		// ================================================================
		if (isset($attrs['options']))
		{
			$attrs['options'] = ArrayHelper::pivotBySort($attrs['options']);
		}

		$element = '';
		$options = array();

		// Build Field Attrs
		// ================================================================

		// set type in attrs
		$attrs['type'] = $field_type;

		// set default
		if (is_array(\JArrayHelper::getValue($attrs, 'default')))
		{
			$attrs['default'] = implode(',', $attrs['default']);
		}

		// start buliding attrs and options
		foreach ($attrs as $key => $attr)
		{
			if ($key == 'options' && is_array($attr))
			{
				// Bulid options
				foreach ($attr as $key => $opt)
				{
					if (!trim($opt['value']))
					{
						continue;
					}

					$value     = addslashes(trim($opt['value']));
					$text      = htmlspecialchars(addslashes(trim($opt['text'])));
					$options[] = "\t<option value=\"{$value}\">{$text}</option>\n";
				}
			}
			else
			{
				// Build attributes
				if (!trim($attr))
				{
					continue;
				}
				$attr = trim($attr);
				$attr = htmlspecialchars(addslashes($attr));
				$element .= "\t{$key}=\"{$attr}\"\n";
			}
		}

		// Build Element
		// ================================================================
		if (count($options) < 0)
		{
			$element = "<{$node_name}\n{$element}/>";
		}
		else
		{
			$options = implode('', $options);
			$element = "<{$node_name}\n{$element}>\n{$options}</{$node_name}>";
		}

		$this->event->trigger('onCCKEngineAfterBuildElement', array(\JArrayHelper::getValue($option, 'context'), &$field_type, &$element, $option));

		return $element;
	}

	/**
	 * Combine all field elements into one JForm XML with root.
	 *
	 * @param    array  $elements   An array contain all fields' XML element.
	 * @param    string $fieldset   Fieldset name.
	 * @param    string $fields     Field group name.
	 * @param    string $fset_label Fieldset label.
	 *
	 * @return   string    Form XML string.
	 */
	public function buildFormXML($elements = array(), $fieldset = 'fieldset', $fields = null, $fset_label = null)
	{
		if (!$fset_label)
		{
			$fset_label = $fieldset;
		}

		$context = null;
		$xml     = '';

		$this->event->trigger('onCCKEngineBeforeBuildFormXML', array($context, &$xml, $fieldset, $fields, $fset_label));

		$xml .= '<?xml version="1.0" encoding="utf-8"?><form>';
		$xml .= $fields ? '<fields name="' . $fields . '">' : '';
		$xml .= '<fieldset name="' . $fieldset . '" label="' . $fset_label . '">' . $elements . '</fieldset>';
		$xml .= $fields ? '</fields>' : '';
		$xml .= '</form>';

		$this->event->trigger('onCCKEngineAfterBuildFormXML', array($context, &$xml, $fieldset, $fields, $fset_label));

		return $xml;
	}

	/**
	 * Parse element data JSON format to array.
	 *
	 * @param    string $attrs A JSON string.
	 *
	 * @return   array     Attributes array.
	 */
	public function parseAttrs($attrs)
	{
		if (!$attrs)
		{
			return false;
		}

		$array = (array) json_decode($attrs, true);

		// Rebuild Options
		// ==================================================================
		if (isset($array['options']))
		{
			$array['options'] = ArrayHelper::pivotByKey($array['options']);
		}

		return $array;
	}

	/**
	 * Convert field XML element string to element data array.
	 *
	 * @param    string $element The field XML element string.
	 *
	 * @return    array    Attributes array.
	 */
	public function parseElement($element)
	{
		if (!$element)
		{
			return false;
		}

		$xml   = \JFactory::getXML($element, false);
		$attrs = $xml->attributes();

		$array = array();

		// Save Attrs
		// ================================================================
		foreach ($attrs as $key => $attr)
		{
			$array[$key] = (string) $attr;
		}

		// Save options
		// ================================================================
		$options = $xml->option;

		if ($options)
		{
			$i = 0;
			foreach ($options as $key => $option)
			{
				$array['options'][$i]['text']  = (string) $option;
				$array['options'][$i]['value'] = (string) $option['value'];
				$i++;
			}
		}

		return $array;
	}

	/**
	 * Convert a JSON element data to array, will not pivot datas.
	 *
	 * @param   mixed $data A JSON string, an array or an object.
	 *
	 * @return  array    Converted datas.
	 */
	public function convertJsonToArray($data)
	{
		if (is_string($data))
		{
			$data = json_decode($data, true);
		}

		return $data;
	}

	/**
	 * Use filter rules to filter value in fields.
	 *
	 * @param   string $field_type Type of this field.
	 * @param   array  $data       The data array for filter.
	 *
	 * @return  array   Filtered dates.
	 */
	public function filterFields($field_type = 'text', $data = array())
	{
		// Clean text
		// ==================================================================
		foreach ($data as $key => &$val)
		{
			if ($key == 'options' || is_array($val))
			{
				continue;
			}

			$val = trim($val);
		}

		// Filter Text
		// ==================================================================
		$form = null;

		// Event
		$this->event->trigger('onCCKEngineBeforeFormLoad', array(&$form, &$data));

		$form = \JForm::getInstance('fields', $field_type, array(), false, false);

		// Event
		$this->event->trigger('onCCKEngineAfterFormLoad', array(&$form, &$data));

		$data = $form->filter($data);

		return $data;
	}

	/**
	 * Make Name as safe ID, eg: 'BASIC_FIELD'
	 *
	 * @param    string $name Field name(ID) from field edit page.
	 *
	 * @return   string    A safe Name.
	 */
	public function regularizeName($name)
	{
		$name = \JFilterOutput::stringURLSafe($name);
		$name = str_replace('-', '_', $name);
		$name = strtoupper($name);

		return $name;
	}

	/**
	 * Check is Table exists needed columns?
	 * If not, will throw error.
	 *
	 * @param    \JTable $table   The item table to detect column exists or not.
	 * @param    string  $context Context for plugin.
	 *
	 * @throws \RuntimeException
	 */
	public function checkTable($table, $context)
	{
		// Needed columns
		$needs = array(
			'title',
			'name',
			'label',
			'field_type',
			'element',
			'attrs'
		);

		\JFactory::getApplication()->triggerEvent('onCCKEngineCheckTable', array($context, $table->getTableName(), &$needs));

		// Check columns
		// ==================================================================
		$lack = array();

		foreach ($needs as $needed)
		{
			if (!property_exists($table, $needed))
			{
				$lack[] = $needed;
			}
		}

		// Raise Error
		if (count($lack) > 0)
		{
			$message = "Table {$table->getTableName()} need columns: " . implode(', ', $lack);

			throw new \RuntimeException($message, 500);
		}
	}
}
 