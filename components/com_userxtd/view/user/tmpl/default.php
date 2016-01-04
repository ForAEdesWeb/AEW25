<?php
/**
 * Part of Component Userxtd files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Userxtd\Router\Route;
use Windwalker\View\Helper\ViewHtmlHelper;

// No direct access
defined('_JEXEC') or die;

/**
 * Prepare data for this template.
 *
 * @var $container Windwalker\DI\Container
 * @var $data      Windwalker\Data\Data
 * @var $item      Windwalker\Data\Data
 * @var $params    Joomla\Registry\Registry
 * @var $profile   \Windwalker\CCK\Form
 */
$container = $this->getContainer();
$params  = $data->item->params;
$item    = $data->item;
$profile = $data->profiles;
$canEdit = $data->item->params->get('access-edit');
$user    = JFactory::getUser();
$item    = $data->item ;
$uri     = JUri::getInstance();
$isSelf	 = ($user->id == $item->id);

// Set Profile Data
$data->datas = $profile->getDataForShow('profile', array('profile' => (array) $data->item->profiles));
$fieldsets	 = $profile->getFieldsets('profile');


$exclude_fields = array() ;
$exclude_fields[] = $avatar_field = $params->get('UserInfo_ImageField', 'BASIC_AVATAR');
$exclude_fields[] = $title_field  = $params->get('UserInfo_TitleField', 'name');
$exclude_fields[] = $about_field  = $params->get('UserInfo_AboutField', 'BASIC_ABOUT');

$data->exclude_fields = $exclude_fields;
$show_cats = $data->params->get('show_categories', '*') ;
?>

<form action="<?php echo JUri::getInstance(); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

	<div id="Userxtd" class="windwalker item container-fluid user<?php echo $params->get('pageclass_sfx'); ?>">
		<div id="userxtd-wrap-inner">

			<div class="user-item item">
				<div class="user-item-inner">

					<!-- afterDisplayTitle -->
					<!-- ============================================================================= -->
					<?php echo $data->item->event->afterDisplayTitle; ?>
					<!-- ============================================================================= -->
					<!-- afterDisplayTitle -->

					<!-- beforeDisplayContent -->
					<!-- ============================================================================= -->
					<?php echo $data->item->event->beforeDisplayContent; ?>
					<!-- ============================================================================= -->
					<!-- beforeDisplayContent -->

					<div class="row-fluid">
						<div class="profile-avatar span4">

							<div class="profile-avatar-inner">
								<?php $avatar = $item->get($avatar_field); ?>
								<img src="<?php echo $avatar ? $avatar : \Userxtd\Avatar\Avatar::getDefaultAvatar(); ?>" class="img-polaroid" alt="UserXTD Avatar <?php echo $user->username; ?>" />
							</div>

						</div>

						<!-- Info -->
						<!-- ============================================================================= -->
						<div class="profile-info span8">
							<div class="profile-info-inner">

								<?php if ($canEdit) : ?>
									<!-- Edit -->
									<!-- ============================================================================= -->
									<div class="edit-icon btn-toolbar fltrt pull-right">
										<div class="btn-group">
											<?php echo JHtml::link(
												Route::_('com_userxtd.user_layout', array('task' => 'user.edit.edit', 'id' => $item->id, 'layout' => 'edit')),
												'<i class="icon-edit"></i> ' . JText::_('JTOOLBAR_EDIT'),
												array('class' => 'btn btn-small')
											);
											?>
										</div>

									</div>
									<div style="display: none;">
										<?php echo JHtml::_('grid.id', $item->id, $item->id); ?>
									</div>
									<!-- ============================================================================= -->
									<!-- Edit End -->
								<?php endif; ?>


								<div class="heading">
									<h2><?php echo $item->get('link_titles') ? JHtml::_('link', $item->link, $item->name) : $item->name ?></h2>
									<div class="user-name">( <?php echo $item->username; ?> )</div>
								</div>

								<hr />

								<div class="about">
									<?php echo $item->{$about_field}; ?>
								</div>
							</div>

						</div>
					</div>

					<!-- ============================================================================= -->
					<!-- Info -->

					<!-- Content -->
					<!-- ============================================================================= -->
					<div class="profile-content">
						<div class="profile-content-inner row-fluid">

							<div class="span12">

								<?php
								foreach($fieldsets as $fieldset)
								{
									if( $show_cats != '*'
										&& !in_array( str_replace('userxtd-cat-', '', $fieldset->name) ,$show_cats) )
									{
										continue;
									}

									echo $this->loadTemplate('profile', array('fieldset' => $fieldset));
								}
								?>
							</div>

						</div>
					</div>
					<!-- ============================================================================= -->
					<!-- Content End -->

					<!-- afterDisplayContent -->
					<!-- ============================================================================= -->
					<?php echo $data->item->event->afterDisplayContent; ?>
					<!-- ============================================================================= -->
					<!-- afterDisplayContent -->

				</div>
			</div>

		</div>
	</div>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<!--<input type="hidden" name="return" value="--><?php //echo base64_encode(JUri::getInstance()->toString()); ?><!--" />-->
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>        
