<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- NAV HELPER -->
<nav class="wrap ja-navhelper">
  <div class="container">	
	 <div class="row">
	
      <div class="span10<?php $this->_c('navhelper')?>">
        <jdoc:include type="modules" name="<?php $this->_p('navhelper') ?>" />
      </div>
	  
	  <div class="span2">
        <div id="back-to-top" class="backtotop">
		   <?php echo JText::_('JA_MITIUS_BACK_TO_TOP');?> 
		</div>
      </div>
	  
    </div>
  </div>
  
  
</nav>
<!-- //NAV HELPER -->