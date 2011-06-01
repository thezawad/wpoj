<?php
/*
Plugin Name: Online Judge
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
		OJ::_init();
		add_action('init',array(__CLASS__,'_register_post_types'));
		if ( is_admin() ) {
			require_once (OJ_LIBRARY.'/class/meta_boxs.php');
			OJ_meta_box::init();
			add_action('save_post','oj_save_metas',"10",2);
		}
	}
	function _init(){
		define('OJ_LIBRARY',dirname(__FILE__).'/library');
		define('OJ_FUNCTIONS',OJ_LIBRARY.'/functions');
		define('OJ_CLASSES',OJ_LIBRARY.'/class');
		include(OJ_FUNCTIONS.'/db.php');
		include(OJ_CLASSES.'/objects.php');
		
	}
	function _register_post_types(){
		/* register post types */
		register_post_type("contest",array(
			'public' => true,
			'labels' => array(
				'name' => 'Contests',
				'singular_name' => 'Contest',
				'search_items' =>'Search Contests',
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



