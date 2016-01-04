<?php 
	$fullWidth 					= $helper->get('full-width');
	$columns						= $helper->get('columns');
	$style							= $helper->get('acm-style');
	$count 							= $helper->getRows('client-item.client-logo');
	$gray								= $helper->get('img-gray');
	$opacity						= $helper->get('img-opacity');
	$float = 0;
	
	if ($opacity=="") {
		$opacity = 100;
	}
	
	if (100%$columns) {
		$float = 0.01;
	}
	
	$blockImg 				= $helper->get('block-bg');
	$blockImgBg  			= 'background-image: url("'.$blockImg.'"); background-repeat: no-repeat; background-size: cover; background-position: center center;';
	 
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($blockImg): echo 'style="'.$blockImgBg.'"'; endif; ?>>	
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
	<div id="uber-cliens-<?php echo $module->id; ?>" class="uber-cliens style-1 <?php if($gray): ?> img-grayscale <?php endif; ?> <?php echo $style; ?> <?php if($fullWidth): ?>full-width <?php endif; ?> <?php if($count > $columns): ?> multi-row <?php endif; ?>">
		<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
	
		 <?php 
		 	for ($i=0; $i<$count; $i++) : 
		 	
			$clientName = $helper->get('client-item.client-name',$i);
			$clientLink = $helper->get('client-item.client-link',$i);
			$clientLogo = $helper->get('client-item.client-logo',$i);
			
			if ($i%$columns==0) echo '<div class="row">'; 
		?>
		
			<div class="col-xs-12 client-item" style="width:<?php echo number_format(100/$columns, 2, '.', ' ') - $float;?>%;">
				<div class="client-img">
					<?php if($clientLink):?><a href="<?php echo $clientLink; ?>" title="<?php echo $clientName; ?>" ><?php endif; ?>
						<img class="img-responsive" alt="<?php echo $clientName; ?>" src="<?php echo $clientLogo; ?>">
					<?php if($clientLink):?></a><?php endif; ?>
				</div>
			</div> 
			
		 	<?php if ( ($i%$columns==($columns-1)) || $i==($count-1) )  echo '</div>'; ?>
		 	
	 	<?php endfor ?>
	
	  <?php if(!$fullWidth): ?></div><?php endif; ?>
	</div>
	
	<?php if($opacity>=0 && $opacity<=100): ?>
	<script>
	(function ($) {
		$(document).ready(function(){ 
			$('#uber-cliens-<?php echo $module->id ?> .client-img img.img-responsive').css({
				'filter':'alpha(opacity=<?php echo $opacity ?>)', 
				'zoom':'1', 
				'opacity':'<?php echo $opacity/100 ?>'
			});
		});
	})(jQuery);
	</script>
	<?php endif; ?>
</div>