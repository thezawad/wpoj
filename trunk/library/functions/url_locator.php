<?php 
/*
 * wp-loaded hook callback
 */
function oj_get_top_page(){
	return array(
		'problem'=>array(
			'url'=>'<a href="/?oj=problems">Problems</a>',
			'url_raw' => '/?oj=problems',
			'label' => 'Problems'
		),
		'statusl' =>array(
			'url' => '<a href="/?oj=statusl">Status</a>',
			'url_raw' => '/?oj=statusl',
			'label' =>'Status',
		),
		'statusd' =>array(
			'url' => '<a href="/?oj=statusd">Status Detail</a>',
			'url_raw' => '/?oj=statusd',
			'label' =>'Status Detail',
		),
		'submitpage' =>array(
			'url' => '<a href="/?oj=submitpage">Submit Page</a>',
			'url_raw' => '/?oj=submitpage',
			'label' =>'Submit Page',
		),
		'addsolution' => array(
			'url_raw' => '/?oj=addsolution'
		)
	);
}
function oj_maybe_redirect_url(){
	global $oj,$oj_bread_trail;
	$oj_bread_trail=array();
	$page=$_GET['oj'];
	if(empty($page)){return ;}
	switch ($page){
		case 'statusl':
			$oj->context='status';
			$oj_bread_trail['trail_end']='Status';
			locate_template('oj-status-list.php',true);
			exit(0);break;
		case 'statusd':
			if($_GET['title']){
				$oj->context='problem';
				$oj_bread_trail[]=$oj->page['problem']['url'];
				$oj_bread_trail[]='<a href="/?problem='.$_GET['title'].'">'.$_GET['title'].'</a>';
				$oj_bread_trail['trail_end']='Problem Status';
			}else{
				$oj->context='status';
				$oj_bread_trail[]=$oj->page['status']['url'];
				$oj_bread_trail['trail_end']='Status Detail';
			}
			locate_template('oj-status-detail.php',true);
			exit(0);break;		
		case 'problems':
			$oj->context='problem';
			$oj_bread_trail['trail_end']='Problem';
			locate_template('oj-problems.php',true);
			exit(0);break;
		case 'submitpage':
			$oj->context='problem';
			$oj_bread_trail[]=$oj->page['problem']['url'];
			$oj_bread_trail[]='<a href="/?problem='.$_GET['title'].'">'.$_GET['title'].'</a>';
			$oj_bread_trail['trail_end']='Submit Solution';
			locate_template('oj-submitpage.php',true);
			exit(0);break;
		case 'addsolution':
			$oj->context='home';
			require_once (dirname(dirname(dirname(__FILE__))).'/addsolution.php');
			exit(0);break;
	}
}
?>