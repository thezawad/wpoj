<?php
function oj_end_with_status($status){
	get_header();
	echo $status;
	get_footer();
	exit(0);
}
function oj_echo_language_select($langmask,$default){
	echo '<select id="language" name="language">';
	$lang=(~((int)$langmask))&127;
	
	if(($lang&1)>0) 	echo"	<option value=0 ".( $default==0?"selected":"").">C</option>";
	if(($lang&2)>0) 	echo"	<option value=1 ".( $default==1?"selected":"").">C++</option>";
	if(($lang&4)>0) 	echo"	<option value=2 ".( $default==2?"selected":"").">Pascal</option>";
	if(($lang&8)>0) 	echo"	<option value=3 ".( $default==3?"selected":"").">Java</option>";
	if(($lang&16)>0) 	echo"	<option value=4 ".( $default==4?"selected":"").">Ruby</option>";
	if(($lang&32)>0) 	echo"	<option value=5 ".( $default==5?"selected":"").">Bash</option>";
	if(($lang&64)>0) 	echo"	<option value=6 ".( $default==6?"selected":"").">Python</option>";
	echo '</select>';
}
?>