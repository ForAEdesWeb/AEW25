<?php
/**
 * @version $Id: list.php 272 2014-05-21 10:25:49Z michal $
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

<?php 
if (count($producers) > 0) {
$jinput = JFactory::getApplication()->input;
$active = ($jinput->get('pid', null, 'int') > 0 && $jinput->get('option', '', 'string') == 'com_djcatalog2') ? $jinput->get('pid', null, 'int') : 0;
?>
<ul class="menu nav mod_djc2_producer_list">
	<?php 
	foreach($producers as $producer){
		$class = 'level0 djc_prodid-'.$producer['id'];
		if ($active > 0 && $active == (int)$producer['id']) {
			$class .= ' active current';	
		}
        
        $class = 'class="'.$class.'"';
        
		if ($params->get('type', '0') == '0') {
			$url = DJCatalogHelperRoute::getCategoryRoute($cid, $producer['id'].':'.$producer['alias']).'&cm=0';
		} else {
			$url = DJCatalogHelperRoute::getProducerRoute($producer['id'].':'.$producer['alias']);
		}
		?>
		<li <?php echo $class;?>><a href="<?php echo JRoute::_($url); ?>"><span><?php echo $producer['name']; ?></span></a></li>
		<?php 
	}
	?>
</ul>

<?php } ?>
