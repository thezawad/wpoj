<?php
	global $userdata;
	if(empty($userdata->user_login)){
		oj_end_with_status('请登录后再提交，谢谢！');
	}
	$problem_id=		$_POST['pid'];
	$contest_id=		$_POST['cid'];
	// 没有用，虽然表面上似乎是修改，但实际上是重新提交
	$solution_id=		$_POST['sid'];
	$cp_id=				$_POST['cpid'];
	$source=			$_POST['source'];
	$language=			$_POST['language'];
	
	$error_message[1]="Source Code Too Short!";
	$error_message[2]="Source Code Too Long!";
	$error_message[3]="You should not submit more than twice in 10 seconds.....";
	

	$status=oj_submit_solution($problem_id,$source,$language,true,$contest_id,$cp_id,$solution_id);
	if($status){
		oj_end_with_status($error_message[$status]);
	}
	
?>