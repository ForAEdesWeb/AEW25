<?php
/**
 * ------------------------------------------------------------------------
 * Uber Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */


defined('_JEXEC') or die;
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"
	  class='<jdoc:include type="pageclass" />'>

<head>
	<jdoc:include type="head" />
	<?php $this->loadBlock('head') ?>
  <?php $this->addCss('layouts/clean') ?>
</head>

<body class="clean">

<div> 
  <?php $this->loadBlock('mainbody') ?>
  <a style="display: block;" class="text-center back" href="<?php echo JURI::base(true) ?>" title="Back to Home Page"><i class="fa fa-long-arrow-left"></i>
 Home Page</a>
</div>

</body>

</html>