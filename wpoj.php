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
	var $prefix='oj_';
	var $page;
	var $current_page;
	var $context="home";
	var $objects;
	var $iscontest=false;
	var $languages=array('C'=>0,'C++'=>1,'Pascal'=>2,'Java'=>3,'Ruby'=>4,'Bash'=>5,'Python'=>6);
	function init(){
		$this->init_consts();
		$this->include_functions();
		$this->include_classes();
		register_activation_hook(__FILE__,'oj_active');
		add_action('plugins_loaded', array($this,'_init'));
	}
	function init_consts(){
		define('OJ_HOME', dirname(__FILE__));
		define('OJ_URL', WP_PLUGIN_URL.'/wpoj');
		define('OJ_LIBRARY',OJ_HOME.'/library');
		define('OJ_THEMES',OJ_HOME.'/themes');
		define('OJ_FUNCTIONS',OJ_LIBRARY.'/functions');
		define('OJ_CLASSES',OJ_LIBRARY.'/class');
	}
	function include_functions(){
		require_once(OJ_FUNCTIONS.'/tools.php');
		require_once(OJ_FUNCTIONS.'/init.php');
		require_once(OJ_FUNCTIONS.'/url_locator.php');
		require_once(OJ_FUNCTIONS.'/core.php');
		require_once(OJ_FUNCTIONS.'/part_loader.php');
		require_once(OJ_FUNCTIONS.'/templates.php');
		require_once(OJ_FUNCTIONS.'/db.php');
		if(is_admin()){
			require_once(OJ_FUNCTIONS.'/fps.php');
		}
	}
	function include_classes(){
		require_once(OJ_CLASSES.'/objects.php');
		require_once(OJ_CLASSES.'/URL.php');
		if(is_admin()){
			require_once (OJ_CLASSES.'/meta_boxs.php');
		}
	}
	function _init(){
		register_theme_directory( OJ_THEMES);
		add_action('init',array($this,'register_core_vars'));
		add_action('init',array($this,'register_core_components'));
		add_action('wp_loaded','oj_maybe_redirect_url');
		add_action('user_register','oj_add_user');
		add_action('deleted_user','oj_delete_user');
	}
	function register_core_components(){
		oj_register_post_types();
		oj_register_page_types();
		if(is_admin()){
			add_action("admin_menu",'oj_register_admin_menu');
		}
	}
	function register_core_vars(){
		global $oju,$ojp;
		$oju=new OJ_URL();
		$ojp=array();
		if(isset($_GET['cid'])){ $this->iscontest=true; }
		$this->objects=oj_get_objects();
	}
	
}
global $oj;
$oj=new OJ();
$oj->init();