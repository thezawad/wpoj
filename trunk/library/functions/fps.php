<?php
function oj_import_problems(){
	if(isset($_POST['submit'])){
?>
<div class="wrap">
<h2>Import Function is On the way</h2>
<div id="poststuff">
	<div class="postbox">
			<h3 class="hndle">Import Details:</h3>
			<div class="inside" style="font-size:12px; line-height:18px;">
<?php 
		if ($_FILES ["import_problem"] ["error"] > 0) {
			echo "Error: " . $_FILES ["fps"] ["error"] . "File size is too big, change in PHP.ini<br />";
		} else {
			$OJ_DATA="/home/judge/data";
			$tempfile = $_FILES ["import_problem"] ["tmp_name"];
			echo "Upload: " . $_FILES ["import_problem"] ["name"] . "<br />";
			echo "Type: " . $_FILES ["import_problem"] ["type"] . "<br />";
			echo "Size: " . ($_FILES ["import_problem"] ["size"] / 1024) . " Kb<br />";
			echo "Stored in: " . $tempfile;
			
			$xmlDoc = new DOMDocument ();
			$xmlDoc->load ( $tempfile );
			
			$searchNodes = $xmlDoc->getElementsByTagName ( "item" );
			
			foreach ( $searchNodes as $searchNode ) {
				$postarr=array();
				$postarr['post_title'] = fps_getValue ( $searchNode, 'title' );
				$postarr['post_content'] = fps_getValue ( $searchNode, 'description' );
				
				$problem_meta['time_limit'] = fps_getValue ( $searchNode, 'time_limit' );
				$problem_meta['memory_limit'] = fps_getValue ( $searchNode, 'memory_limit' );
				$problem_meta['input'] = fps_getValue ( $searchNode, 'input' );
				$problem_meta['output'] = fps_getValue ( $searchNode, 'output' );
				$problem_meta['sample_input'] = fps_getValue ( $searchNode, 'sample_input' );
				$problem_meta['sample_output'] = fps_getValue ( $searchNode, 'sample_output' );
				$problem_meta['hint'] = fps_getValue ( $searchNode, 'hint' );
				$problem_meta['source'] = fps_getValue ( $searchNode, 'source' );
				$problem_meta['spj'] = fps_getValue ( $searchNode, 'spj' );
				$problem_meta['spj'] = trim($problem_meta['spj'])?'Y':'N';

				//$solutions = $searchNode->getElementsByTagName("solution");
				
			
				$pid=fps_addproblem ($postarr,$problem_meta );
	
				$basedir = "$OJ_DATA/$pid";
			    mkdir ( $basedir );
				if(strlen($problem_meta['sample_input'] )) mkdata($pid,"sample.in",$problem_meta['sample_input'],$OJ_DATA);
				if(strlen($problem_meta['sample_output'])) mkdata($pid,"sample.out",$problem_meta['sample_output'],$OJ_DATA);
				$testinputs=$searchNode->getElementsByTagName("test_input");
				$testno=0;
				foreach($testinputs as $testNode){
					if($testNode->nodeValue)
					mkdata($pid,"test".$testno++.".in",$testNode->nodeValue,$OJ_DATA);
				}
				$testinputs=$searchNode->getElementsByTagName("test_output");
				$testno=0;
				foreach($testinputs as $testNode){
					//if($testNode->nodeValue)
					mkdata($pid,"test".$testno++.".out",$testNode->nodeValue,$OJ_DATA);
				}
			}
			unlink ( $tempfile );
		}
?>		
			</div>
		</div>
	</div>
</div>
<?php 
}else{
		$maxfile=min(ini_get("upload_max_filesize"),ini_get("post_max_size"));
?>

<div class="wrap">
<h2>Import Problems</h2>
	
<form method="post" enctype="multipart/form-data">
	<div id="poststuff">
		<?php import_problems_page_hidden_args();?>
		<div class="error"></div>

		<div class="updated">
			<h3 class="hndle">Uploading Notice:</h3>
			<ul>
			<li>Import FPS data ,please make sure you file is smaller than [<span style="color:#c00"><?php echo $maxfile;?></span>] </li>
			<li>or set upload_max_filesize and post_max_size in PHP.ini</li>
			<li>&nbsp;</li>
			<li>if you fail on import big files[10M+],try enlarge your [memory_limit] setting in php.ini</li>
			</ul>
			
		</div>
		
		<div class="postbox">
			<h3 class="hndle">Choose File：</h3>
			<div class="inside">
				<input class="button-secondary" type="file" name='import_problem'/>
				<input type="submit" name="submit" class="button-primary" value="Start Importing"/>
				
			</div>
		</div>
		<div class="error">
		<P>
		free problem set FPS-xml can be download at <a href=http://code.google.com/p/freeproblemset/downloads/list>FPS-Googlecode</a>
		</P>
		</div>
	</div>
	
</form>
</div>
<?php
	}
}

function import_problems_page_hidden_args(){
	echo '<input type="hidden" name="page" value="import_problems"/>';
	echo '<input type="hidden" name="post_type" value="problem"/>';
}
function fps_getValue($Node, $TagName) {
	
	$children = $Node->getElementsByTagName ( $TagName );
	if ($children->length > 0)
		$ret = $children->item ( 0 )->nodeValue;
	else
		$ret = "";
	return $ret;
}
function fps_getAttribute($Node, $TagName,$attribute) {
	
	$children = $Node->getElementsByTagName ( $TagName );
	if ($children->length > 0)
		$ret = $children->item ( 0 )->getAttribute($attribute);
	else
		$ret = "";
	return $ret;
}
function mkdata($pid,$filename,$input,$OJ_DATA){
	
	$basedir = "$OJ_DATA/$pid";
	
	$fp = @fopen ( $basedir . "/$filename", "w" );
	if($fp){
		fputs ( $fp, preg_replace ( "(\r\n)", "\n", $input ) );
		fclose ( $fp );
	}else{
		echo "Error while opening".$basedir . "/$filename ,try [chgrp -R www-data $OJ_DATA] and [chmod -R 771 $OJ_DATA ] ";
		
	}
}
function fps_addproblem($postarr,$problem_metas,$OJ_DATA="/home/judge/data"){
	$postarr['post_status']="publish";
	$postarr['post_type']='problem';
	$post_id=wp_insert_post($postarr);
	$problem_metas['post_id']=$post_id;
	oj_save_object_metas($post_id,get_post($post_id),$problem_metas);
	return $post_id;
}
?>