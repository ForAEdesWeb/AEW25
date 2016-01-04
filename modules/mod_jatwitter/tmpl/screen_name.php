<?php
/**
 * ------------------------------------------------------------------------
 * JA Twitter Module for J25 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div class="ja-twitter-user">
	<div class="ja-twitter-wrapper">
	
 		<div class="ja-twitter-thumb clearfix">
		 <a href="http://twitter.com/<?php echo $accountInfo->screen_name; ?>" title="<?php echo $accountInfo->name; ?>" target="_blank">
			<img width="<?php echo $sizeIconaccount; ?>" src="<?php echo $accountInfo->profile_image_url;?>" alt="<?php echo $accountInfo->name; ?>" class="ja-twitter-avatar" />
		</a>
	   
	  <h3><a href="http://twitter.com/<?php echo $accountInfo->screen_name; ?>" target="_blank"><?php echo $accountInfo->name; ?></a></h3>
	  </div>
	    
    <ul>
		<?php if(!empty($accountInfo->location)) : ?>
    	<li><strong><?php echo JText::_( 'LOCATION' ); ?></strong> <?php echo $accountInfo->location; ?></li>
    <?php endif; ?>
	    
    <?php if( !empty($accountInfo->url) ) : ?>
    	<li><strong><?php echo JText::_( 'WEB' ); ?></strong> <a href="<?php echo $accountInfo->url; ?>"><?php echo $accountInfo->url; ?></a></li>
    <?php endif; ?>
        
		<?php if( !empty($accountInfo->description) ) : ?>
	    <li><strong><?php echo JText::_( 'BIO' ); ?></strong> <?php echo $accountInfo->description; ?></li>
    <?php endif; ?>
    </ul>
        
    <ul>
    	<li>
			<span class="count"><?php echo $accountInfo->friends_count; ?></span>
			<span><?php echo JText::_( 'FOLLOWING' ); ?></span>
		</li>
		<li>
			<span class="count"><?php echo $accountInfo->followers_count; ?></span>
			<span><?php echo JText::_( 'FOLLOWERS' ); ?></span>
		</li>
		<li>
			<span class="count"><?php echo $accountInfo->statuses_count; ?></span>
			<span><?php echo JText::_( 'TWEETS' ); ?></span>
		</li>
	</ul>
		
	</div>
</div>
