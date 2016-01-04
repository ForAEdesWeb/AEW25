<?php 
	$fullWidth 					= $helper->get('full-width');
	$columns						= $helper->get('columns');
	$style							= $helper->get('acm-style');
?>

<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">
	<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
		<div class="acm-cta style-1">
			<?php $count = $helper->getRows('data.button');  ?>
			<div class="row">
			<?php for ($i=0; $i<$count; $i++) : ?>
				<?php if($helper->get('data.button',$i) && $helper->get('data.link',$i)): ?>
				<div class="col-xs-12 col-md-<?php echo 12/$columns; ?> ">
					<a href="<?php echo $helper->get('data.link',$i) ?>" class="btn btn-block <?php if($helper->get('data.button_class',$i)): echo $helper->get('data.button_class',$i); else: echo 'btn-default'; endif; ?>"><?php echo $helper->get('data.button',$i) ?>				</a>
				</div>
				<?php endif; ?>
			<?php endfor; ?>
			</div>
			<?php if($helper->get('img')): ?>
			<div style="animation-duration: <?php echo $helper->get('animation_speed') ?>ms; -webkit-animation-duration: <?php echo $helper->get('animation_speed') ?>ms;" data-animation="<?php echo $helper->get('animation'); ?>" class="call-to-action-image">
				<img alt="" src="<?php echo $helper->get('img') ?>">
			</div>
			<?php endif; ?>
		</div>
	<?php if(!$fullWidth): ?></div><?php endif; ?>
</div>