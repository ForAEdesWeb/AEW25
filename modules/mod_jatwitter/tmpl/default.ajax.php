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
<div id="ja_twitter_div" class="content">
	<?php if($showtextheading == "1"){ ?>
		<h4 class="twitter-title"><?php echo $headingtext;?></h4>
	<?php } ?>	
	<?php if ( $showfollowlink == "1" ){ ?>
		<center>
		<script src='http://platform.twitter.com/anywhere.js?id=<?php echo $apikey?>&amp;v=1' type='text/javascript'></script>
<div id="anywhere-block-follow-button"></div>
  <script type="text/javascript">
  	twttr.anywhere(function(twitter) {
    	twitter('#anywhere-block-follow-button').followButton("<?php echo $screenName?>");
  		})
  </script>
  </center>
	<?php }  ?>
</div>