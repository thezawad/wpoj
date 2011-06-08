<?php 
/*
 * wp-loaded hook callback
 */
function oj_get_menu_main(){
	return array('home','blogs','contests','problems','statusl','faqs');
}
function oj_get_pages(){
	return array(
		'home' =>array(
			'label' => 'Home',
		),
		'blogs' => array(
			'label' => 'Blogs',
		),
		'contests' => array(
			'label' => 'Contests'
		),
		'problems'=>array(
			'label' => 'Problems'
		),
		'statusl' =>array(
			'label' =>'Status',
		),
		'faqs'=>array(
			'label'=>'F.A.Qs',
		),
		'submitpage' => array(),
		'addsolution' => array(),
		'showsource' => array(),
		'compileinfo'=> array(),
	);
}
function oj_maybe_redirect_url(){
	global $oj,$oju,$oj_bread_trail,$wp_query;
	$oj_bread_trail=array();
	$page=$_GET['oj'];
	if(empty($page)){return ;}
	switch ($page){
		case 'contests':
			$subpage=$_GET['ct'];
			if(empty($subpage)){
				$oj->context='contests';
				$oj_bread_trail['trail_end']=$oju->label($page);
				locate_template('oj-contests.php',true);
				exit(0);break;
			}else{
				switch($subpage){
				
				}
			}
		case 'blogs':
			$oj->context='blogs';
			wp();
			$wp_query->is_home=false;
			$oj_bread_trail['trail_end']=$oju->label($page);
			locate_template('oj-blogs.php',true);
			exit(0);break;
		case 'statusl':
			if($_GET['title']){
				$oj->context='problems';
				$oj_bread_trail[]=$oju->link('problems');
				$oj_bread_trail[]='<a href="/?post_type=problem&p='.$_GET['pid'].'">Problem-'.$_GET['pid'].'</a>';
				$oj_bread_trail['trail_end']='Problem Status';
			}else{
				$oj->context='statusl';
				$oj_bread_trail['trail_end']=$oju->label($page);		
			}
			locate_template('oj-status-list.php',true);
			exit(0);break;
		case 'problems':
			$oj->context='problems';
			$oj_bread_trail['trail_end']=$oju->label($page);
			locate_template('oj-problems.php',true);
			exit(0);break;
		case 'submitpage':
			$oj->context='problems';
			$oj_bread_trail[]=$oju->link('problems');;
			$oj_bread_trail[]='<a href="/?post_type=problem&p='.$_GET['pid'].'">Problem-'.$_GET['pid'].'</a>';
			$oj_bread_trail['trail_end']='Submit Solution';
			locate_template('oj-submitpage.php',true);
			exit(0);break;
		case 'addsolution':
			global $userdata;
			$oj->context='home';
			require_once (dirname(dirname(dirname(__FILE__))).'/addsolution.php');
			header("Location:/?oj=statusl&user_id=".$userdata->ID);
			exit(0);break;
		case 'showsource':
			$oj->context='statusl';
			$oj_bread_trail[]=$oju->link('statusl');
			$oj_bread_trail['trail_end']='solution-'.$_GET['sid'].'-source';
			if(empty($_GET['sid'])){
				oj_end_with_status('Solution ID missing!');
			}
			locate_template('oj-showsource.php',true);
			exit(0);break;
		case 'compileinfo':
			$oj->context='statusl';
			$oj_bread_trail[]=$oju->link('statusl');
			$oj_bread_trail['trail_end']='solution-'.$_GET['sid'].'-compileinfo';
			if(empty($_GET['sid'])){
				oj_end_with_status('Solution ID missing!');
			}
			locate_template('oj-compileinfo.php',true);
			exit(0);break;
		case 'faqs':
			$oj->context='faqs';
			$oj_bread_trail['trail_end']=$oju->label($page);;
			locate_template('oj-faqs.php',true);
			exit(0);break;
	}
}
?>