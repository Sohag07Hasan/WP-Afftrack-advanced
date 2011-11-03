<?php


$name = trim($_POST['link_name']);
$name = rtrim($name,'/');
$destination = trim($_POST['link_url']);

$status = $_POST['link_stauts'];
$link_id = (int)$_POST['link_id'];

if(!$status) $status = 'active' ;

global $ClicyPlus_clicky;




if($name == ''){
	
	echo '<div class="error"><p> Empty Name! </p></div>';
}

elseif($destination == ''){
	echo '<div class="error"><p> Web Address must not be empty </p></div>';
}

else{
	
	global $wpdb;
	$table = $wpdb->prefix . 'afftracks' ;
		
	//clicky table data
	$data = array(
		'name' => $name,
		'afflink' => $destination,
		'status' => $status
	);
	$data_format = array('%s','%s','%s');
	$where = array('id'=>$link_id);
	$where_format = array('%d');	
	
	
	if($link_id){
		$wpdb->update($table,$data,$where,$data_format,$where_format);
		$link = get_option('home').'/wp-admin/admin.php?page=wp_clicky_addnew&edit=yes&update=Y&linkid=' . $link_id ;
		
	}
	else{
		if($this->name_exists($name)) :
			$link = get_option('home').'/wp-admin/admin.php?page=wp_clicky_addnew&edit=yes&update=D&linkid=' . $link_id ;
			header("Location: $link");
			exit;	
		endif;
		$wpdb->insert($table,$data,$data_format);
		$link_id = $wpdb->insert_id;
		//kgoal table data		
		$link = get_option('home').'/wp-admin/admin.php?page=wp_clicky_addnew&edit=yes&update=N&linkid=' . $link_id ;
		
	}
	
	if(!function_exists('wp_redirect')){
		include ABSPATH . '/wp-includes/pluggable.php';
	}
	
	
	//ob_start();
	wp_redirect($link,301);
	//header("Location:$link");
	exit;
}
  