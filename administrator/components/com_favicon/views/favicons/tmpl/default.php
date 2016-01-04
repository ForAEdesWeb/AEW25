<?php
/**
 * @copyright	Copyright (C) 2005 - 2010 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
?>
<form action="<?php echo JRoute::_('index.php?option=com_favicon'); ?>" name="adminForm" id="adminForm" method="post">
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="id" id="iconid" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
<div class="span8 width-60 fltlft">
<h3><?php echo count($this->favicons).' '.JText::_('COM_FAVICON_ICONS_FOUND'); ?></h3>
<table class="iconlist table-striped">
    <tr>
        <th class="iconid"><?php echo JText::_('COM_FAVICON_ID');?></th>
        <th class="iconedit"><?php echo JText::_('COM_FAVICON_EDIT');?></th>
        <th class="x16">16X16</th>
        <th class="iconsize"><?php echo JText::_('COM_FAVICON_SIZE');?></th>
        <th class="imagecount"><?php echo JText::_('COM_FAVICON_IMAGECOUNT');?></th>
        <?php if($this->plugin):?>
        <th class="default"><?php echo JText::_('COM_FAVICON_DEFAULT');?></th>
        <th class="assignments"><?php echo JText::_('COM_FAVICON_ASSIGNMENTS');?></th>
        <?php endif; ?>
        <th class="delete"><?php echo JText::_('COM_FAVICON_DELETE');?></th>
    </tr>
    <?php
    $linenum = 0;
    foreach($this->favicons as $icon):
            $linenum++;
            $editscript = "document.id('iconid').value = ".$icon.";Joomla.submitbutton('favicon.edit');return false;";
    ?>
    <tr class="line<?php echo $linenum%2;?>">
       <td><?php echo $icon;?></td>
       <td class="iconedit"><a href="#" onclick="<?php echo $editscript;?>"><img src="<?php echo JURI::root(true);?>/media/system/images/edit.png" alt="edit"/></a></td>
       <td class="x16">
            <?php
                $iconobj = $this->iconmodel->getIcon($icon);
                $iconkey = $this->iconmodel->get16($iconobj);
                $image = ($iconkey!==false)?JRoute::_('index.php?option=com_favicon&task=favicon.image&id='.$icon.'key='.$iconkey):JURI::root(true).'/'.$this->mediapath.'assets/images/missing.png';
            ?>
            <img src="<?php echo $image;?>" alt="16x16 favicon <?php echo $icon; ?>" class="icon16"/>
        </td>
        <td class="iconsize"><?php echo FaviconHelper::format_bytes(filesize($this->iconpath.DIRECTORY_SEPARATOR.$icon.DIRECTORY_SEPARATOR.'favicon.ico'));?></td>
        <td class="imagecount"><?php echo $iconobj->countImages();?></td>
        <?php if($this->plugin):?>
        <td class="default">
                <?php
                $onclick="document.id('iconid').value=".$icon.";Joomla.submitbutton('favicons.setdefault');return false;";
                $imageurl = JURI::root().'media/com_favicon/assets/images/icon-16-';
                if($icon == $this->default) {
                    $imageurl.='default.png';
                } else {
                    $imageurl.='notdefault.png';
                }
                ?>
            <a href="#" onclick="<?php echo $onclick;?>"><img src="<?php echo $imageurl;?>" alt="set default"/></a>
        </td>
        <td class="assignments">
            <?php 
            $link = "index.php?option=com_favicon&view=assign&layout=edit&id=".$icon;
            if(property_exists($this,'assignments') && is_object($this->assignments) && property_exists($this->assignments,$icon)) :
                    $iconproperty = (string)$icon;
            ?>
            <a href="<?php echo $link; ?>" class="modal" rel="{handler: 'iframe', size: {x: 875, y: 550}, onClose: function() {}}">
                <?php 
                if(count($this->assignments->$iconproperty)==1) {
                    echo JText::_('COM_FAVICON_MENU_ASSIGNMENT');
                } else {
                    echo JText::sprintf('COM_FAVICON_MENU_ASSIGNMENTs',count($this->assignments->$iconproperty));
                }
                ?>
            </a>
            <?php else : ?>
                <?php if($icon == $this->default) : ?>
                <?php echo JText::_('COM_FAVICON_DEFAULT'); ?>
                <?php else : ?>
                <a href="<?php echo $link; ?>" class="modal" rel="{handler: 'iframe', size: {x: 875, y: 550}, onClose: function() {}}">
                <?php echo JText::_('COM_FAVICON_MENU_NO_ASSIGNMENTS'); ?>
                </a>
                <?php endif; ?>
            <?php endif; ?>
        </td>
        <?php endif; ?>
        <td class="delete">
            <a href="#" onclick="if(confirm('<?php echo JText::_('COM_FAVICON_CONFIRM_DELETE');?>')) { document.id('iconid').value=<?php echo $icon;?>;Joomla.submitbutton('favicons.deleteicon');return false; } else { return false; }"><img src="<?php echo JURI::root(true);?>/media/system/images/icon_error.gif" alt="<?php echo JText::_('COM_FAVICON_DELETE');?>"/></a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>
<div class="span4 width-40 fltrt">
    <fieldset><legend><?php echo JText::_('COM_FAVICON_PLUGIN');?></legend>
    <?php if (!$this->plugin) :?>
        <?php echo $this->plugin; ?>
        <?php echo JText::_('COM_FAVICON_PLUGIN_NOT_ENABLED');?>
        <?php echo JText::_('COM_FAVICON_PLUGIN_NOT_ENABLED_DESC');?>
    <?php else : ?>
        <?php echo JText::_('COM_FAVICON_PLUGIN_ENABLED');?>
        <?php echo JText::_('COM_FAVICON_PLUGIN_ENABLED_DESC');?>
    <?php endif; ?>
    </fieldset>
</div>