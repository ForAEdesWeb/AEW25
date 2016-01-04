<?php
/**
 * @version $Id: djlicense.php 285 2014-06-13 10:03:52Z michal $
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
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.framework');
JHTML::_('behavior.modal');

class DJLicense{
	
	public static function getSubscription() {
		
		if (!in_array('curl', get_loaded_extensions())) {
			return '<div class="lic_manager"><div class="lic_details"><div class="lic_status_box lic_invalid"><div class="lic_srow1">'.JText::_('COM_DJCATALOG2_DJLIC_CURL_NOT_INSTALLED').'</div></div></div></div>';
		}
		
		$app	= JFactory::getApplication();		
		$ext = $app->input->get('option', '', 'string');
		
		$ch = curl_init();		
		$db = JFactory::getDBO();
			$query = "SELECT manifest_cache FROM #__extensions WHERE element ='".$ext."' AND type='component' ";
			$db->setQuery($query);
			$mc = json_decode($db->loadResult());
		$version = $mc->version;
		$config = JFactory::getConfig();
		
		$secret_file = JFile::makeSafe('license_'.$config->get('secret').'.txt');
		$license_file = JPATH_BASE.DS."components".DS.$ext.DS.$secret_file;
		
		if(JFile::exists($license_file)){
			$fh = fopen($license_file, 'r');
			$license = fgets($fh);	
			fclose($fh);
		}else{
			$license = '';
		}
		
		curl_setopt($ch, CURLOPT_URL,'http://dj-extensions.com/index.php?option=com_djsubscriptions&view=checkDomain&license='.$license.'&ext='.$ext.'&v='.$version.'');
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$u = JFactory::getURI();
		
		curl_setopt ($ch, CURLOPT_REFERER, $u->getHost());
		
		$contents = curl_exec ($ch);
		
		if(curl_errno($ch)) {
			curl_close ($ch);
			return '<p>CURL ERROR: '.curl_error($ch).'</p>';
		}
		curl_close ($ch);
		
		if (empty($contents)) {
			return '<p><strong>Notice</strong>: Server does not allow to verify your subscription\'s status.</p>';
		}
		
		$res= explode(';', $contents);		
		
		if(strstr($res[0], 'E')){
			$t = $app->input->get('task',''); 
			if($t!='license' && $t!='Savelicense'){
				if($license==''){
					//$app->enqueueMessage(JText::_('COM_DJCATALOG2_DJLIC_ENTER_LICENSE'),'Error');
				}else{
					//$app->enqueueMessage(end($res),'Error');
				}					
			}
			$update = '<div class="djlic_box">';
				$update .= '<div class="djlic_title">DJ-Catalog<sup>2</sup></div>';
				$update .= '<div class="djlic_separator"></div>';
				$update .= '<div class="djlic_line djll1">'.JText::_('COM_DJCATALOG2_DJLIC_INSTALLED_VER').' <span>'.$version.'</span></div>';
				$update .= '<div class="djlic_line djll2">'.JText::_('COM_DJCATALOG2_DJLIC_AUTHOR').' <a target="_blank" href="http://www.dj-extensions.com"><span>DJ-EXTENSIONS</span></a></div>';
				$update .= '<div class="djlic_line djll3">'.JText::_('COM_DJCATALOG2_DJLIC_LAST_VERSION_AVAILABLE').' <span>'.$res[1].'</span></div>';
				
					$update .= '<div class="djlic_invalid">';
					$update .= '<a title="'.JText::_('COM_DJCATALOG2_DJLIC_LICENSE_MANAGER').'" style="font-weight:bold;" class="modal" rel="{handler: \'iframe\', size: {x: 500, y: 420},onClose:function(){window.parent.document.location.reload(true);}}" href="index.php?option='.$ext.'&task=license.edit&tmpl=component">';
						$update .= '<span class="djlic_iline1">'.JText::_('COM_DJCATALOG2_DJLIC_ENTER_LICENSE_CODE_FOR').'</span>';						
						$update .= '<span class="djlic_iline2">'.$u->getHost().'</span>';
						$update .= '<span class="djlic_iline3">'.JText::_('COM_DJCATALOG2_DJLIC_INVALID_INFO').'</span>';											
					$update .='</a></div>';
				//$update .= '<div style="text-align:left;margin-bottom:5px;"><img style="margin:-3px 0 -5px 0 !important;padding:0px;" src="'.JURI::base().'/templates/hathor/images/admin/publish_r.png"/> '.JText::_('COM_DJCATALOG2_DJLIC_YOUR_LIC_IS').' <a style="font-weight:bold;" class="modal" rel="{handler: \'iframe\', size: {x: 500, y: 250},onClose:function(){window.parent.document.location.reload(true);}}" href="index.php?option='.$ext.'&task=license.edit&tmpl=component">'.JText::_('COM_DJCATALOG2_DJLIC_INVALID').'</a></div>';						
				//$update .= '<div style="text-align:left;margin-bottom:5px;"><img style="margin:-3px 0 -5px 0 !important;padding:0px;" src="'.JURI::base().'/templates/hathor/images/admin/publish_r.png" /> '.JText::_('COM_DJCATALOG2_DJLIC_EXP_DATE').' </div>';
				//$update .= '<div style="text-align:left;margin-bottom:5px;"><img style="margin:-3px 0 -5px 0 !important;padding:0px;" src="'.JURI::base().'/templates/hathor/images/admin/publish_r.png" /> '.JText::_('COM_DJCATALOG2_DJLIC_LAST_VERSION_AVAILABLE').'</div>';
			$update .= '</div>';			
			return $update;			

		}else{
						
			$chversions = DJlicense::checktVersions();
			$chver= explode(';', $chversions);
			$update_avaible=0;
			foreach($chver as $r){
				$e = explode(',', $r); 									
				//$query = "SELECT manifest_cache FROM #__extensions WHERE element ='".$e[0]."' ";
				
				if(strstr($e[0],'com_')){
					$v_type= " AND type='component' ";
				}else if(strstr($e[0],'com_')){
					$v_type= " AND type='module' ";
				}else{
					$v_type= "";
				}
				$query = "SELECT manifest_cache FROM #__extensions WHERE element ='".$e[0]."' ".$v_type;
				
				
				
				$db->setQuery($query);
				$mc = json_decode($db->loadResult());
				$v = null;
				if(isset($mc)) $v = $mc->version; else $v = '';
				if($v!=''){
					if(version_compare($v,$e[1],'<')){
						$update_avaible = 1;
						break;
					}
				}
			}			
			
								
			$update = '<div class="djlic_box">';
				$update .= '<div class="djlic_title">DJ-Catalog<sup>2</sup></div>';
				$update .= '<div class="djlic_separator"></div>';
				$update .= '<div class="djlic_line djll1">'.JText::_('COM_DJCATALOG2_DJLIC_INSTALLED_VER').' <span>'.$version.'</span></div>';
				$update .= '<div class="djlic_line djll2">'.JText::_('COM_DJCATALOG2_DJLIC_AUTHOR').' <a target="_blank" href="http://www.dj-extensions.com"><span>DJ-EXTENSIONS</span></a></div>';
				$update .= '<div class="djlic_line djll3">'.JText::_('COM_DJCATALOG2_DJLIC_LAST_AVAILABLE_VER').' <span>'.$res[3].'</span></div>';
				if($update_avaible){			
					$update .= '<div class="djlic_line djll4 update"><a style="font-weight:bold;" class="modal" rel="{handler: \'iframe\', size: {x: 800, y: 450},onClose:function(){window.parent.document.location.reload(true);}}" href="index.php?option='.$ext.'&task=license.update_list&tmpl=component">'.JText::_('COM_DJCATALOG2_DJLIC_NEW_UPDATE_AVAILABLE').'</a></div>';
				}else{
					$update .= '<div class="djlic_line djll4 noupdate"><a style="font-weight:bold;" class="modal" rel="{handler: \'iframe\', size: {x: 800, y: 450},onClose:function(){window.parent.document.location.reload(true);}}" href="index.php?option='.$ext.'&task=license.update_list&tmpl=component">'.JText::_('COM_DJCATALOG2_DJLIC_UPDATE_MANAGER').'</a></div>';					
				}
				
				
				$update .= '<div class="djlic_valid">';
				$update .= '<a title="'.JText::_('COM_DJCATALOG2_DJLIC_LICENSE_MANAGER').'" style="font-weight:bold;" class="modal" rel="{handler: \'iframe\', size: {x: 500, y: 420},onClose:function(){window.parent.document.location.reload(true);}}" href="index.php?option='.$ext.'&task=license.edit&tmpl=component">';
					$update .= '<span class="djlic_vline1">'.JText::_('COM_DJCATALOG2_DJLIC_YOUR_LIC_IS_VALID').'</span>';											
					$update .= '<span class="djlic_vline2">'.JText::_('COM_DJCATALOG2_DJLIC_EXP_DATE').' '.date("d.m.y", strtotime($res[2])).'</span>';											
				$update .='</a></div>';

				 /*if(version_compare($version,$res[3],'<')){
					$update .= '<div style="text-align:left;margin-bottom:5px;"><img style="margin:-3px 0 -5px 0 !important;padding:0px;" src="'.JURI::base().'/templates/hathor/images/admin/publish_r.png" /> New version avaible <a style="font-weight:bold;" class="modal" rel="{handler: \'iframe\', size: {x: 600, y: 350},onClose:function(){window.parent.document.location.reload(true);}}" href="index.php?option='.$ext.'&task=license.update_list&tmpl=component">'.JText::_('COM_DJCATALOG2_DJLIC_UPDATE').'</a></div>';
				}else{
	
					
					
				}*/

			$update .= '';
			$update .= '</div>';
			return $update;			
		}
	}
	
	public static function checkSubscription($license){
		$app = JFactory::getApplication();
		$ch = curl_init();		
		$ext = $app->input->get('option', '', 'string');
		
			$db =  JFactory::getDBO();
			$query = "SELECT manifest_cache FROM #__extensions WHERE element ='".$ext."' ";
			$db->setQuery($query);
			$mc = json_decode($db->loadResult());
			$version = $mc->version;

		
		curl_setopt($ch, CURLOPT_URL,'http://dj-extensions.com/index.php?option=com_djsubscriptions&view=checkDomain&license='.$license.'&ext='.$ext.'&v='.$version.'');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$u = JFactory::getURI();
		curl_setopt ($ch, CURLOPT_REFERER, $u->getHost());
	
		
		if(!curl_errno($ch))
		{
			$contents = curl_exec ($ch);
		}
		
		curl_close ($ch);
		$res= explode(';', $contents);		
		
		return $res;
	}
	
	public static function checktVersions(){
		$app = JFactory::getApplication();
		$ext = $app->input->get('option', '', 'string');
		$config = JFactory::getConfig();
		
		$secret_file = JFile::makeSafe('license_'.$config->get('secret').'.txt');
					
		$license_file = JPATH_BASE.DS."components".DS.$ext.DS.$secret_file;
		
		if(JFile::exists($license_file)){
			$fh = fopen($license_file, 'r');
			$license = fgets($fh);	
			fclose($fh);
		}else{
			return JText::_('COM_DJCATALOG2_DJLIC_WRONG_LICENSE');
		}
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,'http://dj-extensions.com/index.php?option=com_djsubscriptions&view=checkVersions&license='.$license.'&ext='.$ext);		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			$u = JFactory::getURI();				
			curl_setopt ($ch, CURLOPT_REFERER, $u->getHost());
			if(!curl_errno($ch))
			{
				$contents = curl_exec ($ch); 
			}
			curl_close ($ch);
		
		return $contents;
	}
	
	public static function getProductName(){
		$app = JFactory::getApplication();
		$ext = $app->input->get('option', '', 'string');
		$db= JFactory::getDBO();			
		//$query = "SELECT name FROM #__extensions WHERE `element` = '".$ext."' LIMIT 1 ";
		$query = "SELECT name FROM #__extensions WHERE `element` = '".$ext."' AND type='component' LIMIT 1 ";
		$db->setQuery($query);
		$name = $db->loadResult();		
		return $name;
	}
}
