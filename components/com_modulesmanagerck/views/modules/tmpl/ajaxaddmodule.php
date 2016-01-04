<?php
/**
 * @name		Modules Manager CK
 * @package		com_modulesmanagerck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
// no direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$ckmodulesmanager = new CKModulesManager();

$input = new JInput();
$id = $input->get('id',0,'int');
$position = $input->get('position','','string');
$title = $input->get('title','','string');
$type = $input->get('type','','string');

$ckmodulesmanager->createModule($id, $position, $title, $type);