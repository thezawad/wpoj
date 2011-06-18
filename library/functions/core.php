<?php 
function oj_wp( $query_vars = '' ) {
	global $wp, $wp_query, $wp_the_query;
	$wp->main( $query_vars );

	$wp->init();
	$wp->query_vars=$query_vars;
	$wp->send_headers();
	$wp->query_posts();
	$wp->handle_404();
	$wp->register_globals();
	do_action_ref_array('wp', array(&$this));

	if ( !isset($wp_the_query) )
		$wp_the_query = $wp_query;
}
?>