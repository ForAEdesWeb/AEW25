<?php
/**
 * Part of joomla3303 project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Class QuickcontentModelGenerator
 *
 * @since 1.0
 */
class QuickcontentModelGenerator extends Windwalker\Model\Model
{
	/**
	 * Property content.
	 *
	 * @var  \JTable
	 */
	protected $content = null;

	/**
	 * Property default_menu.
	 *
	 * @var  string
	 */
	protected $default_menu = array();

	/**
	 * Constructor
	 *
	 * @param   array                $config    An array of configuration options (name, state, dbo, table_path, ignore_request).
	 * @param   \Joomla\DI\Container $container Service container.
	 * @param   \JRegistry           $state     The model state.
	 * @param   \JDatabaseDriver     $db        The database adapter.
	 *
	 * @throws  \Exception
	 */
	public function __construct($config = array(), \Joomla\DI\Container $container = null, \JRegistry $state = null, \JDatabaseDriver $db = null)
	{
		parent::__construct($config, $container, $state, $db);

		$this->app = JFactory::getApplication();

		$this->categoryTable = JTable::getInstance('category');
		$this->articleTable  = JTable::getInstance('content');
		$this->menuTable     = JTable::getInstance('menu');
		$this->params        = JComponentHelper::getParams('com_quickcontent');

		$this->restore['categories'] = array();
		$this->restore['articles']   = array();
		$this->restore['menus']      = array();
		$this->restore['menutype']   = array();
	}

	/**
	 * saveContent
	 *
	 * @param integer $id
	 *
	 * @return  void
	 */
	public function saveContent($id)
	{
		$content = $this->getContent($id);
		$this->content = $content;

		$this->createMenutype();

		// fix Editor and MS Word HTML different bug
		$content->content = $this->fixMceHtml($content->content);

		$dom = simplexml_load_string('<root>' . $content->content . '</root>');

		$this->getCategories($dom);

		// Save Restore
		$content->restore = json_encode($this->restore);

		// Set Generated
		$content->generated = 1;

		$content->params = (string) $content->params;

		$content->store();
	}

	/**
	 * getCategories
	 *
	 * @param \SimpleXmlElement $cats
	 * @param integer           $parent_id
	 * @param integer           $level
	 *
	 * @return  void
	 */
	public function getCategories($cats = null, $parent_id = null, $level = 1)
	{
		if (!$cats)
		{
			return;
		}

		if (!$parent_id)
		{
			$parent_id = array('cat' => 1, 'menu' => 1);
		}

		if ($cats->ul->li)
		{
			foreach ($cats->ul->li as $li)
			{
				if ($li->strong)
				{
					// Create Article and menu
					echo 'article:' . $li->strong . '<br />';

					$this->createArticle($li, $parent_id);
				}
				else
				{
					// Create Category and menu
					$id = $this->createCategory($li, $parent_id);

					echo 'Create Category';

					$this->getCategories($li, $id);
				}

			}
		}

	}

