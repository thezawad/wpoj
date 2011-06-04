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
			exit(0);
			break;
		case 'statusd':
			locate_template('oj-status-detail.php',true);
			exit(0);
			break;
		case 'problems':
			$top_page='problem';
			locate_template('oj-problems.php',true);
			exit(0);
			break;
		case 'solution-submit':
			echo "hfs";
			break;
	}
}
?>