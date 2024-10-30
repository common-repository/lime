<?php
$wpLoad = dirname(__FILE__) . "/../../../../wp-load.php";
include_once($wpLoad);
global $Lime;
$lime_news = $Lime->lime_fetch_news();
print $lime_news;
?>