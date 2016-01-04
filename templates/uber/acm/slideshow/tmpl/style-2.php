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
  
  <div class="acm-slideshow container">
  	<div id="acm-slideshow-<?php echo $module->id; ?>" class="carousel slide" data-ride="carousel">
  	<?php $count = $helper->getRows('data-s2.img') ?>
  	  <!-- Indicators -->
  	  <ol class="carousel-indicators">
  		<?php for ($i=0; $i < $count; $i++) :?>
  			<li data-target="#acm-slideshow-<?php echo $module->id; ?>" data-slide-to="<?php echo $i ?>" class="<?php if($i<1) echo "active" ; ?>"></li>
  		<?php endfor ;?>
  	  </ol>
  
  	  <!-- Wrapper for slides -->
  	  <div class="carousel-inner">
  		<?php for ($i=0; $i < $count; $i++) :?>
  		<div class="item <?php if($i<1) echo "active" ?>">
  		  <img src="<?php echo $helper->get('data-s2.img', $i) ?>" alt="" />
  		</div>
  		<?php endfor ;?>
  	  </div>
  	  
  	 <?php if($helper->get('enable-controls')): ?>
  	 <a data-slide="prev" role="button" href="#acm-slideshow-<?php echo $module->id ?>" class="left carousel-control"><i class="fa fa-angle-left"></i></a>
  	 <a data-slide="next" role="button" href="#acm-slideshow-<?php echo $module->id ?>" class="right carousel-control"><i class="fa fa-angle-right"></i></a>
  	 <?php endif; ?>
  	 </div>
  </div>
</div>
