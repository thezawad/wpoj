<?php
function oj_save_object_metas($post_id,$object,$meta_to_save){
	global $wpdb;
	$table=$wpdb->prefix . $object->post_type . '_meta';
	$data = apply_filters('wp_insert_post_data', array('input','output','sample_input','sample_output','hint','source'), $meta_to_save);
	$exist=$wpdb->query("select ID from `{$table}` where post_id={$post_id}");
	foreach ($meta_to_save as $key=>$value) {
		if(is_string($value)){$meta_to_save[$key]="'".$value."'";}
	}
	if($exist){
		$update_values=array();;
		foreach ($meta_to_save as $key => $value){
			$update_values[] = ' `'.$key.'` = '.mysql_real_escape_string($value);
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
	foreach ($object_meta as $key=>$value){
		$object->$key=$value;
	}
	return $object;
}
?>