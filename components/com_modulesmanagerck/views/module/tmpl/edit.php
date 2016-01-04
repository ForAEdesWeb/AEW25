<?php
/**
 * @name		Modules Manager CK
 * @package		com_modulesmanagerck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;

if (version_compare(JVERSION, '3.0.0', 'le') > 0) {
	echo $this->loadTemplate('j25');
} else {
	echo $this->loadTemplate('j3');
}