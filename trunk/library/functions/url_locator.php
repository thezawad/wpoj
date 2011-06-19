<?php 
/*
 * wp-loaded hook callback
 */
function oj_get_menu_main(){
	return array('home','blogs','ranklist','contests','problems','statusl','faqs');
}
function oj_get_menu_contest(){
	return array('contests-clarication','contests-problems','contests-standing','contests-status','contests-statistics');
}
function oj_get_pages(){
	return array(
		'home' =>array(
			'label' => 'Home',
		),
		'blogs' => array(
			'label' => 'Blogs',
		),
		'ranklist'=>array(
			'label' => 'Ranklist'
		),
		'contests' => array(
			'label' => 'Contests'
		),
			'contests-clarication'=>array(
				'label' => 'Clarication',
			),
			'contests-problems'=>array(
				'label' => 'Problems',
			),
			'contests-standing'=>array(
				'label' => 'Standing',
			),
			'contests-status'=>array(
				'label' => 'Status',
			),
			'contests-statistics'=>array(
				'label' => 'Statistics',
			),
			
		'problems'=>array(
			'label' => 'Problems'
		),
		'problem'=>array(
			'label' => 'Problem'
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
			$oj->context='contests';
			$oj->page=$page;
			$oj_bread_trail['trail_end']=$oju->label($page);
			locate_template('oj-contests.php',true);
			exit(0);break;
		case 'blogs':
			$oj->context='blogs';
			$oj->page=$page;
			wp();
			$wp_query->is_home=false;
			$oj_bread_trail['trail_end']=$oju->label($page);
			locate_template('oj-blogs.php',true);
			exit(0);break;
		case 'ranklist':
			$oj->context='ranklist';
			$oj->page=$page;
			$oj_bread_trail['trail_end']=$oju->label($page);
			locate_template('oj-ranklist.php',true);
			exit(0);break;
			case 'user':
				$oj->context='ranklist';
				$oj->page=$page;
				$oj_bread_trail[]=$oju->link('ranklislt');
				$oj_bread_trail['trail_end']=$oju->label($page);
				locate_template('oj-user.php',true);
				exit(0);break;
		case 'statusl':
			
			if(empty($_GET['cid'])){
				$oj->context='statusl';
				$oj->page=$page;
				$oj_bread_trail['trail_end']=$oju->label($page);
				get_header();	
			}else{
				$oj->context='statusl';
				$oj->page="contests-status";
				$oj_bread_trail['trail_end']=$oju->label($page);
				get_header('contest');	
			}
			
			locate_template('oj-statusl.php',true);
			exit(0);break;
		case 'statusd':
			if(empty($_GET['cid'])){
				$oj->context='problems';
				$oj->page=$page;
				$oj_bread_trail[]=$oju->link('problems');
				$oj_bread_trail[]='<a href="/?post_type=problem&p='.$_GET['pid'].'">Problem-'.$_GET['pid'].'</a>';
				$oj_bread_trail['trail_end']='Problem Status';
				get_header();
			}else{
				$oj->context='problems';
				$oj->page=$page;
				$oj_bread_trail[]=$oju->link('problems');
				$oj_bread_trail[]='<a href="/?post_type=problem&p='.$_GET['pid'].'">Problem-'.$_GET['pid'].'</a>';
				$oj_bread_trail['trail_end']='Problem Status';
				get_header('contest');
			}
			locate_template('oj-statusd.php',true);
			exit(0);break;
		case 'problems':
			$oj->context='problems';
			$oj->page=$page;
			$oj_bread_trail['trail_end']=$oju->label($page);
			locate_template('oj-problems.php',true);
			exit(0);break;
		case 'problem':
			$wp_query->query_vars=array('p'=>$_GET['pid'],'post_type'=>'problem');
			$wp_query->query($wp_query->query_vars);
			
			if(empty($_GET['cid'])){
				$oj->context='problems';
				$oj->page=$page;
				$oj_bread_trail[]=$oju->link("problems");
				$oj_bread_trail['trail_end']='Problem-'.$_GET['pid'];
				get_header();
			}else{
				$oj->context='contests';
				$oj->page='contests-problems';
				$oj_bread_trail[]=$oju->link("contests");
				$oj_bread_trail[]=$_GET['ctitle'];
				$oj_bread_trail[]=$oju->link("contests-problems");
				$oj_bread_trail['trail_end']='Problem-'.$_GET['pid'];
				get_header('contest');
			}
			
			locate_template('oj-problem.php',true);
			exit(0);break;
		case 'submitpage':
			if(empty($_GET['cid'])){
				$oj->context='problems';
				$oj->page=$page;
				$oj_bread_trail[]=$oju->link('problems');;
				$oj_bread_trail[]='<a href="/?post_type=problem&p='.$_GET['pid'].'">Problem-'.$_GET['pid'].'</a>';
				$oj_bread_trail['trail_end']='Submit Solution';
				get_header();
			}else{
				$oj->context='contests';
				$oj->page='contests-problems';
				$oj_bread_trail[]=$oju->link("contests");
				$oj_bread_trail[]=$_GET['ctitle'];
				$oj_bread_trail[]=$oju->link("contests-problems");
				$oj_bread_trail[]='<a href="'.$oju->url('problem').'">Problem-'.$_GET['pid'].'</a>';
				$oj_bread_trail['trail_end']='Submit Solution';
				get_header('contest');
			}
			
			locate_template('oj-submitpage.php',true);
			exit(0);break;
		case 'addsolution':
			global $userdata,$oju;
			$oj->context='home';
			$oj->page=$page;
			require_once (OJ_HOME.'/addsolution.php');
			if(isset($_POST['cpid'])){
				header('Location:'.$oju->url('contests-status')."&user_id={$userdata->ID}");
			}else{
				header('Location:'.$oju->url('statusl')."&user_id={$userdata->ID}");
			}
			exit(0);break;
		case 'showsource':
			$oj->context='statusl';
			if(empty($_GET['sid'])){
				oj_end_with_status('Solution ID missing!');
			}
			if(isset($_GET['cid'])){
				$oj->page='contests-status';
				$oj_bread_trail[]=$oju->link('contests');
				$oj_bread_trail[]=$_GET['ctitle'];
				$oj_bread_trail[]=$oju->link('contests-status');
				$oj_bread_trail['trail_end']='solution-'.$_GET['sid'].'-source';
				get_header('contest');
			}else{
				$oj->page=$page;
				$oj_bread_trail[]=$oju->link('statusl');
				$oj_bread_trail['trail_end']='solution-'.$_GET['sid'].'-source';
				get_header();
			}
			locate_template('oj-showsource.php',true);
			exit(0);break;
		case 'compileinfo':
			$oj->context='statusl';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('statusl');
			$oj_bread_trail['trail_end']='solution-'.$_GET['sid'].'-compileinfo';
			if(empty($_GET['sid'])){
				oj_end_with_status('Solution ID missing!');
			}
			locate_template('oj-compileinfo.php',true);
			exit(0);break;
		case 'faqs':
			$oj->context='faqs';
			$oj->page=$page;
			$oj_bread_trail['trail_end']=$oju->label($page);
			locate_template('oj-faqs.php',true);
			exit(0);break;
		case 'contests-clarication':
			$oj->context='contests';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('contests');
			$oj_bread_trail[]=$_GET['ctitle'];
			$oj_bread_trail['trail_end']=$oju->label("contests-clarication");
			locate_template('oj-contests-clarication.php',true);
			exit(0);break;
		case 'contests-problems':
			$oj->context='contests';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('contests');
			$oj_bread_trail[]=$_GET['ctitle'];
			$oj_bread_trail['trail_end']=$oju->label("contests-problems");
			locate_template('oj-contests-problems.php',true);
			exit(0);break;
		case 'contests-status':
			$oj->context='contests';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('contests');
			$oj_bread_trail[]=$_GET['ctitle'];
			$oj_bread_trail['trail_end']=$oju->label("contests-status");
			locate_template('oj-contests-status.php',true);
			exit(0);break;
		case 'contests-standing':
			$oj->context='contests';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('contests');
			$oj_bread_trail[]=$_GET['ctitle'];
			$oj_bread_trail['trail_end']=$oju->label("contests-standing");
			locate_template('oj-contests-standing.php',true);
			exit(0);break;
		case 'contests-statistics':
			$oj->context='contests';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('contests');
			$oj_bread_trail[]=$_GET['ctitle'];
			$oj_bread_trail['trail_end']=$oju->label("contests-statistics");
			locate_template('oj-contests-statistics.php',true);
			exit(0);break;
	}
}
?>