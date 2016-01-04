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
defined ( '_JEXEC' ) or die ( 'Restricted access' );
?>

<section class="wrap ja-topbar">
	<div class="container">
    <div class="row">
	
      <div class="span8<?php $this->_c('top-newsticker')?>">
        <div class="top-menu">
			<jdoc:include type="modules" name="<?php $this->_p('top-newsticker') ?>" style="raw" />
		</div>
      </div>
	  
	  <div class="span4 clearfix">
        <div class="head-search<?php $this->_c('head-search')?>">
			<jdoc:include type="modules" name="<?php $this->_p('head-search') ?>" style="raw" />
		  </div>
	<div class="<?php $this->_c('social')?>">
		  <jdoc:include type="modules" name="<?php $this->_p('social') ?>" style="raw" />
	</div>
      </div>
	  
    </div>
  </div>
</section>