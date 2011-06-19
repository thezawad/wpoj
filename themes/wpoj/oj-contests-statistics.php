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

get_header('contest'); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // retro-fitted_before_content ?>
	<div class="content-wrapper hentry clearfix">
	<div id="content">

	<?php 
	global $oj,$oju,$wpdb;
	$contest_id=$_GET['cid'];
	
	$sql="SELECT p2p_id cpid, p2p_to pid FROM {$wpdb->prefix}p2p WHERE p2p_from={$contest_id}";
	$cps=$wpdb->get_results($sql);
	
	
	$sql="SELECT result,cp_id,language	FROM {$oj->prefix}solution WHERE contest_id={$contest_id}";
	$solutions=$wpdb->get_results($sql);

	$problems=array();
	foreach ($solutions as $sl){
		$cpid=$sl->cp_id;
		$result=$sl->result;
		$lang=$sl->language;
		//result statistics
		if (isset($problems[$cpid]['result'][$result])){
			$problems[$cpid]['result'][$result]++;
		}else{
			$problems[$cpid]['result'][$result]=1;
		}
		if (isset($problems[$cpid]['result'][13])){
			$problems[$cpid]['result'][13]++;
		}else{
			$problems[$cpid]['result'][13]=1;
		}
		//language statistics
		if (isset($problems[$cpid]['language'][$lang])){
			$problems[$cpid]['language'][$lang]++;
		}else{
			$problems[$cpid]['language'][$lang]=1;
		}
	}
	$compile_status=oj_get_compile_status();
	$langs=array_keys($oj->languages);
	
	?>
	<table class="tb-problems">
		<caption>比赛题目列表-结果统计</caption>
		<tr>
			<td></td>
			<?php for($i=4;$i<13;$i++){
				echo '<th title="'.$compile_status['full'][$i].'">'.$compile_status['short'][$i].'</th>';
			}?>
			<th>ALL</th>
		</tr>
		<?php $p_name=65;
		foreach ($cps as $cp ){ $cpid=$cp->cpid;?>
		<tr>
			<th><?php echo chr($p_name++);?></th>
			<?php for($i=4;$i<14;$i++){
				if (isset($problems[$cpid]['result'][$i])){
					echo '<td>'.$problems[$cpid]['result'][$i].'</td>';
				}else echo '<td></td>';
			}?>
		</tr>
		<?php }?>
	</table>
	
	<table class="tb-problems">
		<caption>比赛题目列表-语言统计</caption>
		<tr>
			<td></td>
			<?php for($i=0;$i<7;$i++){
				echo '<th>'.$langs[$i].'</th>';
			}?>
		</tr>
		<?php $p_name=65;
		foreach ($cps as $cp ){ $cpid=$cp->cpid;?>
		<tr>
			<th><?php echo chr($p_name++);?></th>
			<?php for($i=0;$i<7;$i++){
				if (isset($problems[$cpid]['language'][$i])){
					echo '<td>'.$problems[$cpid]['language'][$i].'</td>';
				}else echo '<td></td>';
			}?>
		</tr>
		<?php }?>
	</table>
	
	</div><!-- #content -->
	</div>
	<?php do_atomic( 'after_content' ); // retro-fitted_after_content ?>
	
	<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

	<?php get_sidebar( 'secondary' ); // Loads the sidebar-secondary.php template. ?>

	<?php do_atomic( 'close_main' ); // retro-fitted_close_main ?>

<?php get_footer(); // Loads the footer.php template. ?>