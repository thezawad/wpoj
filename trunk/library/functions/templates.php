<?php
function oj_list_menu($pos,$menu_class="",$item_class="menu-item"){
	global $oju;
	$menu_id='oj-menu-'.$pos;
	$items=$oju->get_menu($pos);
	printf('<ul id="%s" class="%s">',$menu_id,$menu_class);
	foreach ($items as $page){
		$item_id='page-'.$page;
		$item_url=$oju->url($page);
		$item_label=$oju->label($page);
		printf('<li id="%s" class="%s"> <a href="%s">%s</a></li>',$item_id,$item_class,$item_url,$item_label);
	}
	echo '</ul>';
}
function oj_list_user_status($user_id=0){
	global $userdata,$oj,$wpdb;
	$the_user=$userdata;
	
	if($user_id){ 
		$the_user=get_userdata($user_id);
	}
	$user_id=$the_user->ID;
	$user_login=$the_user->user_login;
	$user_email=$the_user->user_email;
	$user_url=$the_user->user_url;

	// count solved
	$sql="SELECT count(DISTINCT problem_id) as `ac` FROM `{$oj->prefix}solution` WHERE `user_id`='".$user_id."' AND `result`=4";
	$result=$wpdb->get_row($sql);
	$user_solved=$result->ac;
	// count submit
	$sql="SELECT count(solution_id) as `submit` FROM `{$oj->prefix}solution` WHERE `user_id`='".$user_id."'";
	$result=$wpdb->get_row($sql);
	$user_submit=$result->submit;
	// update solved,submit
	$sql="UPDATE {$oj->prefix}users_meta SET solved={$user_solved},submit={$user_submit} WHERE user_id=".$user_id;
	$wpdb->query($sql);
	// count AC,WA,TLE,RE,CE等
	$sql="SELECT result,count(1) as count FROM {$oj->prefix}solution WHERE `user_id`='{$user_id}'	AND result>=4 group by result order by result";
	$user_kinds_submit=$wpdb->get_results($sql);
?>
<?php
	$compile_status=oj_get_compile_status();
	oj_load_part('fusioncharts');
  	$FC=new FusionCharts('Pie3D','360','260');
    //$FC=new FusionCharts('Column3D','360','260');
	$FC->setSWFPath(FC_URL.'/');
	$strParam='baseFontSize=12;xAxisName=status;yAxisName=counts;decimalPrecision=0;formatNumberScale=1;showExportDataMenuItem=1';
	$FC->setChartParams($strParam);
	foreach ($user_kinds_submit as $item){
		$name=$compile_status['short'][$item->result];
		$count=$item->count;
		$FC->addChartData($count,'name='.$name);
	}
	
	$sql="SELECT DISTINCT `problem_id` FROM `{$oj->prefix}solution` WHERE `user_id`='$user_id' AND `result`=4 ORDER BY `problem_id` ASC";
	$user_problems=$wpdb->get_results($sql);
?>
<script type="text/javascript" src="<?php echo FC_URL.'/FusionCharts.js'?>"></script>
<table class="tb2cols">
	<tr>	<th>NO.</th>		<td><?php echo $user_id;?></td>									</tr>
	<tr>	<th>login_name</th>	<td><?php echo $user_login;?></td>								</tr>
	<tr>	<th>Solved</th>		<td><?php echo $user_solved;?></td>								</tr>
	<tr>	<th>Submit</th>		<td><?php echo $user_submit;?></td>								</tr>
<?php foreach ($user_kinds_submit as $item){ $status=$item->result;	$count=$item->count;?>
	<tr>	<th><?php echo $compile_status['full'][$status]?></th><td><?php echo $count;?></td>	</tr><?php }?>
	<tr>	<th>Statistics</th>	<td><?php $FC->renderChart(false,true);?></td>					</tr>
	<tr>	<th>Email</th>		<td><?php echo $user_email;?></td>								</tr>
	<tr>	<th colspan=2>Problems:</th>														</tr>
	<tr>	<td colspan=2><?php foreach ($user_problems as $problem){ echo $problem->problem_id.' '; }?></td></tr>
</table>

<?php 
}
function oj_list_statusd($pid){
	global $oj,$wpdb;
	// total users
	$sql="SELECT count(DISTINCT user_id) as count FROM {$oj->prefix}solution WHERE problem_id='$pid'";
	$result=$wpdb->get_row($sql);
	$total_users=$result->count;
	// ac users
	$sql="SELECT count(DISTINCT user_id) as count FROM {$oj->prefix}solution WHERE problem_id='$pid' AND result='4'";
	$result=$wpdb->get_row($sql);
	$ac_users=$result->count;
	// total submit
	$sql="SELECT count(*) as count FROM {$oj->prefix}solution WHERE problem_id='$pid'";
	$result=$wpdb->get_row($sql);
	$total_submit=$result->count;
	// kinds submit
	$sql="SELECT result,count(1) as count FROM {$oj->prefix}solution WHERE problem_id='$pid' AND result>=4 group by result order by result";
	$user_kinds_submit=$wpdb->get_results($sql);

	$sql=" SELECT solution_id, user_login,count(*) count ,user_id, language, memory, time, code_length, min(10000000000000000000+time *100000000000 + memory *100000 + code_length ) score, in_date
	FROM {$oj->prefix}solution
	WHERE problem_id =$pid
	AND result =4
	GROUP BY user_id
	ORDER BY score, in_date";
	$problem_submits=$wpdb->get_results($sql);
?>
<?php
	$compile_status=oj_get_compile_status();
	oj_load_part('fusioncharts');
  	$FC=new FusionCharts('Pie3D','360','260');
    //$FC=new FusionCharts('Column3D','360','260');
	$FC->setSWFPath(FC_URL.'/');
	$strParam='baseFontSize=12;xAxisName=status;yAxisName=counts;decimalPrecision=0;formatNumberScale=1;showExportDataMenuItem=1';
	$FC->setChartParams($strParam);
	foreach ($user_kinds_submit as $item){
		$name=$compile_status['short'][$item->result];
		$count=$item->count;
		$FC->addChartData($count,'name='.$name);
	}
	$i=0;
	$languages=array_keys($oj->languages);
?>
<script type="text/javascript" src="<?php echo FC_URL.'/FusionCharts.js'?>"></script>
<table class="tb2cols">
	<tr>	<th>User(Submit)</th>		<td><?php echo $total_users;?></td>									</tr>
	<tr>	<th>User(Solved)</th>	<td><?php echo $ac_users;?></td>								</tr>
<?php foreach ($user_kinds_submit as $item){ $status=$item->result;	$count=$item->count;?>
	<tr>	<th><?php echo $compile_status['full'][$status]?></th><td><?php echo $count;?></td>	</tr><?php }?>
	<tr>	<th>Statistics</th>	<td><?php $FC->renderChart(false,true);?></td>					</tr>
</table>

<table>
	<tr><th colspan="8">Success Submits:</th></tr>
	<tr><th>NO.</th><th>RunID</th><th>User</th><th>Memory</th><th>Time</th><th>Language</th><th>Code Length</th><th>Submit Time</th></tr>
	<?php foreach ($problem_submits as $item){$i++;?><tr>
		<td><?php echo $i; ?></td>
		<td><?php echo $item->solution_id;?></td>
		<td><?php echo $item->user_login;?></td>
		<td><?php echo $item->memory;?> KB</td>
		<td><?php echo $item->time;?> MS</td>
		<td><?php echo $languages[$item->language];?></td>
		<td><?php echo $item->code_length;?> B</td>
		<td><?php echo $item->in_date;?></td>
	</tr><?php }?>
</table>
<?php }
function oj_list_statusl(){
	global $wpdb,$oj,$oju,$paged,$wp_query;
	$problem_id=$_GET['s_pid'];
	$contest_id=$_GET['cid'];
	$user_id=$_GET['user_id'];
	$user_login=$_GET['user_login'];
	$orderby=$_GET['orderby'];
	
	if(!isset($_GET['language'])){
		$s_language=-1;
	}else{
		$s_language=intval($_GET['language']);
	}
	if(!isset($_GET['result'])){
		$s_result=-1;
	}else{
		$s_result=intval($_GET['result']);
	}
	
	$perpage=20;
	$paged=1;
	$where_clause='WHERE 1';
	$limit_clause='';
	
	if($s_language!=-1){
		$where_clause.=' AND language='.$s_language;
	}
	if($s_result!=-1){
		$where_clause.=' AND result='.$s_result;
	}
	if(!empty($problem_id) && $problem_id=intval($problem_id)){
		$where_clause.=' AND problem_id='.$problem_id;
	}else{
		$problem_id="Problem ID";
	}
	if(!empty($user_id)){
		$where_clause.=' AND user_id='.$user_id;
	}
	if(!empty($user_login) && $user_login!="User"){
		$where_clause.=' AND user_login=\''.$user_login.'\'';
	}else{
		$user_login="User";
	}
	if(!empty($contest_id)){
		$where_clause.=' AND contest_id='.$contest_id;
	}
	if(empty($orderby)) $orderby=' ORDER BY in_date DESC ';
	if(!empty($_GET['paged'])) $paged=$_GET['paged'];
	if($paged>1) $limit_clause=($paged-1) * $perpage.' , ';
	
	$limit_clause.=$perpage;
	
	$sql="SELECT SQL_CALC_FOUND_ROWS solution_id,user_id,user_login,problem_id,result,memory,time,language,code_length,in_date FROM {$oj->prefix}solution $where_clause $orderby limit $limit_clause";
	$posts=$wpdb->get_results($sql);
	$wp_query->max_num_pages=ceil($wpdb->get_var( 'SELECT FOUND_ROWS()' )/20);
	$wp_query->query_vars=array('paged'=> $paged);
?>
<style type="text/css">
<!--
#status-filter{padding-bottom:15px;}
#status-filter .filter-warpper{border:1px solid #CE3000; float:left; vertical-align:top}
#status-filter label{background-color:#CE3000; height:35px; line-height:35px; color:#FFF; float:left; padding:0 5px;}
#status-filter select{border:0; height:35px; line-height:35px; float:left; }
#status-filter option{vertical-align:middle; padding:5px;}
#status-filter input,#status-filter select{color:#04648D; font-style:italic;}
#status-filter input[type="text"]{border:0; height:35px;  float:left; padding:0 10px;}
#status-filter .search-btn {font-size:12px; font-style:normal; color:#FFF; float:left; height:37px;}
#status-filter .search-btn input{margin:0;  }
-->
</style>
<div id="status-filter" class="clearfix">
	<form>
	<input type="hidden" name="oj" value="<?php echo $oj->page?>"/>
	<input type="hidden" name="cid" value="<?php echo $contest_id;?>"/>
	<div class="filter-warpper"><label for="s_pid">Problem ID:</label><input type="text" id="s_pid" name="s_pid" value="<?php echo $problem_id;?>" onfocus="if(this.value==this.defaultValue) this.value='';"/></div>
	<div class="filter-warpper"><label for="user_login">User:</label><input type="text" id="user_login" name="user_login" value="<?php echo $user_login;?>" onfocus="if(this.value==this.defaultValue) this.value='';"/></div>
	<div class="filter-warpper"><label for="language">Language:</label><?php oj_echo_language_select(0, $s_language,true);?></div>
	<div class="filter-warpper"><label for="result">Result:</label><?php oj_echo_filter_select($s_result)?></div>
	<div class="search-btn"><input class="search-btn" type="submit" value="Search"/></div>
	</form>
</div>
<table class="tb-statusl">
	<thead>
		<tr>
		<th>Run ID</th><th>User</th><th>Problem</th><th>Result</th><th>Memory</th><th>Time</th><th>Language</th><th>Code Length</th><th>Submit Time</th>
		</tr>
	</thead>
	<tbody>	
<?php foreach ($posts as $post):?>
		<tr>
		<?php 
			$compile_status=oj_get_compile_status();
			$languages=array_keys($oj->languages);
			$pid_clause='&pid='.$post->problem_id;
			$sid_clause='&sid='.$post->solution_id;
			$lang_clause='&language='.$post->language;
			if(isset($_GET['cid'])){
				$submitpage_url=$oju->url('contest-submitpage').$pid_clause.$sid_clause.$lang_clause;
				$showsource_url=$oju->url('contest-showsource').$sid_clause;
				$problem_url=$oju->url('contest-problem').$pid_clause;
				$user_url=$oju->url('contest-user').'&uid='.$post->user_id;
			}else{
				$submitpage_url=$oju->url('submitpage').$pid_clause.$sid_clause.$lang_clause;
				$showsource_url=$oju->url('showsource').$sid_clause;
				$problem_url=$oju->url('problem').$pid_clause;
				$user_url=$oju->url('user').'&uid='.$post->user_id;
			}	

			$compileinfo_url=$oju->url('compileinfo').$sid_clause;
		?>
			<td><?php echo $post->solution_id;?></td>
			<td><a href="<?php echo $user_url;?>"><?php echo $post->user_login;?></a></td>
			<td><a href="<?php echo $problem_url;?>"><?php echo $post->problem_id?></a></td>
			<td><span class="<?php echo $compile_status['class'][$post->result];?>">
				<?php 
					if($post->result==11){
						echo '<a href="'.$compileinfo_url.'">'.$compile_status['full'][$post->result].'</a>';
					}else {
						echo $compile_status['full'][$post->result];
					}?>
			</span></td>
			<td><?php echo $post->memory;?> <span>kb</span></td>
			<td><?php echo $post->time;?> <span>ms</span></td>
			<td>
				<a href="<?php echo $showsource_url;?>"><?php echo $languages[$post->language];?></a>/
				<a href="<?php echo $submitpage_url;?>">Edit</a>
				</td>
			<td><?php echo $post->code_length;?> B</td>
			<td><?php echo $post->in_date;?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php }?>
