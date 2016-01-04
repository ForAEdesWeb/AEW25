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
if(!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}
set_time_limit(0);

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 *
 * JA social plugin system
 * @author JoomlArt
 *
 */
class plgSystemjasocial_feed extends JPlugin
{

	protected $plugin;
	protected $plgParams;

	protected $aInfo = array();
	protected $maxRedirect = 1;

	protected $update_article;
	protected $table_com;
	protected $target_com;
	protected $created_by;
	protected $save_image;
	protected $valid_img_width;
	protected $valid_img_height;
	protected $get_url_service;


	/**
     * Object Constructor.
     *
     * @access	public
     * @param	object	The object to observe -- event dispatcher.
     * @param	object	The configuration object for the plugin.
     * @return	void
     * @since	1.7
     */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		
		$mainframe = JFactory::getApplication();
		$this->plugin = JPluginHelper::getPlugin('system', 'jasocial_feed');
		// $this->plgParams = new JParameter($this->plugin->params);
		$this->plgParams = new JRegistry($this->plugin->params);
		$this->loadLanguage('plg_' . $this->plugin->type . '_' . $this->plugin->name, JPATH_ADMINISTRATOR);

		//init
		$this->update_article = $this->plgParams->get('update_article', 0);
		$this->target_com = $this->plgParams->get('target', 'joomla');
		$this->created_by = $this->plgParams->get('created_by', 42);
		$this->save_image = $this->plgParams->get('save_image', 0);
		$this->valid_img_width = $this->plgParams->get('valid_img_width', 360);
		$this->valid_img_height = $this->plgParams->get('valid_img_height', 180);
		$this->get_url_service = $this->plgParams->get('get_url_service', 'untiny.com');
		
		if($mainframe->isAdmin()){
			//$table = $this->getTable($this->target_com);
			return;
		}

		//fix imported data in early versions
		$db = JFactory::getDbo();
		$tables = $db->getTableList();
		$k2installed = in_array($db->getPrefix().'k2_items', $tables);

		$versions = array('1.1.1');
		foreach($versions as $ver) {
			$file = dirname(__FILE__).'/'.$ver.'.log';
			if(!JFile::exists($file)) {
				switch($ver) {
					case '1.1.1':
						JFile::write($file, date('Y-m-d H:i:s'));

						$query = "UPDATE #__content
									SET
										`introtext` = REPLACE(`introtext`, ".$db->quote('"/profile.php').", ".$db->quote('"https://www.facebook.com/profile.php')."),
										`fulltext` = REPLACE(`fulltext`, ".$db->quote('"/profile.php').", ".$db->quote('"https://www.facebook.com/profile.php').")
									WHERE `alias` LIKE 'facebook-id-%'";
						$db->setQuery($query);
						$db->execute();

						if($k2installed) {
							$query = "UPDATE #__k2_items
									SET
										`introtext` = REPLACE(`introtext`, ".$db->quote('"/profile.php').", ".$db->quote('"https://www.facebook.com/profile.php')."),
										`fulltext` = REPLACE(`fulltext`, ".$db->quote('"/profile.php').", ".$db->quote('"https://www.facebook.com/profile.php').")
									WHERE `alias` LIKE 'facebook-id-%'";
							$db->setQuery($query);
							$db->execute();
						}
						break;
				}
			}
		}
		
