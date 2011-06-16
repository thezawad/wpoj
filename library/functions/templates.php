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
<table>
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
<table>
	<tr>	<th>User(Submit)</th>		<td><?php echo $total_users;?></td>									</tr>
	<tr>	<th>User(Solved)</th>	<td><?php echo $ac_users;?></td>								</tr>
<?php foreach ($user_kinds_submit as $item){ $status=$item->result;	$count=$item->count;?>
	<tr>	<th><?php echo $compile_status['full'][$status]?></th><td><?php echo $count;?></td>	</tr><?php }?>
	<tr>	<th>Statistics</th>	<td><?php $FC->renderChart(false,true);?></td>					</tr>

	<tr>	<th colspan="2">Success Submits:</th>														</tr>
	<tr><td colspan="2">
		<table>
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
	</td></tr>
</table>
<?php }?>