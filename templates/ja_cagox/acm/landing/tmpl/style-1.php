<?php
  $items_position = $helper->get('position');
  $mods = JModuleHelper::getModules($items_position);
  
  $doc = JFactory::getDocument();
  $doc->addStyleSheet (T3_TEMPLATE_URL . '/acm/landing/css/jquery.fullPage.css');
  $doc->addScript (T3_TEMPLATE_URL . '/acm/landing/js/jquery.fullPage.min.js');
?>

<div id="fullpage" class="style-1 block-landing">
  <?php $i = 0; foreach ($mods as $mod) : ?>
  <div class="block-landing-item item fp-section <?php if (isset($animation)) echo $animation; ?> <?php if($i==0): echo 'active'; endif; ?>" >
	<?php
      echo JModuleHelper::renderModule($mod);
    ?>
  </div>
  <?php $i++; endforeach; ?>
</div>
