<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>


<?php if ($this->checkSpotlight('spotlight-2', 'position-2, position-3')) : ?>
<!-- SPOTLIGHT 2 -->
<section class="ja-sl ja-sl-2">
  <?php 
  	$this->spotlight ('spotlight-2', 'position-2, position-3', array('row-fluid'=>1))
  ?>
</section>
<!-- //SPOTLIGHT 2 -->
<?php endif ?>