<?php

/* 
 * this class is to manipulate the admin panel
 */

if(!class_exists('wpafftrack_clicky_admin')) : 

	class wpafftrack_clicky_admin{
		function __construct(){
			add_action('admin_menu', array($this,'create_a_menu'));
			add_action('init',array($this,'formdata_manipulation'));
			
			
		}
		
		function create_a_menu(){
		//add_menu_page(__('get clicky into your site'),__('WP Clicky'),'activate_plugins','wp-get-clicky',array($this,'OptionsPage'));			
			/*
			add_menu_page(__('get clicky into your site'),__('Outbound Links'),'activate_plugins','wp_clicky_links',array($this,'OptionsPage'));			
			add_submenu_page('wp_clicky_links',__('add or edit'),__('Add New Link'),'activate_plugins','wp_clicky_addnew',array($this,'add_new'));		
			//add_submenu_page('wp_clicky_links',__('clicky information'),__('Clicky Info'),'activate_plugins','clicky-import-result-information',array($this,'import_result_cicky_info'));
			add_submenu_page('wp_clicky_links',__('Log Goals Into GetClikcy'),__('Log SIDs'),'activate_plugins','clicky-stats',array($this,'clicky_log'));
			add_submenu_page('wp_clicky_links',__('Adjust GetClicky Settings in ClickyPlus'),__('Settings'),'activate_plugins','clicky-import-result-information',array($this,'import_result_cicky_info'));
			*/
			
			add_menu_page(__('get clicky into your site'),__('ClickyPlus Settings'),'activate_plugins','wp_clicky_links',array($this,'import_result_cicky_info'));
			
			add_submenu_page('wp_clicky_links',__('get clicky into your site'),__('Outbound Links'),'activate_plugins','wp_clicky_outbound_links',array($this,'OptionsPage'));			
			add_submenu_page('wp_clicky_links',__('add or edit'),__('Add New Link'),'activate_plugins','wp_clicky_addnew',array($this,'add_new'));		
			//add_submenu_page('wp_clicky_links',__('clicky information'),__('Clicky Info'),'activate_plugins','clicky-import-result-information',array($this,'import_result_cicky_info'));
			add_submenu_page('wp_clicky_links',__('Log Goals Into GetClikcy'),__('Log SIDs'),'activate_plugins','clicky-stats',array($this,'clicky_log'));
			
		}

			
		//formdata manipulation
		function formdata_manipulation(){
			//if the form is submitted
			if($_POST['clickysubmit'] == 'yes' && $_REQUEST['page'] == 'wp_clicky_addnew') :
				include dirname(__FILE__) .'/form-data-manipulation.php' ;
			endif;
		}
		
		
		// clicky importation data
		function clicky_log(){
						
			$options = get_option('clicky_new');
			$goal_id = $options['goal_id'];
							
			?>
			<div class="wrap">
				<?php screen_icon('link-manager'); ?>
				<h2>Log Goals With ClickyPlus</h2>
				
				<?php 
					if($_REQUEST['clciky_log_status'] == 'Y') :
						include dirname(__FILE__) . '/log-controller.php';
					endif;
				?>
								
				<form name="clicky" id="clicky" method="post" action="">
					<input type="hidden" name="clciky_log_status" value="Y"  />
					<div id="poststuff" class="metabox-holder has-right-sidebar">
						<div id="post-body">
							<div id="post-body-content">
							
								<div id="namediv" class="stuffbox">
									<h3> <level for="link_name" >Goal Information</level> </h3>
									<div class="inside">
										<table class="form-table">
											<tbody>
												<tr class="site_id_row">
													<th valign="top" scope="row">
														Goal Id : 
													</th>
													<td valign="top">
														<input type="text" name="goal_id" value="<?php echo $goal_id; ?>" />
													</td>
												</tr>
																		
												
												<tr class="site_id_row">
													<th valign="top" scope="row">
														Type : 
													</th>
													<td valign="top">
														<select name="clicky_type">
															<option value="sale">Sale</option>
															<option value="lead">Lead</option>
															<option value="download">Download</option>
															<option value="pageview">Pageview</option>
															
														</select>
													</td>
												</tr>
												
												<tr class="site_id_row">
													<th valign="top" scope="row">
														SIDs (comma separated) : 
													</th>
													<td valign="top">
														<textarea name="sold_sids" rows="3" cols="55"></textarea>
													</td>
												</tr>
												
											</tbody>
										</table>																			
									</div>
								</div> <!-- stuffbox -->
																
								
							</div>
						</div>
					
										
					<div id="side-info-column" class="inner-sidebar">
							<div id="linkgoaldiv" class="postbox ">
								<div class="handlediv" title="Click to toggle"><br/></div>
								<h3 class="hndle"><span> Log into clicky </span></h3>
								<div class="inside">
									<input type="submit" value="Log" class="button-primary" />
								</div>
							</div>
					</div> <!-- innder sidebar -->
				</div>						
				</form>
			</div> <!-- wrap -->
			
			
			<?php 		
		}
		
		
		
		function OptionsPage(){
			
			if($_REQUEST['page'] == 'wp_clicky_outbound_links' && $_REQUEST['linkid'] && $_REQUEST['delete'] == 'yes') : 
				include dirname(__FILE__) . '/delete-links.php';
			endif;
			
			if($_REQUEST['page'] == 'wp_clicky_outbound_links' && $_REQUEST['clicky_bulk_action'] == 'Y') : 
				include dirname(__FILE__) . '/delete-links.php';
			endif;
			
			echo '<div class="wrap">' ;
			screen_icon('link-manager');
			echo '<h2>Links</h2>';
			
			
		?>
			
			<form action="" method="post">
				
				<input type="hidden" name="clicky_bulk_action" value="Y" />
				
				<div class="tablenav top">
				
					<div class="alignleft actions">
					
						<select name="action">
							<option selected="selected" value="-1">Bulk Actions</option>
							<option value="delete">Delete</option>
						</select>
						<input id="doaction" class="button-secondary action" type="submit" value="Apply" name="clicky_bulk_action_submit">
					</div>
					<div class="alignleft actions">
						
					</div>
					<br class="clear">
				</div>
				<table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
					<thead>
						<tr>
							<th id="cb" class="manage-column column-cb check-column" style="" scope="col">
								<input type="checkbox">
							</th>
							<th id="name" class="manage-column column-name sortable desc" style="" scope="col">
								<a href="#">
									<span>Name</span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="url" class="manage-column column-url sortable desc" style="" scope="col">
								<a href="#">
									<span>URLS</span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="url_cloaked" class="manage-column column-url sortable desc" style="" scope="col">
								<a href="#">
									<span>Cloaked URL</span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							
							<th class="manage-column column-visible sortable desc" style="" scope="col">
								<a href="#">
									<span>Status</span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							
							
						</tr>
					</thead>
					
					<tbody>
					<?php 
						global $wpdb;
						$table1 = $wpdb->prefix . 'afftracks';					
						
						$clickys = $wpdb->get_results("SELECT * FROM $table1 ORDER BY `name` ASC");
						//$goals = $wpdb->get_results("SELECT * FROM $table2 ");
						$edit_link = get_option('home').'/wp-admin/admin.php?page=wp_clicky_addnew&edit=yes&linkid=';
						$delete_link = get_option('home').'/wp-admin/admin.php?page=wp_clicky_outbound_links&delete=yes&linkid=';
						$home = get_option('siteurl');
						foreach($clickys as $clicky){
							$cloakedlink = $home. '/' . $clicky->name ;						
							echo "<tr class='wp-clicky-td'>
								<th class='check-column' scope='row'>
									<input type='checkbox' value='$clicky->id' name='linkcheck[]'>
								</th>
								<td> 
									$clicky->name
									<div class='row-actions'>
										<a href='$edit_link".$clicky->id."'>Edit</a>&nbsp| 
										<a style='color:red' href='$delete_link".$clicky->id."'>Delete</a>
									</div>
								</td>
								<td> $clicky->afflink </td>
								<td> $cloakedlink </td>
								<td> $clicky->status </td>						
												
							</tr>";
						}
					?>
					</tbody>
					
					<tfoot>
						<tr>
							<th class="manage-column column-cb check-column" style="" scope="col">
								<input type="checkbox">
							</th>
							<th class="manage-column column-name sortable desc" style="" scope="col">
								<a href="#">
									<span>Name</span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th class="manage-column column-url sortable desc" style="" scope="col">
								<a href="#">
									<span>URL</span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th class="manage-column column-url sortable desc" style="" scope="col">
								<a href="#">
									<span>Cloaked URL</span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							
							<th class="manage-column column-visible sortable desc" style="" scope="col">
								<a href="#">
									<span>Status</span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
													
						</tr>
					</tfoot>
				</table>
			</form>
			
		<?php 
		echo '</div>';
		//wrap class
		}
		
		function add_new(){			
			
			//edit links
			if($_GET['edit'] == 'yes' && $_GET['linkid'] && $_REQUEST['page'] == 'wp_clicky_addnew') : 
				include dirname(__FILE__) .'/link-edit.php' ;
			endif;
			
						
		?>
		 
			<div class="wrap">
				<?php screen_icon('link-manager'); ?>
				<h2>Add New Link</h2>
				
				<?php 
		
					if($_REQUEST['update'] == 'Y'){
						echo '<div class="updated"><p>Updated</p></div>';
					}
					
					if($_REQUEST['update'] == 'N'){
						echo '<div class="updated"><p>Saved</p></div>';
					}
					if($_REQUEST['update'] == 'D'){
						echo '<div class="error"><p>Duplicate Name found!</p></div>';
					}
					
				?>
				
				<form name="addlink" id="addlink" method="post" action="">
				
					<input type="hidden" name="link_id" value="<?php echo $link_id; ?>" />
					<input type="hidden" name="clickysubmit" value="yes" />
				
					<div id="poststuff" class="metabox-holder has-right-sidebar">
						<div id="post-body">
							<div id="post-body-content">
							
								<div id="namediv" class="stuffbox">
									<h3> <level for="link_name" >Name</level> </h3>
									<div class="inside">
										<input id="link_name" type="text" value="<?php echo $name; ?>" tabindex="1" size="30" name="link_name" />
										<p>Example: Google will make your link /Google/, out/Google/ will make your link /out/Google</p>										
									</div>
								</div>
								
								<div id="addressdiv" class="stuffbox">
									<h3> <level for="link_address" >Web Addresses (separated by new line)</level> </h3>
									<div class="inside">
										<textarea tabindex="1" rows="10" cols="72" type="text"  name="link_url"><?php echo $afflink;?></textarea>
											<p>Example: <code>http://www.example.com?affid=123&sid={SID}</code></p>										
									</div>
								</div>
								
								<div id="cloakeddiv" class="stuffbox">
									<h3> <level for="cloaked_address" >Cloaked Address (will update when you save)</level> </h3>
									<div class="inside">
									
										<p><?php echo $cloakedlink; ?> </p>
										<p>Example:<code> <?php echo get_option('home').'/name'; ?></code> </p>
																					
									</div>
								</div>
																						
																
							</div>
						</div> <!-- poststuff  -->
						
						<!-- sidepanel is starting -->
						
						
						<div id="side-info-column" class="inner-sidebar">
							
							<div id="linksubmitdiv" class="postbox ">
								<div class="handlediv" title="Click to toggle"><br/></div>
								<h3 class="hndle"><span> Save / Update </span></h3>
								<div class="inside">
									<div class="misc-pub-section misc-pub-section-last">
										<label class="selectit" for="link_private">
										<input tabindex="3" id="link_private" type="checkbox" value="inactive" name="link_stauts" <?php checked('inactive',$status); ?> >
											Add as inactive
										</label>
										<br/><br/>
										<hr/>								
										<input tabindex="4" type="submit" value="Save" class="button-primary" />									
									</div>
									
								</div>
							</div>
							
						</div> <!-- side-info-column -->
						
					</div>				
				</form>
			</div>
			
			
		<?php 	
		}
		
		function import_result_cicky_info(){
			
			if($_REQUEST['clciky_submit'] == 'Y') : 
				include dirname(__FILE__) .'/clicky-options.php';
			endif;
			
			$options = get_option('clicky_new');
		?>
			<div class="wrap">
				<?php screen_icon('link-manager'); ?>
				<h2>Configure ClickyPlus</h2>
				
				<p >To see the stats  <a style="color:red" href="https://secure.getclicky.com/user">Go to Clicky Home page</a> </p>
				
				<form name="clicky" id="clicky" method="post" action="">
					<input type="hidden" name="clciky_submit" value="Y"  />
					<div id="poststuff" class="metabox-holder has-right-sidebar">
						<div id="post-body">
							<div id="post-body-content">
							
								<div id="namediv" class="stuffbox">
									<h3> <level for="link_name" >Clicky Settings</level> </h3>
									<div class="inside">
										<table class="form-table">
											<tbody>
												<tr class="site_id_row">
													<th valign="top" scope="row">
														Site ID : 
													</th>
													<td valign="top">
														<input type="text" name="site_id" value="<?php echo $options['site_id'] ; ?>" />
													</td>
												</tr>
												
												<tr class="site_id_row">
													<th valign="top" scope="row">
														Site Key : 
													</th>
													<td valign="top">
														<input type="text" name="site_key" value="<?php echo $options['site_key'] ; ?>" />
													</td>
												</tr>
												
												<tr class="site_id_row">
													<th valign="top" scope="row">
														Admin Site Key : 
													</th>
													<td valign="top">
														<input type="text" name="admin_site_key" value="<?php echo $options['admin_site_key'] ; ?>" />
													</td>
												</tr>
												<tr class="site_id_row">
													<th valign="top" scope="row">
														Goal Id : 
													</th>
													<td valign="top">
														<input type="text" name="goal_id" value="<?php echo $options['goal_id'] ; ?>" />
													</td>
												</tr>
											</tbody>
										</table>																			
									</div>
								</div> <!-- stuffbox -->
																
								
							</div>
						</div>
					
					<div class="inner-sidebar">
							<div id="linkgoaldiv" class="postbox ">
								<div class="handlediv" title="Click to toggle"><br/></div>
								<h3 class="hndle"><span> Advanced Settings </span></h3>
								<div class="inside">
									<input type="checkbox" name="disable_admin" value='yes' <?php checked('yes',$options['disable_admin']) ?> /> Ignore Registered Users
								</div>
							</div>
					</div> <!-- innder sidebar -->
					
					<div id="side-info-column" class="inner-sidebar">
							<div id="linkgoaldiv" class="postbox ">
								<div class="handlediv" title="Click to toggle"><br/></div>
								<h3 class="hndle"><span> Save/Update </span></h3>
								<div class="inside">
									<input type="submit" value="Update Clicky Settings" class="button-primary" />
								</div>
							</div>
					</div> <!-- innder sidebar -->
				</div>						
				</form>
			</div> <!-- wrap -->
		
		<?php 
		}
		
		//name exists function
		function name_exists($a){
			global $wpdb;
			$table = $wpdb->prefix .'afftracks';
			$result = $wpdb->get_var("SELECT `id` FROM $table WHERE `name` = '$a' ");
			return $result;
		}
	
	}
	//object
	$wpafftrack_admin = new wpafftrack_clicky_admin();
endif;