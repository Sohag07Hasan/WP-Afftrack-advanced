<?php
/*
 * plugin name: ClickyPlus
 * author: Clicky Plus
 * author uri: http://www.clickyplus.com/about.html
 * plugin uri: http://www.clickyplus.com
 * description: ClickyPlus, integrates GetClicky webstats with your blog, and adds a variety of useful features for advanced affiliate marketing tracking; cloak outbound links, add a unique SID or SubID to outbound urls, and import sales data as goals back into GetClicky. Learn more about setting up this plugin by visiting <a href="http://www.clickyplus.com/setup.html">ClickyPlus.com</a>
 * version: 1.0.0
 * */

if(!class_exists('wpafftrack_clicky')) : 
	
	class wpafftrack_clicky{
		
		function __construct(){
			register_activation_hook( __FILE__, array($this,'table_creation'));			
			
			include dirname(__FILE__) . '/includes/links-management.php';
			
			add_action('init',array($this,'redirect'));
			
			add_action('admin_enqueue_scripts',array($this,'add_css'),20);
			
			add_action('admin_print_scripts',array($this,'add_js'));
			
			//clicky trakcing code in getclicky
			add_action('wp_footer',array($this,'clicky_script'),90);
			add_action('comment_post',array($this,'clicky_track_comment'),10,2);
			
		}
		
		//css
		function add_css(){
			
			if($_REQUEST['page'] == 'wp_clicky_links') :
				wp_register_style('clicky_css', plugins_url('', __FILE__).'/css/style.css');
				wp_enqueue_style('clicky_css');
			endif;
		}
		
		//js
		function add_js(){
			
			if($_REQUEST['page'] == 'wp_clicky_links') : 
				wp_enqueue_script('jquery');
				wp_register_script('clicky_js', plugins_url('', __FILE__).'/js/hover-script.js',array('jquery'));
				wp_enqueue_script('clicky_js');
			endif;
		}
		
		//table creation
		function table_creation(){			
			global $wpdb;
			$table_1 = $wpdb->prefix . 'afftracks';
			$sql_1 = "CREATE TABLE IF NOT EXISTS $table_1(
				`id` bigint unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(100) NOT NULL collate utf8_bin,
				`afflink` longtext NOT NULL,																
				`clktime` TIMESTAMP,
				`status` varchar(100) DEFAULT 'active',
				PRIMARY KEY(id),
				UNIQUE(name) 
			)";
			
			$table_2 = $wpdb->prefix . 'goals';
			$sql_2 = "CREATE TABLE IF NOT EXISTS $table_2(
				`g_id` bigint unsigned NOT NULL,
				`g_name` varchar(100) DEFAULT 'rename',
				`goalid` varchar(300) NOT NULL,
				`revenue` bigint NOT NULL							
			)";
			
			//including database script
			if(!function_exists('dbDelta')) :
				include ABSPATH . 'wp-admin/includes/upgrade.php';
			endif;
			dbDelta($sql_1);			
			//dbDelta($sql_2);			
		} //table creation
		
		
		//password generator
		function generate_password( $length = 12, $special_chars = true, $extra_special_chars = false ) {
			$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			if ( $special_chars )
				$chars .= '!@#$%^&*()';
			if ( $extra_special_chars )
				$chars .= '-_ []{}<>~`+=,.;:/?|';

			$password = '';
			for ( $i = 0; $i < $length; $i++ ) {
				$password .= substr($chars, rand(0, strlen($chars) - 1), 1);
			}

			return $password ;
	
		}
		
		//randomstring generator
		function randmonstring(){
			$id = $this->generate_password(26,false,false);
			global $wpdb;
			$table = $wpdb->prefix . 'afftracks' ;
			$var = $wpdb->get_var("SELECT `id` FROM $table WHERE `sid` = '$id' ");
			if($var) return $this->randmonstring();
			return $id;
		}
		
		//redirection and log to the clicky
		function redirect(){
			
			$url = $_SERVER['HTTPS']== 'on' ? 'https' : 'http';
			$url .= '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$home = get_option('siteurl') . '/';
			
			$pattern = '/' . preg_replace('/\//','\\/',$home) . '/';
			
			$slug = preg_replace($pattern,'',$url);
			$slug = rtrim($slug,'/');
			
			global $wpdb;
			$clicky_table = $wpdb->prefix . 'afftracks';
			
			
			$afflinks = $wpdb->get_var("SELECT `afflink` FROM $clicky_table WHERE `name` = '$slug' AND `status` = 'active'");
			if($afflinks) : 
				$afflinks = explode("\n", $afflinks);
						
				//redirection occurs here
				if(count($afflinks)>0) :
					
					/*
					$sid = session_id();
					if(!$sid){
						@ session_start();
						$sid = session_id();
					}
				*/
					$dd = array('a','b','c','d');
					$max = count($dd) - 1;
					$i = rand(0,$max);				
					$sid = $_SERVER['REMOTE_ADDR'];
					$sid = preg_replace('/\./',$dd[$i], $sid);
					
					$clicky = get_option('clicky_new');					
					$array_no = count($afflinks) - 1 ;
					$random = rand(0,$array_no);
					
					//$sid = time() + rand(1000,9000);
					$afflink = preg_replace('/[ ]/','',$afflinks[$random]);
					
					$afflink = preg_replace('/[\[{(].*[\])}]/',$sid,$afflink);
					
					$log_clicky = array(
									'type' => 'outbound',
									'href' => 'http://' . $slug,
									'ua' => $_SERVER['HTTP_USER_AGENT'],									
									'custom' => array(
										'time' => time(),										
										'outbound' => $afflink
									 ),
								);
					$this->clicky_log($log_clicky);
															
					if(!function_exists('wp_redirect')){
						include ABSPATH . '/wp-includes/pluggable.php';
					}
					//wp_redirect($afflink,301);
					wp_redirect($afflink,301);
					
					exit;
						
				endif;
			endif;
			
		}
		
		//clicky log function
		function clicky_log( $a ) {
			//var_dump($a);
			
			$options = get_option('clicky_new');
			
			if (!isset($options['site_id']) || empty($options['site_id']) || !isset($options['admin_site_key']) || empty($options['admin_site_key']))
				return;
		
			$type = $a['type'];
			if( !in_array( $type, array( "pageview", "download", "outbound", "click", "custom", "goal" ))) 
				$type = "pageview";
		
			$file = "http://in.getclicky.com/in.php?site_id=".$options['site_id']."&sitekey_admin=".$options['admin_site_key']."&type=".$type;
		
			# referrer and user agent - will only be logged if this is the very first action of this session
			if( $a['ref'] ) 
				$file .= "&ref=".urlencode( $a['ref'] );
				
			if( $a['ua']  ) 
				$file .= "&ua=". urlencode( $a['ua']  );
		
			# we need either a session_id or an ip_address...
			if( is_numeric( $a['session_id'] )) {
				$file .= "&session_id=".$a['session_id'];
			} else {
				if( !$a['ip_address'] ) 
					$a['ip_address'] = $_SERVER['REMOTE_ADDR']; # automatically grab IP that PHP gives us.
				if( !preg_match( "#^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$#", $a['ip_address'] )) 
					return false;
				$file .= "&ip_address=".$a['ip_address'];
			}
		
			# goals can come in as integer or array, for convenience
			if( $a['goal'] ) {
				if( is_numeric( $a['goal'] )) {
					$file .= "&goal[id]=".$a['goal'];
				} else {
					if( !is_numeric( $a['goal']['id'] )) 
						return false;
					foreach( $a['goal'] as $key => $value ) 
						$file .= "&goal[".urlencode( $key )."]=".urlencode( $value );
				}
			}
		
			# custom data, must come in as array of key=>values
			foreach( $a['custom'] as $key => $value ) 
				$file .= "&custom[".urlencode( $key )."]=".urlencode( $value );
		
			if( $type == "goal" || $type == "custom" ) {
				# dont do anything, data has already been cat'd
			} else {
				if( $type == "outbound" ) {
					if( !preg_match( "#^(https?|telnet|ftp)#", $a['href'] )) 
						return false;
				} else {
					# all other action types must start with either a / or a #
					if( !preg_match( "#^(/|\#)#", $a['href'] )) 
						$a['href'] = "/" . $a['href'];
				}
				$file .= "&href=".urlencode( $a['href'] );
				if( $a['title'] ) 
					$file .= "&title=".urlencode( $a['title'] );
			}
						
					
			return wp_remote_get( $file ) ? true:false;
			//return file( $file ) ? true:false;
		}
		
		//copy and paste from original clicky
		function clicky_script(){
			$options = get_option('clicky_new');
			if ( is_preview() )	return;
			
		// Bail early if current user is admin and ignore admin is true
			if( $options['disable_admin'] == 'yes') {
				echo "\n<!-- ".__("Clicky tracking not shown because you're an administrator and you've configured Clicky to ignore administrators.", 'clicky')." -->\n";
				return;
			}
			
		
	// Branding
		?>
			<!-- Clicky Web Analytics - http://getclicky.com, WordPress Plugin by Yoast - http://yoast.com -->
			
				<script type='text/javascript'>
			
					function clicky_gc( name ) {
						var ca = document.cookie.split(';');
						for( var i in ca ) {
							if( ca[i].indexOf( name+'=' ) != -1 )
								return decodeURIComponent( ca[i].split('=')[1] );
						}
						return '';
					}
					var clicky_custom_session = { 
						username: clicky_gc( 'comment_author_<?php echo md5( get_option("siteurl") ); ?>' )
					};
	
				</script>
			  	<?php
							
				// Goal tracking
				if (is_singular()) {
					global $post;
					$clicky_goal = get_post_meta($post->ID,'_clicky_goal',true);
					if (is_array($clicky_goal) && !empty($clicky_goal['id'])) {
						echo '<script type="text/javascript">';
						echo 'var clicky_goal = { id: "'.trim($clicky_goal['id']).'"';
						if (isset($clicky_goal['value']) && !empty($clicky_goal['value'])) 
							echo ', revenue: "'.$clicky_goal['value'].'"';
						echo ' };';
						echo '</script>';
					}
				}
				
				// Display the script
			?>
			<script type="text/javascript">
				var clicky = { log: function(){ return; }, goal: function(){ return; }};
				var clicky_site_id = <?php echo $options['site_id']; ?>;
				(function() {
					var s = document.createElement('script');
					s.type = 'text/javascript'; s.async = true;
					s.src = '//static.getclicky.com/js';
					( document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0] ).appendChild( s );
				})();
			</script>
			<noscript><p><img alt="Clicky" width="1" height="1" src="http://in.getclicky.com/<?php echo $options['site_id']; ?>ns.gif" /></p></noscript>
		
			<!-- End Clicky Tracking -->
		<?php

		}
		
		//comment trakcing
		function clicky_track_comment($commentID, $comment_status) {
		// Make sure to only track the comment if it's not spam (but do it for moderated comments).
			if ($comment_status != 'spam') {
				$comment = get_comment($commentID);
				// Only do this for normal comments, not for pingbacks or trackbacks
				if ($comment->comment_type != 'pingback' && $comment->comment_type != 'trackback') {
					$this->clicky_log( 
						array( 
							"type" 			=> "click", 
							"href" 			=> "/wp-comments-post.php", 
							"title" 		=> __("Posted a comment",'clicky'),
							"ua"			=> $comment->comment_agent,
							"ip_address"	=> $comment->comment_author_IP,
							"custom" 		=> array(
								"username" 	=> $comment->comment_author,
								"email"		=> $comment->comment_author_email,
							)
						) 
					);
				}
			}
		}
		
		
	}
	
	
	//object creation
	$wpafftrack_clicky = new wpafftrack_clicky();
endif;

?>
