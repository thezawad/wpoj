<?php
	$problem_id=		$_POST['pid'];
	$contest_id=		$_POST['cid'];
	$solution_id=		$_POST['sid'];
	$source=			$_POST['source'];
	$language=			$_POST['language'];

	$error_message[1]="Source Code Too Short!";
	$error_message[2]="Source Code Too Long!";
	$error_message[3]="You should not submit more than twice in 10 seconds.....";
	$status=oj_submit_solution($problem_id,$source,$language);
	if($status){
		oj_end_with_status($error_message[$status]);
	}
?>