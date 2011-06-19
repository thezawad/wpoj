<?php
function oj_end_with_status($status){
	get_header();
	echo '<div class="hentry">'.$status.'</div>';
	get_footer();
	exit(0);
}
function oj_echo_language_select($langmask,$default,$all=false){
	echo '<select id="language" name="language">';
	$lang=(~((int)$langmask))&127;
	if($all){
		$selected="";
		if($default==-1){
			$selected=' selected="selected"';
		}
		echo'	<option value="-1"'.$selected.'">ALL</option>'; 
	}
	if(($lang&1)>0) 	echo"	<option value=0 ".( $default==0?"selected":"").">C</option>";
	if(($lang&2)>0) 	echo"	<option value=1 ".( $default==1?"selected":"").">C++</option>";
	if(($lang&4)>0) 	echo"	<option value=2 ".( $default==2?"selected":"").">Pascal</option>";
	if(($lang&8)>0) 	echo"	<option value=3 ".( $default==3?"selected":"").">Java</option>";
	if(($lang&16)>0) 	echo"	<option value=4 ".( $default==4?"selected":"").">Ruby</option>";
	if(($lang&32)>0) 	echo"	<option value=5 ".( $default==5?"selected":"").">Bash</option>";
	if(($lang&64)>0) 	echo"	<option value=6 ".( $default==6?"selected":"").">Python</option>";
	echo '</select>';
}
function oj_echo_filter_select($default){
	$status=oj_get_compile_status('full');
	echo '<select id="result" name="result">';
		
		if($default==-1){
			$selected=' selected="selected"';
		}else{
			$selected="";
		}
		echo'<option value="-1"'.$selected.'">ALL</option>'; 
		for($i=4;$i<12;$i++){
			if($default==$i){
				$selected=' selected="selected"';
			}else{
				$selected="";
			}
			echo '<option'.$selected.' value="'.$i.'">'.$status[$i].'</option>';
		}
	echo '</select>';
}
function oj_get_compile_status($key="all"){
	$compile_status=array(
		'full'=>array('Pending','Pending Rejudging','Compiling','Running & Judging','Accepted','Presentation Error','Wrong Answer','Time Limit Exceed','Memory Limit Exceed','Output Limit Exceed','Runtime Error','Compile Error','Compile OK'),
		'short'=>array('PD','PR','CI','RJ','AC','PE','WA','TLE','MLE','OLE','RE','CE','CO'),
		'class'=>array('PD','PR','CI','RJ','AC','PE','WA','TLE','MLE','OLE','RE','CE','CO')
	);
	if($key=='all'){
		return $compile_status;
	}else{
		return $compile_status[$key];
	}
}
?>