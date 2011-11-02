<?php

global $wpdb;
$table_1 = $wpdb->prefix . 'afftracks';

if($_REQUEST['clicky_bulk_action'] == 'Y') :
	if($_POST['action'] == 'delete'){
		$bulk_ids = $_POST['linkcheck'];
				
		foreach($bulk_ids as $linkid){
			$wpdb->query("DELETE FROM $table_1 WHERE `id` = '$linkid'");	
		}
		
		echo '<div class="updated"><p> Deleted Successfully! </p></div>';
	}
	else{
		echo '<div class="error"><p> Select an action! </p></div>';
	}

else:

	$linkid = preg_replace('/[^\d]/', '', $_GET['linkid']);
		
	$wpdb->query("DELETE FROM $table_1 WHERE `id` = '$linkid'");
		
	echo '<div class="updated"><p> Deleted ! </p></div>';

endif;