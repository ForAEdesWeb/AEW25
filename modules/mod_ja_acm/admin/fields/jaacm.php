<?php
/** 
 *------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 *------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github 
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org 
 *------------------------------------------------------------------------------
 */

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

require_once 'jafield.php';

/**
 * Radio List Element
 *
 * @since      Class available since Release 1.2.0
 */
class JFormFieldJAAcm extends JAFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $type = 'jaacm';


	function getLabel() {
		return '';
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	function getInput()
	{
		$jdoc = JFactory::getDocument();
		if(version_compare(JVERSION, '3.0', 'lt')){
			$jdoc->addScript('http://code.jquery.com/jquery-latest.js');
			// add bootstrap for Joomla 2.5

			if (defined('T3_ADMIN_URL')) {
				$jdoc->addStyleSheet(T3_ADMIN_URL . '/admin/bootstrap/css/bootstrap.min.css');
				$jdoc->addScript(T3_ADMIN_URL . '/admin/bootstrap/js/bootstrap.min.js');
			}
		}
		// add font awesome 4
		if (defined('T3_ADMIN_URL')) {
				$jdoc->addStyleSheet(T3_ADMIN_URL . '/admin/fonts/fa4/css/font-awesome.min.css');
		}

		$jdoc->addStyleSheet(JUri::root(true) . '/modules/mod_ja_acm/admin/assets/style.css');
		$jdoc->addStyleSheet('//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css');
		$jdoc->addScript('//code.jquery.com/ui/1.11.1/jquery-ui.js');
		$jdoc->addScript(JUri::root(true) . '/modules/mod_ja_acm/admin/assets/script.js');


		// load all xml
		$paths = array();
		$paths['_'] = JPATH_ROOT . '/modules/mod_ja_acm/acm/';
		// template folders
		$tpls = JFolder::folders (JPATH_ROOT. '/templates/');
		foreach ($tpls as $tpl) {
			$paths[$tpl] = JPATH_ROOT . '/templates/' . $tpl . '/acm/';
		}

		$fields = array();
		$group_types = array();
		$group_layouts = array();
		foreach ($paths as $template => $path) {
			if (!is_dir($path)) continue;
			$types = JFolder::folders($path);
			if (!is_array($types)) continue;

			$group_types[$template] = array();

			// get layout for each type
			foreach ($types as $type) {
				if (!isset($group_layouts[$type])) $group_layouts[$type] = array();
				if (!is_dir($path . $type . '/tmpl')) continue;
				$layouts = JFolder::files ($path . $type . '/tmpl', '.php');
				if (is_array($layouts)) {
					foreach ($layouts as $layout) {
						$layout = JFile::stripExt($layout);
						$group_layouts[$type][] = $layout;
					}
				}
			}

			foreach ($types as $type) {
				$lname = $type;
				if (is_file($path . $type . '/config.xml')) {
					$form = new JForm($lname);
					// $form->loadFile ($path . $type . '/config.xml', false);
					$xml = JFactory::getXML($path . $type . '/config.xml', true);
					$form->load ($xml, false);

					$fieldsets = $form->getFieldsets();
					/*
					$fieldsets_html = array();
					if (!is_array($fieldsets)) continue;
					foreach ($fieldsets as $fsname => $fieldset) {
						$fieldsets_html[$fsname] = $this->renderFieldSet ($form, $fsname);
					} */
					$title = isset($xml->title) ? $xml->title : $lname;
					$description = isset($xml->description) ? $xml->description : '';
					$sampledata = isset($xml->sampledata) ? $xml->sampledata : '';

					$group_types[$template][$lname] = $title;
					$fields[$lname] = $this->renderLayout ('jaacm-type', array('form' => $form, 'fieldsets' => $fieldsets, 'type' => $type,
						'layouts' => $group_layouts[$type], 'description' => $description, 'sample-data' => $sampledata));
					/*
					$fields[$lname] = $this->renderConfig('layout-config',
						array('form' => $form, 'fieldsets' => $fieldsets, 'type' => $type,
							'layouts' => $group_layouts[$type], 'description' => $description, 'sample-data' => $sampledata),
						JPATH_ROOT); */
				}
			}
		}

		$html = '';
		$html .= "\n<input type=\"hidden\" name=\"{$this->name}\" id=\"jatools-config\" value=\"". htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') ."\" />";
		$html .= $this->renderLayout('jaacm', array('group_types' => $group_types, 'fields' => $fields));
		// $html .= $this->renderConfig('layouts-config', array('group_types' => $group_types, 'fields' => $fields), JPATH_ROOT);
		return $html;
	}

	function renderConfig ($file, $displayData) {
		$path = JPATH_ROOT . '/modules/mod_ja_acm/admin/tmpl/' . $file . '.php';
		if (!is_file ($path)) return null;
		ob_start();
		include $path;
		$layoutOutput = ob_get_contents();
		ob_end_clean();

		return $layoutOutput;
	}

	function renderFieldSet ($form, $name) {
		//if (method_exists ($form, 'renderFieldSet')) {
		//	$html = $form->renderFieldSet ($name);
		//	return $html;
		//} else {
			$fields = $form->getFieldset($name);
			$html = array();
			foreach ($fields as $field)
			{
				$layouts = $field->element['layouts'] ? ' data-layouts="' . $field->element['layouts'] . '"' : '';

				$html[] = '
				<div class="control-group"'. $layouts . '>
					<div class="control-label">' . $field->getLabel() . '</div>
					<div class="controls">' . $field->getInput() . '</div>
				</div>';
			}

			return implode('', $html);
		//}
	}
} 