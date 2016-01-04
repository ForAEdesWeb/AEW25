<?php
/**
 * @name		Modules Manager CK
 * @package		com_modulesmanagerck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class CKModulesmanager extends JObject {

	public function createPosition($position) {
		$modules = $this->getModulesList($position);
		//if (!$modules) return '';

		$html = '
		<div class="ckmodules clearfix" ckposition="'.$position.'">
			<div class="ckmodulescontrols clearfix">
				<div class="ckmodulescontrolposition">'.$position .'</div>
				<div class="ckmodulescontroladd cktip" title="'. JText::_('CK_ADD_MODULE') .'" onclick="ckShowModulesList($ck(this).parents(\'.ckmodules\'))">+</div>
			</div>
			<div class="ckmodulesinner clearfix" ckposition="'.$position.'">';

			foreach ($modules as $module) {
				$html .= $this->createModule($module);
			}
		$html .= '
			</div>
		</div>
		';

		return $html;
	}

	public function createModule($module) {
		if (!isset($module->id)) return;
		if (!isset($module->pageonly)) $module->pageonly = 0;
		$renderedmodule = '
		<div class="ckmodule clearfix" ckid="' . $module->id . '" ckposition="'. $module->position .'" cktype="'.$module->module .'" ckpublished="'.$module->published .'" ckpageonly="'.$module->pageonly .'">
			<div class="ckhandler"></div>
			<div class="cktitle cktip" title="'. JText::_('CK_DBLCLICK_TO_EDIT')  .'">'. $module->title .'</div>
			<div class="ckcontrols">
				<div class="ckremove"></div>
				<div class="ckoptions"></div>
				<div class="ckpublish"></div>
			</div>
			<div class="cktype">['.$module->module .']</div>
		</div>';

		return $renderedmodule;
	}

	private function getModulesList($position) {
		$db = JFactory::getDBO();
		$query = "SELECT *
				FROM #__modules
				WHERE position='" . (string) $position . "'
				AND client_id = 0
				ORDER BY ordering";
		$db->setQuery((string) $query);
		try {
			$rowsAll = $db->loadObjectList('id');
		} catch (Exception $e) {
			JError::raiseWarning(500, $e->getMessage());
			return false;
		}

		$rowsPageOnly = JModuleHelper::getModules($position);;

		if (count($rowsPageOnly) && $rowsPageOnly[0]->id != 0) {
			foreach ($rowsPageOnly as $module) {
				if (isset($rowsAll[$module->id])) $rowsAll[$module->id]->pageonly = '1';
			}
		}
		return $rowsAll;
	}
}