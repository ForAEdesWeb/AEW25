<?php/*** @version 1.4.0* @package RSForm! Pro 1.4.0* @copyright (C) 2009-2012 www.rsjoomla.com* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html*/defined('_JEXEC') or die('Restricted access');?><div class="dashboard-container">	<?php foreach ($this->buttons as $button) { ?>		<?php if ($button['access']) { ?>			<div class="dashboard-info dashboard-button">				<a href="<?php echo $button['link']; ?>"> 					<img src="<?php echo $button['image']; ?>" alt="<?php echo $button['text']; ?>" />					<span class="dashboard-title"><?php echo $button['text']; ?></span> 				</a> 			</div>		<?php } ?>	<?php } ?></div><span class="rsform_clear_both"></span>