		$this->aInfo = $this->getCacheInfo();
	}

	function onAfterInitialise() {
		$mainframe = JFactory::getApplication();
		if($mainframe->isAdmin()){
			return;
		}
		$cron = JRequest::getInt('jasocial_feed_cron', 0);
		$next_run = $this->getNextRun();
		
		$jatoken = JRequest::getInt('jatoken', 0);
		$last_run = (isset($this->aInfo['last_run'])) ? $this->aInfo['last_run'] : 0;
		$cron_interval = $this->plgParams->get('cache_time', 3600);
		
		$diff = $last_run - $jatoken;
		$isRunbyadmin = ($diff >= 0 && $diff <= $cron_interval) ? 1 : 0;
		
		if(($next_run > time()) && (!$isRunbyadmin)) {
			return;
		}
		
		if(!$cron) {
			$document = JFactory::getDocument();
			$script = "
			function jaGetSocialFeed() {
				var jaSocialFeedReq = new Request({method: 'get', url: '".JURI::root()."/?jasocial_feed_cron=1'}).send();
			}
			window.addEvent('load', function() {
				setTimeout('jaGetSocialFeed()', 1000);
			});
			";
			$document->addScriptDeclaration($script);
		} else {
			ignore_user_abort(true);
			
			require_once(dirname(__FILE__).'/helpers.php');
			
			//update last run info to limit other people call request to get feed
			$this->updateCacheInfo(1);
			
			//order descreasing by processing speed
			$sources = array( 'vimeo', 'youtube','flickr', 'instagram' , 'facebook', 'twitter');
			
			foreach ($sources as $source) {
				$method = 'fetch'.ucfirst($source).'Post';
				$profiles = jaSocialFeedGetProfiles($source);
				if(count($profiles)) {
					foreach ($profiles as $profile => $pName) {
						$data = jaSocialFeedGetProfile($source, $profile);
						if(is_object($data)) {
							$status = $this->getProperty($data, $source.'_status', 1);
							if($status) {
								call_user_func_array(array($this, $method), array($data));
							}
						}
					}
				}
			}
			
			jexit('done');
		}
		return ;
	}
	
	private function getProperty($obj, $property, $default = '') {
		return isset($obj->$property) ? $obj->$property : $default;
	}
	
	private function getTable($source, $raiseMessage = 1) {
		static $tables = array();
		
		$source = strtolower($source);
		if(!isset($tables[$source])) {
			$table = false;
			if($source != 'base') {
				$path = dirname(__FILE__).'/tables/'.$source.'.php';
				if(JFile::exists($path)) {
					require_once($path);
					$className = 'jaSocialFeedTable'.ucfirst($source);
					if(class_exists($className)) {
						$table = new $className($this->plgParams);
						
						if(!$table->isAvailable()) {
							$table = false;
							if($raiseMessage) {
								JError::raiseWarning(100, JText::sprintf('%s component is not installed!', $source));
							}
						}
					} else {
						if($raiseMessage) {
							JError::raiseWarning(100, JText::sprintf('%s component is not supported! Please select another one.', $source));
						}
					}
				} else {
					if($raiseMessage) {
						JError::raiseWarning(100, JText::sprintf('%s component is not supported! Please select another one.', $source));
					}
				}
			} else {
				if($raiseMessage) {
					JError::raiseWarning(100, JText::_('This source is not available.'));
				}
			}
			$tables[$source] = $table;
		}
		return $tables[$source];
	}

	private function fetchFacebookPost($profile) {
		$fbid = $this->getProperty($profile, 'facebook_account');
		$target_com = $this->getProperty($profile, 'facebook_target', 'joomla');
		$tableContent = $this->getTable($target_com, 0);
		$catid = $this->getProperty($profile, 'facebook_'.$target_com.'_catid', 0);
		if(!$tableContent || !$fbid || !$catid) {
			return false;
		}

		$created_by = $this->getProperty($profile, 'facebook_created_by', 42);
		$getImg = $this->getProperty($profile, 'facebook_getimg', 1);
		$srcTxt = $this->getProperty($profile, 'facebook_sourcetxt', '(Source Facebook)');
		$limit = $this->getProperty($profile, 'facebook_limit_post', 20);
		$update_article = $this->getProperty($profile, 'facebook_update_article', 0);

		$artical_title = $this->getProperty($profile, 'facebook_title_txt', 'Facebook');
		$titleType = $this->getProperty($profile, 'facebook_title_type', 'author');
		
		//override global settings
		$this->save_image = $this->getProperty($profile, 'facebook_save_image', 0);
		$this->valid_img_width = $this->getProperty($profile, 'facebook_valid_img_width', 360);
		$this->valid_img_height = $this->getProperty($profile, 'facebook_valid_img_height', 180);
		
		
		//IMPORT PROCESS
		$fbKey = $fbid.'_';

		$last_insert_id = isset($this->aInfo[$fbKey.'fb_last_id']) ? $this->aInfo[$fbKey.'fb_last_id'] : '';

		$fetchUrl = "http://www.facebook.com/feeds/page.php?id=".$fbid."&format=json";
		$content = $this->getContent($fetchUrl);

		$data = json_decode($content);

		$numResult = 0;
		//$aResult = array();

		if(is_object($data) && isset($data->entries) && is_array($data->entries)) {
			for ($i=0; $i<count($data->entries); $i++) {
				if($numResult >= $limit) {
					break;
				}
				$dt = $data->entries[$i];

				/*if($dt->id == $last_insert_id) {
					//remain posts are imported => skipable
					break;
				}*/

				$alias = JApplication::stringURLSafe('facebook-id-'.$dt->id);

				$oldId = $tableContent->existsPost($alias);
				if($oldId && !$update_article) {
					continue;
				}

				if($i == 0) {
					$this->aInfo[$fbKey.'fb_last_id'] = $dt->id;
				}

				//$tcontent = !(trim($dt->title) == '') ? $dt->title . '<br />' . $dt->content : $dt->content;
				$tcontent = (trim($dt->content) != '') ? $dt->content : $dt->title;
				
				$testContent = preg_replace('/[\r\n\s\t]+/', '', $tcontent);
				if(empty($testContent)) {
					continue;
				}
				
				$tcontent = $this->updateFacebookImage($tcontent);
				
				//update url
				$pattern = '/href="\/l.php\?u=(http[^\'"&]+)[^\'"]*"/i';
				$tcontent = preg_replace_callback($pattern, 
						create_function(
							'$matches',
							'return "href=\"".rawurldecode($matches[1])."\"";'
						),
						$tcontent
					);

				$tcontent = str_replace(array('"/profile.php', '"profile.php'), '"https://www.facebook.com/profile.php', $tcontent);
				
				$post = array();
				$post['source_alias'] = $alias;
				$post['source_title'] = ($titleType == 'author' && isset($dt->author->name)) ? $dt->author->name : $artical_title;
				$post['created_by'] = $created_by;
				$post['source_author'] = isset($dt->author->name) ? $dt->author->name : '';
				$post['catid'] = $catid;
				$post['source_published_date'] = $dt->published;
				$post['source_url'] = $dt->alternate;
				$post['source_url_txt'] = $srcTxt;
				//images
				if($getImg) {
					$img_caption = $this->getImageCaption($tcontent);
					$post['source_images'] = $this->getFacebookImage($tcontent, $img_caption, $img_caption);
				} else {
					$post['source_images'] = false;
				}
				$post['source_content'] = $tcontent;

				$tableContent->store('facebook', $post, $oldId);
				//
				$numResult++;
			}
		}
		//return $aResult;
		$this->updateCacheInfo(0);
		return ;
	}

	/**
	 * fetchTwitterPost
	 * get tweet from Twitter by given query string
	 * @see https://dev.twitter.com/docs/api/1/get/search
	 *
	 */
	private function fetchTwitterPost ($profile) {
		require_once (dirname(__FILE__)  . '/TwitterAPIExchange.php');

		$queryString = $this->getProperty($profile, 'twitter_account');
		$target_com = $this->getProperty($profile, 'twitter_target', 'joomla');
		$tableContent = $this->getTable($target_com, 0);
		$catid = $this->getProperty($profile, 'twitter_'.$target_com.'_catid', 0);
		if(!$tableContent || !$queryString || !$catid) {
			return false;
		}

		$created_by = $this->getProperty($profile, 'twitter_created_by', 42);
		$getImg = $this->getProperty($profile, 'twitter_getimg', 1);
		$srcTxt = $this->getProperty($profile, 'twitter_sourcetxt', '(Source Twitter)');
		$limit = $this->getProperty($profile, 'twitter_limit_post', 20);
		$update_article = $this->getProperty($profile, 'twitter_update_article', 0);

		$artical_title = $this->getProperty($profile, 'twitter_title_txt', 'Twitter');
		$titleType = $this->getProperty($profile, 'twitter_title_type', 'author');
		$getRetweet = (int) $this->getProperty($profile, 'twitter_fetch_retweet', 0);
		
		//override global settings
		$this->save_image = $this->getProperty($profile, 'twitter_save_image', 0);
		$this->valid_img_width = $this->getProperty($profile, 'twitter_valid_img_width', 360);
		$this->valid_img_height = $this->getProperty($profile, 'twitter_valid_img_height', 180);
		
		/**
		 * - rpp: The number of tweets to return per page, up to a max of 100.
		 * - page: The page number (starting at 1) to return, up to a max of roughly 1500 results (based on rpp * page).
		 * - since_id: Returns results with an ID greater than (that is, more recent than) the specified ID. There are limits to the number of Tweets which can be accessed through the API. If the limit of Tweets has occured since the since_id, the since_id will be forced to the oldest ID available.
		 */
		$rpp = ($limit < 100) ? $limit : 100;
		$page = 1;

		$twKey = md5(trim($queryString)) . '_';

		$since_id = isset($this->aInfo[$twKey.'tw_since_id']) ? $this->aInfo[$twKey.'tw_since_id'] : '';
		//$url = 'http://search.twitter.com/search.json?';
		$url = 'https://api.twitter.com/1.1/search/tweets.json';

		$total = 0;
		//$aResult = array();

		$settings = array(
			'consumer_key' => $this->params->get('consumer_key', ''),
			'consumer_secret' => $this->params->get('consumer_secret', ''),
			'oauth_access_token' => $this->params->get('oauth_access_token', ''),
			'oauth_access_token_secret' => $this->params->get('oauth_access_token_secret', '')
		);

		$requestMethod = 'GET';
		
		$next_results = '';
		do {
			
			if(empty($next_results) || !$next_results) {
				$params = '?q='.rawurlencode($queryString).'&count='.$rpp;
			} else {
				$params = $next_results;
			}

			$twitter = new TwitterAPIExchange($settings);
			$result = $twitter->setGetfield($params)
					->buildOauth($url, $requestMethod)
					->performRequest();
			
			$data = json_decode($result);
			if(isset($data->errors) && count($data->errors)) {
				echo 'Twitter: '.$data->errors[0]->message . '<br />';
				return ;
			}
			
			if(is_object($data) && isset($data->search_metadata)) {
				$next_results = @$data->search_metadata->next_results;
			} else {
				$next_results = '';
			}

			$numResult = 0;
			if(is_object($data) && isset($data->statuses) && is_array($data->statuses)) {
				for ($i=0; $i<count($data->statuses); $i++) {
					$numResult++;
					$dt = $data->statuses[$i];
					$alias = JApplication::stringURLSafe('twitter-id-'.$dt->id_str);

					$oldId = $tableContent->existsPost($alias);
					if($oldId && !$update_article) {
						continue;
					}

					if(!$getRetweet) {
						if($this->isRetweet($dt->text)) {
							continue;
						}
					}
					
					$user = $dt->user;
					$post = array();
					$post['source_alias'] = $alias;
					$post['source_title'] = ($titleType == 'author' && isset($user->name)) ? $user->name : $artical_title;
					$post['created_by'] = $created_by;
					$post['source_author'] = isset($user->name) ? $user->name : '';
					$post['catid'] = $catid;
					$post['source_published_date'] = $dt->created_at;
					$post['source_url'] = 'http://twitter.com/'.$user->id_str. '/statuses/'.$dt->id_str;
					$post['source_url_txt'] = $srcTxt;
					//images
					if($getImg) {
						$img_caption = $this->getImageCaption($dt->text);
						$post['source_images'] = $this->getTweetImage($dt->text, $img_caption, $img_caption);
					} else {
						$post['source_images'] = false;
					}
					$post['source_content'] = $this->updateTweetUrls($dt->text);
	
					$tableContent->store('twitter', $post, $oldId);
				}
			}

			//get next page
			$page++;
			$total += $numResult;
		} while (($total < $limit) && ($page <= 15) && ($numResult == $rpp) && (!(empty($next_results) || !$next_results)));

		//update log
		$this->aInfo[$twKey.'tw_since_id'] = $since_id;

		//return $aResult;
		$this->updateCacheInfo(0);
		return ;
	}
	
	
	/**
	 * fetchYoutubeVideo
	 * get tweet from Youtube by given username
	 * @see http://gdata.youtube.com/feeds/base/users/joomlart/uploads?orderby=updated&alt=json
	 *
	 */
	private function fetchYoutubePost ($profile) {
		$ytName = $this->getProperty($profile, 'youtube_account');
		$target_com = $this->getProperty($profile, 'youtube_target', 'joomla');
		$tableContent = $this->getTable($target_com, 0);
		$catid = $this->getProperty($profile, 'youtube_'.$target_com.'_catid', 0);
		if(!$tableContent || !$ytName || !$catid) {
			return false;
		}

		$created_by = $this->getProperty($profile, 'youtube_created_by', 42);
		$getImg = $this->getProperty($profile, 'youtube_getimg', 1);
		$srcTxt = $this->getProperty($profile, 'youtube_sourcetxt', '(Source YouTube)');
		$limit = $this->getProperty($profile, 'youtube_limit_post', 20);
		$update_article = $this->getProperty($profile, 'youtube_update_article', 0);
		
		//override global settings
		$this->save_image = $this->getProperty($profile, 'youtube_save_image', 0);
		$this->valid_img_width = $this->getProperty($profile, 'youtube_valid_img_width', 360);
		$this->valid_img_height = $this->getProperty($profile, 'youtube_valid_img_height', 180);
		
		//video settings
		$youtube_video_width 				= (int) $this->getProperty($profile, 'youtube_video_width', 480);
		$youtube_video_height	 			= (int) $this->getProperty($profile, 'youtube_video_height', 360);
		$youtube_show_suggested 			= (int) $this->getProperty($profile, 'youtube_show_suggested', 1);
		$youtube_use_https 					= (int) $this->getProperty($profile, 'youtube_use_https', 0);
		$youtube_privacy_enhanced_mode 		= (int) $this->getProperty($profile, 'youtube_privacy_enhanced_mode', 0);
		$youtube_use_old_code 				= (int) $this->getProperty($profile, 'youtube_use_old_code', 0);
		
		$embedCode = '';
		
		$url_method = $youtube_use_https ? 'https' : 'http';
		if($youtube_privacy_enhanced_mode) {
			$youtube_url = $url_method.'://www.youtube-nocookie.com/';
		} else {
			$youtube_url = $url_method.'://www.youtube.com/';
		}
		
		if($youtube_use_old_code) {
			$youtube_url .= 'v/{youtube_id}?version=3&amp;hl=en_US';
			if(!$youtube_show_suggested) {
				$youtube_url .= '&amp;rel=0';
			}
			$embedCode = '
			<object width="'.$youtube_video_width.'" height="'.$youtube_video_height.'" id="yt-player-{youtube_id}">
			<param name="movie" value="'.$youtube_url.'"></param>
			<param name="allowFullScreen" value="true"></param>
			<param name="allowscriptaccess" value="always"></param>
			<param name="wmode" value="transparent"></param>
			<embed src="'.$youtube_url.'" type="application/x-shockwave-flash" width="'.$youtube_video_width.'" height="'.$youtube_video_height.'" allowscriptaccess="always" allowfullscreen="true" wmode="transparent"></embed>
			</object>';
		} else {
			$youtube_url .= 'embed/{youtube_id}?wmode=transparent';
			if(!$youtube_show_suggested) {
				$youtube_url .= '&amp;rel=0';
			}
			$embedCode = '<iframe class="youtube-player" width="'.$youtube_video_width.'" height="'.$youtube_video_height.'" src="'.$youtube_url.'" wmode="Opaque" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		}
		
		//IMPORT PROCESS
		$ytKey = $ytName.'_';
		
		$last_insert_id = isset($this->aInfo[$ytKey.'fb_last_id']) ? $this->aInfo[$ytKey.'fb_last_id'] : '';
		
		$fetchUrl = "http://gdata.youtube.com/feeds/base/users/".$ytName."/uploads?orderby=updated&alt=json";
		$content = $this->getContent($fetchUrl);

		$data = json_decode($content);

		$numResult = 0;
		//$aResult = array();

		if(is_object($data) && isset($data->feed->entry) && is_array($data->feed->entry)) {
			
			$author = isset($data->feed->author) ? @$data->feed->author[0] : false;
			$authorName = $author ? @$author->name->{'$t'} : '';
			
			for ($i=0; $i<count($data->feed->entry); $i++) {
				if($numResult >= $limit) {
					break;
				}
				
				$dt = $data->feed->entry[$i];
				$etid = str_replace('http://gdata.youtube.com/feeds/base/videos/','',$dt->id->{'$t'});
                
				$alias = JApplication::stringURLSafe('youtube-id-'.$etid);
				$oldId = $tableContent->existsPost($alias);
				if($oldId && !$update_article) {
					continue;
				}
				
				if($i == 0) {
					$this->aInfo[$ytKey.'yt_last_id'] = $etid;
				}
				
				$title = $dt->title->{'$t'};
				
				$tcontent = '<div class="player">'.str_replace('{youtube_id}', $etid, $embedCode).'</div>';
				$tcontent .= $this->getYoutubeContent($dt->content->{'$t'});
				
				if(trim($title) == '') {
					continue;
				}
				
				$post = array();
				$post['source_alias'] = $alias;
				$post['source_title'] = $title;
				$post['created_by'] = $created_by;
				$post['source_author'] = $authorName;
				$post['catid'] = $catid;
				$post['source_published_date'] = $dt->published->{'$t'};
				$post['source_url'] = 'http://www.youtube.com/watch?v='.$etid;
				$post['source_id'] = $etid;
				$post['source_url_txt'] = $srcTxt;
				//images
				if($getImg) {
    				$img_caption = $title;
					//no need check size for image getting from youtube, since they was cropped to the same size by youtube
					//$post['source_images'] = $this->getArticleImageObj("http://img.youtube.com/vi/".$etid."/0.jpg", $img_caption, $img_caption, 'youtube');
					$post['source_images'] = $this->saveImage("http://img.youtube.com/vi/".$etid."/0.jpg", 'youtube', $img_caption, $img_caption);
				} else {
					$post['source_images'] = false;
				}
				$post['source_content'] = $tcontent;

				$tableContent->store('youtube', $post, $oldId);
				//
				$numResult++;
			}
		}
		//return $aResult;
		$this->updateCacheInfo(0);
		return ;
	}
	
	private function fetchVimeoPost ($profile) {
		$vmAccType = $this->getProperty($profile, 'vimeo_account_type');
		$vmName = $this->getProperty($profile, 'vimeo_account');
		$target_com = $this->getProperty($profile, 'vimeo_target', 'joomla');
		$tableContent = $this->getTable($target_com, 0);
		$catid = $this->getProperty($profile, 'vimeo_'.$target_com.'_catid', 0);
		if(!$tableContent || !$vmName || !$catid) {
			return false;
		}

		$created_by = $this->getProperty($profile, 'vimeo_created_by', 42);
		$getImg = $this->getProperty($profile, 'vimeo_getimg', 1);
		$srcTxt = $this->getProperty($profile, 'vimeo_sourcetxt', '(Source Vimeo)');
		$limit = $this->getProperty($profile, 'vimeo_limit_post', 20);
		$update_article = $this->getProperty($profile, 'vimeo_update_article', 0);
		
		//override global settings
		$this->save_image = $this->getProperty($profile, 'vimeo_save_image', 0);
		$this->valid_img_width = $this->getProperty($profile, 'vimeo_valid_img_width', 360);
		$this->valid_img_height = $this->getProperty($profile, 'vimeo_valid_img_height', 180);
		
		//video settings
		$vimeo_video_width 				= (int) $this->getProperty($profile, 'vimeo_video_width', 500);
		$vimeo_video_height	 			= (int) $this->getProperty($profile, 'vimeo_video_height', 281);
		$vimeo_autoplay 			= (int) $this->getProperty($profile, 'vimeo_autoplay', 0);
		$vimeo_loop 					= (int) $this->getProperty($profile, 'vimeo_loop', 0);
		$vimeo_use_old_code 				= (int) $this->getProperty($profile, 'vimeo_use_old_code', 0);
		
		$embedCode = '';
				
		if($vimeo_use_old_code) {
			$vimeo_url = 'http://vimeo.com/moogaloop.swf?clip_id={vimeo_id}&amp;force_embed=1&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=90d5ec&amp;fullscreen=1';
			if($vimeo_autoplay) $vimeo_url .= '&amp;autoplay=1';
			if($vimeo_loop) $vimeo_url .= '&amp;loop=1';
			$embedCode = '
				<object width="'.$vimeo_video_width.'" height="'.$vimeo_video_height.'" id="vm-player-{vimeo_id}">
				<param name="allowfullscreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="wmode" value="transparent" />
				<param name="movie" value="'.$vimeo_url.'" />
				<embed src="'.$vimeo_url.'" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" wmode="transparent" width="'.$vimeo_video_width.'" height="'.$vimeo_video_height.'"></embed>
				</object>';
		} else {
			$vimeo_url = 'http://player.vimeo.com/video/{vimeo_id}?title=0&amp;byline=0&amp;portrait=0&amp;color=90d5ec';
			if($vimeo_autoplay) $vimeo_url .= '&amp;autoplay=1';
			if($vimeo_loop) $vimeo_url .= '&amp;loop=1';
			$embedCode = '<iframe src="'.$vimeo_url.'" width="'.$vimeo_video_width.'" height="'.$vimeo_video_height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		}
		
		//IMPORT PROCESS
		$vmKey = $vmName.'_';
		
		$last_insert_id = isset($this->aInfo[$vmKey.'fb_last_id']) ? $this->aInfo[$vmKey.'fb_last_id'] : '';
		
		$fetchUrl = '';
		switch ($vmAccType) {
			case 'user': $fetchUrl = 'http://vimeo.com/api/v2/'.$vmName.'/all_videos.json'; break;
			case 'album': $fetchUrl = 'http://vimeo.com/api/v2/album/'.$vmName.'/videos.json'; break;
			case 'group': $fetchUrl = 'http://vimeo.com/api/v2/group/'.$vmName.'/videos.json'; break;
			case 'channel': $fetchUrl = 'http://vimeo.com/api/v2/channel/'.$vmName.'/videos.json'; break;
		}
		$content = $this->getContent($fetchUrl);

		$data = json_decode($content);

		$numResult = 0;
		//$aResult = array();

		if(is_array($data) && count($data)) {
			
			for ($i=0; $i<count($data); $i++) {
				if($numResult >= $limit) {
					break;
				}
				
				$dt = $data[$i];
				$etid = $dt->id;
                
				$alias = JApplication::stringURLSafe('vimeo-id-'.$etid);
				$oldId = $tableContent->existsPost($alias);
				if($oldId && !$update_article) {
					continue;
				}
				
				if($i == 0) {
					$this->aInfo[$vmKey.'vm_last_id'] = $etid;
				}
				
				$title = $dt->title;
				
				$tcontent = '<div class="player">'.str_replace('{vimeo_id}', $etid, $embedCode).'</div>';
				$tcontent .= '<p>'.$dt->description.'</p>';
				
				$post = array();
				$post['source_alias'] = $alias;
				$post['source_title'] = $title;
				$post['created_by'] = $created_by;
				$post['source_author'] = $dt->user_name;
				$post['catid'] = $catid;
				$post['source_published_date'] = $dt->upload_date;
				$post['source_url'] = $dt->url;
				$post['source_id'] = $etid;
				$post['source_url_txt'] = $srcTxt;
				//images
				if($getImg) {
    				$img_caption = $title;
					//no need check size for image getting from vimeo, since they was cropped to the same size by vimeo
					$post['source_images'] = $this->saveImage($dt->thumbnail_large, 'vimeo', $img_caption, $img_caption);
				} else {
					$post['source_images'] = false;
				}
				$post['source_content'] = $tcontent;

				$tableContent->store('vimeo', $post, $oldId);
				//
				$numResult++;
			}
		}
		//return $aResult;
		$this->updateCacheInfo(0);
		return ;
	}
	
	private function fetchInstagramPost ($profile) {
		$igid = $this->getProperty($profile, 'instagram_account');
		$target_com = $this->getProperty($profile, 'instagram_target', 'joomla');
		$tableContent = $this->getTable($target_com, 0);
		$catid = $this->getProperty($profile, 'instagram_'.$target_com.'_catid', 0);
		if(!$tableContent || !$igid || !$catid) {
			return false;
		}

		$created_by = $this->getProperty($profile, 'instagram_created_by', 42);
		$getImg = $this->getProperty($profile, 'instagram_getimg', 1);
		$srcTxt = $this->getProperty($profile, 'instagram_sourcetxt', '(Source Instagram)');
		$limit = $this->getProperty($profile, 'instagram_limit_post', 20);
		$update_article = $this->getProperty($profile, 'instagram_update_article', 0);

		$artical_title = $this->getProperty($profile, 'instagram_title_txt', 'Instagram');
		$titleType = $this->getProperty($profile, 'instagram_title_type', 'author');
		
		//override global settings
		$this->save_image = $this->getProperty($profile, 'instagram_save_image', 0);
		$this->valid_img_width = $this->getProperty($profile, 'instagram_valid_img_width', 360);
		$this->valid_img_height = $this->getProperty($profile, 'instagram_valid_img_height', 180);
		
		
		//IMPORT PROCESS
		$igKey = $igid.'_';

		$last_insert_id = isset($this->aInfo[$igKey.'ig_last_id']) ? $this->aInfo[$igKey.'ig_last_id'] : '';

		$items = $this->getInstagramItems($igid, $limit);

		$numResult = 0;
		//$aResult = array();

		if(count($items)) {
			$i = 0;
			foreach ($items as $dt) {
				$i++;

				/*if($dt->id == $last_insert_id && !$update_article) {
					//remain posts are imported => skipable
					break;
				}*/

				$alias = JApplication::stringURLSafe('instagram-id-'.$dt->id);

				$oldId = $tableContent->existsPost($alias);
				if($oldId && !$update_article) {
					continue;
				}

				if($i == 1) {
					$this->aInfo[$igKey.'ig_last_id'] = $dt->id;
				}

				$title = $this->getPostTitle($dt->title);
				$tcontent = $dt->title;
				
				$post = array();
				$post['source_alias'] = $alias;
				$post['source_title'] = $title;
				$post['created_by'] = $created_by;
				$post['source_author'] = $dt->author;
				$post['catid'] = $catid;
				$post['source_published_date'] = $dt->pubDate;
				$post['source_url'] = $dt->link;
				$post['source_url_txt'] = $srcTxt;
				//images
				$post['source_images'] = $this->saveImage($dt->image, 'instagram', $title, $title);
				$post['source_content'] = $tcontent;

				$tableContent->store('instagram', $post, $oldId);
				//
				$numResult++;
			}
		}
		//return $aResult;
		$this->updateCacheInfo(0);
		return ;
	}
	
	
	private function fetchFlickrPost ($profile) {
		$frid = $this->getProperty($profile, 'flickr_account');
		$target_com = $this->getProperty($profile, 'flickr_target', 'joomla');
		$tableContent = $this->getTable($target_com, 0);
		$catid = $this->getProperty($profile, 'flickr_'.$target_com.'_catid', 0);
		if(!$tableContent || !$frid || !$catid) {
			return false;
		}

		$created_by = $this->getProperty($profile, 'flickr_created_by', 42);
		$getImg = $this->getProperty($profile, 'flickr_getimg', 1);
		$srcTxt = $this->getProperty($profile, 'flickr_sourcetxt', '(Source Flickr)');
		$limit = $this->getProperty($profile, 'flickr_limit_post', 20);
		$update_article = $this->getProperty($profile, 'flickr_update_article', 0);

		$artical_title = $this->getProperty($profile, 'flickr_title_txt', 'Flickr');
		$titleType = $this->getProperty($profile, 'flickr_title_type', 'author');
		
		//override global settings
		$this->save_image = $this->getProperty($profile, 'flickr_save_image', 0);
		$this->valid_img_width = $this->getProperty($profile, 'flickr_valid_img_width', 360);
		$this->valid_img_hefrht = $this->getProperty($profile, 'flickr_valid_img_hefrht', 180);
		
		
		$flickr_get_favorite_photos = $this->getProperty($profile, 'flickr_get_favorite_photos', 1);
		//IMPORT PROCESS
		$frKey = $frid.'_';

		$last_insert_id = isset($this->aInfo[$frKey.'fr_last_id']) ? $this->aInfo[$frKey.'fr_last_id'] : '';

		//$items = $this->getFlickrItems($frid, $limit);
		$regex = '/\d+\@[^\@]+/';
		$searchById = 0;
		$fetchUrl = 'http://api.flickr.com/services/feeds/photos_public.gne?format=php_serial';
		if(preg_match($regex, $frid)) {
			$searchById = 1;
			$fetchUrl .= '&id='.$frid;
		} else {
			$fetchUrl .= '&tags='.$frid;
		}
		
		$content = $this->getContent($fetchUrl);
		$data = unserialize($content);

		$numResult = 0;
		//$aResult = array();
		
		$items = array();
		if(isset($data['items']) && is_array($data['items']) && count($data['items'])) {
			$items = $data['items'];
		}
		
		//Favorite Photos
		if($searchById && $flickr_get_favorite_photos) {
			$fetchUrl2 = 'http://api.flickr.com/services/feeds/photos_faves.gne?format=php_serial&id='.$frid;
			$content2 = $this->getContent($fetchUrl2);
			$data2 = unserialize($content2);
			
			if(isset($data2['items']) && is_array($data2['items']) && count($data2['items'])) {
				foreach ($data2['items'] as $dt) {
					$items[] = $dt;
				}
			}
		}

		if(count($items)) {
			$i = 0;
			foreach ($items as $dt) {
				$i++;
				if($numResult >= $limit) {
					break;
				}
				
				//$dt['id'] = preg_replace('/.*?([0-9]+)\/*$/', '$1', $dt->link);
				$dt['id'] = str_replace('/', '', $dt['guid']);

				$alias = JApplication::stringURLSafe('flickr-id-'.$dt['id']);

				$oldId = $tableContent->existsPost($alias);
				if($oldId && !$update_article) {
					continue;
				}

				if($i == 1) {
					$this->aInfo[$frKey.'fr_last_id'] = $dt['id'];
				}

				$title = $dt['title'];
				$tcontent = $dt['description'];
				
				$post = array();
				$post['source_alias'] = $alias;
				$post['source_title'] = $title;
				$post['created_by'] = $created_by;
				$post['source_author'] = $dt['author_name'];
				$post['catid'] = $catid;
				$post['source_published_date'] = date('Y-m-d', $dt['date']);
				$post['source_url'] = $dt['url'];
				$post['source_url_txt'] = $srcTxt;
				//images
				//$largeImage = preg_replace('/_[a-z]{1}(\.(?:jpg|jpeg|png|gif|bmp))$/i', '_z$1', $dt->media->m);
				$post['source_images'] = $this->saveImage($dt['l_url'], 'flickr', $title, $title);
				$post['source_content'] = $tcontent;

				$tableContent->store('flickr', $post, $oldId);
				//
				$numResult++;
			}
		}
		
		//return $aResult;
		$this->updateCacheInfo(0);
		return ;
	}
	
	private function checkImgSize($url) {
		$result = @getimagesize($url);
		if($result) {
			list($width, $height, $type, $attr) = $result;
			//get only image with width is lager than 360px
			if($width < $this->valid_img_width || $height < $this->valid_img_height) {
				return false;
			}
		} else {
			return false;
		}
		return true;
	}

	private function saveImage($url, $src, $caption = '', $alt = '') {
		if($this->save_image) {
			$folder = JPATH_ROOT.'/images/jasocial_feed/';
			$result = JFolder::create($folder);
			if($result) {
				$filename = basename($url);
				$filename = preg_replace('/\?.*?$/', '', $filename);
				$ext = JFile::getExt($filename);
				$ext = strtolower($ext);
				if($ext == 'php') $ext = 'jpg';
				$filename = md5($url).'.'.$ext;
		
				$file = $folder.$filename;
				
				if(!JFile::exists($file)) {
					if($src == 'facebook') {
						$validImg = $this->checkImgSize($url);
						if(!$validImg) {
							return false;
						}
					}
					
					$content = @file_get_contents($url);
					if($content) {
						$result = JFile::write($file, $content);
						if($result) {
							//update with local link
							$url = 'images/jasocial_feed/'.$filename;
						}
					}
					return $this->getArticleImageObj($url, $caption, $alt, $src);
				} else {
					$url = 'images/jasocial_feed/'.$filename;
					return $this->getArticleImageObj($url, $caption, $alt, $src);
				}
			}
		}
		
		if($src == 'facebook') {
			$validImg = $this->checkImgSize($url);
		} else {
			//image that get from twitter is checked before
			$validImg = true;
		}
		if($validImg) {
			return $this->getArticleImageObj($url, $caption, $alt, $src);
		} else {
			return false;
		}
		
	}
	
	private function getArticleImageObj($url, $caption = '', $alt = '', $src = '') {
		$image = new stdClass();
		
		$image->float_fulltext = '';
		$image->float_intro = '';
		$image->image_intro = $url;
		$image->image_intro_alt = $alt;
		$image->image_intro_caption = $caption;
		$image->image_fulltext = $url;
		$image->image_fulltext_alt = $alt;
		$image->image_fulltext_caption = $caption;
		
		if($src == 'youtube' || $src == 'vimeo'){
			$image->image_fulltext = '';
			$image->image_fulltext_alt = '';
			$image->image_fulltext_caption = '';
		}
		
		return $image;
	}
	
	private function getYoutubeContent($content){
		$pattern = '/<div style=\"font-size: 12px; margin: 3px 0px;\">(.*)<\/div>/';
		preg_match($pattern, $content, $matches);
		if(isset($matches[1])){
		    return strip_tags($matches[1]);
		
		}
		return false;
	
	}

	private function getFacebookImage($post, $caption, $alt) {
		
		$images = false;
		$aImages = $this->parseImageFromContent($post, '', 'facebook');
		$aImages2 = $this->getValidImages($aImages);
		
		if(count($aImages2)) {
			$url = $aImages2[0];
			$images = $this->saveImage($url, 'twitter', $caption, $alt);
		}
		return $images;
	}
	
	private function updateFacebookImage($post) {
		$pattern = '/http[^\'\"\>\<]+(?:safe_image|app_full_proxy).php\?([^\"\'\>]+)/i';
		preg_match($pattern, $post, $matches);
		if(isset($matches[1])) {
			$params = str_replace('&amp;', '&', $matches[1]);

			$patternUrl = '/\&(?:url|src)=([^\"\'\&]+)/';
			preg_match($patternUrl, $params, $matches2);
			if(isset($matches2[1])) {
				$url = rawurldecode($matches2[1]);
				$post = str_replace($matches[0], $url, $post);
			}
		}
		return $post;
	}

	private function getTweetImage($tweet, $caption, $alt) {
		$url = $this->parseTweetUrls($tweet);
		if($url) {
			$info = $this->parsePageInfo($url);
			if(count($info['images'])) {
				$url = $info['images'][0];
				return $this->saveImage($url, 'twitter', $caption, $alt);
			}
		}
		return false;
	}

	private function isRetweet($content) {
		return (preg_match('/^[\s\t\r\n]*RT/', $content) ? 1 : 0);
	}

	private function updateTweetUrls($content) {
		$maxLen = 16;
		//split long words
		$pattern = '/[^\s\t]{'.$maxLen.'}[^\s\.\,\+\-\_]+/';
		$content = preg_replace($pattern, '$0 ', $content);

		//
		$pattern = '/\w{2,4}\:\/\/[^\s\"]+/';
		$content = preg_replace($pattern, '<a href="$0" title="" target="_blank">$0</a>', $content);

		//search
		$pattern = '/\#([a-zA-Z0-9_-]+)/';
		$content = preg_replace($pattern, '<a href="https://twitter.com/#%21/search/%23$1" title="" target="_blank">$0</a>', $content);

		//user
		$pattern = '/\@([a-zA-Z0-9_-]+)/';
		$content = preg_replace($pattern, '<a href="https://twitter.com/#!/$1" title="" target="_blank">$0</a>', $content);

		return $content;
	}

	private function parseTweetUrls($content) {
		//
		$pattern = '/\w{2,4}\:\/\/[^\s\"]+/';
		preg_match($pattern, $content, $matches);

		if(isset($matches[0])) {
			return $this->getRealUrl($matches[0]);
		} else {
			return false;
		}
	}
	
	private function getPostTitle($txt) {
		$txt = strip_tags($txt);
		$txt = preg_replace('/[^a-zA-Z0-9\.\,\-\_\?\!\@\#\s]/', '', $txt);
		if(strlen($txt) > 64) {
			$posDot = strpos($txt, '.', 32);
			$posCom = strpos($txt, ',', 32);
			if($posDot !== false && $posDot < 64) {
				$txt = substr($txt, 0, $posDot);
			} elseif($posCom !== false && $posCom < 64) {
				$txt = substr($txt, 0, $posCom);
			} else {
				$txt = substr($txt, 0, 64);
			}
		}
		
		return $txt;
	}

	private function getImageCaption($txt) {
		$txt = strip_tags($txt);
		$txt = preg_replace('/[\s\t\r\n]+/', ' ', $txt);
		$txt = substr($txt, 0, 32);
		return $txt;
	}

	private function getRealUrl($shortenUrl) {
		if($this->get_url_service == 'realurl.org') {
			
			//E.g: http://bit.ly/10QRTY
			$checkUrl = 'http://realurl.org/api/v1/getrealurl.php?url='.rawurlencode($shortenUrl);
			$content = $this->getContent($checkUrl);
	
			$pattern = '/<real>([\s\S]*?)<\/real>/i';
			preg_match($pattern, $content, $matches);
			if(isset($matches[1])) {
				return trim($matches[1]);
			} else {
				return $shortenUrl;
			}
		} else {
			//untiny.com
			
			$checkUrl = 'http://www.untiny.com/api/1.0/extract/?url='.base64_encode($shortenUrl).'&format=text&enc=64al';
			$realUrl = $this->getContent($checkUrl);
			if($realUrl) {
				return $realUrl;
			} else {
				return $shortenUrl;
			}
		}
	}

	function parsePageInfo($url) {
		$aImages = array();
		$page_title = '';
		$urlIsImg = 0;
		if($this->urlIsImage($url)) {
			$aImages[] = $url;
			$urlIsImg = 1;
		} else {
			if(strpos($url, 'twitter.com') !== false) {
				$url = str_replace('http:', 'https:', $url);
			}
			$content = $this->getContent($url);
			$aImages = $this->parseImageFromContent($content, $url);
		}
		$aImages2 = $this->getValidImages($aImages);

		return array('images' => $aImages2, 'title' => $page_title);
	}
	
	private function getValidImages($aImages, $limit = 1) {
		$aImages2 = array();
		if(count($aImages)) {
			$cnt = 0;
			foreach ($aImages as $img) {
				//if(!is_file($img)) continue;
				$isValidImg = $this->checkImgSize($img);
				if($isValidImg) {
					$cnt++;
					$aImages2[] = $img;
					if($cnt >= $limit) {
						break;//get only 1 images
					}
				}
			}
		}
		return $aImages2;
	}
	
	private function parseImageFromContent($content, $url = '', $source = '') {
		$aImages = array();
		$aIgnore = $this->getIgnoreWords();
		$numIngnore = count($aIgnore);

		$pattern = '/<img[^>]+src\s*=\s*["\']([^"\']+)["\']/i';
		preg_match_all($pattern, $content, $matches);
		if(isset($matches[1])) {
			for ($i = 0; $i < count($matches[1]); $i++) {
				$imgName = basename($matches[1][$i]);
				$imgName = strtolower($imgName);
				$ignore = 0;
				for($j=0; $j < $numIngnore; $j++) {
					if(strpos($imgName, $aIgnore[$j]) !== false) {
						$ignore = 1;
						break;
					}
				}
				if(!$ignore) {
					$aImages[] = $matches[1][$i];
				}
			}
		}

		//UPDATE URLS TO ABSOLUTE FORMAT
		if(!empty($url)) {
			$domain = preg_replace('/(^\w+\:\/\/[^\/]+).*/i', '$1', $url);
			$path = preg_replace('/^\w+\:\/\/[^\/]+/i', '', $url);//remove domain
			$path = preg_replace('/\?.*$/i', '', $path);//remove query string
			$paths = explode('/', $path);
	
			$aPaths = array();
			if(count($paths)) {
				$path = $domain.'/';
				$aPaths[] = $path;
				for ($i=0; $i<count($paths); $i++) {
					if(!empty($paths[$i])) {
						$path .= $paths[$i].'/';
						$aPaths[] = $path;
					}
				}
			}
	
			if(!count($aPaths)) {
				$aPaths[] = $domain . '/';
			}
	
	
			$numPath = count($aPaths);
			if($numPath) {
				$pattern = '/^\w+\:\/\//i';
				$aImgTmp = array();
	
				$aCheck = array();
	
				for ($j=0; $j < $numPath; $j++) {
					for ($i = 0; $i < count($aImages); $i++) {
						if(isset($aCheck[$i])) continue;
	
						$img = $aImages[$i];
						$img = preg_replace('/^[\/\\\\]+/', '', $img);
						if(!preg_match($pattern, $img)) {
							$aImgTmp[] = $aPaths[$j] . $img;
						} else {
							$aImgTmp[] = $img;
							$aCheck[] = $i;
						}
					}
				}
				$aImgTmp = array_unique($aImgTmp);
	
				$aImages = $aImgTmp;
			}
		}
		
		if($source == 'facebook') {
			if(count($aImages)) {
				//JAECPLGSOCIALFEED-44 Get images from FB with better resolution
				$patternSmallImg = '/_s\.(jpg|jpeg|png|gif|bmp)$/i';
				foreach ($aImages as $index => $img) {
					if(strpos($img, 'fbcdn.net') !== false && preg_match($patternSmallImg, $img)) {
						$img = preg_replace($patternSmallImg, '_b.$1', $img);
						$aImages[$index] = $img;
					}
				}
			}
		}
		
		return $aImages;
	}

	private function urlIsImage($url) {
		$rPos = strrpos($url, '?');
		if($rPos !== false) {
			$url = substr($url, 0, $rPos);
		}
		if(preg_match('/\.(jpg|jpeg|png|gif|bmp)$/i', $url)) {
			return $url;
		}
		return false;
	}

	private function getContent($url) {
		if (function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 600);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
			if(strpos($url, 'https:') === 0) {
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			}
			$content = curl_exec($ch);
			curl_close($ch);
		} else {
			// curl library is not installed so we better use something else
			$content = @file_get_contents($url);
		}

		return $content;
	}

	private function getCacheFile() {
		$file = dirname(__FILE__).DS.'cache'.DS.'cache.txt';
		if(!JFile::exists($file)) {
			$content = '';
			$result = JFile::write($file, $content);
			if(!$result) {
				JError::raiseWarning(100, JText::_('Plugin JA Social Feed:: Can not create cache file'));
				return false;
			}
		}
		return $file;
	}
	
	private function tracelog($mesage){
	   $file = dirname(__FILE__).DS.'cache'.DS.'runhere.txt';
	    JFile::write($file, $mesage);
	}

	/**
	 * getCacheInfo
	 *
	 * @return array information of the last run
	 */
	private function getCacheInfo() {
		$aInfo = array();
		$file = $this->getCacheFile();
		if($file) {
			$content = JFile::read($file);
			$aItem = preg_split('/[\r*\n]+/', $content);
			for ($i=0; $i<count($aItem); $i++) {
				$item = explode(':', $aItem[$i], 2);
				if(count($item) == 2) {
					$aInfo[trim($item[0])] = trim($item[1]);
				}
			}
		}
		return $aInfo;
	}

	private function updateCacheInfo($updateTime = 1) {
		$content = '';
		if($updateTime) {
			$this->aInfo['last_run'] = time();
		}
		if(count($this->aInfo)) {
			$aInfo = array();
			foreach ($this->aInfo as $key => $val) {
				$aInfo[] = $key.':'.$val;
			}
			$content = implode("\r\n", $aInfo);
		}
		$file = $this->getCacheFile();
		if($file) {
			JFile::write($file, $content);
		}
	}

	private function getNextRun() {

		$last_run = isset($this->aInfo['last_run']) ? $this->aInfo['last_run'] : 0;
		$cron_interval = $this->plgParams->get('cache_time', 3600);

		$next_run = $last_run + $cron_interval;

		return $next_run;
	}

	private function cmpLongInt($n1, $n2) {
		$a1 = str_split($n1);
		$a2 = str_split($n2);
		$len1 = count($a1);
		$len2 = count($a1);
		if($len1 > $len2) {
			return 1;
		} elseif ($len2 > $len1) {
			return -1;
		} else {
			if(!$len1) {
				return 0;
			} else {
				for ($i = 0; $i<$len1; $i++) {
					if ($a1[$i] > $a2[$i]) {
						return 1;
					} elseif ($a1[$i] < $a2[$i]) {
						return -1;
					}
				}
				return 0;
			}
		}

	}

	private function getIgnoreWords () {
		$aIgnore = array('logo', 'banner', 'advertising');
		return $aIgnore;
	}
	
	private function getInstagramItems($keyword, $limit) {
		$keyword = trim($keyword);
		$author = '';
		if(strtoupper($keyword) == '[POPULAR]') {
			$xml = ("http://widget.stagram.com/rss/popular/");
		} elseif (substr($keyword, 0, 1) == '@') {
			$username = substr($keyword, 1);
			$author = $username;
			$xml = ("http://widget.stagram.com/rss/n/{$username}/");
		} elseif (substr($keyword, 0, 1) == '#') {
			$keyword = substr($keyword, 1);
			$xml = ("http://widget.stagram.com/rss/tag/{$keyword}/");
		} else {
			$xml = ("http://widget.stagram.com/rss/tag/{$keyword}/");
		}

		$xmlDoc = new DOMDocument();
		//$xmlDoc->load($xml);
		$xml = $this->getContent($xml);
		$xmlDoc->loadXML($xml);
		
		//get elements from "<channel>"
		/*$channel=$xmlDoc->getElementsByTagName('channel')->item(0);
		$channel_title 	= $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
		$channel_link 	= $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
		$channel_desc 	= $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;*/
		
		$items = array();
		
		$x= $xmlDoc->getElementsByTagName('item');
		if(is_object($x)) {
			for ($i=0; $i<$limit; $i++) {
				$item = @$x->item($i);
				if(!is_object($item)) {
					break;
				}
				
				$it = new stdClass();
				$it->image 		= @$item->getElementsByTagName('image')->item(0)->getElementsByTagName('url')->item(0)->childNodes->item(0)->nodeValue;
				if(!$it->image) {
					continue;
				}
				$it->title		= $item->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
				$it->link		= $item->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
				$it->desc		= $item->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
				$it->pubDate	= $item->getElementsByTagName('pubDate')->item(0)->childNodes->item(0)->nodeValue;
				//$it->author		= $item->getElementsByTagName('author')->item(0)->childNodes->item(0)->nodeValue;
				if(!empty($author)) {
					$it->author		= $author;
				} else {
					preg_match('/>\s*(\@[^<]+)</', $it->desc, $matches);
					if(isset($matches[1])) {
						$it->author		= trim(substr($matches[1], 1));
					} else {
						$it->author		= '';
					}
				}
				
				
				$it->id = preg_replace('/.*?([0-9_]+)\s*$/', '$1', $it->link);
				
				$items[] = $it;
			}
		}
		return $items;
	}
	
}