	/**
	 * createCategory
	 *
	 * @param \SimpleXmlElement $cat
	 * @param integer           $pid
	 * @param integer           $level
	 *
	 * @return  mixed
	 */
	public function createCategory($cat, $pid, $level = 1)
	{
		$title = (string) $cat;
		$title = trim($title);

		// set Category
		// ============================================================
		$t = $this->categoryTable;
		$t->reset();

		$t->id        = null;
		$t->title     = $title;
		$t->parent_id = $pid['cat'];
		$t->published = 1;
		$t->extension = 'com_content';
		$t->access    = 1;
		$t->language  = '*';

		$t->setLocation($pid['cat'], 'last-child');

		$this->tranAlias($t);
		$t->check();
		$this->app->triggerEvent('onContentBeforeSave', array('com_categories.category', $t, true));

		// is same alias exists?
		$t2       = JTable::getInstance('Category', 'JTable');
		$titleTmp = $t->title;
		$aliasTmp = $t->alias;
		$i        = 2;

		while ($t2->load(array('alias' => $t->alias, 'parent_id' => $t->parent_id, 'extension' => $t->extension))
			&& ($t2->id != $t->id || $t->id == 0))
		{

			if ($this->params->get('titlesufix_when_alias_exists', false))
			{
				$t->title = $titleTmp . " ({$i})";
			}

			$t->alias = $aliasTmp . "-{$i}";

			$i++;
		}

		// OK, store!
		$t->store();

		$this->app->triggerEvent('onContentAfterSave', array('com_content.article', $t, true));

		$pid['cat'] = $t->id;

		// Set Restore
		$this->restore['categories'][] = $t->id;

		// set Menu
		// ============================================================
		$layout = $this->content->category_menutype;

		switch ($layout)
		{
			case 'list':
				$link         = "index.php?option=com_content&view=category&id={$pid['cat']}";
				$params       = $this->content->list;
				$type         = 'component';
				$component_id = 22;
				break;

			case 'blog':
				$link         = "index.php?option=com_content&view=category&layout=blog&id={$pid['cat']}";
				$params       = $this->content->blog;
				$type         = 'component';
				$component_id = 22;
				break;

			case 0:
			default:
				$type         = 'url';
				$link         = 'javascript:void(0);';
				$params       = '';
				$component_id = 0;
				break;
		}

		$m = $this->menuTable;
		$m->reset();

		$m->title        = $t->title;
		$m->alias        = $t->alias;
		$m->parent_id    = $pid['menu'];
		$m->link         = $link;
		$m->type         = $type;
		$m->published    = 1;
		$m->access       = 1;
		$m->language     = '*';
		$m->component_id = $component_id;
		$m->params       = $params;
		$m->menutype     = $this->content->menutype;

		$m->setLocation($pid['menu'], 'last-child');

		$m->check();
		$m->store();

		$pid['menu'] = $m->id;

		// Set Restore
		$this->restore['menus'][] = $m->id;

		$m->reset();
		$m->id = null;

		return $pid;
	}

	/**
	 * createMenutype
	 *
	 * @return  void
	 */
	public function createMenutype()
	{
		$menutype = $this->content->menutype;

		$db = JFactory::getDbo();
		$q  = $db->getQuery(true);

		$q->select("*")
			->from("#__menu_types")
			->where("menutype='{$menutype}'");

		$db->setQuery($q);
		$result = $db->loadObject();

		if (!$result)
		{
			$my = JTable::getInstance('menuType');

			$my->menutype    = $menutype;
			$my->title       = $menutype;
			$my->description = $menutype;

			$my->check();
			$my->store();
		}

		if ($this->content->delete_existing)
		{
			$this->deleteMenus($menutype);
		}

		// Set Restore
		$this->restore['menutype'][] = $menutype;
	}

