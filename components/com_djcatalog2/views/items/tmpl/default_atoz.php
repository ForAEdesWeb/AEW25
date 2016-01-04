<?php
/**
 * @version $Id: default_atoz.php 272 2014-05-21 10:25:49Z michal $
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

JURI::reset();

$app = JFactory::getApplication();
$menu = $app->getMenu();

$active = $app->input->get('ind','', 'string');

$juri = JURI::getInstance();
$uri = JURI::getInstance($juri->toString());
$query = $uri->getQuery(true);

$query['option'] = 'com_djcatalog2';
$query['view'] = 'items';
$query['Itemid'] = $menu->getActive() ? $menu->getActive()->id : null;
$cid = $app->input->get('cid', false, 'string');
$pid = $app->input->get('pid', false, 'string');

if ($cid) {
	$query['cid'] = $cid;
}
if ($pid) {
	$query['pid'] = $pid;
}

unset($query['limitstart']);
unset($query['search']);
unset($query['start']);
unset($query['ind']);

$uri->setQuery($query);
$indexUrl = 'index.php?'.$uri->getQuery(false);

$letter_count = count($this->lists['index']);
$letter_width = (100 / $letter_count);
$letter_margin = ($letter_width * 0.05);
$letter_width -= $letter_margin;
?>

<?php if (count($this->lists['index']) > 0) { ?>
<div class="djc_atoz_in">
    <ul class="djc_atoz_list djc_clearfix">
            <?php foreach($this->lists['index'] as $letter => $count) { 
            	$btn_active = ($letter == $active) ? ' active' : '';
            	?>
               <li style="width: <?php echo $letter_width; ?>%; margin: 0 <?php echo $letter_margin/2; ?>%;">
                   <?php 
                       $catslug = '0';
                       if ($this->item) {
                           $catslug = $this->item->catslug;
                       }
                       if ($count > 0) { ?>
                       <?php $url = ($letter == $active) ? JRoute::_($indexUrl.'#tlb') : JRoute::_($indexUrl.'&ind='.urlencode($letter).'#tlb'); ?>
                           <a href="<?php echo $url; ?>">
                               <span class="btn<?php echo $btn_active; ?>"><?php echo $letter == 'num' ? '#' : $letter; ?></span>
                           </a>
                       <?php }
                       else { ?>
                           <span><span class="btn">
                               <?php echo $letter == 'num' ? '#' : $letter; ?>
                           </span></span>
                       <?php }
                   ?>
               </li>
            <?php } ?>
         </ul>
</div>
<?php } ?>
<?php 
JURI::reset();
?>