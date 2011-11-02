<?php
$options = get_option('clicky_new');
@ extract($options);
$api_link = "http://api.getclicky.com/api/stats/4?site_id=$site_id&sitekey=$site_key&type=links-outbound,actions-list&output=csv&date=today";
$a = file($api_link);
$b = implode('"\n"', $a);
header('Content-type: text/csv');
header("Content-disposition: attachment;filename=clickystats.csv");
echo $b;
exit;