	/**
	 * createArticle
	 *
	 * @param string  $title
	 * @param integer $pid
	 *
	 * @return  mixed
	 */
	public function createArticle($title, $pid)
	{
		$t = $this->articleTable;

		$t->reset();

		$t->id = null;

		$title = (string) $title->strong;
		$title = trim($title);

		$lorem = $this->params->get('lorem');

		if (!$this->params->get('use_custom_lorem', 0))
		{
			$dIntrotext = JText::_('COM_QUICKCONTENT_LOREM_INTROTEXT');
			$dFulltext  = JText::_('COM_QUICKCONTENT_LOREM_FULLTEXT');

		}
		else
		{
			$lorem = explode('<hr id="system-readmore" />', $lorem);

			$dIntrotext = $lorem[0];

			$dFulltext = $lorem[1];
		}

		$t->title     = $title;
		$t->introtext = $dIntrotext;
		$t->fulltext  = $dFulltext;
		$t->state     = 1;
		$t->catid     = $pid['cat'];
		$t->access    = 1;
		$t->language  = '*';

		$this->tranAlias($t);
		$t->check();
		$this->app->triggerEvent('onContentBeforeSave', array('com_content.article', $t, true));

		// Is same alias exists?
		$t2       = JTable::getInstance('Content', 'JTable');
		$titleTmp = $t->title;
		$aliasTmp = $t->alias;
		$i        = 2;

		while ($t2->load(array('alias' => $t->alias, 'catid' => $t->catid))
			&& ($t2->id != $t->id || $t->id == 0))
		{
			if ($this->params->get('titlesufix_when_alias_exists', false))
			{
				$t->title = $titleTmp . " ({$i})";
			}

			$t->alias = $aliasTmp . "-{$i}";

			$i++;
		}

		$t->store();

		$this->app->triggerEvent('onContentAfterSave', array('com_content.article', $t, true));

		// Set Restore
		$this->restore['articles'][] = $t->id;

		// set Menu
		$link = "index.php?option=com_content&view=article&id={$t->id}";

		$m = $this->menuTable;
		$m->reset();

		$m->title        = $t->title;
		$m->alias        = $t->alias;
		$m->parent_id    = $pid['menu'];
		$m->link         = $link;
		$m->type         = 'component';
		$m->published    = 1;
		$m->access       = 1;
		$m->language     = '*';
		$m->component_id = 22;
		$m->params       = $this->content->article;
		$m->menutype     = $this->content->menutype;

		$m->setLocation($pid['menu'], 'last-child');

		$m->check();
		$m->store();

		// Set Restore
		$this->restore['menus'][] = $m->id;

		$m->reset();

		$m->id = null;

		return $pid;
	}

	/**
	 * getContent
	 *
	 * @param integer $id
	 *
	 * @return  JTable
	 */
	public function getContent($id)
	{
		$list = $this->getTable('List', 'QuickcontentTable');
		$list->load($id);

		$list->params = new JRegistry($list->params);

		return $list;
	}

	/**
	 * deleteAll
	 *
	 * @return  void
	 */
	public function deleteAll()
	{
		$this->deleteArticles();

		$this->deleteCategories();

		$this->deleteMenus();

		$this->deleteMenuTypes();

		$this->clearGenerated();
	}

	/**
	 * clearGenerated
	 *
	 * @return  void
	 */
	public function clearGenerated()
	{
		$db = JFactory::getDbo();
		$q  = $db->getQuery(true);

		$q->update('#__quickcontent_lists')
			->set('generated=0');

		$db->setQuery($q);
		$db->execute();
	}

	/**
	 * deleteMenuTypes
	 *
	 * @return  void
	 */
	public function deleteMenuTypes()
	{
		// Delete MenuTypes
		$db = JFactory::getDbo();
		$q  = $db->getQuery(true);

		$q->select("id")
			->from("#__menu_types");

		$db->setQuery($q);
		$cids = $db->loadColumn();

		$content = JTable::getInstance('MenuType');

		foreach ($cids as $cid)
		{
			$content->load($cid);

			if (!in_array($content->menutype, $this->default_menu))
			{
				$content->delete();
			}
		}
	}

	/**
	 * deleteMenus
	 *
	 * @param string $menutype
	 *
	 * @return  void
	 */
	public function deleteMenus($menutype = null)
	{
		// Delete Menus
		$db = JFactory::getDbo();
		$q  = $db->getQuery(true);

		if ($menutype)
		{
			$q->where(" menutype='{$menutype}' ");
		}
		else
		{
			$q->where(" menutype != 'menu' AND menutype != 'main' AND level != 0 ");
		}

		$q->select("id")
			->from("#__menu");

		$db->setQuery($q);
		$cids = $db->loadColumn();

		$content      = JTable::getInstance('Menu');
		$default_menu = array();

		foreach ($cids as $cid)
		{
			$content->load($cid);

			if ($content->home != 0)
			{
				$default_menu[] = $content->menutype;
			}
			else
			{
				$content->delete();
			}
		}

		$this->default_menu = $default_menu;
	}

