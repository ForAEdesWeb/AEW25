<?php
  $count = $helper->count('member-name');
  $col = $helper->get('number_col');
  
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
	<div class="acm-teams">	
		<div class="team-items">
			<?php for ($i=0; $i < $count; $i++) :?>
			<div class="item" style="width: <?php echo (100/$col); ?>%;" >
				<div class="img-intro">
					<img src="<?php echo $helper->get('member-image', $i); ?>" alt="" />
					<p>“<?php echo $helper->get('member-slogan', $i); ?>” </p>
				</div>
				<div class="info" style="background-color:<?php echo $helper->get('member-bg-color', $i); ?>;">
					<h4><?php echo $helper->get('member-name', $i); ?></h4>
					<p><?php echo $helper->get('member-position', $i); ?></p>
				</div>
			</div>
			<?php endfor; ?>
		</div>
	</div>
</div>