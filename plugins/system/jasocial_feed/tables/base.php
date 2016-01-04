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

abstract class jaSocialFeedTableBase extends JObject 
{
	public $table = null;
	public $plgParams = null;
	
	public function __construct($plgParams) {
		$this->plgParams = $plgParams;
	}
	/**
	 * check whether if component is installed or not
	 *
	 */
	abstract public function isAvailable();
	/**
	 * check whether if the article with given alias is existed or not
	 *
	 * @param string $alias
	 */
	abstract public function existsPost($alias);
	
	/**
	 * save article into database
	 *
	 * @param array $post
	 */
	abstract public function store($source, $post, $id = 0);
	
	public function dateToDb($date) {
		$date = date('Y-m-d H:i:s', strtotime($date));
		if(strpos($date, '1970-01-01') !== false) {
			$date = date('Y-m-d H:i:s');
		}
		return $date;
	}
}
