<?php
function oj_save_object_metas($post_id,$object){
	$object->input=$_POST['input'];
	$object->output=$_POST['output'];
	$object->sample_input=$_POST['sample_input'];
	$object->sample_output=$_POST['sample_output'];
	$object->test_input=$_POST['test_input'];
	$object->test_output=$_POST['test_output'];
	$object->hint=$_POST['hint'];
	
	$object->time_limit=$_POST['time_limit'];
	$object->memory_limit=$_POST['memory_limit'];
	$object->special_judge=$_POST['special_judge'];
	$object->source=$_POST['source'];
	
	oj_update_problem_metas($post_id,array(
		'input'=>$object->input,
		'output'=>$object->output,
		'time_limit'=>$object->time_limit,
		'memory_limit'=>$object->memory_limit,
		'sample_input'=>$object->sample_input,
		'sample_output'=>$object->sample_output,
		'test_input'=>$object->test_input,
		'test_output'=>$object->test_output,
		'hint'=>$object->hint,
		'special_judge'=>$object->special_judge,
		'source'=>$object->source
	));
}
function oj_save_contest_metas($object,$post_id){
	
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