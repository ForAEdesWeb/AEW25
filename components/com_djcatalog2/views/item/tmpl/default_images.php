<?php
/**
 * @version $Id: default_images.php 272 2014-05-21 10:25:49Z michal $
 * @package DJ-Catalog2
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
 *
 * DJ-Catalog2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Catalog2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Catalog2. If not, see <http://www.gnu.org/licenses/>.
 *
 */

defined ('_JEXEC') or die('Restricted access');
?>
<div class="djc_images pull-right">
	<div class="djc_mainimage">
		<a id="djc_mainimagelink" rel="djc_lb_0" title="<?php echo $this->item->images[0]->caption; ?>" href="<?php echo $this->item->images[0]->fullscreen; ?>">
			<img id="djc_mainimage" class="img-polaroid" alt="<?php echo $this->item->images[0]->caption; ?>" src="<?php echo $this->item->images[0]->large; ?>" />
		</a>
	</div>
	<?php if (count($this->item->images) > 1) { ?>
		<div class="djc_thumbnails" id="djc_thumbnails">
		<?php for($i = 0; $i < count($this->item->images); $i++) { ?>
			<div class="djc_thumbnail">
				<a rel="<?php echo $this->item->images[$i]->large; ?>" title="<?php echo $this->item->images[$i]->caption; ?>" href="<?php echo $this->item->images[$i]->fullscreen; ?>">
					<img class="img-polaroid" alt="<?php echo $this->item->images[$i]->caption; ?>" src="<?php echo $this->item->images[$i]->small; ?>" />
				</a>
			</div>
			<?php } ?>
		</div>
	<?php } ?>
	<?php for($i = 0; $i < count($this->item->images); $i++) { ?>
		<a id="djc_lb_<?php echo $i; ?>" rel="djimagebox-djitem" title="<?php echo $this->item->images[$i]->caption; ?>" href="<?php echo $this->item->images[$i]->fullscreen; ?>" style="display: none;"></a>
	<?php } ?>
</div>