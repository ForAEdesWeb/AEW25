<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$url = '';
if (is_array($displayData)) {
  $params = $displayData['params'];
  $images = json_decode($displayData['images']);
  $url = isset($displayData['url']) ? $displayData['url'] : '';  
} else {
  $params  = $displayData->params;
  $images = json_decode($displayData->images);
}
?>

<?php if (isset($images->image_intro) && !empty($images->image_intro)) : ?>
	<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
	<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image"> 

   <?php if ($url): ?>
  <a href="<?php echo $url ?>" title="">
  <?php endif ?>

    <img
    <?php if ($images->image_intro_caption):
      echo 'class="caption"' . ' title="' . htmlspecialchars($images->image_intro_caption) . '"';
    endif; ?>
    src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" itemprop="thumbnailUrl"/> 

    <?php if ($url): ?>
  </a>
  <?php endif ?>

  
  </div>
<?php endif; ?>
