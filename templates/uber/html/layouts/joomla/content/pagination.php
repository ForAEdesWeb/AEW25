<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$params = $displayData['params'];
$pagination = $displayData['pagination'];

$show_option = $params->def('show_pagination', 2);
$pagination_type = $params->def('pagination_type', 1);
?>

<?php if ($show_option == 1 || ($show_option == 2 && $pagination->get('pages.total') > 1)) : ?>
  <div class="pagination-wrap">
    <?php if ($params->def('show_pagination_results', 1)) : ?>
    <p class="counter pull-right">
        <?php echo $pagination->getPagesCounter(); ?>
    </p>
    <?php  endif; ?>
    <?php echo $pagination->getPagesLinks(); ?>
  </div>
<?php endif ?>

<?php if ($show_option && $pagination_type > 1) : ?>
<?php   echo JLayoutHelper::render('joomla.content.pagination-infinitive', $displayData); ?>
<?php endif ?>