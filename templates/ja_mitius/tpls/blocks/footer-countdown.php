<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- FOOTER -->
<footer id="ja-footer" class="wrap ja-footer">

  <section class="ja-copyright">
    <div class="container">
      <div class="row">
        <div class="span8 copyright">
          <jdoc:include type="modules" name="<?php $this->_p('footer') ?>" />
        </div>
        <?php if($this->getParam('t3-rmvlogo', 1)): ?>
        <div class="span4 poweredby">
          <small><a href="http://t3.joomlart.com" title="Powered By T3 Framework" target="_blank">Powered by <strong>T3 Framework</strong></a></small>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

</footer>
<!-- //FOOTER -->