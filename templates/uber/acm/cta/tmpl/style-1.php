<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">
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
	<div class="acm-cta style-1">
		<?php $count = $helper->getRows('data.button');  ?>
	
		<?php for ($i=0; $i<$count; $i++) : ?>
			<?php if($helper->get('data.button',$i) && $helper->get('data.link',$i)): ?>
			<a href="<?php echo $helper->get('data.link',$i) ?>" class="btn <?php if($helper->get('data.button_class',$i)): echo $helper->get('data.button_class',$i); else: echo 'btn-default'; endif; ?>"><?php echo $helper->get('data.button',$i) ?>
				<i class="fa fa-angle-right"></i>
			</a>
			<?php endif; ?>
		<?php endfor; ?>
	
		<?php if($helper->get('img')): ?>
		<div style="animation-duration: <?php echo $helper->get('animation_speed') ?>ms; -webkit-animation-duration: <?php echo $helper->get('animation_speed') ?>ms;" data-animation="<?php echo $helper->get('animation'); ?>" class="call-to-action-image">
			<img alt="" src="<?php echo $helper->get('img') ?>">
		</div>
		<?php endif; ?>
	</div>
</div>