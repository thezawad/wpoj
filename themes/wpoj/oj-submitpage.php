<?php 
get_header();
?>
<?php
	global $oj;
	$problem_id=$_GET['pid'];
	$contest_id=$_GET['cid'];
	$solution_id=$_GET['sid'];
	$source_code=oj_get_source_code($solution_id);
	if(isset($_GET['language'])) $language=$_GET['language'];  else	$language=0;
?>
<div id="content">
	<script language="Javascript" type="text/javascript" src="<?php echo THEME_URI;?>/edit_area/edit_area_full.js"></script>
	<script language="Javascript" type="text/javascript">
	editAreaLoader.init({
	        id: "source"            
	        ,start_highlight: true 
	        ,allow_resize: "both"
	        ,allow_toggle: true
	        ,word_wrap: true
	        ,language: "en"
	        ,syntax: "cpp"  
			,font_size: "8"
	        ,syntax_selection_allow: "basic,c,cpp,java,pas,perl,php,python,ruby"
			,toolbar: "search, go_to_line, fullscreen, |, undo, redo, |, select_font,syntax_selection,|, change_smooth_selection, highlight, reset_highlight, word_wrap, |, help"          
	});
	</script>
	<form class="solution-submit" action="<?php echo $oj->page['addsolution']['url_raw'];?>" method="post" >
		<input type="hidden" name="pid" value="<?=$problem_id?>">
		<input type="hidden" name="cid" value="<?=$contest_id?>">
		<input type="hidden" name="sid" value="<?=$solution_id?>">
		<div>
			Problem <span style="color:#00f; font-weight:bold;"><?=$problem_id?></span> : <?php echo $_GET['title'];?>
		</div>
		<div>
			Language : <?php oj_echo_language_select(0, $language)?>
		</div>
		<div>
			<textarea cols=80 rows=20 id="source" style="height:308px;" name="source"><?=$source_code?></textarea>
		</div>
		<div>
			<input type="submit" value="Submit"><input type="reset" value="Reset">
		</div>
	</form>
</div>
<?php 
get_footer();
?>