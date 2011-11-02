<?php
$link_id = preg_replace('/[^\d]/', '', $_REQUEST['linkid']) ;
$link_id = (int)$link_id;

global $wpdb;
$table = $wpdb->prefix . 'afftracks' ;

$cliky_datas = $wpdb->get_row("SELECT * FROM $table WHERE `id` = '$link_id'",ARRAY_A);

@ extract($cliky_datas,EXTR_SKIP);

$cloakedlink = get_option('home') . '/' . $name;
