<?php
/**
 * @package    M2C
 * @subpackage Views
 * @author     Douglas Machado {@link http://idealextensions.com}
 * @author     Created on 14-Apr-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

?>

<div class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>" id="m2c">
<?php if ( $this->params->get( 'show_page_heading', 1 ) ) : ?>
	<div class="page-header componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
<?php endif; ?>
<?php 
	if(trim($this->params->get('mdescription'))){
		echo '<div id="m2c-description"><p>'.$this->params->get('mdescription')."</p></div>";
	}
	
	jimport('joomla.application.module.helper');
	
	$document	= JFactory::getDocument();
	$renderer	= $document->loadRenderer('module');
	$params		= array('style'=>'-2');
//echo '<pre>'; print_r($this->params); exit;
	$contents = '';
	foreach (JModuleHelper::getModules($this->params->get('position','left')) as $mod)  {
		$contents .= $renderer->render($mod, $params);
	}
	echo $contents;
 ?>
 
</div>
