<?php
class OJ_meta_box{
	function init(){
		add_filter('mce_buttons',array(__CLASS__,'_add_code_button') );
		add_action('admin_print_styles', array(__CLASS__,'_print_box_styles'));
		OJ_meta_box::problem_meta();
	}
	function problem_meta(){
		add_meta_box('problem_meta_input', 'Sample Input', array(__CLASS__,'_problem_meta_input'), 'problem','normal');
		add_meta_box('problem_meta_output', 'Sample Output', array(__CLASS__,'_problem_meta_output'), 'problem','normal');
		add_meta_box('problem_meta_test', 'Test Data', array(__CLASS__,'_problem_meta_test'), 'problem','normal');
		add_meta_box('problem_meta_hint', 'Hint', array(__CLASS__,'_problem_meta_hint'), 'problem','normal');
		add_meta_box("problem_meta_detail", "Problem Detail", array(__CLASS__,'_problem_meta_detail'), 'problem','side',"core");
	}
	function _problem_meta_input(){
		echo '<textarea id="sample-input" class="theEditor">input data</textarea>';
	}
	function _problem_meta_output(){
		echo '<textarea id="sample-output" class="theEditor">input data</textarea>';
	}
	function _problem_meta_test(){
		?>
		<div class="test-data test-input">
			<div class="inner-wrapper">
				<h4>Test Input</h4>
				<textarea id="test-input">test input</textarea>
			</div>
		</div>
		<div class="test-data test-output">
			<div class="inner-wrapper">
				<h4>Test Ouput</h4>
				<textarea id="test-output">test output</textarea>
			</div>
		</div>
		<div style="clear:both"></div>
		<?php
	}
	function _problem_meta_hint(){
		echo '<textarea id="hint" class="theEditor">input data</textarea>';
	}
	function _add_code_button($buttons){
		$buttons[]='code';
		return $buttons;
	}
	function _print_box_styles(){
		?>
		<style>
		<!--
			#poststuff #problem_meta_hint .inside,#poststuff #problem_meta_input .inside,#poststuff #problem_meta_output .inside{margin:0;}
			.test-data{width:49.8%; float:left;}
			.test-data textarea{width:100%; height:120px;}
			.test-data h4{margin:5px 0;}
			.test-input .inner-wrapper{padding-right:5px;}
			.test-output .inner-wrapper{padding-left:5px;}
			#problem_meta_detail .source-limit{width:100px; float:right;}
			.item-line{clear:both; height:29px;line-height:29px;}
			.p2p-search input[type="text"]{width:266px;}
		-->
		</style>
		<?php 
	}
	function _problem_meta_detail(){
		?>
		<div class="item-line"><label><strong>Time Limit(S):</strong><input type="text" value="1" class="source-limit"/></label></div>
		<div class="item-line"><label><strong>Memory Limit(MByte):</strong><input type="text" value="128" class="source-limit"/></label></div>
		<div class="item-line"><strong><label for="special-n">Special Judge:</label></strong>
			<label style="padding-left:72px;">N<input style="margin-left:5px;" id="special-n" name="specialjudge" type="radio" selected="selected" value="N"/></label>
			<label style="padding-left:5px;">Y<input style="margin-left:5px;" name="specialjudge" type="radio" value="Y"/></label>
		</div>
		<div class="item-line"><label><strong>Source:</strong><input type="text" style="width:214px"/></label></div>
		<div style="clear:both"></div>
		<?php 
	}
}
?>