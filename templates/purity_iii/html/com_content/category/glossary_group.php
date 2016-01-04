<?php
 /**
 *------------------------------------------------------------------------------
 * @package Purity III Template - JoomlArt
 * @version 1.0 Feb 1, 2014
 * @author JoomlArt http://www.joomlart.com
 * @copyright Copyright (c) 2004 - 2014 JoomlArt.com
 * @license GNU General Public License version 2 or later;
 *------------------------------------------------------------------------------
 */

defined('_JEXEC') or die;
?>

<div class="glossary-group">
	<a name="<?php echo $this->group ?>"></a>
	<h3 class="glossary-group-title"><?php echo $this->group ?></h3>
	<div class="glossary-group-items">
		<ul class="row">
		<?php foreach ($this->group_items as $item):
			$title = $item->title;
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
		?>
				<li class="col-xs-6 col-md-4"><a href="<?php echo $link ?>"><?php echo $title ?></a></li>
		<?php endforeach ?>
		</ul>
	</div>
</div>
