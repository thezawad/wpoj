<?php 
get_header();
function echo_language_select($langmask,$default){
	echo '<select id="language" name="language">';
	$lang=(~((int)$langmask))&127;
	
	if(($lang&1)>0) echo"	    <option value=0 ".( $default==0?"selected":"").">C</option>";
	if(($lang&2)>0) echo"		<option value=1 ".( $default==1?"selected":"").">C++</option>";
	if(($lang&4)>0) echo"		<option value=2 ".( $default==2?"selected":"").">Pascal</option>";
	if(($lang&8)>0) echo"		<option value=3 ".( $default==3?"selected":"").">Java</option>";
	if(($lang&16)>0) echo"		<option value=4 ".( $default==4?"selected":"").">Ruby</option>";
	if(($lang&32)>0) echo"		<option value=5 ".( $default==5?"selected":"").">Bash</option>";
	if(($lang&64)>0) echo"		<option value=6 ".( $default==6?"selected":"").">Python</option>";
	echo '</select>';
}
?>
<?php 
	$problem_id=$_GET['pid'];
	$contest_id=$_GET['cid'];
	$solution_id=$_GET['sid'];
	$source_code=oj_get_solution_source($solution_id);
	if(isset($_GET['langmask'])) $langmask=$_GET['langmask'];  else	$langmask=0;
	if(isset($_COOKIE['lastlang'])) $lastlang=$_COOKIE['lastlang'];	else $lastlang=1;
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
	<form action="submit.php" method="post" class="solution-submit">
		<input type="hidden" name="pid" value="<?=$problem_id?>">
		<input type="hidden" name="cid" value="<?=$contest_id?>">
		<input type="hidden" name="sid" value="<?=$solution_id?>">
		<div>
			Problem <span style="color:#00f; font-weight:bold;"><?=$problem_id?></span> : <?php echo $_GET['title'];?>
		</div>
		<div>
			Language : <?php echo_language_select($langmask, $lastlang)?>
		</div>
		<div>
			<textarea cols=80 rows=20 id="source" name="source"><?=$source_code?></textarea>
		</div>
		<div>
			<input type="submit" value="Submit"><input type="reset" value="Reset">
		</div>
	</form>
</div>
<?php 
get_footer();
?>