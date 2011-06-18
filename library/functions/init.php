<?php
function oj_register_admin_menu(){
	add_submenu_page('edit.php?post_type=problem', 'Import Problems', 'Import Problems', 6, 'import_problems','oj_import_problems');
}
function oj_register_post_types(){
	/* register post types */
	register_post_type("contest",array(
		'public' => true,
		'labels' => array(
			'name' => 'Contests',
			'singular_name' => 'Contest',
			'search_items' =>'Search Contests',
			'new_item' => 'New Contest',
			'edit_item' => 'Edit Contest',
			'view_item' => 'View Contest',
			'add_new_item' => 'Add New Contest',
			'not_found' => 'No contests found'
		),
		'has_archive' => 'contests',
		'supports' => array('title','comments')
	));
	
	register_post_type("problem",array(
		'public' => true,
		'labels' => array(
			'name' => 'Problems',
			'singular_name' => 'Problem',
			'new_item' => 'New Problem',
			'edit_item' => 'Edit Problem',
			'view_item'	=> 'View Problem',
			'add_new_item' => 'Add New Problem',
			'search_items' => 'Search Problems',
			'not_found' => 'No problems found'
		),
		'has_archive' =>'problems',
	));
	register_post_type("solution",array(
		'public' => true,
		'labels' => array(
			'name' => 'Solutions',
			'singular_name' => 'Solution',
			'search_items' => 'Search solutions',
			'not_found' => 'No solutions found'
		)
	));
	/* register post types connections*/
	p2p_register_connection_type(array(
		'from' => 'contest',
		'to' => 'problem',
		'reciprocal' => true,
		'prevent_duplicates'=> false,
		'title' => array('from'=>'Include Prolems','to'=>'Included Contest')
	));
	p2p_register_connection_type('solution','problem');
	/* register post types taxonomies */
	register_taxonomy("problem_cat", "problem",array(
		'public' => true,
		'labels' => array(
			'name'=>'Problem Categories',
			'singular_name'=>'Problem Category',
		),
		'hierarchical'=> true
	));
	register_taxonomy("fps_cat", "problem",array(
		'public' => true,
		'labels' => array(
			'name'=>'FPS Categores',
			'singular_name'=>'FPS Category',
		),
		'hierarchical'=> true
	));
	register_taxonomy("problem_tag", "problem",array(
		'public' => true,
		'labels' => array(
			'name'=>'Problem Tags',
			'singular_name'=>'Problem Tag',
		),
		'hierarchical'=> false
	));
	add_action('save_post','oj_save_metas',10,2);
	add_action('delete_post','oj_delete_object_metas',10,1);
}
function oj_active(){
	global $wpdb,$oj;
	// Check for capability
	if ( !current_user_can('activate_plugins') ) 
		return;
	
	
	// add charset & collate like wp core
	$charset_collate = '';

	if ( ! empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	if ( ! empty($wpdb->collate) )
		$charset_collate .= " COLLATE $wpdb->collate";

   	$problem_meta					= $oj->prefix . 'problem_meta';
	$contest_meta					= $oj->prefix . 'contest_meta';
	$solution						= $oj->prefix . 'solution';
	$source_code					= $oj->prefix . 'source_code';
	$compileinfo					= $oj->prefix . 'compileinfo';
	$users_meta						= $oj->prefix . 'users_meta';
   
	if($wpdb->get_var("show tables like '$problem_meta'") != $problem_meta) {
      
		$sql = "CREATE TABLE " . $problem_meta . " (
		`ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
		  `input` text,
		  `output` text,
		  `sample_input` text,
		  `sample_output` text,
		  `spj` char(1) NOT NULL DEFAULT '0',
		  `hint` text,
		  `source` varchar(100) DEFAULT NULL,
		  `time_limit` int(11) NOT NULL DEFAULT '0',
		  `memory_limit` int(11) NOT NULL DEFAULT '0',
		  `accepted` int(11) DEFAULT '0',
		  `submit` int(11) DEFAULT '0',
		  PRIMARY KEY (`ID`),
		  KEY `post_id` (`post_id`)
		) $charset_collate;";
	
      $wpdb->query($sql);
    }

	if($wpdb->get_var("show tables like '$contest_meta'") != $contest_meta) {
      
		$sql = "CREATE TABLE " . $contest_meta . " (
		`ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
		  `start_time` datetime DEFAULT NULL,
		  `end_time` datetime DEFAULT NULL,
		  `private` tinyint(4) NOT NULL DEFAULT '0',
		  `langmask` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'bits for LANG to mask',
		  PRIMARY KEY (`ID`),
		  KEY `post_id` (`post_id`)
		) $charset_collate;";
	
      $wpdb->query($sql);
   }
   
	if($wpdb->get_var("show tables like '$solution'") != $solution) {
      //className,valid,num 不知道干嘛的
		$sql = "CREATE TABLE " . $solution . " (
		`solution_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  `problem_id` bigint(20) unsigned NOT NULL DEFAULT '0',
		  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
		  `user_login` varchar(60) NOT NULL DEFAULT '',
		  `time` int(11) NOT NULL DEFAULT '0',
		  `memory` int(11) NOT NULL DEFAULT '0',
		  `in_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `className` varchar(20) NOT NULL DEFAULT '',
		  `result` smallint(6) NOT NULL DEFAULT '0',
		  `language` tinyint(4) NOT NULL DEFAULT '0',
		  `ip` varchar(20) NOT NULL DEFAULT '',
		  `contest_id` bigint(20) unsigned NOT NULL DEFAULT '0',
		  `valid` tinyint(4) NOT NULL DEFAULT '1',
		  `cp_id` bigint(20) unsigned NOT NULL DEFAULT '-1',
		  `code_length` int(11) NOT NULL DEFAULT '0',
		  `judgetime` datetime DEFAULT NULL,
		  PRIMARY KEY (`solution_id`),
		  KEY `uid` (`user_id`),
		  KEY `user_login_key` (`user_login`),
		  KEY `pid` (`problem_id`),
		  KEY `res` (`result`),
		  KEY `cid` (`contest_id`)
		) $charset_collate;";
		
		$wpdb->query($sql);
	}
	
	if($wpdb->get_var("show tables like '$source_code'") != $source_code) {
      //className,valid,num 不知道干嘛的
		$sql = "CREATE TABLE " . $source_code . " (
		  `solution_id` bigint(20) unsigned NOT NULL DEFAULT '0',
		  `source` text NOT NULL,
		  PRIMARY KEY (`solution_id`)
		) $charset_collate;";
		
      $wpdb->query($sql);
   }
   
	if($wpdb->get_var("show tables like '$compileinfo'") != $compileinfo) {
      //className,valid,num 不知道干嘛的
		$sql = "CREATE TABLE " . $compileinfo . " (
		  `solution_id` bigint(20) unsigned NOT NULL DEFAULT '0',
		  `error` text,
		  PRIMARY KEY (`solution_id`)
		) $charset_collate;";
		
      $wpdb->query($sql);
   }
   
	if($wpdb->get_var("show tables like '$users_meta'") != $users_meta) {
      //className,valid,num 不知道干嘛的
		$sql = "CREATE TABLE " . $users_meta . " (
		    `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
		    `user_login` varchar(60) NOT NULL DEFAULT '',
			  `submit` int(11) DEFAULT '0',
			  `solved` int(11) DEFAULT '0',
			  `defunct` char(1) NOT NULL DEFAULT 'N',
			  `ip` varchar(20) NOT NULL DEFAULT '',
			  `accesstime` datetime DEFAULT NULL,
			  `volume` int(11) NOT NULL DEFAULT '1',
			  `language` int(11) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`user_id`),
			  KEY `user_login` (`user_login`)
		) $charset_collate;";
		
      $wpdb->query($sql);
      $sql="INSERT INTO $users_meta(user_id,user_login) SELECT ID,user_login FROM {$wpdb->prefix}users";
      $wpdb->query($sql);
   }
	// check one table again, to be sure
	if($wpdb->get_var("show tables like '$contest_meta'")!= $contest_meta) {
		update_option( "ngg_init_check", __('NextGEN Gallery : Tables could not created, please check your database settings',"nggallery") );
		return;
	}
}
?>