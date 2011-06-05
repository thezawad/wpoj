<?php 
/*
 * wp-loaded hook callback
 */
function oj_maybe_redirect_url(){
	global $top_page,$page_urls,$oj_bread_trail;
	$oj_bread_trail=array();
	$page_urls=array(
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
	$page=$_GET['oj'];
	if(empty($page)){
		$top_page ='home';
		return ;
	}
	switch ($page){
		case 'statusl':
			$top_page='status';
			$oj_bread_trail['trail_end']='Status';
			locate_template('oj-status-list.php',true);
			exit(0);break;
		case 'statusd':
			if($_GET['title']){
				$top_page='problem';
				$oj_bread_trail[]=$page_urls['problem']['url'];
				$oj_bread_trail[]='<a href="/?problem='.$_GET['title'].'">'.$_GET['title'].'</a>';
				$oj_bread_trail['trail_end']='Problem Status';
			}else{
				$top_page='status';
				$oj_bread_trail[]=$page_urls['status']['url'];
				$oj_bread_trail['trail_end']='Status Detail';
			}
			locate_template('oj-status-detail.php',true);
			exit(0);break;		
		case 'problems':
			$top_page='problem';
			$oj_bread_trail['trail_end']='Problem';
			locate_template('oj-problems.php',true);
			exit(0);break;
		case 'submitpage':
			$top_page='problem';
			$oj_bread_trail[]=$page_urls['problem']['url'];
			$oj_bread_trail[]='<a href="/?problem='.$_GET['title'].'">'.$_GET['title'].'</a>';
			$oj_bread_trail['trail_end']='Submit Solution';
			locate_template('oj-submitpage.php',true);
			exit(0);break;
		case 'addsolution':
			$top_page='home';
			require_once (dirname(dirname(dirname(__FILE__))).'/addsolution.php');
			exit(0);break;
	}
}
?>