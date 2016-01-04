<?php
  $count      = $helper->getRows('data.event-title');
  $col        = $helper->get('number_col');
  $fullWidth  = $helper->get('full-width');
  $menuStyle  = $helper->get('event-style');
?>

<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($helper->get('block-bg')) : ?>style="background-image: url("<?php echo $helper->get('block-bg'); ?>")"<?php endif; ?> >
	<?php if($module->showtitle || $helper->get('block-intro')): ?>
	<h3 class="section-title ">
		<?php if($module->showtitle): ?>
			<span><?php echo $module->title ?></span>
		<?php endif; ?>
		<?php if($helper->get('block-intro')): ?>
			<p class="container-sm section-intro hidden-xs"><?php echo $helper->get('block-intro'); ?></p>
		<?php endif; ?>	
	</h3>
	<?php endif; ?>
  
  <div class="acm-event">
    <?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
  
  	<div class="style-2 event-items <?php echo $menuStyle; ?>">
  		<?php
        for ($i=0; $i < $count; $i++) :
      ?>
  		<div class="event-item row">
          <div class="col-md-6">
            <div class="event-date">
              <span class="date"><?php echo $helper->get('data.event-date', $i); ?></span>
              <span class="month"><?php echo $helper->get('data.event-month', $i); ?></span>
              <span class="year"><?php echo $helper->get('data.event-year', $i); ?></span>
            </div>
            <div class="event-time">
              <?php echo $helper->get('data.event-time', $i); ?>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="event-content">
              <h4 class="event-title">
                <?php if($helper->get('data.event-link', $i)): ?>
                <a href="<?php echo $helper->get('data.event-link', $i); ?>" title="<?php echo $helper->get('data.event-title', $i); ?>">
                <?php endif; ?>
                
                <?php echo $helper->get('data.event-title', $i); ?>
                  
                <?php if($helper->get('data.event-link', $i)): ?>
                </a>
                <?php endif; ?>
              </h4>
              <p class="event-description"><?php echo $helper->get('data.event-description', $i); ?></p>            
            </div>
          </div>
  		</div>
  		<?php endfor; ?>
  	</div>
    
  <?php if(!$fullWidth) : ?></div><?php endif; ?>
  </div>
</div>