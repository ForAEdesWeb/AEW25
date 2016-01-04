<?php
/**
 * ------------------------------------------------------------------------
 * Uber Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.image.image');

class JATempHelper {
	public static function loadParamsContents($item, $pdata = 'attribs'){
		$data = $item->$pdata;
		if(is_string($pdata)){
			$data = new JRegistry;
			$data->loadString($item->$pdata);
		}

		if($data instanceof JRegistry){
			return array(
				'title_caption' => $data->get('title_caption', ''),
				'title_icon'    => $data->get('title_icon', ''),
				'live_demo' 		=> $data->get('live_demo', ''),
				'download'  		=> $data->get('download', ''),
				'price'  				=> $data->get('price', ''),
				'version'  			=> $data->get('version', ''),
				'requirement'  	=> $data->get('requirement', ''),
				'thirdex'  			=> $data->get('thirdex', ''),
				'type'     			=> $data->get('type', ''),
				'bugtracker'    => $data->get('bugtracker', ''),
				'lastupdate'    => $data->get('lastupdate', ''),
				'document'			=> $data->get('document', ''),
				'blogreview'		=> $data->get('blogreview', '')
			);
		}
		
		return array(
			'title_caption' => '',
			'title_icon' => '',
			'live_demo' => '',
			'download' => '',
			'price' => '',
			'version' => '',
			'requirement' => '',
			'thirdex' => '',
			'type' => '',
			'bugtracker' => '',
			'lastupdate' => '',
			'document' => '',
			'blogreview' => ''
		);
	}
	
	public static function ProductSlideImage($text) {
      if(preg_match_all('#<img[^>]+>#iU', $text, $imgs)){
          //remove the text
          $text = preg_replace('#<img[^>]+>#iU', '', $text);
          //collect all images
          $img_data = array();
          
          // parse image attributes
          foreach( $imgs[0] as $img_tag){
              $img_data[$img_tag] = JUtility::parseAttributes($img_tag);
          }
          $total = count($img_data);

          if($total > 0) :
              $portfolioImage =  '';
              $j = 1;
              $class = '';
              foreach ($img_data as $img => $attr) :
              		if($j==1): $class='first'; elseif($j==$total): $class='last'; else: $class ='next'; endif;
                	$portfolioImage .=  '<div class="product-carousel-item '.$class.'">'.$img.'</div>';
                  $j++;
              endforeach;
          endif;

          return $portfolioImage;
      } else {
          return false;
      }
    }
	
	public static function loadModules($position, $style = 'raw') {
		jimport('joomla.application.module.helper');
		$modules = JModuleHelper::getModules($position);
		$params = array('style' => $style);
		foreach ($modules as $module) {
			echo JModuleHelper::renderModule($module, $params);
		}
	}
	
	// Function by Videos page
	public static function sanitize($item, $prop = 'introtext'){
		$result = $item->$prop;

		$result = preg_replace('@<iframe\s[^>]*src=[\"|\']([^\"\'\>]+)[^>].*?</iframe>@ms', '', $item->$prop);
	
		return $result;
	}
	
	public static function image($item, $type = ''){

		$result = array();

		if($type == 'video'){
			$result = self::video($item);
		}

		if(empty($result)){

			if(preg_match('/<img[^>]+>/i', isset($item->text) ? $item->text : $item->introtext, $imgs)){
				return JUtility::parseAttributes($imgs[0]);
			}
		}

		return $result;
	}
	
	public static function icon($type){
		return $type != 'text' ? ('<i class="fa fa-' . ($type == 'video' ? 'play-circle-o' : 'camera') . '"></i>') : '';
	}
	
	public static function video($item){
		$result = array();
		$prop   = 'text';
		if(!isset($item->$prop)){
			$prop = 'introtext';
		}

		if(preg_match_all('@<iframe\s[^>]*src=[\"|\']([^\"\'\>]+)[^>].*?</iframe>@ms', $item->$prop, $iframesrc) > 0){
			if(isset($iframesrc[1])){

				if(strpos($iframesrc[1][0], 'vimeo.com') !== false ) {
					$vid = str_replace(
						array(
							'http:',
							'https:', 
							'//player.vimeo.com/video/'
						), '', $iframesrc[1][0]);
				} else {
					$vid = str_replace(
						array(
							'http:',
							'https:',
							'//youtu.be/',
							'//www.youtube.com/embed/',
							'//youtube.googleapis.com/v/'
						), '', $iframesrc[1][0]);
				}

				//remove any parameter
				$vid = preg_replace('@(\/|\?).*@i', '', $vid);
				
				if(!(empty($vid))){ 
					if(strpos($iframesrc[1][0], 'vimeo.com') !== false ) {
						require_once (JPATH_ADMINISTRATOR . '/components/com_joomlaupdate/helpers/download.php');
						$filepath = JPATH_SITE . '/cache/vimeo/' . $vid . '.json';

						if(!is_file($filepath)){							
							AdmintoolsHelperDownload::download("http://vimeo.com/api/v2/video/$vid.json", $filepath);
						}

						if(is_file($filepath)){
							$vimeojson = json_decode(@file_get_contents($filepath));
							$result['src'] = $vimeojson[0]->thumbnail_large;
						}
					} else {
						$result['src'] = 'http://img.youtube.com/vi/'.$vid.'/0.jpg';
					}
					
					$item->$prop = str_replace($iframesrc['0'], '', $item->$prop);
				}
			}
		}

		return $result;
	}
}