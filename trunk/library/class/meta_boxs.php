<?php
function oj_save_metas($post_id,$object){
	if ( !wp_verify_nonce( $_POST["{$object->post_type}_meta_box_nonce"], "{$object->post_type}_meta_box_nonce" ) ) return $post_id;
	$object_metas=OJ_OBJECT::$fields[$object->post_type];

	foreach ($object_metas as $field){
		$key=$field['name'];
		switch ($field['type']){
			case 'datetime':
				$meta_value=intval($_POST[$field['yy']]).'-'.intval($_POST[$field['mm']]).'-'.intval($_POST[$field['dd']]).' '.intval($_POST[$field['hh']]).':'.intval($_POST[$field['mn']]).':00';
				break;
			case 'select':
				if(!is_array($_POST[$key])) $_POST[$key]=array($_POST[$key]); 
				$meta_value=implode(',',$_POST[$key]);
				break;
			default:
				$meta_value=$_POST[$key];
		}
		if ($key=="test_input" || $key=="test_output"){
			continue;
		}
		$meta_to_save[$key]=$meta_value;
	}
	oj_save_object_metas($post_id,$object,$meta_to_save);
}
class OJ_meta_box{
	static $_object_nonce=array(
		'problem' => true,
		'contest' => true
	);
	
	function init(){
		add_filter('mce_buttons',array(__CLASS__,'_add_code_button') );
		add_action('admin_print_styles', array(__CLASS__,'_print_box_styles'));
		add_action("add_meta_boxes", array(__CLASS__,"_register_meta_boxes"),10,2);
	}
	static function get_meta_box_args($post_type){
		$meta_box_args=array(
			'contest' =>array(
				array(
					'box' => array('id' =>'contest_meta_detail','title' => 'Contest Detail','callback' =>array(__CLASS__,'_meta_box_default'), 'context' => 'normal', 'priority' =>'default','callback_args' =>null),
					'fields' => array(
						'start_time'=> OJ_OBJECT::$fields['contest']['start_time'],
						'end_time' => OJ_OBJECT::$fields['contest']['end_time'],
						'private' => OJ_OBJECT::$fields['contest']['private'],
						'language'=> OJ_OBJECT::$fields['contest']['language'],
					)
				),
			),
			'problem' =>array(
				array(
					'box' => array('id'=>'problem_meta_input_output_hint','title'=>'Input & Output & Hint','callback' =>array(__CLASS__,'_meta_box_default'),'context'=>'normal','priority'=>'default','callback_args' =>null),
					'fields' =>array(
						'input' => OJ_OBJECT::$fields['problem']['input'],
						'output' => OJ_OBJECT::$fields['problem']['output'],
						'hint' => OJ_OBJECT::$fields['problem']['hint'],
					)
				),
				array(
					'box' => array('id'=>'problem_meta_sample','title'=>'Sample Data','callback' =>array(__CLASS__,'_meta_box_default'),'context'=>'normal','priority'=>'default','callback_args' =>null),
					'fields' =>array(
						'sample_input' => OJ_OBJECT::$fields['problem']['sample_input'],
						'sample_output' => OJ_OBJECT::$fields['problem']['sample_output'],
					)
				),
				array(
					'box' => array('id'=>'problem_meta_test','title'=>'Test Data','callback' =>array(__CLASS__,'_meta_box_default'),'context'=>'normal','priority'=>'default','callback_args' =>null),
					'fields' =>array(
						'test_input' => OJ_OBJECT::$fields['problem']['test_input'],
						'test_output' => OJ_OBJECT::$fields['problem']['test_output'],
					)
				),
				array(
					'box' => array('id' =>'problem_meta_detail','title' => 'Problem Detail','callback' =>array(__CLASS__,'_meta_box_default'), 'context' => 'side', 'priority' =>'core','callback_args' =>null),
					'fields' => array(
						'time_limit' => OJ_OBJECT::$fields['problem']['time_limit'],
						'memory_limit' => OJ_OBJECT::$fields['problem']['memory_limit'],
						'spj' => OJ_OBJECT::$fields['problem']['spj'],
						'source' => OJ_OBJECT::$fields['problem']['source'],
					)
				),
			)
		);
		return $meta_box_args[$post_type];
	}
	function _register_meta_boxes($post_type,$post){
		if(!in_array($post_type, array('problem','contest'))){ return;}
		$object_metas = OJ_meta_box::get_meta_box_args( $post_type );
		if($_GET['action']=="edit") {
			$post=oj_fill_object_metas($post);
		}
		foreach ($object_metas as $box){
			add_meta_box($box['box']['id'], $box['box']['title'], $box['box']['callback'], $post_type,$box['box']['context'],$box['box']['priority'],$box['fields']);
		}
	}
	function _meta_box_default($object,$box){

		$fields=$box['args']; 
		call_user_func(array(__CLASS__,'_echo_object_nonce'),$object);
		?>
		<table class="form-table">

			<?php foreach ( $fields as $field ) {
				
				if ( function_exists( "wpoj_post_meta_box_{$field['type']}" ) )
					call_user_func( "wpoj_post_meta_box_{$field['type']}", $field, $object->$field['name'] );
			} ?>
	
		</table><!-- .form-table --><?php
	}
	function _echo_object_nonce($object){
		if (OJ_meta_box::$_object_nonce[$object->post_type]){
			?>
			<input type="hidden" name="<?php echo "{$object->post_type}_meta_box_nonce"; ?>" value="<?php echo wp_create_nonce("{$object->post_type}_meta_box_nonce"); ?>" />
			<?php
			OJ_meta_box::$_object_nonce[$object->post_type]=false;	
		}
	}
	function _add_code_button($buttons){
		$buttons[]='code';
		return $buttons;
	}
	function _print_box_styles(){
		?>
		<style>
		<!--
			.p2p-search input[type="text"]{width:266px;}
			#poststuff #problem_meta_input_output_hint .inside{margin:0;}
			#problem_meta_input_output_hint table{margin:0;}
			#problem_meta_input_output_hint th{border-right:1px solid #EEE; vertical-align:middle; text-align:center;}
			#problem_meta_input_output_hint td{padding:0;}
			.form-table label{color:#21759B}
			#contest_meta_detail td select{height:auto; min-width:80px;}
		-->
		</style>
		<?php 
	}
}
function wpoj_post_meta_box_radio( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); 
	if($args['options']['default']&&!value) $value=$args['options']['default'];	?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label></th>
		<td>
			<?php foreach ( $args['options']['values'] as  $label => $val) { ?>
				<?php echo $label; ?> <input <?php if ( $value == $val ) echo ' checked="checked"'; ?>type="radio" name="<?php echo $name; ?>" value="<?php echo $val; ?>" /> 
			<?php } ?>
		</td>
	</tr>
	<?php
}
function wpoj_post_meta_box_select( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); 
	$options=$args['options'];
	if($value) {
		$value=explode(',', $value);
		$ref_value=array();
		$raw_options=array_keys($options);
		foreach ($raw_options as $select){
			$ref_value[$select]=false;
		}
		foreach ($value as $select){
			$ref_value[$select]=true;
		}
		$options=$ref_value;
	}
	?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name;?>"><?php echo $args['title']; ?></label></th>
		<td>
		<select name="<?php echo $name;if($args['multiple']) echo '[]'; ?>" <?php if($args['multiple']) echo 'multiple="multiple"';?>>
		<?php foreach ( $options as  $option => $flag ) { ?>
			<option value="<?php echo $option;?>" <?php if($flag) echo ' selected="selected"';?>><?php echo $option;?></option>
		<?php } ?>
		</select>
		</td>
	</tr>
	<?php
}
function wpoj_post_meta_box_text( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); ?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label></th>
		<td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo wp_specialchars( $value, 1 ); ?>" size="30" tabindex="30" style="width: 97%;" /></td>
	</tr>
	<?php
}
function wpoj_post_meta_box_textarea( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); ?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label></th>
		<td><textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" cols="60" rows="3" tabindex="30" style="width: 99%;"><?php echo wp_specialchars( $value, 1 ); ?></textarea></td>
	</tr>
	<?php
}
function wpoj_post_meta_box_tinymce( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); ?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label></th>
		<td><textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" cols="60" rows="3" tabindex="30" style="width: 97%;" class="theEditor"><?php echo wp_specialchars( $value, 1 ); ?></textarea></td>
	</tr>
	<?php
}
function wpoj_post_meta_box_datetime( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] );
	$yy="";
	$mm="";
	$dd="";
	$hh="";
	$mn="";
	if($value==false && ($name ="start_time" || $name="end_time")){
		date_default_timezone_set("PRC");
		$value=date("Y-m-d H:i:s");
	}
	$yy=substr($value,0,4);
	$mm=substr($value,5,2);
	$dd=substr($value,8,2);
	$hh=substr($value,11,2);
	$mn=substr($value,14,2);
	?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label></th>
		<td>
		Year:<input type="text" name="<?php echo $args['yy'];?>" size="4" value="<?php echo $yy;?>"/>-
		<input type="text" name="<?php echo $args['mm'];?>" size="2" value="<?php echo $mm;?>"/>-
		<input type="text" name="<?php echo $args['dd'];?>" size="2" value="<?php echo $dd;?>"/>@
		<input type="text" name="<?php echo $args['hh'];?>" size="2" value="<?php echo $hh;?>"/>:
		<input type="text" name="<?php echo $args['mn'];?>" size="2" value="<?php echo $mn;?>"/>
		</td>
	</tr>
	<?php
}
?>