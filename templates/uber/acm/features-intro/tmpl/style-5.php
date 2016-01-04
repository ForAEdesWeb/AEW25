<?php
	$count = $helper->getRows('data.title');
	$animationDelay = $helper->get('animation-delay'); 
	$featuresImg 				= $helper->get('img-features');
	$featuresBackground  = 'background-image: url("'.$featuresImg.'"); background-repeat: no-repeat; background-size: auto auto; background-position: center center;';
	if (!$animationDelay): $animationDelay = 200; endif;
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">
	<div class="acm-features style-5 <?php echo $helper->get('features-style'); ?>" <?php if($featuresImg): echo 'style="'.$featuresBackground.'"'; endif; ?>>
		<div class="container product-features">
		
		<header class="features-header">
		  <h2 class="features-title">
		  
		  	<?php if($module->showtitle): ?>
		    <span class="small-head"><?php echo $module->title ?></span>
		    <?php endif; ?>
		    
		    <?php if($helper->get('block-intro')): ?>
		    <div class="rw-words-text"><?php echo $helper->get('block-intro'); ?></div>
		    <?php endif; ?>
		    
		    <?php
					$count_carousel = $helper->count('carousel-text'); 
				?>
				
				<?php if($count_carousel): ?>
		    <div class="rw-words">
		      <div id="carousel-home" class="carousel slide" data-ride="carousel">
					  <div class="carousel-inner">
							
							<?php for ($i=0; $i<$count_carousel; $i++) : ?>
					    <div class="item <?php if($i==0): echo 'active'; endif; ?>">
					      <p><?php echo $helper->get('carousel-text',$i) ?></p>
					    </div>
					    <?php endfor ?>
					  </div>
					</div>
		    </div>
		    <?php endif; ?>
		  
		  </h2>
		</header>
		
		<div class="features-content">
		
			<?php for ($i=0; $i<$count; $i++) : ?>
		    <div style="animation-delay: <?php echo ($animationDelay + ($animationDelay * $i)); ?>ms; -webkit-animation-delay: <?php echo ($animationDelay + ($animationDelay * $i)); ?>ms;" data-animation="fade" class="col animate">
		    	<?php if($i!=($count-1)): ?>
		      <div class="icon-line">&nbsp;</div>
		      <?php endif; ?>
		      <div class="icon-wrapper icon-wrapper-show">
		      	<?php if($helper->get('data.font-icon',$i)): ?>
		        	<i class="fa <?php echo $helper->get('data.font-icon',$i) ?>"></i>
		        <?php endif; ?>
		      </div>
		      
		      <div class="intro-content">
		      
		      	<?php if($helper->get('data.img-icon', $i)) : ?>
						<div class="img-icon">
							<img src="<?php echo $helper->get('data.img-icon', $i) ?>" alt="" />
						</div>
						<?php endif ; ?>
		      
		      	<?php if($helper->get('data.title',$i)): ?>
		        <h3><?php echo $helper->get('data.title',$i) ?></h3>
		        <?php endif; ?>
		        
		        <?php if($helper->get('data.description',$i)): ?>
		        <p><?php echo $helper->get('data.description',$i) ?></p>
		        <?php endif; ?>
		        
		      </div>
		    </div>
		    <?php endfor ?>
		  </div>
		  
		</div>
	</div>
</div>