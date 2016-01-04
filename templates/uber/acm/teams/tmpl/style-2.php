<?php
  $count = $helper->count('member-name');
  $col = $helper->get('number_col');
  $fullWidth = $helper->get('full-width');
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
	  <?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
	
		<div class="style-2 team-items">
			<?php
	      for ($i=0; $i < $count; $i++) :
	        if ($i%$col==0) echo '<div class="row">'; 
	    ?>
			<div class="item col-sm-6 col-md-<?php echo (12/$col); ?>">
	    
	      <div class="member-image">
	        <img src="<?php echo $helper->get('member-image', $i); ?>" alt="<?php echo $helper->get('member-name', $i); ?>" />
	      </div>
	      <h4><?php echo $helper->get('member-name', $i); ?></h4>
	      <p class="member-title"><?php echo $helper->get('member-position', $i); ?></p>
	      <p class="member-desc"><?php echo $helper->get('member-desc', $i); ?></p>
	        
			</div>
	    
	    <?php if ( ($i%$col==($col-1)) || $i==($count-1) )  echo '</div>'; ?>
			<?php endfor; ?>
		</div>
	  
	<?php if(!$fullWidth) : ?></div><?php endif; ?>
	</div>
</div>