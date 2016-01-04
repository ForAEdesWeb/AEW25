<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

$params = $displayData['params'];

$title_field = $params->get('UserInfo_TitleField', 'name');
$about_field = $params->get('UserInfo_AboutField', 'BASIC_ABOUT');
$allowGuestSee = $params->get('UserProfile_GuestSeeProfile', 1);

$website_link = $displayData['website_link'];
$image = $displayData['image'];
$user  = $displayData['user'];
$link  = $displayData['link'];
?>
<!-- UserXTD Information Box -->
<div class="ux-user-info-warp well">
	<div class="ux-user-inner row-fluid">
		<div class="ux-user-left span3">
			<div class="ux-user-left-inner">
				<img src="<?php echo $image; ?>" alt="<?php echo $user->get($title_field); ?>" />
			</div>
		</div>
		<div class="ux-user-right span8">
			<div class="ux-user-right-inner">
				<h2 class="ux-user-info-heading"><?php echo $user->get($title_field); ?></h2>
				<div class="ux-user-about">
					<?php echo $user->get($about_field); ?>
				</div>
			</div>
		</div>
		<div class="pull-right more-about">
			<?php if ($website_link): ?>
			<a href="<?php echo $website_link; ?>" target="_blank">
				<?php echo \JText::_('COM_USERXTD_USER_INFO_WEBSITE'); ?>
			</a>
			|
			<?php endif; ?>

			<?php if ($allowGuestSee): ?>
			<a href="<?php echo $link ?>">
				<?php echo \JText::_('COM_USERXTD_USER_INFO_MORE'); ?>
			</a>
			<?php endif; ?>
		</div>
	</div>
</div>
