<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$noneResponsive = ""; 

if (!($this->getParam('responsive', 1))): 
 
 $noneResponsive = " none-responsive";

endif;

?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" class='<jdoc:include type="pageclass" /> <?php echo $noneResponsive; ?>'>

  <head>
    <jdoc:include type="head" />
    <?php $this->loadBlock ('head') ?>  
  </head>

  <body>

    <?php $this->loadBlock ('top-header') ?> 
 
    <?php $this->loadBlock ('header') ?>
    
    <?php $this->loadBlock ('mainnav') ?>
	
    <?php $this->loadBlock ('top-bar') ?> 
	
    <?php $this->loadBlock ('slideshow') ?>
	
    <?php $this->loadBlock ('spotlight-1') ?>

    <?php $this->loadBlock ('mainbody-content-left') ?>
    
    <?php $this->loadBlock ('navhelper') ?>
    
    <?php $this->loadBlock ('footer') ?>
    
  </body>

</html>