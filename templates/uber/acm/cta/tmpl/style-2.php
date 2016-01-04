<?php
  $ctaImg 				= $helper->get('img');
  $ctaBackground  = 'background-image: url("'.$ctaImg.'"); background-position: center bottom; background-repeat: no-repeat; background-size: auto;';
  $ctaAnimation   = $helper->get('animation');
  $ctaSpeed       = $helper->get('animation_speed');
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">
	<div class="acm-cta style-2" <?php if($ctaImg): echo 'style="'.$ctaBackground.'"'; endif; ?>>
	  <div class="container">
	    <div class="row">
	      <div class="col-md-4 col-md-offset-8">
	        <div class="cta-showcase-item">
	        	<?php if($module->showtitle): ?>
							<h1 class="cta-showcase-header"><?php echo $module->title ?></h1>
						<?php endif; ?>
	          <?php if($helper->get('block-intro')): ?>
							<p class="cta-showcase-intro"><?php echo $helper->get('block-intro'); ?></p>
						<?php endif; ?>	
	          <?php
	            $count = $helper->getRows('data.button');
	          ?>
	          <nav class="cta-showcase-actions">
	            <?php for ($i=0; $i<$count; $i++) : ?>
	              <a href="<?php echo $helper->get ('data.link',$i) ?>" target="_blank" class="<?php echo $helper->get ('data.class',$i) ?>"><?php echo $helper->get ('data.button',$i) ?></a>
	            <?php endfor;?>
	          </nav>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
</div>