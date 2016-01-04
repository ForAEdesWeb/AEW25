<?php
	$count = $helper->getRows('data.position');
	$module_style = 't3xhtml';
	$blockImg 				= $helper->get('block-bg');
	$blockImgBg  			= 'background-image: url("'.$blockImg.'"); background-repeat: no-repeat; background-size: cover; background-position: center center;';
?>

<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($blockImg): echo 'style="'.$blockImgBg.'"'; endif; ?>>	
	<div class="acm-spotlight container">
		<div class="row">
		<?php 
			for ($i=0; $i<$count; $i++) : 
			$screensXs = $helper->get('data.xs',$i);
			$screensSm = $helper->get('data.sm',$i);
			$screensMd = $helper->get('data.md',$i);
			$screensLg = $helper->get('data.lg',$i);
		?>
		<div class="<?php echo $screensXs.' '.$screensSm.' '.$screensMd.' '.$screensLg; ?>">
			<?php
				$spotlight_position = $helper->get('data.position',$i);
			 	echo $helper->renderModules($spotlight_position,array('style'=>$module_style));
			?>
		</div>
		<?php endfor; ?>
		</div>
	</div>
</div>