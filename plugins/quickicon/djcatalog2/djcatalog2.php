<?php
/**
 * @version $Id: djcatalog2.php 105 2013-01-23 14:05:57Z michal $
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

defined('_JEXEC') or die;

class plgQuickiconDjcatalog2 extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	public function onGetIcons($context)
	{
		if ($context != $this->params->get('context', 'mod_quickicon')) {
			return;
		}
		
		$icons = array();
		
		
		$version = new JVersion;
		if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
			$icons[] = array(
				'link' => 'index.php?option=com_djcatalog2',
				'image' => 'djcatalog2/quickicon/quickicon-djcatalog.png',
				'text' => JText::_('PLG_QUICKICON_DJCATALOG2'),
				'id' => 'plg_quickicon_djcatalog2'
			);
		} else {
			$icons[] = array(
				'link' => 'index.php?option=com_djcatalog2',
				'image' => 'database',
				'text' => JText::_('PLG_QUICKICON_DJCATALOG2'),
				'id' => 'plg_quickicon_djcatalog2'
			);
		}
		
		return $icons;

	}
}
