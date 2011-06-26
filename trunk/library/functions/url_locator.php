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
function oj_maybe_redirect_url_wp($wp){
	if(isset($wp->query_vars['problem_tag'])){
		oj_loadtemplates_cat('problem_tag',$wp->query_vars['problem_tag']);
	}elseif(isset($wp->query_vars['problem_cat'])){
		oj_loadtemplates_cat('problem_cat',$wp->query_vars['problem_cat']);
	}elseif(isset($wp->query_vars['fps_cat'])){
		oj_loadtemplates_cat('fps_cat',$wp->query_vars['fps_cat']);
	}
}
function oj_loadtemplates_cat($tax,$cat_name){
	global $wpdb,$oj,$wp_query;
	$wp_query->is_archive=true;
	$oj->context='problems';
	$tax_query = new WP_Tax_Query( array(array('taxonomy'=>$tax,'terms'=>$cat_name,'field'=>'slug')) );
	$clauses = $tax_query->get_sql( $wpdb->posts, 'ID' );
	$sql="SELECT * FROM {$wpdb->prefix}posts {$clauses['join']} WHERE post_type='problem' {$clauses['where']}";
	$problems=$wpdb->get_results($sql);
	oj_load_template('oj-'.$tax.'.php',array('ojproblems'=>$problems));
	exit();
}
?>