<?php
/**
 * ------------------------------------------------------------------------
 * JA System Social Feed Plugin for J25 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).'/base.php');

class jaSocialFeedTableJoomla extends jaSocialFeedTableBase {
	
	public function __construct($plgParams) {
		if(!$this->isAvailable()) {
			return false;
		}
		
		parent::__construct($plgParams);
		
		$this->table = JTable::getInstance('content');
	}
	
	public function isAvailable() {
		return true;
	}
	
	public function existsPost($alias) {
		$db = JFactory::getDbo();
		$query = 'SELECT id FROM #__content WHERE `alias` = '.$db->quote($alias);

		$db->setQuery($query);
		$id = $db->loadResult();
		if(!$id) $id = 0;
		return intval($id);
	}
	
	public function store($source, $post, $id = 0) {
		if(empty($post['source_alias']) || (!$id && $this->existsPost($post['source_alias']))) {
			return false;
		}
		$item = array();
		//general property
		$item['state'] = 1;
		$item['publish_up'] = '';
		$item['publish_down'] = '';
		$metakey = '';
		if(version_compare(JVERSION, '3.1', 'ge')) {
			// Convert the metadata field to an array.
			$registry = new JRegistry;
			$registry->loadString($metakey);
			$metadata = $registry->toArray();
			$metadata['tags'] = null;
			$item['metadata'] = $metadata;
		}
		$item['metakey'] = $metakey;
		$item['metadesc'] = '';
		$item['access'] = '1';
		$item['language'] = '*';
		$item['featured'] = '0';
		
		
		//////
		$item['id'] = $id;
		$item['created_by'] = $post['created_by'];
		$item['created_by_alias'] = html_entity_decode($post['source_author'], ENT_COMPAT, 'UTF-8');
		$item['catid'] = $post['catid'];
		$item['alias'] = $post['source_alias'];
		$item['title'] = html_entity_decode($post['source_title'], ENT_COMPAT, 'UTF-8');
		$item['created'] = $this->dateToDb($post['source_published_date']);
		
		if($post['source_images']) {
			$item['images'] = json_encode($post['source_images']);
		} else {
			$item['images'] = '';
		}
		
		//urls
		$urls = new stdClass();
		$urls->targeta = '1';
		$urls->urla = $post['source_url'];
		$urls->urlatext = $post['source_url_txt'];
		$urls->targetb = '';
		$urls->urlb = null;
		$urls->urlbtext = '';
		$urls->targetc = '';
		$urls->urlc = null;
		$urls->urlctext = '';
		
		$item['urls'] = json_encode($urls);
		$source_content = preg_replace('@<img @', '<img class="'.$source.'-img ja-social-img" ', $post['source_content']);
		$txt = '<div class="'.$source.'-item">';
		$txt .= '<div class="content">' . $source_content . '</div>';
		//$txt .= '<p class="readmore"><a class="readmore" href="'.$post['source_url']. '" title="'.JText::_('More').'" target="_blank">'.JText::_('More').'</a></p>';
		$txt .= '</div>';

		$item['articletext'] = html_entity_decode($txt, ENT_COMPAT, 'UTF-8');

		//save article
		$this->table->load($id);
		$this->table->bind($item);
		if($this->table->check()) {
			return @$this->table->store();
		}
		return false;
	}
}