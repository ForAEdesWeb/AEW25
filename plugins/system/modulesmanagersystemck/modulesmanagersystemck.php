<?php

/**
 * @name		Modules Manager CK
 * @package		com_modulesmanagerck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
defined('JPATH_BASE') or die;

jimport('joomla.application.component.helper');

class PlgSystemModulesmanagersystemck extends JPlugin {

	protected $showbutton = false;
	
	protected $isedit = false;

	public function __construct(&$subject, $config = array()) {
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$doctype = $doc->getType();
		$user = JFactory::getUser();
		$input = new JInput();

		if ($app->isAdmin()) {
			return;
		}

		if ($doctype !== 'html') {
			return;
		}

		if ($user->authorise('core.manage', 'com_modules')) {
			if ($input->get('ck', 0, 'int') == 1) {
				$input->set('tp', 1);
				JComponentHelper::getParams('com_templates')->set('template_positions_display', 1);
			}
		}

		$lang = JFactory::getLanguage();
		$lang->load('com_modulesmanagerck');

		parent::__construct($subject, $config);
	}

	public function onBeforeRender() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.manage', 'com_modules'))
			return;

		$input = new JInput();

		if ($input->get('option') == 'com_templateck' && $input->get('view') == 'template') {
			return;
		}
		
		// to not show in the template creator preview
		if ($input->get('template') == 'templatecreatorck' && $input->get('tmpl') == 'preview') {
			return;
		}

		if ($input->get('option', '', 'string') == 'com_modulesmanagerck'
				&& $input->get('ck', 0, 'int') != 1) {
//			$this->cleanCss();
			$this->isedit = true;
		}

		if ($input->get('option', '', 'string') == 'com_modulesmanagerck' || $input->get('option', '', 'string') == 'com_media' || $input->get('format', '', 'string') == 'raw') {
			return;
		}

		$this->showbutton = true;
		$this->addManagerButtonCss();

		if ($input->get('ck', 0, 'int') == 1) {

			$doc = JFactory::getDocument();
			JHtml::_('stylesheet', 'components/com_modulesmanagerck/assets/modulesmanagerck.css');
			JHtml::_('behavior.tooltip');
			JHtml::_('behavior.formvalidation');
			JHtml::_('behavior.combobox');

			$positions = $this->getPositions($doc->template);
			
			include_once JPATH_ROOT . '/components/com_modulesmanagerck/helpers/modulesmanager.php';
			$ckmodulesmanager = new CKModulesManager();

			// JFactory::getCache()->clean('com_modules', '');

			foreach ($positions as $position) {
				$html = $ckmodulesmanager->createPosition($position);
				$doc->setBuffer($html, "modules", $position);
			}
		}
	}

	public function onAfterRender() {
		$input = new JInput();
		if ($input->get('ck', 0, 'int') == 1)
			$this->cleanScripts();
		if ($this->showbutton) {
			$this->addManagerButton();
		}
		if ($this->isedit == true) {
			$this->cleanCss();
		}
	}

	protected function getPositions($template) {
		$templatephpfile = JPath::clean(JPATH_THEMES . '/' . $template . '/index.php');
		$templatephp = file_get_contents($templatephpfile);
		$matches = array();
		$positions = array();

		if (preg_match_all('#<jdoc:include type="module([^"]+)" (.*)\/>#iU', $templatephp, $matches))
		{
			for ($i = count($matches[0]) - 1; $i >= 0; $i--)
			{
				$attribs = empty($matches[2][$i]) ? array() : JUtility::parseAttributes($matches[2][$i]);
				$name = isset($attribs['name']) ? $attribs['name'] : null;
				if ($name) $positions[] = (string) $name;
			}
		} else {
			$templateDetails = JPATH_THEMES . '/' . $template . '/templateDetails.xml';
			$path = JPath::clean($templateDetails);
			if (file_exists($path) && ($xml = simplexml_load_file($path)) && isset($xml->positions[0])) {
				foreach ($xml->positions[0] as $position) {
					$positions[] = (string) $position;
				}
			}
		}
		return $positions;
	}

	protected function cleanCss() {

		$body = JResponse::getBody();
		if (preg_match_all('#<link(.*?)\/>#s', $body, $matches))
		{
			for ($i = count($matches[0]) - 1; $i >= 0; $i--)
			{
				if (!stristr($matches[0][$i], 'maximenuck')
						&& !stristr($matches[0][$i], 'slideshowck')) $body = str_replace($matches[0][$i], '', $body);
			}
		}
		$css = '<link rel="stylesheet" href="'.JUri::base(true).'/components/com_modulesmanagerck/assets/edit.css" type="text/css"/>';
		$css .= '<link rel="stylesheet" href="'.JUri::base(true).'/components/com_modulesmanagerck/assets/modulesmanagerck.css" type="text/css"/>';
		$css .= '<link rel="stylesheet" href="'.JUri::base(true).'/media/system/css/modal.css" type="text/css" />';

		if (!version_compare(JVERSION, '3.0.0', 'le') > 0) {
		$css .= '<link rel="stylesheet" href="'.JUri::base(true).'/media/jui/css/chosen.css" type="text/css" />
  <link rel="stylesheet" href="'.JUri::base(true).'/media/jui/css/bootstrap.min.css" type="text/css" />
  <link rel="stylesheet" href="'.JUri::base(true).'/media/jui/css/bootstrap-responsive.min.css" type="text/css" />
  <link rel="stylesheet" href="'.JUri::base(true).'/media/jui/css/bootstrap-extended.css" type="text/css" />
  <link rel="stylesheet" href="'.JUri::base(true).'/media/system/css/calendar-jos.css" type="text/css"  title="Vert"  media="all" />
  ';
		 }
		 
		$body = str_replace('</head>', $css . '</head>', $body);
		JResponse::setBody($body);
	}

	protected function cleanScripts() {
		$body = JResponse::getBody();
		if (preg_match_all('#<script(.*?)\/script>#s', $body, $matches))
		{
			for ($i = count($matches[0]) - 1; $i >= 0; $i--)
			{
				$body = str_replace($matches[0][$i], '', $body);
			}
		}
		$scripts = '<script src="'.JUri::base(true).'/components/com_modulesmanagerck/assets/jquery.min.js" type="text/javascript"></script>';
		$scripts .= '<script src="'.JUri::base(true).'/components/com_modulesmanagerck/assets/jquery-noconflict.js" type="text/javascript"></script>';
		$scripts .= '<script src="'.JUri::base(true).'/components/com_modulesmanagerck/assets/jquery-ui-1.10.2.custom.min.js" type="text/javascript"></script>';
		$scripts .= '<script src="'.JUri::base(true).'/components/com_modulesmanagerck/assets/modulesmanagerck.js" type="text/javascript"></script>';

		$body = str_replace('</head>', $scripts . '</head>', $body);
		JResponse::setBody($body);
	}

	protected function addManagerButton() {
		$input = new JInput();

		$isactive = ($input->get('ck', 0, 'int') == 1);
		$state = $isactive ? 'active' : 'inactive';
		$statetext = $isactive ? JText::_('CK_DISABLE') : JText::_('CK_ENABLE');
		$link = $isactive ? str_replace('?ck=1', '', '?' . str_replace('&ck=1', '', JURI::getInstance()->getQuery())) : (JURI::getInstance()->getQuery() ? '?' . JURI::getInstance()->getQuery() . '&ck=1' : '?ck=1');

		$selectList = $isactive ? '<select id="managerselect" onChange="MMCKswitchModulesDisplay(this.options[this.selectedIndex].value)">'
				.'<option value="onlypage" selected="selected">'.JText::_('CK_MODULES_ON_THIS_PAGE_ONLY').'</option>'
				.'<option value="all">'.JText::_('CK_ALL_MODULES').'</option>'
				.'</select>' : '';

		$button = '<div id="managerbutton" class="' . $state . '">'
				.'<a id="managerstate" href="' . JURI::current() . $link . '">' . $statetext . '</a>'
				.$selectList
				.'</div>';

		$body = JResponse::getBody();
		$body = str_replace('</body>', $button . '</body>', $body);
		JResponse::setBody($body);
	}

	protected function addManagerButtonCss() {
		$doc = JFactory::getDocument();
		$css = '#managerbutton {
	position:  absolute;
	z-index: 10000;
	right: 20px;
	top: 0;
	width: 165px;
	min-height: 30px;
	background: #333 url(' . JUri::base(true) . '/components/com_modulesmanagerck/images/logo_modulesmanagerck_110.png) center 5px no-repeat;
	border-radius: 0 0 10px 10px;
	color: #f1f1f1;
	border: 1px solid #919191;
	box-shadow: #888 0 0 7px;
	text-align: center;
	padding: 100px 0 0 0;
}

#managerstate {
	display: inline-block;
	border-radius: 10px;
	font-size: 12px;
	text-align: center;
	text-transform: uppercase;
	color: #fff;
	cursor: pointer;
	text-decoration: none;
	padding: 2px 10px;
	margin: 5px 15px;
}

#managerbutton.active {
	/*padding-top: 0px;*/
	/*background: #333;*/
}

#managerbutton.active #managerstate {
	background: #D83B20;
}

#managerbutton.inactive #managerstate {
	background: green;
}

#managerstate:hover {
	font-weight: bold;
}

#managerselect {
	width: 160px;
	padding: 2px;
	margin: 3px 0 5px 0;
}
';

		$doc->addStyleDeclaration($css);
	}

}