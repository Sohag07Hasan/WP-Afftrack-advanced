<?php

$clicky = array(
	'site_id' => trim($_REQUEST['site_id']),
	'site_key' => trim($_REQUEST['site_key']),
	'admin_site_key' => trim($_REQUEST['admin_site_key']),
	'disable_admin' => trim($_REQUEST['disable_admin']),
	'goal_id' => trim($_POST['goal_id'])
);

update_option('clicky_new',$clicky);
echo '<div class="updated"><p>Options are saved</p></div>';