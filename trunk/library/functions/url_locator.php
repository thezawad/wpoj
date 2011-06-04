<?php 
/*
 * wp-loaded hook callback
 */
function oj_maybe_redirect_url(){
	global $top_page;
	$page=$_GET['oj'];
	if(empty($page)){
		$top_page ='home';
		return ;
	}
	switch ($page){
		case 'statusl':
			$top_page='status';
			locate_template('oj-status-list.php',true);
			break;
		case 'statusd':
			locate_template('oj-status-detail.php',true);
			break;
		case 'problems':
			$top_page='problem';
			locate_template('oj-problems.php',true);
			break;
	}
	exit(0);
}
?>