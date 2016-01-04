<?php
	$items_position = $helper->get('position');
	$mods = JModuleHelper::getModules($items_position);
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
	<div class="acm-container-tabs" id="mod-<?php echo $module->id ?>">
	
		<div class="container-tabs-nav">
			<div class="container">
	
				<!-- BEGIN: TAB NAV -->
				<ul class="nav nav-tabs" role="tablist">
					<?php
					$i = 0;
					foreach ($mods as $mod):
						?>
						<li class="<?php if ($i < 1) echo "active"; ?>">
							<a href="#mod-<?php echo $module->id ?> .mod-<?php echo $mod->id ?>" role="tab"
								 data-toggle="tab"><?php echo $mod->title ?></a>
						</li>
						<?php
						$i++;
					endforeach
					?>
	
				</ul>
				<!-- END: TAB NAV -->
			</div>
		</div>
	
		<!-- BEGIN: TAB PANES -->
		<div class="tab-content container">
			<?php $i = 0; foreach ($mods as $mod) : ?>
		  <div class="tab-pane fade mod-<?php echo $mod->id ?> <?php if($i==0): echo 'active in'; endif; ?>" >
			<?php
		      echo JModuleHelper::renderModule($mod);
		    ?>
		  </div>
		  <?php $i++; endforeach; ?>
		</div>
		<!-- END: TAB PANES -->
	</div>
</div>