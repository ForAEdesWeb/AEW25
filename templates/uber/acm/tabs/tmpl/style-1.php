<?php 
	$blockImg 				= $helper->get('block-bg');
	$blockImgBg  			= 'background-image: url("'.$blockImg.'"); background-repeat: no-repeat; background-size: cover; background-position: center center;';
	 
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($blockImg): echo 'style="'.$blockImgBg.'"'; endif; ?>>	
	<div class="acm-tabs">
		<div class="style-1">
		<div class="container">
		<!-- Nav tabs -->
		<?php
			$count = $helper->count('nav-tabs'); 
		?>
	
		<ul class="nav nav-tabs" role="tablist">
		<?php for ($i=0; $i<$count; $i++) : ?>
			<li class="<?php if($i<1) echo("active");?>"><a href="#tab-style1-<?php echo ($i) ?>" role="tab" data-toggle="tab"><?php echo $helper->get('nav-tabs', $i) ?></a></li>
		<?php endfor ?>
		</ul>
	
		<!-- Tab panes -->
		<div class="tab-content">
		<?php for ($i=0; $i<$count; $i++) : ?>
			<div class="tab-pane <?php if($i<1) echo("active");?>" id="tab-style1-<?php echo ($i) ?>">
			<div class="row">
				<div class="content-tab col-sm-5">
					<?php if ($helper->get('intro')) : ?>
						<p><?php echo $helper->get('intro', $i) ?></p>
					<?php endif; ?>
					
					<?php if ($helper->get('link')) : ?>
						<a class="<?php echo $helper->get('class-btn'); ?>" href="<?php echo $helper->get('link', $i) ?>"><i class="<?php echo $helper->get('icon-btn', $i) ?>"></i><?php echo $helper->get('text-btn', $i) ?></a>
					<?php endif; ?>
				</div>
			
				<div class="col-sm-7">
					<?php if ($helper->get('img')) : ?>
					<img src="<?php echo $helper->get('img', $i) ?>" alt="" />
					<?php endif; ?>
				</div>
			</div>
			</div>
		<?php endfor ?>
		</div>
	
		</div>
		</div>
	</div>
</div>