<?php
function oj_end_with_status($status){
	require_once('oj-header.php');
	echo $status;
	require_once('oj-footer.php');
	exit(0);
}
	global $wpdb,$userdata;
	$problem_id=		$_POST['pid'];
	$contest_id=		$_POST['cid'];
	$solution_id=		$_POST['sid'];
	$solution_source=	$_POST['source'];
	$language=			$_POST['language'];
	
	if ($language>6 || $language<0) $language=0;
	$language=strval($language);
	$ip=$_SERVER['REMOTE_ADDR'];
	$len=strlen($solution_source);
	if ($len<2){ oj_end_with_status("Source Code Too Short!");}
	if ($len>65536){ oj_end_with_status("Source Code Too Long!");}
	$sql="SELECT `in_date` from `solution` where `user_id`='$userdata->ID' and in_date>now()-10 order by `in_date` desc limit 1";
	echo $sql;echo "<br>";
	if( $wpdb->query($sql)){ oj_end_with_status('You should not submit more than twice in 10 seconds.....');}
	
	$sql="INSERT INTO {$wpdb->prefix}solution(problem_id,user_id,in_date,language,ip,code_length) VALUES('$problem_id','$userdata->ID',NOW(),'$language','$ip','$len')";
	echo $sql;echo "<br>";
	$wpdb->query($sql);	
	$insert_id=mysql_insert_id();
	$sql="INSERT INTO `{$wpdb->prefix}solution_source`(`solution_id`,`source`)VALUES('$insert_id','$solution_source')";
	echo $sql;echo "<br>";
	$wpdb->query($sql);
	header("Location: /?oj=statusl.php&user_id=".$userdata->ID);
?>