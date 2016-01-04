<?php 
	$featuresImg 				= $helper->get('block-bg');
	$featuresBackground  = 'background-image: url("'.$featuresImg.'"); background-repeat: no-repeat; background-size: cover; background-position: center center;';
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($featuresImg): echo 'style="'.$featuresBackground.'"'; endif; ?>>
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
	<div class="acm-features style-7 <?php echo $helper->get('features-style'); ?>">
		<div class="container">			
			<div class="row">
				<div class="features-content clearfix">
					<?php $count = $helper->getRows('data.title'); ?>
					<?php for ($i=0; $i<$count; $i++) : ?>
					
						<?php if( (($i+1) % 2) == 1): ?>
						<div class="col-sm-6 col-md-3 odd">
						<?php else : ?>
						<div class="col-sm-6 col-md-3 col-md-offset-6">
						<?php endif; ?>
	
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
							
						</div>
					<?php endfor ?>
				</div>
				
				<?php if($helper->get('img-features')) : ?>		
				<div class="features-image hidden-xs hidden-sm col-md-6 col-md-offset-3">
					<img src="<?php echo $helper->get('img-features'); ?>" />
				</div>
				<?php endif; ?>
	
			</div>
		
		</div>
	</div>
</div>