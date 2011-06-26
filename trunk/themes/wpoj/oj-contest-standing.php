<?php
/**
 * Singular Template
 *
 * This is the default singular template.  It is used when a more specific template can't be found to display
 * singular views of posts (any post type).
 *
 * @package Retro-fitted
 * @subpackage Template
 */
class TM{
	var $solved=0;
	var $time=0;
	var $wa_num;
	var $ac_sec;
	var $user_id;
	var $user_login;
	var $nick;
	function TM(){
		$this->solved=0;
		$this->time=0;
		$this->wa_num=array(0);
		$this->ac_sec=array(0);
	}
	function Add($cpid,$sec,$res){
//		echo "Add $pid $sec $res<br>";
		if (isset($this->ac_sec[$cpid])&&$this->ac_sec[$cpid]>0)
			return;
		if ($res!=4){
			if(isset($this->wa_num[$cpid])){
				$this->wa_num[$cpid]++;
			}else{
				$this->wa_num[$cpid]=1;
			}
		}else{
			$this->ac_sec[$cpid]=$sec;
			$this->solved++;
			$this->time+=$sec+$this->wa_num[$cpid]*1200;
//			echo "Time:".$this->time."<br>";
//			echo "Solved:".$this->solved."<br>";
		}
	}
}

function s_cmp($A,$B){
//	echo "Cmp....<br>";
	if ($A->solved!=$B->solved) return $A->solved<$B->solved;
	else return $A->time>$B->time;
}
function sec2str($sec){
	return sprintf("%02d:%02d:%02d",$sec/3600,$sec%3600/60,$sec%60);
}
get_header('contest'); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // retro-fitted_before_content ?>
	<div class="content-wrapper hentry clearfix">
	<div id="content">
	<?php
	global $wpdb,$wp,$oj,$oju;
	$contest_id=$_GET['cid'];
	
	$sql="SELECT start_time FROM contest_meta WHERE post_id={$contest_id}";
	$contest=$wpdb->get_row($sql);
	$start_time=$contest->start_time;
	
	
	$sql="SELECT p2p_id cpid,p2p_to pid FROM {$wpdb->prefix}p2p WHERE p2p_from={$contest_id}";
	$cps=$wpdb->get_results($sql);
	$name='65';
	$cp_name=array();
	
	$sql="SELECT user_id,user_login,result,cp_id,in_date FROM {$oj->prefix}solution WHERE contest_id={$contest_id} ORDER BY user_login, in_date";
	$solutions=$wpdb->get_results($sql);
	$user_cnt=0;
	$user_name='';
	$U=array();
	foreach ($solutions as $solution){
		$n_user=$solution->user_login;
		if (strcmp($user_name,$n_user)){
			$user_cnt++;
			$U[$user_cnt]=new TM();
			$U[$user_cnt]->user_id=$solution->user_id;
			$U[$user_cnt]->user_login=$solution->user_login;
			$U[$user_cnt]->nick=$solution->user_login;
			$user_name=$n_user;
		}
		$U[$user_cnt]->Add($solution->cp_id,strtotime($solution->in_date)-$start_time,intval($solution->result));
	}
	usort($U,"s_cmp");

	?>
	<table class="tb-statusl">
		<tr>
			<th>Rank</th><th>User</th><th>Nick</th><th>Solved</th><th>Penalty</th>
			<?php foreach ($cps as $cp){$cp_name[$cp->cpid]=$name;?>
				<th><a href="<?php echo $oju->url('problem').'&pid='.$cp->pid; ?>" style="color:#FFF;"><?php echo chr($name++); ?></a></th>
			<?php }?>
		</tr>
		<?php for ($i=0;$i<$user_cnt;$i++){ $user_id=$U[$i]->user_id; $user_login=$U[$i]->user_login; $user_solved=$U[$i]->solved;?>
		<tr>
			<td><?php echo $i+1;?></td>
			<td><a href="<?php echo $oju->url('contest-user').'&uid='.$user_id?>"><?php echo $user_login;?></a></td>
			<td><a href="<?php echo $oju->url('contest-user').'&uid='.$user_id?>"><?php echo $U[$i]->nick;?></a></td>
			<td><a href="<?php echo $oju->url('contests-status');?>"><?php echo $user_solved;?></a></td>
			<td><?php echo sec2str($U[$i]->time);?></td>
				<?php foreach($cps as $cp){
						$ac_flag= (isset($U[$i]->ac_sec[$cp->cpid]) ) && ($U[$i]->ac_sec[$cp->cpid]>0 );
						$wa_flag= (isset($U[$i]->wa_num[$cp->cpid]) ) && ($U[$i]->wa_num[$cp->cpid]>0 );
						if ($ac_flag){ $class=' class="C-AC"'; } else if($wa_flag){$class=' class="C-WA"'; } else $class=' class="NN"';?>
			<td <?php echo $class;?>>
				  <?php if ($ac_flag) echo sec2str($U[$i]->ac_sec[$cp->cpid]); if($wa_flag) echo "(-".$U[$i]->wa_num[$cp->cpid].")";?>
			</td>
				<?php }?>
		</tr>
		<?php } ?>
	</table>

	</div><!-- #content -->
	</div>
	<?php do_atomic( 'after_content' ); // retro-fitted_after_content ?>
	
	<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

	<?php get_sidebar( 'secondary' ); // Loads the sidebar-secondary.php template. ?>

	<?php do_atomic( 'close_main' ); // retro-fitted_close_main ?>

<?php get_footer(); // Loads the footer.php template. ?>