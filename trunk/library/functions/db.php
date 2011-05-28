<?php
function oj_save_problem_metas($post_id,$post){
	$post->input=$_POST['input'];
	$post->output=$_POST['output'];
	$post->sample_input=$_POST['sample_input'];
	$post->sample_output=$_POST['sample_output'];
	$post->test_input=$_POST['test_input'];
	$post->test_output=$_POST['test_output'];
	$post->hint=$_POST['hint'];
	
	$post->time_limit=$_POST['time_limit'];
	$post->memory_limit=$_POST['memory_limit'];
	$post->special_judge=$_POST['special_judge'];
	$post->source=$_POST['source'];
	
	oj_update_problem_metas($post_id,array(
		'input'=>$post->input,
		'output'=>$post->output,
		'time_limit'=>$post->time_limit,
		'memory_limit'=>$post->memory_limit,
		'sample_input'=>$post->sample_input,
		'sample_output'=>$post->sample_output,
		'test_input'=>$post->test_input,
		'test_output'=>$post->test_output,
		'hint'=>$post->hint,
		'special_judge'=>$post->special_judge,
		'source'=>$post->source
	));
}
function oj_save_contest_metas($post,$post_id){
	
}
function oj_update_problem_metas($post_id,$data){
	update_post_meta($post_id, 'input', $data['input']);
	update_post_meta($post_id, 'output',$data['output']);
	unset($data['input']);
	unset($data['output']);
	update_post_meta($post_id, 'info',$data);
}
function oj_fill_problem_metas($post){
	if(!$post) return false;
	if($post->time_limit) return $post;
	$input=get_post_meta($post->ID, "input",true);
	$output=get_post_meta($post->ID, "output",true);
	$info=get_post_meta($post->ID, "info",true);
	$post->input = $input;
	$post->output = $output;
	$post->time_limit = $info['time_limit'];
	$post->memory_limit = $info['memory_limit'];
	$post->sample_input = $info['sample_input'];
	$post->sample_output = $info['sample_output'];
	$post->test_input = $info['test_input'];
	$post->test_output = $info['test_output'];
	$post->hint = $info['hint'];
	$post->special_judge = $info['special_judge'];
	$post->source = $info['source'];
	return $post;
}
?>