<?php get_header();?>
<?php 
global $wpdb,$oj;
$sid=intval($_GET['sid']);
$sql="SELECT error FROM {$oj->prefix}compileinfo WHERE solution_id={$sid}";
$compileinfo=$wpdb->get_row($sql);
?>
<pre>
<?php echo $compileinfo->error;?>
</pre>
<?php get_footer()?>