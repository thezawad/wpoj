<?php get_header('contest');?>
<link href="<?php echo THEME_URI;?>/highlight/styles/shCore.css" rel="stylesheet" type="text/css"/> 
<link href="<?php echo THEME_URI;?>/highlight/styles/shThemeDefault.css" rel="stylesheet" type="text/css"/> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shCore.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushCpp.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushCSharp.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushCss.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushJava.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushDelphi.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushRuby.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushBash.js" type="text/javascript"></script>

<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushPython.js" type="text/javascript"></script> 
<script language='javascript'> 
SyntaxHighlighter.config.bloggerMode = false;
SyntaxHighlighter.config.clipboardSwf = "<?php echo THEME_URI;?>/highlight/scripts/clipboard.swf";
SyntaxHighlighter.all();
</script>
<?php
	global $oj;
	$sid=intval($_GET['sid']);
	$solution=oj_get_solution($sid,true);
	$languages=array_keys($oj->languages);
	
	$brush=strtolower($languages[$solution->language]);
	if ($brush=='pascal') $brush='delphi';
	$lang_class='brush:'.$brush.';';
?>
<div style="border:1px solid #ccc; background-color:#fff;">
<pre class="<?php echo $lang_class;?>">
	<?php echo $solution->source;?>
</pre>
</div>
<?php get_footer()?>