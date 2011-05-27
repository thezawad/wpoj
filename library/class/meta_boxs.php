<?php
class OJ_meta_box{
	function init(){
		add_filter('mce_buttons',array(__CLASS__,'_add_code_button') );
		add_action('admin_print_styles', array(__CLASS__,'_print_box_styles'));
		add_action("add_meta_boxes", array(__CLASS__,"_register_meta_boxes"),10,2);
	}
	function _register_meta_boxes($post_type,$post){
		switch($post_type){
			case "problem":
				if($_GET['action']=="edit") {
					$post=oj_fill_problem_metas($post);
				}
				add_meta_box('problem_meta_input', 'Input', array(__CLASS__,'_problem_meta_input'), 'problem','normal');
				add_meta_box('problem_meta_output', 'Output', array(__CLASS__,'_problem_meta_output'), 'problem','normal');
				add_meta_box('problem_meta_sample', 'Sample Data', array(__CLASS__,'_problem_meta_sample'), 'problem','normal');
				add_meta_box('problem_meta_test', 'Test Data', array(__CLASS__,'_problem_meta_test'), 'problem','normal');
				add_meta_box('problem_meta_hint', 'Hint', array(__CLASS__,'_problem_meta_hint'), 'problem','normal');
				add_meta_box("problem_meta_detail", "Problem Detail", array(__CLASS__,'_problem_meta_detail'), 'problem','side',"core");	
		}	
	}
	function _problem_meta_input($post){
		echo '<textarea id="input" class="theEditor" name="input">'.$post->input.'</textarea>';
	}
	function _problem_meta_output($post){
		echo '<textarea id="output" class="theEditor" name="output">'.$post->output.'</textarea>';
	}
	function _problem_meta_sample($post){
		?>
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
?>