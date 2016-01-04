<?php
/**
 * Part of Component Userxtd files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Userxtd\Router\Route;
use Joomla\Registry\Registry;
use Windwalker\Data\Data;
use Windwalker\Helper\DateHelper;
use Windwalker\View\Html\ItemHtmlView;

// No direct access
defined('_JEXEC') or die;

/**
 * Userxtd Users view
 *
 * @since 1.0
 */
class UserxtdViewUserHtml extends ItemHtmlView
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
	protected $name = 'user';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'user';

	/**
	 * The list name.
	 *
	 * @var  string
	 */
	protected $viewList = 'users';

	/**
	 * prepareRender
	 *
	 * @return  void
	 */
	protected function prepareRender()
	{
		$user = $this->container->get('user');
		$input = $this->container->get('input');
		$app = $this->container->get('app');
		$state = $this->get('State');
		$params = $this->get('Params');

		// Not login and no id, return.
		if (! $input->get('id') && $user->get('guest'))
		{
			$app->redirect(JUri::root());
		}

		// Guest can not see other users' profile if config set.
		if (!$params->get('UserProfile_GuestSeeProfile', 1) && $user->get('guest'))
		{
			$app->redirect(JUri::root());
		}

		// Set user id into model
		$state->set('user.id', $input->get('id', $user->id));

		parent::prepareRender();
	}

	/**
	 * Prepare data hook.
	 *
	 * @return  void
	 */
	protected function prepareData()
	{
		$data = $this->getData();
		$user = $this->container->get('user');

		$data->category = $this->get('Category');
		$data->params   = $this->get('Params');

		$data->fields	= $this->get('Fields');
		$data->profiles = $this->get('Profiles');
		$data->canDo	= UserxtdHelper::getActions();

		// Prepare setting data
		$item = $data->item = new Data($data->item);

		if($this->getLayout() == 'edit')
		{
			$data->form	= $this->get('Form');
		}

		// Link
		// =====================================================================================
		$query = array(
			'id'    => $item->id,
			'alias' => $item->alias,
			// 'catid' => $item->catid
		);

		$item->link = Route::_('com_userxtd.user', $query);

		// Can Edit
		// =====================================================================================
		if (!$user->get('guest'))
		{
			$userId	= $user->get('id');

			if($item->id == $userId && $data->params->get('UserProfile_CanEdit', 1))
			{
				$data->params->set('access-edit', true);
			}

			// Now check if core.edit is available.
			elseif (!empty($userId) && $user->authorise('core.edit', 'com_user'))
			{
				// Check for a valid user and that they are the owner.
				$data->params->set('access-edit', true);
			}
		}

		$this->prepareEvents($item);

		$this->configureParams($item);
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
		$doc = \JFactory::getDocument();
		$config = JFactory::getConfig();

		if ($config->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $config->get('sitename'), $title);
		}
		elseif ($config->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $config->get('sitename'));
		}

		$doc->setTitle($title);
	}

	/**
	 * Prepare the content events.
	 *
	 * @param Data $item The item object.
	 *
	 * @return  void
	 */
	protected function prepareEvents($item)
	{
		$data = $this->getData();

		// Plugins
		// =====================================================================================
		$item->event = new stdClass;

		$dispatcher = $this->container->get('event.dispatcher');
		JPluginHelper::importPlugin('content');

		$item->text = $item->introtext . $item->fulltext;
		$results = $dispatcher->trigger('onContentPrepare', array('com_userxtd.user', &$item, &$data->params, 0));

		$results = $dispatcher->trigger('onContentAfterTitle', array('com_userxtd.user', &$item, &$data->params, 0));
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_userxtd.user', &$item, &$data->params, 0));
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentAfterDisplay', array('com_userxtd.user', &$item, &$data->params, 0));
		$item->event->afterDisplayContent = trim(implode("\n", $results));
	}

	/**
	 * Configure the config data.
	 *
	 * @param Data $item The item object
	 *
	 * @return  void
	 */
	protected function configureParams($item)
	{
		$app  = $this->container->get('app');
		$data = $this->getData();

		// Params
		// =====================================================================================

		// Merge user params. If this is single-user view, menu params override article params
		// Otherwise, user params override menu item params
		$active       = $app->getMenu()->getActive();
		$temp         = clone ($data->params);
		$item->params = new Registry($item->params);

		$item->title = JText::_('COM_USERS_PROFILE') . ' - ' . $item->name;

		// Check to see which parameters should take priority
		if ($active)
		{
			$currentLink = $active->link;

			// If the current view is the active item and an user view for this user,
			// then the menu item params take priority
			if (strpos($currentLink, 'view=user') && (strpos($currentLink, '&id=' . (string) $item->id)))
			{
				// $item->params are the user params, $temp are the menu item params
				// Merge so that the menu item params take priority
				$item->params->merge($temp);

				// Load layout from active query (in case it is an alternative menu item)
				if (isset($active->query['layout']))
				{
					$this->setLayout($active->query['layout']);
				}
			}
			else
			{
				// Current view is not a single user, so the user params take priority here
				// Merge the menu item params with the user params so that the user params take priority
				$temp->merge($item->params);
				$this->params = $temp;

				// Check for alternative layouts (since we are not in a single-user menu item)
				// Single-user menu item layout takes priority over alt layout for an user
				if ($layout = $data->params->get('user_layout'))
				{
					$this->setLayout($layout);
				}

				// If not Active, set Title
				$this->setTitle($item->title);
			}
		}
		else
		{
			// Merge so that article params take priority
			$temp->merge($data->params);
			$this->params = $temp;

			// Check for alternative layouts (since we are not in a single-article menu item)
			// Single-article menu item layout takes priority over alt layout for an article
			if ($layout = $data->params->get('user_layout'))
			{
				$this->setLayout($layout);
			}

			// If not Active, set Title
			$this->setTitle($item->title);
		}

		$item->params = $data->params;
	}
}
