<?php
/**
 * @version $Id: tableless.php 217 2014-01-13 08:30:22Z michal $
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

defined('_JEXEC') or die ('Restricted access');
?>
<div class="djf_mod djf_default" id="djf_mod_<?php echo $mid;?>">
	<div class="djf_left">
		<div class="djf_gal" id="djfgal_<?php echo $mid;?>">
			<div class="djf_thumbwrapper">
				<!-- gallery  START -->
			<?php
			$counter = 0;
			for ($row = 0; $row < $params->get('rows'); $row++)
			{
				?>
				<div class="djf_row">
				<?php
				for ($col = 0; $col < $params->get('cols'); $col++)
				{
					?>
					<div id="djfptd_<?php echo $mid;?>_<?php echo $counter;?>" class="djc_col">
					</div>
						<?php
						$counter++;
				}
				?>
				</div>
				<?php
			}
			?>
			</div>
		</div>
		<!-- gallery  END -->

		<!-- fullsize image outer START -->
		<div class="djf_img">
			<!-- fullsize image  START -->
			<?php $imgclass = $params->get('modalimage', 1) == 1 ? 'modal' : ''; ?>
			<?php $target = $params->get('modalimage', 1) == 1 ? 'target="_blank"' : ''; ?>
			<a style="display: block" id="djfimg_<?php echo $mid;?>"
				<?php echo $target; ?> class="<?php echo $imgclass; ?>"></a>
			<!-- fullsize image  END -->
		</div>
		<!-- fullsize image outer END -->
	</div>

	<!-- intro text outer START -->
	<div class="djf_text">
		<!-- Category Title START -->
	<?php if ($params->get('showcattitle') == 1) { ?>
		<div class="djf_cat">
			<div id="djfcat_<?php echo $mid;?>"></div>
		</div>
		<?php } ?>
		<!-- Category Title END -->

		<div id="djftext_<?php echo $mid;?>">
			<!-- intro text  START -->
		</div>
		<!-- intro text  END -->
	</div>
	<!-- intro text outer END -->

	<!-- pagination START -->
	<?php if ((int)$params->get('showpagination') > 0) { ?>
	<div style="clear: both;"></div>
	<div class="djf_pag">
		<div id="djfpag_<?php echo $mid;?>"></div>
	</div>
	<?php } ?>
	<!-- pagination END -->
</div>
