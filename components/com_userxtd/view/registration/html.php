<?php
/**
 * Part of Component Userxtd files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\Registry\Registry;
use Windwalker\View\Html\ItemHtmlView;

// No direct access
defined('_JEXEC') or die;

/**
 * Userxtd Users view
 *
 * @since 1.0
 */
class UserxtdViewRegistrationHtml extends ItemHtmlView
{
	/**
	 * The component prefix.
	 *
	 * @var  string
	 */
	protected $prefix = 'userxtd';

	/**
	 * The component option name.
	 *
	 * @var string
	 */
	protected $option = 'com_userxtd';

	/**
	 * The text prefix for translate.
	 *
	 * @var  string
	 */
	protected $textPrefix = 'COM_USERXTD';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $name = 'registration';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'registration';

	/**
	 * The list name.
	 *
	 * @var  string
	 */
	protected $viewList = 'registrations';

	/**
	 * prepareRender
	 *
	 * @return  void
	 */
	protected function prepareRender()
	{
		parent::prepareRender();
	}

	/**
	 * Prepare data hook.
	 *
	 * @return  void
	 */
	protected function prepareData()
	{
		$app  = JFactory::getApplication();
		$data = $this->getData();
		$data->form = $this->get('Form');
		$user = $this->container->get('user');

		$data->params = JComponentHelper::getParams('com_users');

		if(!$data->params->get('allowUserRegistration', 1))
		{
			$app->redirect(JRoute::_('index.php?option=com_users&view=login'));

			return;
		}

		$data->canDo = UserxtdHelper::getActions();

		$this->setTitle();
	}

	/**
	 * Set title of this page.
	 *
	 * @param string $title Page title.
	 * @param string $icons Title icon.
	 *
	 * @return  void
	 */
	protected function setTitle($title = null, $icons = 'stack')
	{
		$app    = JFactory::getApplication();
		$config = JFactory::getConfig();
		$menus  = $app->getMenu();
		$title  = null;

		$document = \JFactory::getDocument();

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->data->params->def('page_heading', $this->data->params->get('page_title', $menu->title));
		}
		else
		{
			$this->data->params->def('page_heading', JText::_('COM_USERS_REGISTRATION'));
		}

		$title = $this->data->params->get('page_title', JText::_('COM_USERS_REGISTRATION'));

		if (empty($title))
		{
			$title = $config->get('sitename');
		}
		elseif ($config->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $config->get('sitename'), $title);
		}
		elseif ($config->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $config->get('sitename'));
		}

		$document->setTitle($title);

		if ($this->data->params->get('menu-meta_description'))
		{
			$document->setDescription($this->data->params->get('menu-meta_description'));
		}

		if ($this->data->params->get('menu-meta_keywords'))
		{
			$document->setMetadata('keywords', $this->data->params->get('menu-meta_keywords'));
		}

		if ($this->data->params->get('robots'))
		{
			$document->setMetadata('robots', $this->data->params->get('robots'));
		}
	}
}
