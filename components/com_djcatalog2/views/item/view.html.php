<?php
/**
 * @version $Id: view.html.php 343 2014-08-21 14:29:20Z michal $
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

defined ('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class DJCatalog2ViewItem extends JViewLegacy {
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->_addPath('template', JPATH_COMPONENT.  '/themes/default/views/item');
		$theme = DJCatalog2ThemeHelper::getThemeName();
		if ($theme && $theme != 'default') {
			$this->_addPath('template', JPATH_COMPONENT.  '/themes/'.$theme.'/views/item');
		}
	}
	
	function display($tpl = null) {
		JHTML::_( 'behavior.modal' );
		$app = JFactory::getApplication();
		$document= JFactory::getDocument();
		$model = $this->getModel();
		
		$menus		= $app->getMenu('site');
		$menu  = $menus->getActive();
		$dispatcher	= JDispatcher::getInstance();
		
		$categories = Djc2Categories::getInstance(array('state'=>'1'));
		
		$limitstart	= $app->input->get('limitstart', 0, 'int');
		
		$state = $this->get('State');
		$item = $this->get('Item');
		$this->contactform	= $this->get('Form');
		$this->showcontactform = ($app->getUserState('com_djcatalog2.contact.data')) ? 'false' : 'true';

		if (empty($item) || !$item->published) {
			throw new Exception(JText::_('COM_DJCATALOG2_PRODUCT_NOT_FOUND'), 404);
			//return JError::raiseError( 404, JText::_( 'COM_DJCATALOG2_PRODUCT_NOT_FOUND') );
		}
		
		$catid = (int)$app->input->get('cid');
		$category = $categories->get($item->cat_id);
		$current_category = ($catid == $item->cat_id) ? $category : $categories->get($catid);
		
		if (($current_category && $current_category->id > 0 && $current_category->published == 0) || empty($category)) {
			if (($category && $category->id > 0 && $category->published == 0) || empty($category))
			{
				throw new Exception(JText::_('COM_DJCATALOG2_PRODUCT_NOT_FOUND'), 404);
			}
		}
		
		// if category id in the URL differs from product's category id
		// we add canonical link to document's header
		/*if (JString::strcmp(DJCatalogHelperRoute::getItemRoute($item->slug, (int)$item->cat_id), DJCatalogHelperRoute::getItemRoute($item->slug, (int)$catid)) != 0) {
			$document->addHeadLink(JRoute::_(DJCatalogHelperRoute::getItemRoute($item->slug, $item->catslug)), 'canonical');
			//$document->addCustomTag('<link rel="canonical" href="'.JRoute::_(DJCatalogHelperRoute::getItemRoute($item->slug, $item->catslug)).'"/>');
		}*/
		
		foreach($this->document->_links as $key => $headlink) {
			if ($headlink['relation'] == 'canonical' ) {
				unset($this->document->_links[$key]);
			}
		}
		
		$this->document->addHeadLink(JRoute::_(DJCatalogHelperRoute::getItemRoute($item->slug, $item->catslug)), 'canonical');
		
		// if category id is not present in the URL or it equals 0
		// we set it to product's cat id
		if ($catid == 0) {
			$app->input->set('cid', $item->cat_id);
		}
		
		// params in this view should be generated only after we make sure
		// that product's cat id is in the request.
		$params = Djcatalog2Helper::getParams();
		if (!empty($item) && !empty($item->params)) {
			$item_params = new JRegistry($item->params);
			$params->merge($item_params);
		}
		
		/* plugins */
		JPluginHelper::importPlugin('djcatalog2');
		
		$results = $dispatcher->trigger('onPrepareItemDescription', array (& $item, & $params, $limitstart));
		
		$item->event = new stdClass();
		$resultsAfterTitle = $dispatcher->trigger('onAfterDJCatalog2DisplayTitle', array (&$item, &$params, $limitstart));
		$item->event->afterDJCatalog2DisplayTitle = trim(implode("\n", $resultsAfterTitle));

		$resultsBeforeContent = $dispatcher->trigger('onBeforeDJCatalog2DisplayContent', array (&$item, &$params, $limitstart));
		$item->event->beforeDJCatalog2DisplayContent = trim(implode("\n", $resultsBeforeContent));

		$resultsAfterContent = $dispatcher->trigger('onAfterDJCatalog2DisplayContent', array (&$item, &$params, $limitstart));
		$item->event->afterDJCatalog2DisplayContent = trim(implode("\n", $resultsAfterContent));

		$this->assignref('categories', $categories);
		$this->assignref('category', $category);
		$this->assignref('item', $item);
		$this->assignref('images', $images);
		$this->assignref('files', $files);
		
		$this->assignref('params', $params);
		$this->relateditems = $model->getRelatedItems();
		$this->attributes = $model->getAttributes();
		$this->navigation = $model->getNavigation($this->item->id, $this->item->cat_id, $params);
		
		if ($app->input->get('pdf') == '1' && $app->input->get('tmpl') == 'component' && $this->getLayout() == 'print') {

			if (JFile::exists(JPath::clean(JPATH_ROOT.'/libraries/dompdf/dompdf_config.inc.php')) == false) {
				throw new Exception('DOMPDF Libary is missing!');
			}
			
			$this->_preparePDF();
			
			$app->close();
			return true;
		}
		
		$this->_prepareDocument();
		
		$model->hit();
		
		parent::display($tpl);
	}
	protected function _prepareDocument() {
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title		= null;
		$heading		= null;
		$document= JFactory::getDocument();
		$menu = $menus->getActive();
		
		$id = (int) @$menu->query['id'];
		$cid = (int) @$menu->query['cid'];
		
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		$title = $this->params->get('page_title', '');
		
		$metakeys = null;
		$metadesc = null;

		if ($menu && ($menu->query['option'] != 'com_djcatalog2' || $menu->query['view'] == 'items' || $id != $this->item->id )) {
			
			if ($this->item->metatitle) {
				$title = $this->item->metatitle;
			}
			else if ($this->item->name) {
				$title = $this->item->name;
			}
			$category = $this->categories->get($this->item->cat_id);
			$path = array(array('title' => $this->item->name, 'link' => ''));
			while (($menu->query['option'] != 'com_djcatalog2' || ($menu->query['view'] == 'items' && $cid != $category->id)) && $category->id > 0)
			{
				$path[] = array('title' => $category->name, 'link' => DJCatalogHelperRoute::getCategoryRoute($category->catslug));
				$category = $this->categories->get($category->parent_id);
			}

			$path = array_reverse($path);

			foreach ($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
			
		} else if (!empty($menu)) {
			if ($this->params->get('menu-meta_description')) {
				$metadesc = $this->params->get('menu-meta_description');
			}
			if ($this->params->get('menu-meta_keywords')) {
				$metakeys = $this->params->get('menu-meta_keywords');
			}
		}
		
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0)) {
			if ($app->getCfg('sitename_pagetitles', 0) == '2') {
				$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
			} else {
				$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
			}
		}
		$this->document->setTitle($title);

		if (!empty($this->item->metadesc))
		{
			$this->document->setDescription($this->item->metadesc);
		}
		elseif (!empty($metadesc)) 
		{
			$this->document->setDescription($metadesc);
		}

		if (!empty($this->item->metakey))
		{
			$this->document->setMetadata('keywords', $this->item->metakey);
		}
		elseif (!empty($metakeys)) 
		{
			$this->document->setMetadata('keywords', $metakeys);
		}
		
		
		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
		
		$this->document->addCustomTag('<meta property="og:title" content="'.trim($title).'" />');
		$this->document->addCustomTag('<meta property="og:url" content="'.JRoute::_(DJCatalogHelperRoute::getItemRoute($this->item->slug, $this->item->catslug)).'" />');
		if ($item_images = DJCatalog2ImageHelper::getImages('item',$this->item->id)) {
			if (isset($item_images[0])) {
				$this->document->addCustomTag('<meta property="og:image" content="'.$item_images[0]->large.'" />');
				//$this->document->setMetadata('og:image', $item_images[0]->large);
			}
		}
	}
	protected function _preparePDF() {
		if (!defined('DOMPDF_ENABLE_REMOTE'))
		{
			define('DOMPDF_ENABLE_REMOTE', true);
		}
			
		$config = JFactory::getConfig();
		$document = JFactory::getDocument();
		
		$document->setMimeEncoding('application/pdf');
		
		if (!defined('DOMPDF_FONT_CACHE'))
		{
			define('DOMPDF_FONT_CACHE', $config->get('tmp_path'));
		}
		
		if (!defined('DOMPDF_DEFAULT_FONT'))
		{
			define('DOMPDF_DEFAULT_FONT', 'DejaVuSans');
		}

		require_once JPath::clean(JPATH_ROOT.'/libraries/dompdf/dompdf_config.inc.php');
		
		if(ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}
		
		$pdf =new DOMPDF();
		
		ob_start();
		parent::display(null);
		$body = ob_get_contents();
		ob_end_clean();
		
		$document->_scripts = array();
		$document->_script = array();
		
		$head = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>'; //$document->getBuffer('head');
		
		$data = '<html><head>'.$head.'</head><body style="font-family: firefly, DejaVu Sans, sans-serif !important;">'.$body.'</body></html>';
		
		$this->fullPaths($data);

		$pdf->load_html($data);
		$pdf->render();
		$pdf->stream(JFile::makeSafe($this->item->name) . '.pdf');
	}
	
	private function fullPaths(&$data)
	{
		$data = str_replace('xmlns=', 'ns=', $data);
		
		$doc = new DOMDocument();
		$doc->loadHTML($data);
		
		libxml_use_internal_errors(true);
		
		$allow_fopen = @ini_get('allow_url_fopen');
		$remote_urls = (empty($allow_fopen) || $allow_fopen == 'Off') ? false : true;
		
		try
		{
			//$ok = new SimpleXMLElement($sxml);
			$ok = simplexml_import_dom($doc);
			if ($ok)
			{
				$uri = JUri::getInstance();
				//$base = JURI::root(false);
				$base = $uri->getScheme() . '://' . $uri->getHost();
				
				$imgs = $ok->xpath('//img');
				foreach ($imgs as &$img) {
					if (!strstr($img['src'], $base)) {
					    if ($remote_urls) {
					        $img['src'] = $base . $img['src'];
					    }
					} else if (!$remote_urls){
					   $img['src'] = str_replace($base.'/', '', $img['src']);    
					}
                    
                    if (strpos($img['src'], '/') == 0 && !$remote_urls) {
                        $img['src'] = substr($img['src'], 1);
                    }
					$img['src'] = str_replace(' ', '%20', $img['src']);
				}
				//links
				$as = $ok->xpath('//a');
				foreach ($as as &$a)
				{
					if (!strstr($a['href'], $base) && !strstr($a['href'], '://'))
					{
						$a['href'] = $base . $a['href'];
					} 
				}
	
				// css files.
				$links = $ok->xpath('//link');
				foreach ($links as &$link)
				{
					if ($link['rel'] == 'stylesheet' && !strstr($link['href'], $base))
					{
						$link['href'] = $base . $link['href'];
					}
				}
				$data = $ok->asXML();
			}
		} catch (Exception $err)
		{
			$errors = libxml_get_errors();
			if (JDEBUG)
			{
				echo "<pre>";print_r($errors);echo "</pre>";
				exit;
			}
		}
	
	}
}

?>