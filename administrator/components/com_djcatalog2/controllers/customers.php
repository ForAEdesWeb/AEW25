<?php
/**
 * @version $Id: customers.php 272 2014-05-21 10:25:49Z michal $
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
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controlleradmin');


class Djcatalog2ControllerCustomers extends JControllerAdmin
{
	public function getModel($name = 'Customer', $prefix = 'Djcatalog2Model', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	public function delete()
	{
		$app = JFactory::getApplication();
		$cid   = $app->input->get('cid', array(), 'array');
		
		$new_cids = array();
		
		if (count($cid) > 0) {
			foreach($cid as $k=>$v) {
				$parts  = explode(',', $v);
				if (count($parts) == 2) {
					$new_cid = $parts[1];
					$new_cids[] = $new_cid;
				}
			}
		}
		
		$app->input->post->set('cid', $new_cids);
		$app->input->set('cid', $new_cids);
		@JRequest::setVar('cid', $new_cids, 'post');
		
		return parent::delete();
	}
	
}