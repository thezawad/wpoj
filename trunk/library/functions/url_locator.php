<?php 
/*
 * wp-loaded hook callback
 */
function oj_get_menu_main(){
	return array('home','blogs','ranklist','contests','problems','statusl','faqs');
}
function oj_get_menu_contest(){
	return array('contest-clarication','contest-problems','contest-standing','contest-statusl','contest-statistics');
}
function oj_maybe_redirect_url(){
	global $oj,$oju,$oj_bread_trail,$wp_query;
	$oj_bread_trail=array();
	$page=$_GET['oj'];
	if(empty($page)){return ;}
	$oj->page=$page;
	do_action('oj-'.$page);
	do_action('oj-loadtemplates',$page);
}
?>