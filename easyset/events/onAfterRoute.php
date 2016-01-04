<?php 

$app = JFactory::getApplication();

if ($app->isSite() and "raw" !== $app->input->getVar("format"))
{
    $doc = JFactory::getDocument();

    $doc->addStyleSheet(JURI::root(true) . '/media/com_aedes/vendors/animate/animate.css');
    $doc->addCustomTag('<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>');
    $doc->addCustomTag('<script src="' . JURI::root(true) . '/media/com_aedes/vendors/gmap3/gmap3.min.js" type="text/javascript"></script>');
    $doc->addCustomTag('<script src="' . JURI::root(true) . '/media/com_aedes/vendors/wow/wow.min.js" type="text/javascript"></script>');
    $doc->addCustomTag('<script src="' . JURI::root(true) . '/media/com_aedes/src/main.js" type="text/javascript"></script>');
    $doc->addCustomTag('<!-- Hi 偷窺狂，在找什麼呢？ -->');
}