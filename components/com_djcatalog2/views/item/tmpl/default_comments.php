<?php
/**
 * @version $Id: default_comments.php 211 2013-11-25 09:02:55Z michal $
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

$uri = JFactory::getURI(); 
$lang = JFactory::getLanguage();
$languge_tag = str_replace('-', '_', $lang->getTag());
?>
<div class="djc_comments djc_clearfix">
	<h3><?php echo JText::_('COM_DJCATALOG2_COMMENTS'); ?></h3>
	<?php if($this->params->get('comments',0) == '1') { ?>
		<?php if ($this->params->get('facebook-sdk', '1') == '1') { ?>
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/<?php echo $languge_tag; ?>/all.js#xfbml=1";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
		<?php } ?>				
		<div class="fb-comments" data-href="<?php echo $uri->toString(); ?>" data-num-posts="2" data-width="auto"></div>
	<?php } else if($this->params->get('comments',0) == '2' && $this->params->get('disqus_shortname','') != '') {?>
    	<?php 
    	$devlist = array('localhost', '127.0.0.1');
    	$disqus_shortname = $this->params->get('disqus_shortname','');
    	$disqus_url = $uri->toString();
    	$disqus_identifier = $disqus_shortname.'-djc2-'.$this->item->id;
    	$disqus_developer = (in_array($_SERVER['HTTP_HOST'], $devlist)) ? 1 : 0;
    	?>
    	<div id="disqus_thread"></div>
	    <script type="text/javascript">
	        var disqus_shortname = '<?php echo $disqus_shortname; ?>';
	        var disqus_url = '<?php echo $disqus_url; ?>';
	        var disqus_identifier = '<?php echo $disqus_identifier; ?>';
			var disqus_developer = <?php echo $disqus_developer; ?>;
			
	        /* * * DON'T EDIT BELOW THIS LINE * * */
	        (function() {
	            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
	            dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
	            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
	        })();
	    </script>
	    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
	    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
	<?php } else if ($this->params->get('comments', 0) == '3') { ?>
   	<?php 
		$jcomments = JPATH_SITE.'/components/com_jcomments/jcomments.php';
		if (file_exists($jcomments)) {
			require_once($jcomments);
			echo JComments::show($this->item->id,'com_djcatalog2', $this->item->name);
		}
	?>
	<?php } else if ($this->params->get('comments', 0) == '4') { ?>
   	<?php 
   		$komento =  JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_komento' . DIRECTORY_SEPARATOR . 'bootstrap.php';
		if (file_exists($komento)) {
			require_once($komento);
			
			$options			= array();
			$options['trigger']	= 'onDJCatalog2Item';
			$options['context']	= 'com_djcatalog2.item';
			$options['params']	= $this->params;
			
			$comments = Komento::commentify( 'com_djcatalog2', $this->item, $options );
			if ($comments) {
				echo $comments;
			}
		}
	?>
	<?php } ?>					
</div>
