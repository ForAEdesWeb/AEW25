<?php
/**
 * @version $Id: view.html.php 272 2014-05-21 10:25:49Z michal $
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

jimport('joomla.application.component.view');

class Djcatalog2ViewQuery extends JViewLegacy {
	protected $state;
	protected $item;
	protected $form;
	
	public function display($tpl = null)
	{
		
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
		$version = new JVersion;
		if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
			$tpl = 'legacy';
		}
		parent::display($tpl);
	}
	
protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		
		$isNew		= ($this->item->id == 0);

		$text = $isNew ? JText::_( 'COM_DJCATALOG2_NEW' ) : JText::_( 'COM_DJCATALOG2_EDIT' );
		JToolBarHelper::title(   JText::_( 'COM_DJCATALOG2_QUERY' ).': <small><small>[ ' . $text.' ]</small></small>', 'generic.png' );
		
		//JToolBarHelper::apply('order.apply', 'JTOOLBAR_APPLY');
		//JToolBarHelper::save('order.save', 'JTOOLBAR_SAVE');
		JToolBarHelper::cancel('query.cancel', 'JTOOLBAR_CANCEL');
	}
}
?>