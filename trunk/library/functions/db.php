<?php
add_filter('posts_join_paged','oj_query_join',10,1);
add_filter('posts_fields','oj_query_fields',10,1);
add_filter('posts_orderby','oj_query_orderby');
function oj_query_join($join){
	global $oj_context,$wpdb;
	switch ($oj_context){
		case 'problems':
			$join=' LEFT JOIN '.$wpdb->prefix.'problem_meta as meta ON meta.post_id='.$wpdb->prefix.'posts.ID ';
			break;
	}
	return $join;
}
function oj_query_fields($fields){
	global $oj_context,$wpdb;
	switch ($oj_context){
		case 'problems':
			$fields = $fields . ', meta.source , meta.accepted , meta.submit';
			break;
	}
	return $fields;
}
function oj_query_orderby($orderby){
	global $oj_context,$wpdb;
	switch ($oj_context){
		case 'problems':
			$new_orderby=trim($_GET['orderby']);
			$allowed_keys=array('source','accedpted','submit');
			if($new_orderby && in_array($new_orderby, $allowed_keys)) return $new_orderby;
			break;
	}
	return $orderby;
}
function oj_save_object_metas($post_id,$object,$meta_to_save){
	global $wpdb;
	$table=$wpdb->prefix . $object->post_type . '_meta';
	$meta_to_save['post_id']=$post_id;
	$data = apply_filters('wp_insert_post_data', array('input','output','sample_input','sample_output','hint','source'), $meta_to_save);
	$exist=$wpdb->query("select ID from `{$table}` where post_id={$post_id}");
	foreach ($meta_to_save as $key=>$value) {
		if(is_string($value)){$meta_to_save[$key]="'".$value."'";}
	}
	if($exist){
		$update_values=array();;
		foreach ($meta_to_save as $key => $value){
			$update_values[] = ' `'.$key.'` = '.$value;
		}
		$update_values=implode(',',$update_values);
		
		$sql = "UPDATE `{$table}` SET {$update_values} WHERE `post_id` ={$post_id}";
	}else{
		$keys= implode('`,`',array_keys($meta_to_save));
		$values= implode(',', $meta_to_save);
		$sql = "INSERT INTO `{$table}` (`{$keys}`) VALUES({$values});";
	}
	//echo $sql;
	$wpdb->query($sql);
}
function oj_delete_object_metas($post_id){
	global $wpdb;
	$object=get_post($post_id);
	$table=$wpdb->prefix . $object->post_type . '_meta';
	$sql= "DELETE FROM `{$table}` WHERE post_id={$post_id}";
	$wpdb->query($sql);
}
function oj_fill_object_metas($object){
	global $wpdb;
	if(!$object) return false;
	$table=$wpdb->prefix . $object->post_type . '_meta';
	$sql="SELECT * FROM `{$table}` WHERE post_id = {$object->ID}";
	$object_meta=$wpdb->get_row($sql);
	if(!$object_meta) return $object;
	unset($object_meta->ID);
	unset($object_meta->post_id);
	foreach ($object_meta as $key=>$value){
		$object->$key=$value;
	}
	return $object;
}
?>