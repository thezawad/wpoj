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
			'contest-clarication'=>array(
				'label' => 'Clarication',
			),
			'contest-problems'=>array(
				'label' => 'Problems',
			),
			'contest-standing'=>array(
				'label' => 'Standing',
			),
			'contest-statusl'=>array(
				'label' => 'Status',
			),
			'contest-statistics'=>array(
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
			locate_template('oj-contest.php',true);
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
			$oj->context='statusl';
			$oj->page=$page;
			$oj_bread_trail['trail_end']=$oju->label($page);
			get_header();
			locate_template('oj-statusl.php',true);
			exit(0);break;
		case 'statusd':
			$oj->context='problems';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('problems');
			$oj_bread_trail[]='<a href="/?post_type=problem&p='.$_GET['pid'].'">Problem-'.$_GET['pid'].'</a>';
			$oj_bread_trail['trail_end']='Problem Status';
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
			$oj->context='problems';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link("problems");
			$oj_bread_trail['trail_end']='Problem-'.$_GET['pid'];
			locate_template('oj-problem.php',true);
			exit(0);break;
		case 'submitpage':
			$oj->context='problems';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('problems');;
			$oj_bread_trail[]='<a href="/?post_type=problem&p='.$_GET['pid'].'">Problem-'.$_GET['pid'].'</a>';
			$oj_bread_trail['trail_end']='Submit Solution';
			locate_template('oj-submitpage.php',true);
			exit(0);break;
		case 'addsolution':
			global $userdata,$oju;
			$oj->context='home';
			$oj->page=$page;
			require_once (OJ_HOME.'/addsolution.php');
			if(isset($_POST['cpid'])){
				header('Location:'.$oju->url('contest-statusl')."&user_id={$userdata->ID}");
			}else{
				header('Location:'.$oju->url('statusl')."&user_id={$userdata->ID}");
			}
			exit(0);break;
		case 'showsource':
			$oj->context='statusl';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('statusl');
			$oj_bread_trail['trail_end']='solution-'.$_GET['sid'].'-source';
			get_header();
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
		case 'contest-clarication':
			$oj->context='contests';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('contests');
			$oj_bread_trail[]=$_GET['ctitle'];
			$oj_bread_trail['trail_end']=$oju->label("contest-clarication");
			locate_template('oj-contest-clarication.php',true);
			exit(0);break;
		case 'contest-problems':
			$oj->context='contests';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('contests');
			$oj_bread_trail[]=$_GET['ctitle'];
			$oj_bread_trail['trail_end']=$oju->label("contest-problems");
			locate_template('oj-contest-problems.php',true);
			exit(0);break;
		case 'contest-problem':
			$wp_query->query_vars=array('p'=>$_GET['pid'],'post_type'=>'problem');
			$wp_query->query($wp_query->query_vars);
			$oj->context='contests';
			$oj->page='contest-problems';
			$oj_bread_trail[]=$oju->link("contests");
			$oj_bread_trail[]=$_GET['ctitle'];
			$oj_bread_trail[]=$oju->link("contest-problems");
			$oj_bread_trail['trail_end']='Problem-'.$_GET['pid'];
			locate_template('oj-contest-problem.php',true);
			exit(0);break;
		case 'contest-statusl':
			$oj->context='contests';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('contests');
			$oj_bread_trail[]=$_GET['ctitle'];
			$oj_bread_trail['trail_end']=$oju->label("contest-statusl");
			locate_template('oj-contest-statusl.php',true);
			exit(0);break;
		case 'contest-statusd':
			$oj->context='problems';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('problems');
			$oj_bread_trail[]='<a href="/?post_type=problem&p='.$_GET['pid'].'">Problem-'.$_GET['pid'].'</a>';
			$oj_bread_trail['trail_end']='Problem Status';
			locate_template('oj-contest-statusd.php',true);
			exit(0);break;
		case 'contest-standing':
			$oj->context='contests';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('contests');
			$oj_bread_trail[]=$_GET['ctitle'];
			$oj_bread_trail['trail_end']=$oju->label("contest-standing");
			locate_template('oj-contest-standing.php',true);
			exit(0);break;
		case 'contest-statistics':
			$oj->context='contests';
			$oj->page=$page;
			$oj_bread_trail[]=$oju->link('contests');
			$oj_bread_trail[]=$_GET['ctitle'];
			$oj_bread_trail['trail_end']=$oju->label("contest-statistics");
			locate_template('oj-contest-statistics.php',true);
			exit(0);break;
		case 'contest-showsource':
			$oj->page='contest-statusl';
			$oj_bread_trail[]=$oju->link('contests');
			$oj_bread_trail[]=$_GET['ctitle'];
			$oj_bread_trail[]=$oju->link('contest-statusl');
			$oj_bread_trail['trail_end']='solution-'.$_GET['sid'].'-source';
			locate_template('oj-contest-showsource.php',true);
			exit(0);break;
		case 'contest-submitpage':
			$oj->context='contests';
			$oj->page='contest-problems';
			$oj_bread_trail[]=$oju->link("contests");
			$oj_bread_trail[]=$_GET['ctitle'];
			$oj_bread_trail[]=$oju->link("contest-problems");
			$oj_bread_trail[]='<a href="'.$oju->url('problem').'">Problem-'.$_GET['pid'].'</a>';
			$oj_bread_trail['trail_end']='Submit Solution';
			locate_template('oj-contest-submitpage.php',true);
			exit(0);break;
	}
}
?>