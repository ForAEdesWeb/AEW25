<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$sitename  = $this->params->get('sitename');
$slogan    = $this->params->get('slogan', '');
$logotype  = $this->params->get('logotype', 'text');
$logoimage = $logotype == 'image' ? $this->params->get('logoimage', 'templates/' . T3_TEMPLATE . '/images/logo.png') : '';
$logoimgsm = ($logotype == 'image' && $this->params->get('enable_logoimage_sm', 0)) ? $this->params->get('logoimage_sm', '') : false;

?>

<!-- HEADER -->
<header id="ja-header" class="ja-header wrap">
  <div class="container">
	<div class="row">

		<!-- LOGO -->
		<div class="span8">
		  <div class="logo logo-<?php echo $logotype, ($logoimgsm ? ' logo-control' : '') ?>">
			<h1>
			  <a href="<?php echo JURI::base(true) ?>" title="<?php echo strip_tags($sitename) ?>">
            <?php if($logotype == 'image'): ?>
              <img class="logo-img" src="<?php echo JURI::base(true) . '/' . $logoimage ?>" alt="<?php echo strip_tags($sitename) ?>" />
            <?php endif ?>
            <?php if($logoimgsm) : ?>
              <img class="logo-img-sm visible-phone visible-tablet" src="<?php echo JURI::base(true) . '/' . $logoimgsm ?>" alt="<?php echo strip_tags($sitename) ?>" />
            <?php endif ?>
          </a>
			  <small class="site-slogan hidden-phone"><?php echo $slogan ?></small>
			</h1>
		  </div>
		</div>
		<!-- //LOGO -->

		<div class="span4">     
		 
		</div>
	</div>

  </div>
</header>
<!-- //HEADER -->
