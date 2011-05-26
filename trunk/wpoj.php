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
		add_action('init',array(__CLASS__,'_register_post_type'));
	}
	function _register_post_type(){
		register_post_type("contest",array(
			'public' => true,
			'labels' => array(
				'name' => 'Contests',
				'singular_name' => 'Contest',
				'search_item' =>'Search contests',
				'not_found' => 'No contests found'
			),
			'has_archive' => 'contests'
		));
		
		register_post_type("problem",array(
			'public' => true,
			'labels' => array(
				'name' => 'Problems',
				'singular_name' => 'Problem',
				'search_items' => 'Search problems',
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
		p2p_register_connection_type(array(
			'from' => 'contest',
			'to' => 'problem',
			'reciprocal' => true,
			'prevent_duplicates'=> false,
			'title' => array('from'=>'Include Prolems','to'=>'Included Contest')
		));
		p2p_register_connection_type(array(
			'from' => 'problem',
			'to' => 'solution',
			'reciprocal' => false,
			'prevent_duplicates'=>false,
			'title' => array('from' => 'Submitted solutions','to'=>'To be submitted')
		));
	}
}
add_action('plugins_loaded', array('OJ','init'));



