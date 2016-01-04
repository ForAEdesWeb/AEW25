<?php
/**
 * ------------------------------------------------------------------------
 * Uber Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

?>

<?php if ($this->countModules('acm-footer')) : ?>
<!-- FOOTER -->
<jdoc:include type="modules" name="<?php $this->_p('acm-footer') ?>" style="raw" />
<!-- //FOOTER -->
<?php else: ?>

<?php
$sitename  = $this->params->get('sitename');

if (!$sitename) {
	$sitename = JFactory::getConfig()->get('sitename');
}

?>

<!-- BACK TOP TOP BUTTON -->
<div id="back-to-top" data-spy="affix" data-offset-top="300" class="back-to-top hidden-xs hidden-sm affix-top">
  <button class="btn btn-primary" title="Back to Top"><i class="fa fa-arrow-up"></i></button>
</div>

<script type="text/javascript">
(function($) {
	// Back to top
	$('#back-to-top').on('click', function(){
		$("html, body").animate({scrollTop: 0}, 500);
		return false;
	});
})(jQuery);
</script>
<!-- BACK TO TOP BUTTON -->

<!-- FOOTER -->
<footer id="t3-footer" class="wrap t3-footer">

	<section class="t3-copyright">
		<div class="container">
			<div class="row">
				<div class="<?php echo $this->countModules('acymailing') ? 'col-md-7' : 'col-md-12' ?> copyright <?php $this->_c('copyright') ?>">
					<?php if($sitename): ?><h2><?php echo $sitename; ?></h2><?php endif; ?>
					<jdoc:include type="modules" name="<?php $this->_p('footer') ?>" />
					<div class="ja-social<?php $this->_c('ja-social') ?>">
						<jdoc:include type="modules" name="<?php $this->_p('ja-social') ?>" style="raw" />
					</div>
					
				</div>
				<?php if ($this->countModules('acymailing')): ?>
					<div class="col-md-5">
						<div class="acymailing<?php $this->_c('acymailing') ?>">
							<jdoc:include type="modules" name="<?php $this->_p('acymailing') ?>" style="raw" />
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

</footer>
<!-- //FOOTER -->
<?php endif; ?>