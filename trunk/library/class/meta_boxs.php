<?php
function oj_save_metas($post_id,$post){
	switch ($post->post_type){
		case 'problem':
			if ( !wp_verify_nonce( $_POST["_problem_meta_box_nonce"], "problem_meta_box" ) ) return $post_id;
			oj_save_problem_metas($post_id,$post);
			break;
		case 'contest':
			if ( !wp_verify_nonce( $_POST["_contest_meta_box_nonce"], "contest_meta_box" ) ) return $post_id;
			oj_save_contest_metas($post_id,$post);
			break;
	}
}
class OJ_meta_box{
	
	function init(){
		add_filter('mce_buttons',array(__CLASS__,'_add_code_button') );
		add_action('admin_print_styles', array(__CLASS__,'_print_box_styles'));
		add_action("add_meta_boxes", array(__CLASS__,"_register_meta_boxes"),10,2);
	}
	static function get_meta_box_args($post_type){
		$public_options=array('default'=>'public','values'=>array('public','private'));
		$language_options=array('C','C++','Pascal','JAVA','Ruby','Bash','Python');
		$special_judge=array('default' => 'N', values=>array("Y","N"));
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
					'box' => array('id'=>'problem_meta_input','title'=>'Input','callback' =>array(__CLASS__,'_meta_box_default'),'context'=>'normal','priority'=>'default','callback_args' =>null),
					'fields' =>array(
						'input' => array('name' =>'input' ,'title' =>'Input', 'type' =>'tinymce')
					)
				),
				array(
					'box' => array('id'=>'problem_meta_output','title'=>'Output','callback' =>array(__CLASS__,'_meta_box_default'),'context'=>'normal','priority'=>'default','callback_args' =>null),
					'fields' =>array(
						'input' => array('name' =>'output' ,'title' =>'Output', 'type' =>'tinymce')
					)
				),
				array(
					'box' => array('id'=>'problem_meta_sample','title'=>'Sample Data','callback' =>array(__CLASS__,'_meta_box_two_column'),'context'=>'normal','priority'=>'default','callback_args' =>null),
					'fields' =>array(
						'sample_input' => array("name" =>'sample_input' ,'title' =>'Sample Input', 'type' =>'textarea','positon'=>'left'),
						'sample_output' => array("name" =>'sample_output' ,'title' =>'Sample Output', 'type' =>'textarea','position'=>'right'),
					)
				),
				array(
					'box' => array('id'=>'problem_meta_test','title'=>'Test Data','callback' =>array(__CLASS__,'_meta_box_two_column'),'context'=>'normal','priority'=>'default','callback_args' =>null),
					'fields' =>array(
						'test_input' => array("name" =>'test_input' ,'title' =>'Sample Input', 'type' =>'textarea','positon'=>'left'),
						'test_output' => array("name" =>'test_output' ,'title' =>'Sample Output', 'type' =>'textarea','position'=>'right'),
					)
				),
				array(
					'box' => array('id'=>'problem_meta_hint','title'=>'Hint','callback' =>array(__CLASS__,'_meta_box_default'),'context'=>'normal','priority'=>'default','callback_args' =>null),
					'fields' =>array(
						'input' => array('name' =>'output' ,'title' =>'Output', 'type' =>'tinymce')
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
				/*
				add_meta_box('problem_meta_input', 'Input', array(__CLASS__,'_problem_meta_input'), 'problem','normal');
				add_meta_box('problem_meta_output', 'Output', array(__CLASS__,'_problem_meta_output'), 'problem','normal');
				add_meta_box('problem_meta_sample', 'Sample Data', array(__CLASS__,'_problem_meta_sample'), 'problem','normal');
				add_meta_box('problem_meta_test', 'Test Data', array(__CLASS__,'_problem_meta_test'), 'problem','normal');
				add_meta_box('problem_meta_hint', 'Hint', array(__CLASS__,'_problem_meta_hint'), 'problem','normal');
				add_meta_box("problem_meta_detail", "Problem Detail", array(__CLASS__,'_problem_meta_detail'), 'problem','side',"core");*/
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

		$fields=$box['args']; ?>
	
		<input type="hidden" name="<?php echo "{$object->post_type}_meta_box_nonce"; ?>" value="<?php echo "{$object->post_type}_meta_box_nonce"; ?>" />
	
		<table class="form-table">

			<?php foreach ( $fields as $field ) {
				
				if ( function_exists( "hybrid_post_meta_box_{$field['type']}" ) )
					call_user_func( "hybrid_post_meta_box_{$field['type']}", $field, get_post_meta( $object->ID, $field['name'], true ) );
			} ?>
	
		</table><!-- .form-table --><?php
	}
	function _meta_box_two_column($object,$box){
		
	}
	function _problem_meta_input($post){
		echo '<textarea id="input" class="theEditor" name="input">'.$post->input.'</textarea>';
	}
	function _problem_meta_output($post){
		echo '<textarea id="output" class="theEditor" name="output">'.$post->output.'</textarea>';
	}
	function _problem_meta_sample($post){
		?>
		<input type="hidden" name="<?php echo "_problem_meta_box_nonce"; ?>" value="<?php echo wp_create_nonce( "problem_meta_box" ); ?>" />
		<div class="text-wrapper text-input">
			<div class="inner-wrapper">
				<h4>Sample Input</h4>
				<textarea name="sample_input"><?php echo $post->sample_input;?></textarea>
			</div>
		</div>
		<div class="text-wrapper text-output">
			<div class="inner-wrapper">
				<h4>Sample Ouput</h4>
				<textarea name="sample_output"><?php echo $post->sample_output;?></textarea>
			</div>
		</div>
		<div style="clear:both"></div>
		<?php
	}
	function _problem_meta_test($post){
		?>
		<div class="text-wrapper text-input">
			<div class="inner-wrapper">
				<h4>Test Input</h4>
				<textarea name="test_input"><?php echo $post->test_input;?></textarea>
			</div>
		</div>
		<div class="text-wrapper text-output">
			<div class="inner-wrapper">
				<h4>Test Ouput</h4>
				<textarea name="test_output"><?php echo $post->test_output;?></textarea>
			</div>
		</div>
		<div style="clear:both"></div>
		<?php
	}
	function _problem_meta_hint($post){
		echo '<textarea id="hint" class="theEditor" name="hint">'.$post->hint.'</textarea>';
	}
	function _add_code_button($buttons){
		$buttons[]='code';
		return $buttons;
	}
	
	function _problem_meta_detail($post){
		$time_limit=$post->time_limit?$post->time_limit:1;
		$memory_limit=$post->memory_limit?$post->memory_limit:128;
		if($post->special_judge=='N'){
			$special_judge_n='checked="true"';
			$special_judge_y='';
		}else{
			$special_judge_n='';
			$special_judge_y='checked="true"';
		}
		$source=$post->source?$post->source:'';
		
		?>
		<div class="item-line"><label><strong>Time Limit(S):</strong><input type="text" value="<?php echo $time_limit;?>" class="source-limit" name="time_limit"/></label></div>
		<div class="item-line"><label><strong>Memory Limit(MByte):</strong><input type="text" value="<?php echo $memory_limit;?>" class="source-limit" name="memory_limit"/></label></div>
		<div class="item-line"><strong><label for="special-n">Special Judge:</label></strong>
			<label style="padding-left:72px;">N<input style="margin-left:5px;" id="special-n" name="special_judge" type="radio" <?php echo $special_judge_n;?> value="N"/></label>
			<label style="padding-left:5px;">Y<input style="margin-left:5px;" name="special_judge" type="radio" <?php echo $special_judge_y;?> value="Y"/></label>
		</div>
		<div class="item-line"><label><strong>Source:</strong><input type="text" style="width:214px" name="source" value="<?php echo $source;?>"/></label></div>
		<div style="clear:both"></div>
		<?php 
	}
	function _contest_meta_detail(){
		?>
		<input type="hidden" name="<?php echo "_problem_meta_box_nonce"; ?>" value="<?php echo wp_create_nonce( "problem_meta_box" ); ?>" />
		
		<?php 
	}
	function _print_box_styles(){
		?>
		<style>
		<!--
			#poststuff #problem_meta_hint .inside,#poststuff #problem_meta_input .inside,#poststuff #problem_meta_output .inside{margin:0;}
			.text-wrapper{width:49.8%; float:left;}
			.text-wrapper textarea{width:100%; height:120px;}
			.text-wrapper h4{margin:5px 0;}
			.text-input .inner-wrapper{padding-right:5px;}
			.text-output .inner-wrapper{padding-left:5px;}
			#problem_meta_detail .source-limit{width:100px; float:right;}
			.item-line{clear:both; height:29px;line-height:29px;}
			.p2p-search input[type="text"]{width:266px;}
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
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); ?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label></th>
		<td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo wp_specialchars( $value, 1 ); ?>" size="30" tabindex="30" style="width: 97%;" /></td>
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
function hybrid_post_meta_box_tinymce( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); ?>
	<tr>
		<th style="width:10%;"><label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label></th>
		<td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo wp_specialchars( $value, 1 ); ?>" size="30" tabindex="30" style="width: 97%;" /></td>
	</tr>
	<?php
}

?>