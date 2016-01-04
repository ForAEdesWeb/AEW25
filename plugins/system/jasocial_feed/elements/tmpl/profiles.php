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

$profiles = jaSocialFeedGetProfiles($source);
?>
<script type="text/javascript">
/*<![CDATA[*/
window.addEvent('load', function (e){
	initProfiles('<?php echo $source; ?>');
});
/*]]>*/
</script>
<ul id="list-profiles-<?php echo $source; ?>" class="ja-profile-titles">
    <?php if ($profiles): ?>
        <!--<li class="ja-profile default active">
            <span class="ja-profile-title" rel="default"><?php echo JText::_('Default')?></span>
            <span class="ja-profile-action">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </li>-->
        <?php $i = 0; ?>
        <?php foreach ($profiles as $profile => $profilename): ?>
        <?php if ($profile != 'default'): ?>
        <?php $i++; ?>
            <li id="ja-profile-<?php echo $source; ?>-<?php echo $profile; ?>" class="ja-profile <?php if($i==1) { echo ' active'; } ?>" rel="<?php echo $profile; ?>">
                <span class="ja-profile-title" rel="<?php echo $profile; ?>"><?php echo $profilename; ?></span>
                <span class="ja-profile-action" rel="<?php echo $profile; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </li>
        <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <li class="ja-profile-new" onclick="jaNewProfile('<?php echo $source; ?>', this)" title="<?php echo JText::_('CLICK_HERE_TO_ADD_NEW_PROFILE')?>"><span><?php echo JText::_('New')?></span></li>
</ul>