	/**
	 * deleteCatrgories
	 *
	 * @return  void
	 */
	public function deleteCategories()
	{
		// Delete Categories
		$db = JFactory::getDbo();
		$q  = $db->getQuery(true);

		$q->select("id")
			->from("#__categories")
			->where("level != 0")
			->where("extension='com_content'");

		$db->setQuery($q);
		$cids = $db->loadColumn();

		/** @var $category JTableCategory */
		$category = JTable::getInstance('Category');

		foreach ($cids as $cid)
		{
			$category->load($cid);

			if (strtolower($category->title) != 'uncategorized')
			{
				$category->delete();
			}
		}
	}

	/**
	 * deleteArticles
	 *
	 * @return  void
	 */
	public function deleteArticles()
	{
		// Delete Categories
		$db = JFactory::getDbo();
		$q  = $db->getQuery(true);

		$q->select("id")
			->from("#__content");

		$db->setQuery($q);
		$cids = $db->loadColumn();

		$content = JTable::getInstance('Content');

		foreach ($cids as $cid)
		{
			$content->load($cid);
			$content->delete();
		}
	}

	/**
	 * restore
	 *
	 * @param integer $id
	 *
	 * @return  void
	 */
	public function restore($id)
	{
		if (! $id)
		{
			return;
		}

		$content       = $this->getContent($id);
		$this->content = $content;
		$restore       = json_decode($this->content->restore);

		// Delete Articles
		$t = JTable::getInstance('Content');

		foreach ($restore->articles as $id)
		{
			$t->delete($id);
		}

		// Delete Categories
		$t = JTable::getInstance('Category');

		foreach ($restore->categories as $id)
		{
			$t->load($id);

			// Fix Content history bug
			$t->extension = 'com_content';

			$t->delete($id);
		}

		// Delete Menu
		$t = JTable::getInstance('Menu');

		foreach ($restore->menus as $id)
		{
			$t->delete($id);
		}

		// Delete MenuType
		$this->deleteMenuTypes($restore->menutype[0]);

		// Set Generated 0
		$content->generated = '0';

		$content->params = (string) $content->params;

		$content->store();
	}

	/**
	 * turn
	 * 	<li>Something<li>
	 * 	<ul>
	 * 		<li>Something</li>
	 * 	</ul>
	 * to
	 * 	<li>Something
	 *		<ul>
	 *			<li>Something</li>
	 *		</ul>
	 *	</li>
	 *
	 * 	Otherwise the DOM parser will misunderstand the categories level
	*/
	public function fixMceHtml($c)
	{
		$c = nl2br($c);

		$c = explode("<br />", $c);
		$o = '';

		foreach ($c as $k => $v)
		{
			$o .= trim($v);
		}

		$o = str_replace('</li><ul>', '<ul>', $o);
		$o = str_replace('</ul></ul>', '</ul></li></ul>', $o);
		$o = str_replace('</ul><li>', '</ul></li><li>', $o);

		return $o;
	}

	/**
	 * tranAlias
	 *
	 * @param \stdClass $article
	 *
	 * @return  void
	 */
	public function tranAlias(&$article)
	{
		$alias = $article->alias;
		$title = $article->title;

		$titleTmp = explode('::', $title);

		if (!empty($titleTmp[1]))
		{
			$title = $titleTmp[0];
			$alias = $titleTmp[1];
		}

		$alias = JFilterOutput::stringURLSafe($alias);

		if (trim($alias) == '')
		{
			$alias = \Windwalker\Helper\LanguageHelper::translate($article->title, null, 'en');

			$alias = trim($alias);
			$alias = JFilterOutput::stringURLSafe($alias);

			$replace = array(
				'aquot' => '',
				'a39'   => '',
				'--'    => '-'
			);

			$alias   = strtr($alias, $replace);
			$alias   = trim($alias, '-');
		}

		$article->title = trim($title);
		$article->alias = trim($alias);
	}
}
 