<?php 

$goal_id = trim($_POST['goal_id']);
$goal_revenue = trim($_POST['goal_revenue']);
$type = trim($_POST['clicky_type']);

$sids = rtrim($_POST['sold_sids'],',');

$sids = explode(',', $sids);
$afflink = get_option('siteurl').'/sale/' . $type;

//log the data into an array

global $ClicyPlus_clicky;

$error = array();

foreach($sids as $sid){
	
	$sid = preg_replace('/[^\d]/', '.', $sid);
		
	$clicky_log = array(
				'type' => 'goal',
				'ip_address' => $sid,
				'href' => $afflink,
				'goal' => $goal_id,
				'custom' => array(
								'type' => $type,
							),
								
			);
	/*		
	$clicky_log = array(
				'type' => 'outbound',
				'session_id' => preg_replace('/[^\d]/','',$sid),
				'href' => $afflink,
				'goal' => $goal_id,
				'custom' => array(
								'name' => $type,
							),
								
			);
			*/
	
		if(!$ClicyPlus_clicky->clicky_log($clicky_log)){
		$error[] = $sid;
			
	}
	
}


if(count($error)>0){
	$str = '';
	foreach($error as $v){
		$str .= $v . ',';
	}
	$str = rtrim($str,',');
	
	echo '<div class="error"> These SIDs ' . $str . ' are failed to logged in. Please copy and paste only these and try again </div>';
}
else{
	echo '<div class="updated"> SIDs are successfully logged </div>';
}
