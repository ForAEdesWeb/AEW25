<?php
/**
 * ------------------------------------------------------------------------
 * JA System Google Map plugin for J2.5 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$plgParams = array(
	'api_version' => '2',
	'context_menu' => 1,
	'to_location' => 'New York',
	'target_lat' => 0.000000,
	'target_lon' => 0.000000,
	'to_location_info' => '',
	'to_location_changeable' => 0,
	'from_location' => '',
	'map_width' => 500,
	'map_height' => 300,
	'maptype' => 'normal',
	'maptype_control_display' => 1,
	'maptype_control_style' => 'drop_down',
	'maptype_control_position' => 'RT',
	'toolbar_control_display' => 1,
	'toolbar_control_style' => 'small',
	'toolbar_control_position' => 'LT',
	'display_layer' => 'none',
	'display_scale' => 1,
	'display_overview' => 1,
	'zoom' => 10,
	'api_key' => '',
	'sensor' => 0,
	'display_popup' => 0,
	'popup_width' => 640,
	'popup_height' => 480,
	'popup_type' => 'highslide'
);
$aUserSetting = $this->mapSetting;
//
$map = new stdClass();

$map->id = $this->mapId;
$aOptions = array();

foreach ($plgParams as $var => $value) {
    $map->$var = (isset($aUserSetting[$var])) ? $aUserSetting[$var] : $this->plgParams->get($var, $value);

    if (is_int($value)) {
        $map->$var = intval($map->$var);
    } elseif (is_float($value)) {
        $map->$var = floatval($map->$var);
    }

    if (is_int($map->$var) || is_float($map->$var)) {
        $aOptions[$var] = $map->$var;
    } else {
        $str = $map->$var;
        $str = preg_replace('/(\n|\r\n|\'|\"|\/)/', '', $str);
        $aOptions[$var] = $str;
    }
}
//exception: don't use default value of from_location
//because: google map can not calculate direction for every case

$map_id = 'ja-widget-map' . $map->id;

$popup_type = ($map->popup_type != 'global') ? 'modal="'.$map->popup_type.'"' : '';

//support unit in width and height
$mapwidth  = (isset($aUserSetting['map_width'])) ? $aUserSetting['map_width'] : $this->plgParams->get('map_width', $value);
$mapheight = (isset($aUserSetting['map_height'])) ? $aUserSetting['map_height'] : $this->plgParams->get('map_height', $value);
preg_match('/^(-?\d*\.?\d+)(px|%|em|rem|pc|ex|in|deg|s|ms|pt|cm|mm|rad|grad|turn)?/', $mapwidth . '', $map_width);
preg_match('/^(-?\d*\.?\d+)(px|%|em|rem|pc|ex|in|deg|s|ms|pt|cm|mm|rad|grad|turn)?/', $mapheight . '', $map_height);
if($map_width && isset($map_width[1])){
	$mapwidth = $map_width[1] . (isset($map_width[2]) ? $map_width[2] : 'px');
}
if($map_height && isset($map_height[1])){
	$mapheight = $map_height[1] . (isset($map_height[2]) ? $map_height[2] : 'px');
}
?>

<div id="<?php echo $map_id.'-container'; ?>" class="map-container" style="width:<?php echo $mapwidth; ?>">
    <div id="<?php echo $map_id; ?>" style="height:<?php echo $mapheight; ?>"></div>
	<div id="<?php echo $map_id.'-route'; ?>" class="map-route"></div>
</div>

<script type="text/javascript">
//<![CDATA[
var JAMapOptions<?php echo $map->id; ?> = <?php echo json_encode($aOptions); ?>;
var objWidgetMap<?php echo $map->id; ?> = new JAWidgetMap('<?php echo $map_id; ?>', JAMapOptions<?php echo $map->id; ?>);
var objWidgetMapPopup<?php echo $map->id; ?>;

window.addEvent('domready', function (){
	objWidgetMap<?php echo $map->id; ?>.displayMap('','',false);
});

</script>
