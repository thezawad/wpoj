<?php
/*
Plugin Name: wpoj
Version: 0.1
Plugin Author: johnnychen
Description: onlinejudge system based on wordpress
Plugin URI: http://code.google.com/p/wpoj/
Text Domain: wpoj
Domain Path: /lang


Copyright (C) 2010 johnnychen(johnnychq@gmail.com)
GPL V2
*/	
class OJ{
	function init(){
		OJ::init_contst_includes();
		add_action('init',array(__CLASS__,'_register_post_types'));
		add_action("admin_menu",array(__CLASS__,'_register_admin_menu'));
		if ( is_admin() ) {
			require_once (OJ_LIBRARY.'/class/meta_boxs.php');
			OJ_meta_box::init();
			add_action('save_post','oj_save_metas',10,2);
			add_action('delete_post','oj_delete_object_metas',10,1);
		}
	}
	function init_contst_includes(){
		define('OJ_LIBRARY',dirname(__FILE__).'/library');
		define('OJ_FUNCTIONS',OJ_LIBRARY.'/functions');
		define('OJ_CLASSES',OJ_LIBRARY.'/class');
		include(OJ_FUNCTIONS.'/db.php');
		include(OJ_FUNCTIONS.'/fps.php');
		include(OJ_CLASSES.'/objects.php');
		register_theme_directory( WP_PLUGIN_DIR . '/wpoj/themes' );
	}
	function _register_admin_menu(){
		add_submenu_page('edit.php?post_type=problem', 'Import Problems', 'Import Problems', 6, 'import_problems','oj_import_problems');
	}
	function _register_post_types(){
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
			'has_archive' => 'contests'
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
			'has_archive' =>'problems'
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
		register_taxonomy("problem_tag", "problem",array(
			'public' => true,
			'labels' => array(
				'name'=>'Problem Tags',
				'singular_name'=>'Problem Tag',
			),
			'hierarchical'=> false
		));
	}
}
add_action('plugins_loaded', array('OJ','init'));
register_activation_hook(__FILE__,'wpoj_active');
function wpoj_active(){
	global $wpdb;
	// Check for capability
	if ( !current_user_can('activate_plugins') ) 
		return;
	
	
	// add charset & collate like wp core
	$charset_collate = '';

	if ( ! empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	if ( ! empty($wpdb->collate) )
		$charset_collate .= " COLLATE $wpdb->collate";

   	$problem_meta					= $wpdb->prefix . 'problem_meta';
	$contest_meta					= $wpdb->prefix . 'contest_meta';

   
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
      echo $sql;
    }

	if($wpdb->get_var("show tables like '$contest_meta'") != $contest_meta) {
      
		$sql = "CREATE TABLE " . $contest_meta . " (
		`ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
		  `start_time` datetime DEFAULT NULL,
		  `end_time` datetime DEFAULT NULL,
		  `private` text,
		  `language` text,
		  PRIMARY KEY (`ID`),
		  KEY `post_id` (`post_id`)
		) $charset_collate;";
	
      $wpdb->query($sql);
   }

	// check one table again, to be sure
	if($wpdb->get_var("show tables like '$contest_meta'")!= $contest_meta) {
		update_option( "ngg_init_check", __('NextGEN Gallery : Tables could not created, please check your database settings',"nggallery") );
		return;
	}
}
