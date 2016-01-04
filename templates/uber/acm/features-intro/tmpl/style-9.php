<?php 
	$featuresImg 				= $helper->get('img-features');
	$featuresBackground  = 'background-image: url("'.$featuresImg.'"); background-repeat: no-repeat; background-size: auto auto; background-position: center center;';
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($featuresImg): echo 'style="'.$featuresBackground.'"'; endif; ?>>	
	<div class="acm-features style-9 <?php echo $helper->get('features-style'); ?>" >
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<?php if($module->showtitle): ?>
						<h2 class="features-description"><?php echo $module->title ?></h2>
					<?php endif ; ?>
					<?php if($helper->get('block-intro')): ?>
						<p><?php echo $helper->get('block-intro'); ?></p>
					<?php endif; ?>	
					
					<?php $count = $helper->getRows('data.title'); ?>
					<?php for ($i=0; $i<$count; $i++) : ?>
					
						<div class="features-item">
							
							<?php if($helper->get('data.font-icon', $i)) : ?>
								<div class="font-icon">
									<i class="<?php echo $helper->get('data.font-icon', $i) ; ?>"></i>
								</div>
							<?php endif ; ?>
	
							<?php if($helper->get('data.img-icon', $i)) : ?>
								<div class="img-icon">
									<img src="<?php echo $helper->get('data.img-icon', $i) ?>" alt="" />
								</div>
							<?php endif ; ?>
							
							<?php if($helper->get('data.title',$i)) : ?>
								<h3><?php echo $helper->get('data.title', $i) ?></h3>
							<?php endif ; ?>
							
							<?php if($helper->get('data.description',$i)) : ?>
								<p><?php echo $helper->get('data.description', $i) ?></p>
							<?php endif ; ?>
						</div>
					<?php endfor ?>
				</div>
			</div>
		</div>
	</div>
</div>