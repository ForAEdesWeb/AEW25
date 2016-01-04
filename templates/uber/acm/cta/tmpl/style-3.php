<?php
  $ctaImg 				= $helper->get('img');
  $ctaBackground  = 'background-image: url("'.$ctaImg.'"); background-attachment: fixed; background-repeat: no-repeat; background-size: cover;';
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">
	<div class="acm-cta style-3 <?php echo $helper->get('style'); ?> <?php if($ctaImg): echo 'bg-image'; endif; ?>" <?php if($ctaImg): echo 'style="'.$ctaBackground.'"'; endif; ?> >
	  <div class="container">
			<div class="row">
				<div class="<?php echo $helper->get('text-align'); ?>">
					<div class="cta-showcase-item">
					
						<?php if($module->showtitle): ?>
							<h2 class="cta-showcase-header"><?php echo $module->title ?></h2>
						<?php endif; ?>
	
						<?php if($helper->get('block-intro')): ?>
							<p class="cta-showcase-intro"><?php echo $helper->get('block-intro'); ?></p>
						<?php endif; ?>

						<?php
							$count = $helper->getRows('data.button');
						?>
						<nav class="cta-showcase-actions">
							<?php for ($i=0; $i<$count; $i++) : ?>
								<a href="<?php echo $helper->get ('data.link',$i) ?>" target="_blank" class="<?php echo $helper->get ('data.button_class',$i) ?>"><i class="fa fa-angle-right"></i><?php echo $helper->get ('data.button',$i) ?></a>
							<?php endfor;?>
						</nav>
					</div>
				</div>
			</div>
	  </div>
	</div>
</div>