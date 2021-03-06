<?php 
	$fullWidth 					= $helper->get('full-width');
	$contacInfoMap 			= $helper->get('contact-info-googlemap');
	$contacInfoImage 		= $helper->get('contact-info-image');
	$contacInfoPosition = $helper->get('contact-info-position');
?>

<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">
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
    
  <div id="uber-contact-<?php echo $module->id; ?>" class="uber-contact-info style-1 <?php if(!($contacInfoImage || $contacInfoMap)): ?> no-background <?php endif; ?> <?php if($fullWidth): ?>full-width <?php endif; ?>">
  	<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
    
    <?php if($contacInfoImage || $contacInfoMap): ?>
    <div class="info-bg">
    	<?php echo $contacInfoMap; ?>
    	<?php if($contacInfoImage): ?><img src="<?php echo $contacInfoImage; ?>" alt="" /><?php endif; ?>
    </div>
    <?php endif; ?>
    
  	<div class="info <?php echo $helper->get('contact-info-position'); ?>">
  		<dl class="info-list">
  		  <?php $count= $helper->getRows('contact-info-item.contact-info-name'); ?>
  		  
  		  <?php for ($i=0; $i<$count; $i++) : ?>
  		  
  			<dt>
  				<?php if($helper->get ('contact-info-item.contact-info-icon', $i)): ?><i class="fa <?php echo $helper->get ('contact-info-item.contact-info-icon', $i); ?>"></i><?php endif; ?>
  				<span><?php echo $helper->get ('contact-info-item.contact-info-name', $i) ?></span>
  			</dt>
  		
  	  	<?php if ($helper->get ('contact-info-item.contact-info-value', $i)) : ?>
  	    <dd><?php echo $helper->get ('contact-info-item.contact-info-value', $i) ?></dd>
  	  	<?php endif; ?>
  	  	
  			<?php endfor; ?>
  			
  		</dl>
  	</div>
    <?php if(!$fullWidth): ?></div><?php endif; ?>
  </div>
</div>

<script>
(function ($) {
	$(document).ready(function(){
	
		if($('#uber-contact-<?php echo $module->id ?>').length > 0) {
			var heightContact = $('#uber-contact-<?php echo $module->id ?> .info-bg').outerHeight(),
					infoContact   = $('#uber-contact-<?php echo $module->id ?> .info').outerHeight();
					
			if(infoContact > (heightContact - 80)) {
				$('#uber-contact-<?php echo $module->id ?> .info.top-left').css({ 
					'top': '20px',
					'left': '20px'
				});
				
				$('#uber-contact-<?php echo $module->id ?> .info.bottom-left').css({ 
					'bottom': '20px',
					'left': '20px'
				});
				
				$('#uber-contact-<?php echo $module->id ?> .info.top-right').css({ 
					'top': '20px',
					'right': '20px'
				});
				
				$('#uber-contact-<?php echo $module->id ?> .info.bottom-right').css({ 
					'bottom': '20px',
					'right': '20px'
				});
			} else {
				$('#uber-contact-<?php echo $module->id ?> .info.top-left').css({ 
					'top': '80px',
					'left': '80px'
				});
				
				$('#uber-contact-<?php echo $module->id ?> .info.bottom-left').css({ 
					'bottom': '80px',
					'left': '80px'
				});
				
				$('#uber-contact-<?php echo $module->id ?> .info.top-right').css({ 
					'top': '80px',
					'right': '80px'
				});
				
				$('#uber-contact-<?php echo $module->id ?> .info.bottom-right').css({ 
					'bottom': '80px',
					'right': '80px'
				});
			}
			
			$(window).resize(function(){
				var heightContact = $('#uber-contact-<?php echo $module->id ?> .info-bg').outerHeight(),
						infoContact   = $('#uber-contact-<?php echo $module->id ?> .info').outerHeight();
						
				if(infoContact > (heightContact - 80)) {
				$('#uber-contact-<?php echo $module->id ?> .info.top-left').css({ 
					'top': '20px',
					'left': '20px'
				});
				
				$('#uber-contact-<?php echo $module->id ?> .info.bottom-left').css({ 
					'bottom': '20px',
					'left': '20px'
				});
				
				$('#uber-contact-<?php echo $module->id ?> .info.top-right').css({ 
					'top': '20px',
					'right': '20px'
				});
				
				$('#uber-contact-<?php echo $module->id ?> .info.bottom-right').css({ 
					'bottom': '20px',
					'right': '20px'
				});
			} else {
				$('#uber-contact-<?php echo $module->id ?> .info.top-left').css({ 
					'top': '80px',
					'left': '80px'
				});
				
				$('#uber-contact-<?php echo $module->id ?> .info.bottom-left').css({ 
					'bottom': '80px',
					'left': '80px'
				});
				
				$('#uber-contact-<?php echo $module->id ?> .info.top-right').css({ 
					'top': '80px',
					'right': '80px'
				});
				
				$('#uber-contact-<?php echo $module->id ?> .info.bottom-right').css({ 
					'bottom': '80px',
					'right': '80px'
				});
			}
			});
		}
			
	});
})(jQuery);
</script>