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
	<div id="content" role="main">
			<table class="tb-problems">
			<thead>
				<tr>
				<th>Problem ID</th><th>Title</th><th>AC</th><th>Submit</th><th>FPS Category</th>
				</tr>
			</thead>
			<tbody>
			<?php
			get_posts();
			global $wp,$wpdb,$wp_query,$oj,$oju;
			$oj->current_page="problems";
			$contest_id=$_GET['cid'];
			/*
			 * 此sql获得的accept及submit包含非竞赛种提交的统计
			 * $sql="SELECT cp.p2p_id cp_id,p2p_to p_id,posts.post_title,meta.accepted,meta.submit
					FROM {$oj->prefix}problem_meta as meta
					RIGHT JOIN {$wpdb->prefix}p2p as cp ON meta.post_id=cp.p2p_to
					INNER JOIN {$wpdb->prefix}posts as posts ON posts.ID=cp.p2p_to
					WHERE p2p_from={$contest_id} AND post_type='problem'
					ORDER BY p2p_id";
				//张老师hustoj启发，不过貌似速度并不快
				$sql="SELECT cp.p2p_id cp_id,p2p_to p_id,posts.post_title,meta.accepted,meta.submit
					FROM (SELECT * FROM {$wpdb->prefix}posts WHERE post_type='problem') as posts
					INNER JOIN {$wpdb->prefix}p2p as cp ON cp.p2p_to=posts.ID
					LEFT JOIN {$oj->prefix}problem_meta as meta ON meta.post_id=posts.ID
					WHERE p2p_from={$contest_id}
					ORDER BY p2p_id";
			 *
			 */
			$sql="SELECT cp.p2p_id cp_id,p2p_to p_id,posts.post_title,meta.accepted,meta.submit
					FROM {$oj->prefix}problem_meta as meta
					RIGHT JOIN {$wpdb->prefix}p2p as cp ON meta.post_id=cp.p2p_to
					INNER JOIN {$wpdb->prefix}posts as posts ON posts.ID=cp.p2p_to
					WHERE p2p_from={$contest_id} AND post_type='problem'
					ORDER BY p2p_id";
		
			
			$cps=$wpdb->get_results($sql);
			$latter=65;
			foreach ($cps as $cp):
				$post_id=$cp->p_id;
				$problem_url=$oju->url('problem').'&pid='.$post_id.'&cpid='.$cp->cp_id;
			?>
				<tr>
					<td><?php echo $post_id;?> <?php echo chr($latter);$latter++;?></td>
					<td><a href="<?php echo $problem_url;?>"><?php echo $cp->post_title;?></a></td>
					<td><?php echo $cp->accepted;?></td>
					<td><?php echo $cp->submit;?></td>
					<td><?php echo get_the_term_list($post_id,'fps_cat','','，');?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
			</table>
			<?php loop_pagination( array( 'prev_text' => __( '&larr; Previous', hybrid_get_textdomain() ), 'next_text' => __( 'Next &rarr;', hybrid_get_textdomain() ) ) ); ?>
	</div><!-- #content -->
	</div>
	<?php do_atomic( 'after_content' ); // retro-fitted_after_content ?>
	
	<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

	<?php get_sidebar( 'secondary' ); // Loads the sidebar-secondary.php template. ?>

	<?php do_atomic( 'close_main' ); // retro-fitted_close_main ?>

<?php get_footer(); // Loads the footer.php template. ?>