<?php
/**
 * @version $Id: defines.djcatalog2.php 243 2014-02-13 16:21:33Z michal $
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

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
defined('_JEXEC') or die('Restricted access');

define ('DJCATIMGFOLDER', JPATH_SITE.DS.'media'.DS.'djcatalog2'.DS.'images');
define ('DJCATIMGURLPATH', JURI::base().'media/djcatalog2/images');

define ('DJCATATTFOLDER', JPATH_SITE.DS.'media'.DS.'djcatalog2'.DS.'files');
define ('DJCATATTURLPATH', JURI::base().'media/djcatalog2/files');

define('DJCATFOOTER', '<div style="text-align: center; padding: 2px 0px;"><a style="font-size:10px;" target="_blank" href="http://dj-extensions.com">Joomla! extensions &amp; templates</a></div>');
define('DJCATCOMPONENTPATH',JPATH_BASE.DS.'components'.DS.'com_djcatalog2');
