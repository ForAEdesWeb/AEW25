<?php 
	$blockImg 				= $helper->get('block-bg');
	$blockImgBg  			= 'background-image: url("'.$blockImg.'"); background-repeat: no-repeat; background-size: cover; background-position: center center;';
	 
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($blockImg): echo 'style="'.$blockImgBg.'"'; endif; ?>>	
	<div class="acm-tabs">	
		<div class="style-2">
		<?php $count = $helper->count('location') ;?>
			<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
				<?php for ($i=0; $i < $count; $i++) : ?>
					<li class="<?php if($i<1) echo "active" ;?>" style="width:<?php echo (100/$count);?>%" >
						<a href="#tab-style2-<?php echo $i ; ?>" role="tab" data-toggle="tab">
							<p><?php echo $helper->get('location', $i) ;?></p>
							<p><?php echo $helper->get('address', $i) ; ?></p>
						</a>
					</li>
				<?php endfor ; ?>
				</ul>
	
				<!-- Tab panes -->
				<div class="tab-content">
				<?php for ($i=0; $i < $count; $i++) : ?>
					<div class="tab-pane active" id="tab-style2-<?php echo $i ; ?>">
							<?php echo $helper->get('map', $i) ; ?>
					</div>
				<?php endfor ; ?>
				</div>
		</div>
	</div>
</div>