<?php
function oj_save_metas($post_id,$object){
	if ( !wp_verify_nonce( $_POST["{$object->post_type}_meta_box_nonce"], "{$object->post_type}_meta_box_nonce" ) ) return $post_id;
	oj_save_object_metas($post_id,$object);
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
		$public_options=array('default'=>'public','values'=>array('public','private'));
		$language_options=array('C','C++','Pascal','JAVA','Ruby','Bash','Python');
		$special_judge=array('default' => 'N', 'values'=>array("Y","N"));
		$meta_box_args=array(
			'contest' =>array(
				array(
					'box' => array('id' =>'contest_meta_detail','title' => 'Contest Detail','callback' =>array(__CLASS__,'_meta_box_default'), 'context' => 'normal', 'priority' =>'default','callback_args' =>null),
					'fields' => array(
						'start_time'=>array('name'=>'start_time', 'title'=>'Start Time', 'type' =>'datetime'),
						'end_time' => array('name'=>'end_time' , 'title' =>'End Time', 'type'=>'datetime'),
						'public' => array('name'=>'public' , 'title' => 'Public' ,'type' => 'select','options' =>$public_options,'multiple'=>false),
						'language'=>array('name'=>'language' , 'title'=> 'Language', 'type'=> 'select', 'options' => $language_options, 'multiple'=>true)
					)
				),
			),
			'problem' =>array(
				array(
					'box' => array('id'=>'problem_meta_input_output_hint','title'=>'Input & Output & Hint','callback' =>array(__CLASS__,'_meta_box_default'),'context'=>'normal','priority'=>'default','callback_args' =>null),
					'fields' =>array(
						'input' => array('name' =>'input' ,'title' =>'Input', 'type' =>'tinymce'),
						'output' => array('name' =>'output' ,'title' =>'Output', 'type' =>'tinymce'),
						'hint' => array('name' =>'hint' ,'title' =>'Hint', 'type' =>'tinymce')
					)
				),
				array(
					'box' => array('id'=>'problem_meta_sample','title'=>'Sample Data','callback' =>array(__CLASS__,'_meta_box_default'),'context'=>'normal','priority'=>'default','callback_args' =>null),
					'fields' =>array(
						'sample_input' => array("name" =>'sample_input' ,'title' =>'Sample Input', 'type' =>'textarea','positon'=>'left'),
						'sample_output' => array("name" =>'sample_output' ,'title' =>'Sample Output', 'type' =>'textarea','position'=>'right'),
					)
				),
				array(
					'box' => array('id'=>'problem_meta_test','title'=>'Test Data','callback' =>array(__CLASS__,'_meta_box_default'),'context'=>'normal','priority'=>'default','callback_args' =>null),
					'fields' =>array(
						'test_input' => array("name" =>'test_input' ,'title' =>'Test Input', 'type' =>'textarea','positon'=>'left'),
						'test_output' => array("name" =>'test_output' ,'title' =>'Test Output', 'type' =>'textarea','position'=>'right'),
					)
				),
				array(
					'box' => array('id' =>'problem_meta_detail','title' => 'Problem Detail','callback' =>array(__CLASS__,'_meta_box_default'), 'context' => 'side', 'priority' =>'core','callback_args' =>null),
					'fields' => array(
						'time_limit' => array('name'=> 'time_limit' ,'title'=> 'Time Limit(s):', 'type'=> 'text'),
						'memory_limit' => array('name'=>'memory_limit','title'=> 'Memory Limit(M)','type'=>'text'),
						'specital_judge' =>array('name'=> 'special_judge','title'=> 'Secial Judge' , 'type' => 'radio','options'=>$special_judge),
						'source' => array('name' => 'source', 'title'=>'Source', 'type' => 'text'),
					)
				),
			)
		);
		return $meta_box_args[$post_type];
	}
	function _register_meta_boxes($post_type,$post){
		switch($post_type){
			case "problem":
				if($_GET['action']=="edit") {
					$post=oj_fill_problem_metas($post);
				}
				$meta_post_boxes = OJ_meta_box::get_meta_box_args( $post_type ); 
				foreach ($meta_post_boxes as $box){
					add_meta_box($box['box']['id'], $box['box']['title'], $box['box']['callback'], $post_type,$box['box']['context'],$box['box']['priority'],$box['fields']);
				}
			case "contest":
				$meta_post_boxes = OJ_meta_box::get_meta_box_args( $post_type ); 
				foreach ($meta_post_boxes as $box){
					add_meta_box($box['box']['id'], $box['box']['title'], $box['box']['callback'], $post_type,$box['box']['context'],$box['box']['priority'],$box['fields']);
				}
		}
	}
	function _meta_box_default($object,$box){

		$fields=$box['args']; 
		call_user_func(array(__CLASS__,'_echo_object_nonce'),$object);
		?>
		<table class="form-table">

			<?php foreach ( $fields as $field ) {
				
				if ( function_exists( "hybrid_post_meta_box_{$field['type']}" ) )
					call_user_func( "hybrid_post_meta_box_{$field['type']}", $field, $object->$field['name'] );
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
		-->
		</style>
		<?php 
	}
}
function hybrid_post_meta_box_select( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); ?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label></th>
		<td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo wp_specialchars( $value, 1 ); ?>" size="30" tabindex="30" style="width: 97%;" /></td>
	</tr>
	<?php
}
function hybrid_post_meta_box_datetime( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); ?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label></th>
		<td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo wp_specialchars( $value, 1 ); ?>" size="30" tabindex="30" style="width: 97%;" /></td>
	</tr>
	<?php
}
function hybrid_post_meta_box_radio( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); 
	if($args['options']['default']&&!value) $value=$args['options']['default'];	?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label></th>
		<td>
			<?php foreach ( $args['options']['values'] as  $val ) { ?>
				<?php echo $val; ?> <input <?php if ( $value == $val ) echo ' checked="checked"'; ?>type="radio" name="<?php echo $name; ?>" value="<?php echo $val; ?>" /> 
			<?php } ?>
		</td>
	</tr>
	<?php
}
function hybrid_post_meta_box_text( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); ?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label></th>
		<td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo wp_specialchars( $value, 1 ); ?>" size="30" tabindex="30" style="width: 97%;" /></td>
	</tr>
	<?php
}
function hybrid_post_meta_box_textarea( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); ?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label></th>
		<td><textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" cols="60" rows="3" tabindex="30" style="width: 99%;"><?php echo wp_specialchars( $value, 1 ); ?></textarea></td>
	</tr>
	<?php
}
function hybrid_post_meta_box_tinymce( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); ?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label></th>
		<td><textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" cols="60" rows="3" tabindex="30" style="width: 97%;" class="theEditor"><?php echo wp_specialchars( $value, 1 ); ?></textarea></td>
	</tr>
	<?php
}

?>