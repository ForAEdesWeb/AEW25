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

class jaSocialFeedTableK2 extends jaSocialFeedTableBase {
	
	public function __construct($plgParams) {
		if(!$this->isAvailable()) {
			return false;
		}
		
		parent::__construct($plgParams);
		
		$db = JFactory::getDbo();
		$this->table = new TableK2Item($db);
	}
	
	public function isAvailable() {
		$table = JPATH_ADMINISTRATOR.'/components/com_k2/tables/table.php';
		$tableK2 = JPATH_ADMINISTRATOR.'/components/com_k2/tables/k2item.php';
		if(!defined('K2_JVERSION')) {
			define('K2_JVERSION', '16');
		}
		if(!JFile::exists($table) && !JFile::exists($tableK2)) {
			//raise warning?
			return false;
		}

		//required for new version
		if(JFile::exists($table)){
			require_once($table);
		}

		if(!JFile::exists($tableK2)) {
			//raise warning?
			return false;
		}
		require_once($tableK2);
		return true;
	}
	
	public function existsPost($alias) {
		$db = JFactory::getDbo();
		$query = 'SELECT id FROM #__k2_items WHERE `alias` = '.$db->quote($alias);

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
		$item['publish_up'] = '';
		$item['publish_down'] = '';
		$item['metakey'] = '';
		$item['metadesc'] = '';
		$item['published'] = '1';
		$item['access'] = '1';
		$item['language'] = '*';
		$item['featured'] = '0';
		$item['metadesc'] = '';
		$item['metakey'] = '';
		$item['meta'] = array('robots' => '', 'author' => '');
		
		//////
		$item['id'] = $id;
		$item['created_by'] = $post['created_by'];
		$item['created_by_alias'] = html_entity_decode($post['source_author'], ENT_COMPAT, 'UTF-8');
		$item['catid'] = $post['catid'];
		$item['alias'] = $post['source_alias'];
		$item['title'] = html_entity_decode($post['source_title'], ENT_COMPAT, 'UTF-8');
		$item['created'] = $this->dateToDb($post['source_published_date']);
		$item['publish_up'] = '';
		$item['publish_down'] = '';
		
		$image = '';
		if(isset($post['source_images']) && is_object($post['source_images'])) {
			$img = $post['source_images'];
			$image = '<img class="'.$source.'-img ja-social-img" title="'. htmlspecialchars($img->image_intro_caption, ENT_QUOTES, 'UTF-8') .'" alt="'. htmlspecialchars($img->image_intro_alt, ENT_QUOTES, 'UTF-8') .'" src="'.$img->image_intro.'" />';
		}

		$txt = '<div class="'.$source.'-item">';
		$txt .= '<div class="content">' . $image . preg_replace('@<img @', '<img class="'.$source.'-img ja-social-img" ', $post['source_content']) . '</div>';
		//$txt .= '<div class="readmore"><a class="readmore" href="'.$post['source_url']. '" title="'.$post['source_url_txt'].'" target="_blank">'.$post['source_url_txt'].'</a></div>';
		$txt .= '</div>';

		$item['introtext'] = html_entity_decode($txt, ENT_COMPAT, 'UTF-8');
		$item['fulltext'] = '';

		//$item['extra_fields'] = json_encode(array(array('id' => 1, 'value' => $source)));

		//save article
		$this->table->load($id);
		$this->table->bind($item);
		if($this->table->check()) {
			return $this->table->store();
		}
		return false;
	}
}