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

defined('JPATH_BASE') or die;

?>

	<?php 
			$createDay = date('d', strtotime( $displayData['item']->publish_up ));
			$createMonth = JText::_(strtoupper(date('F', strtotime( $displayData['item']->publish_up )))."_SHORT");
			$createYear = date('Y', strtotime( $displayData['item']->publish_up ));
	?>

	<span class="blog-date">
		<span class="date"><?php echo $createDay; ?></span>
		<span class="month-year">
			<?php echo $createMonth; ?>
			<?php echo $createYear; ?>
		</span>
	</span>