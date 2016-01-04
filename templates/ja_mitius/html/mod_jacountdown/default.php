<?php
/**
 * ------------------------------------------------------------------------
 * JA Mitius Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="ja-countdown <?php echo $jalayout;?>">

<div class="custom-text">
<?php if($custom_titles) : ?>
<h1><?php echo $custom_titles;?></h1>
<?php endif;?>

<?php if($custom_message): ?>
<div class="custom-message"><?php echo $custom_message; ?></div>
<?php endif;?>
</div>

<?php 
	require JModuleHelper::getLayoutPath('mod_jacountdown/', $jalayout.'/layout');
?>
</div>