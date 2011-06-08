<?php
get_header(); ?>
<div class="content-wrapper hentry clearfix">
	<div id="content" role="main">
		<div class="inner-wrapper">
			<table>
			<thead>
				<tr>
				<th>Run ID</th><th>User</th><th>Problem</th><th>Result</th><th>Memory</th><th>Time</th><th>Language</th><th>Code Length</th><th>Submit Time</th>
				</tr>
			</thead>
			<tbody>
			<?php
			global $wpdb,$oj,$oju,$paged,$wp_query;
			$problem_id=$_GET['pid'];
			$user_id=$_GET['user_id'];
			$user_login=$_GET['user_login'];
			$orderby=$_GET['orderby'];
			$perpage=20;
			$paged=1;
			$where_clause='WHERE 1';
			$limit_clause='';
			
			
			if(!empty($problem_id)){
				$where_clause.=' AND problem_id='.$problem_id;
			}
			if(!empty($user_id)){
				$where_clause.=' AND user_id='.$user_id;
			}
			if(!empty($user_login)){
				$where_clause.=' AND user_login='.$user_login;
			}
			if(empty($orderby)) $orderby=' ORDER BY in_date DESC ';
			if(!empty($_GET['paged'])) $paged=$_GET['paged'];
			if($paged>1) $limit_clause=($paged-1) * $perpage.' , ';
			
			$limit_clause.=$perpage;
			
			$sql="SELECT SQL_CALC_FOUND_ROWS solution_id,user_id,user_login,problem_id,result,memory,time,language,code_length,in_date FROM {$oj->prefix}solution $where_clause $orderby limit $limit_clause";
			$posts=$wpdb->get_results($sql);
			
			$wp_query->max_num_pages=ceil($wpdb->get_var( 'SELECT FOUND_ROWS()' )/20);
			$wp_query->query_vars=array('paged'=> $paged);
			
			foreach ($posts as $post):?>
				<tr>
				<?php 
					$compile_statas=array(
						'full'=>array('Pending','Pending Rejudging','Compiling','Running & Judging','Accepted','Presentation Error','Wrong Answer','Time Limit Exceed','Memory Limit Exceed','Output Limit Exceed','Runtime Error','Compile Error','Compile OK'),
						'short'=>array('PD','PR','CI','RJ','AC','PE','WA','TLE','MLE','OLE','RE','CE','CO'),
						'class'=>array('PD','PR','CI','RJ','AC','PE','WA','TLE','MLE','OLE','RE','CE','CO')
					);	
					$languages=array_keys($oj->languages);
					$pid_clause='&pid='.$post->problem_id;
					$sid_clause='&sid='.$post->solution_id;
					$lang_clause='&language='.$post->language;
					$submitpage_url=$oju->url('submitpage').$pid_clause.$sid_clause.$lang_clause;
					$showsource_url=$oju->url('showsource').$sid_clause;
					$compileinfo_url=$oju->url('compileinfo').$sid_clause;
				?>
					<td><?php echo $post->solution_id;?></td>
					<td><a href="/?author=<?php echo $post->user_id;?>"><?php echo $post->user_login;?></a></td>
					<td><a href="/?post_type=problem&p=<?php echo $post->problem_id;?>"><?php echo $post->problem_id?></a></td>
					<td><span class="<?php echo $compile_statas['class'][$post->result];?>">
						<?php 
							if($post->result==11){
								echo '<a href="'.$compileinfo_url.'">'.$compile_statas['full'][$post->result].'</a>';
							}else {
								echo $compile_statas['full'][$post->result];
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
			<?php loop_pagination( array('prev_text' => __( '&larr; Previous', hybrid_get_textdomain() ), 'next_text' => __( 'Next &rarr;', hybrid_get_textdomain() ) ) ); ?>
		</div>
	</div><!-- #content -->
</div>
	<?php do_atomic( 'after_content' ); // retro-fitted_after_content ?>
	
	<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

	<?php get_sidebar( 'secondary' ); // Loads the sidebar-secondary.php template. ?>

	<?php do_atomic( 'close_main' ); // retro-fitted_close_main ?>
<?php get_footer(); ?>
