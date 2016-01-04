<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Mainbody 3 columns, content in left, mast-col on top of 2 sidebars: content - sidebar1 - sidebar2
 */
defined('_JEXEC') or die;
?>

<?php

  // Layout configuration
  $layout_config = json_decode ('{  
    "two_sidebars": {
      "default" : [ "span12"         , "span12"             , "span12"               , "span12"           ],
      "wide"    : [],
      "xtablet" : [ "span12"         , "span12"             , "span12"               , "span12 spanfirst"           ],
      "tablet"  : [ "span12"        , "span12 spanfirst"  , "span12"               , "span12"           ]
    },
    "one_sidebar": {
      "default" : [ "span12"         , "span12"             , "span12"             ],
      "wide"    : [],
      "xtablet" : [ "span12"         , "span12"             , "span12"             ],
      "tablet"  : [ "span12"        , "span12 spanfirst"  , "span12"            ]
    },
    "no_sidebar": {
      "default" : [ "span12 no-sidebar" ]
    }
  }');

  // positions configuration
  $mastcol  = 'mast-col';
  $sidebar1 = 'position-7';
  $sidebar2 = 'position-5';

  // Detect layout
  if ($this->countModules("$sidebar1 and $sidebar2")) {
    $layout = "two_sidebars";
  } elseif ($this->countModules("$sidebar1 or $sidebar2")) {
    $layout = "one_sidebar";
  } else {
    $layout = "no_sidebar";
  }

  $layout = $layout_config->$layout;

  //
  $col = 0;
?>

<section id="ja-mainbody" class="ja-mainbody wrap">
  <div class="container">
    <div class="row">
      
      <!-- MAIN CONTENT -->
      <div id="ja-content" class="ja-content <?php echo $this->getClass($layout, $col) ?>" <?php echo $this->getData ($layout, $col++) ?>>
        <div class="main-content">
		  <jdoc:include type="message" />
		  <?php if ($this->countModules($mastcol)) : ?>
			<!-- MASSCOL 1 -->
			<div class="ja-mastcol ja-mastcol-1<?php $this->_c($mastcol)?>">
			  <jdoc:include type="modules" name="<?php $this->_p($mastcol) ?>" style="T3Xhtml" />
			</div>
			<!-- //MASSCOL 1 -->
			<?php endif ?>
		  <jdoc:include type="component" />
		  <?php $this->loadBlock ('spotlight-2') ?>
		</div>
      </div>
      <!-- //MAIN CONTENT -->

    </div>
  </div>
</section> 