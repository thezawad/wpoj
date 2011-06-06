<?php
function oj_end_with_status($status){
	get_header();
	echo $status;
	get_footer();
	exit(0);
}
?>