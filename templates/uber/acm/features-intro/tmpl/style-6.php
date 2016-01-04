<?php
  $align = $helper->get('align');
  
  if ($align==1): 
  	$contentAlign = "content-right";
  	$featuresContentPull = "col-sm-12 col-xs-12 col-md-6 pull-right";
  	$featuresImgPull = " hidden-sm hidden-xs col-md-6 pull-left";
  elseif ($align==0): 
  	$contentAlign = "content-left";
  	$featuresContentPull = "col-sm-12 col-xs-12 col-md-6 pull-left";
  	$featuresImgPull = " hidden-sm hidden-xs col-md-6 pull-right";
	else: 
		$contentAlign = "container";
  	$featuresContentPull = "";
  	$featuresImgPull = ""; 
  endif;
?>
<?php 
	$featuresImg 				= $helper->get('block-bg');
	$featuresBackground  = 'background-image: url("'.$featuresImg.'"); background-repeat: no-repeat; background-size: cover; background-position: center center;';
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($featuresImg): echo 'style="'.$featuresBackground.'"'; endif; ?>>
	<div class="acm-features style-6 row <?php echo $helper->get('features-style'); ?>">
		<div class="<?php echo $contentAlign; ?>">
			<?php if($helper->get('img-features')) : ?>
			<div class="features-image <?php echo $featuresImgPull; ?>">
				<img src="<?php echo $helper->get('img-features'); ?>" />
			</div>
			<?php endif; ?>
			
			<div class="features-content <?php echo $featuresContentPull; ?>">
				<!--- Features Content -->
				<?php if($module->showtitle || $helper->get('block-intro')) : ?>
					<div class="row ft-top">
						<div class="col-sm-12">
							<?php if($module->showtitle): ?>
								<h3><?php echo $module->title ?></h3>
							<?php endif; ?>
							<div class="lead">
								<?php echo $helper->get('block-intro'); ?>
							</div>
						</div>
					</div>
				<?php endif ; ?>
				
				<?php if($helper->get('data-s6.img-icon') || $helper->get('data-s6.title') || $helper->get('data-s6.description')) : ?>
					<?php 
						$count = $helper->getRows('data-s6.title'); 
						if (!$helper->get('columns')): $numberColumn = 3; else: $numberColumn = $helper->get('columns'); endif;
					?>
						
						<?php for ($i=0; $i<$count; $i++) : ?>
						<?php  if ($i%$numberColumn==0) echo '<div class="row ft-bottom">'; ?>
							<div class="col-sm-<?php echo 12/$numberColumn; ?>">
							
								<?php if($helper->get('data-s6.img-icon', $i)): ?>
									<div class="image">
										<img class="img-responsive" src="<?php echo $helper->get('data-s6.img-icon', $i); ?>" />
									</div>
								<?php endif; ?>
								
								<?php if($helper->get('data-s6.title', $i)): ?>
									<div class="title">
										<?php if ($helper->get('data-s6.title-link', $i)): ?>
											<h4><a href="<?php echo $helper->get('data-s6.title-link',$i); ?>"><?php echo $helper->get('data-s6.title', $i); ?></a></h4>
											<?php else : ?>
											<h4><?php echo $helper->get('data-s6.title', $i); ?></h4>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								
								<?php if($helper->get('data-s6.description', $i)): ?>
									<div class="description">
										<?php echo $helper->get('data-s6.description', $i); ?>
									</div>
								<?php endif; ?>
							</div>
							<?php if ( ($i%$numberColumn==($numberColumn-1)) || $i==($count-1) )  echo '</div>'; ?>
						<?php endfor; ?>
				<?php endif; ?>
				<!--- //Features Content -->
			</div>
		</div>
	</div>
</div>