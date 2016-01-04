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

$db = JFactory::getDBO();
$helpurl = 'http://www.joomlart.com/?ajax=module&name=youtubeplaylist';
JHTML::_('behavior.framework', true);
JHTML::_('behavior.modal', 'a.modal');

?>

<?php if(version_compare(JVERSION, '3.0', 'lt')): ?>
<script type="text/javascript">
    window.addEvent('load', function (e){
        jatabs = new JATabs('ja-tabswrap', {
            numbtab : 1,
            animType : 'animNone',
            style : 'default',
            position : 'top',
            width : '100%',
            height : 'auto',
            mouseType : 'click',
            duration : 1000,
            colors : 10,
            useAjax : false,
            skipAnim : true
        });

        var jacpanel = $('ja-tabswrapmain');
        //
        var paramframe = getParentByClassName(jacpanel, 'pane-sliders');
        paramframe.getElements('div.panel').each(function(el) {
        	el.setStyle('display', 'none');
        });
        paramframe.appendChild(jacpanel);
        //tab facebook
        var sec_fb = $('facebook-options');
        $('ja-facebook-content').appendChild(sec_fb.getParent().getElements('fieldset.panelform')[0]);
        //tab twitter
        var sec_tw = $('twitter-options');
        $('ja-twitter-content').appendChild(sec_tw.getParent().getElements('fieldset.panelform')[0]);
        //tab youtube
        var sec_yt = $('youtube-options');
        $('ja-youtube-content').appendChild(sec_yt.getParent().getElements('fieldset.panelform')[0]);
        //tab vimeo
        var sec_vm = $('vimeo-options');
        $('ja-vimeo-content').appendChild(sec_vm.getParent().getElements('fieldset.panelform')[0]);
        //tab instagram
        var sec_ig = $('instagram-options');
        $('ja-instagram-content').appendChild(sec_ig.getParent().getElements('fieldset.panelform')[0]);
        //tab flickr
        var sec_fr = $('flickr-options');
        $('ja-flickr-content').appendChild(sec_fr.getParent().getElements('fieldset.panelform')[0]);
        //move advanced section to 'general tab'
        var sec_advanced = $('advanced-options');
        $('ja-general-content').appendChild(sec_advanced.getParent().getElements('fieldset.panelform')[0]);
    });
    
</script>
<div style="width: 100%;" class="ja-tabswrap default" id="ja-tabswrapmain">
<div class="container" id="ja-tabswrap">
    <div style="height: 30px;" class="ja-tabs-title-top">
        <ul class="ja-tabs-title">
            <li class="first general" title="<?php echo JText::_('General')?>">
                <h3><span class="general"><?php echo JText::_('General')?></span></h3>
            </li>
            <li class="social facebook" title="<?php echo JText::_('Facebook')?>">
                <h3><span class="facebook">&nbsp;</span></h3>
            </li>
            <li class="social twitter" title="<?php echo JText::_('Twitter')?>">
                <h3><span class="twitter">&nbsp;</span></h3>
            </li>
            <li class="social youtube" title="<?php echo JText::_('YouTube')?>">
                <h3><span class="youtube">&nbsp;</span></h3>
            </li>
            <li class="social vimeo" title="<?php echo JText::_('Vimeo')?>">
                <h3><span class="vimeo">&nbsp;</span></h3>
            </li>
            <li class="social instagram" title="<?php echo JText::_('Instagram')?>">
                <h3><span class="instagram">&nbsp;</span></h3>
            </li>
            <li class="last social flickr" title="<?php echo JText::_('Flickr')?>">
                <h3><span class="flickr">&nbsp;</span></h3>
            </li>
        </ul>
    </div>
    <div class="ja-tab-panels-top" style="height:0;">
        <!-- Begin: General Content -->
        <div class="ja-tab-content" style="position: absolute; left: 0px; display: block;">
            <div class="ja-tab-subcontent" id="ja-general-content">
                
            </div>
        </div>
        <!-- End: General Content -->
        
        <!-- Begin: Facebook Content -->
        <div class="ja-tab-content" style="position: absolute; left: 0px; display: block;">
            <div class="ja-tab-subcontent" id="ja-facebook-content">
                <?php require_once(dirname(__FILE__).'/facebook.php'); ?>
            </div>
        </div>
        <!-- End: Facebook Content -->

        <!-- Begin: Twitter Content -->
        <div class="ja-tab-content" style="position: absolute; left: 0px; display: block;">
            <div class="ja-tab-subcontent" id="ja-twitter-content">
                <?php require_once(dirname(__FILE__).'/twitter.php'); ?>
            </div>
        </div>
        <!-- End: Twitter Content -->

        <!-- Begin: YouTube Content -->
        <div class="ja-tab-content" style="position: absolute; left: 0px; display: block;">
            <div class="ja-tab-subcontent" id="ja-youtube-content">
                <?php require_once(dirname(__FILE__).'/youtube.php'); ?>
            </div>
        </div>
        <!-- End: YouTube Content -->

        <!-- Begin: Vimeo Content -->
        <div class="ja-tab-content" style="position: absolute; left: 0px; display: block;">
            <div class="ja-tab-subcontent" id="ja-vimeo-content">
                <?php require_once(dirname(__FILE__).'/vimeo.php'); ?>
            </div>
        </div>
        <!-- End: Vimeo Content -->

        <!-- Begin: Instagram Content -->
        <div class="ja-tab-content" style="position: absolute; left: 0px; display: block;">
            <div class="ja-tab-subcontent" id="ja-instagram-content">
                <?php require_once(dirname(__FILE__).'/instagram.php'); ?>
            </div>
        </div>
        <!-- End: Instagram Content -->

        <!-- Begin: Flickr Content -->
        <div class="ja-tab-content" style="position: absolute; left: 0px; display: block;">
            <div class="ja-tab-subcontent" id="ja-flickr-content">
                <?php require_once(dirname(__FILE__).'/flickr.php'); ?>
            </div>
        </div>
        <!-- End: Flickr Content -->
        
        <div id="ja-form-wrapper">
            
        </div>
    </div>
</div>
</div>
<?php else: //joomla version ?>
<script type="text/javascript">
    window.addEvent('load', function (e){
    	$$('#style-form ul.nav-tabs')[0].getElements('li')[1].setStyle('display', 'none');
    });
</script>
<?php endif; //joomla version ?>


<div id="ja-profile-action">
    <ul>
        <!--<li class="saveas"><?php echo JText::_('Clone');?></li>-->
        <!--<li class="rename"><?php echo JText::_('Rename');?></li>-->
        <li class="delete"><?php echo JText::_('Delete');?></li>
    </ul>
</div>


<script type="text/javascript">
    window.addEvent('load', function (e){
	    $('ja-profile-action').inject ($(document.body));
	    
	    $(document.body).addEvent( 'click', function() {
			$('ja-profile-action').hide();
		}.bind(this));
		
		//init action
		$$('#ja-profile-action li.delete').addEvent('click', function(){
			var profile = $('ja-profile-action').getProperty('rel');
			if(!profile) {
				alert('Profile is not specified!');
				return false;
			}
			
			var params = profile.split('|');
			
			jaDeleteProfile(params[0], params[1]);
		});
    });
</script>