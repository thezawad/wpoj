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
			<table>
			<thead>
				<tr>
				<th>Problem ID</th><th>Title</th><th>Source</th><th>AC</th><th>Submit</th><th>FPS Category</th><th>Tagged</th>
				</tr>
			</thead>
			<tbody>
			<?php
			get_posts();
			global $wp_query,$oj;
			$oj->current_page="problems";
			$contest_id=$_GET['cid'];
			$ids=p2p_get_connected($contest_id,'from');
			$posts=$wp_query->query(array("post_type" => "problem",'posts_per_page' =>20 ,'post__in'=>$ids));
			foreach ($posts as $post):setup_postdata($post);
			$problem_url=site_url().'?oj=problem&pid='.$post->ID.'&cid='.$_GET['cid'].'&ctitle='.$_GET['ctitle'];
			?>
				<tr>
					<td><?php echo $post->ID;?></td>
					<td><a href="<?php echo $problem_url;?>"><?php the_title(); ?></a></td>
					<td><?php echo $post->source;?></td>
					<td><?php echo $post->accepted;?></td>
					<td><?php echo $post->submit;?></td>
					<td><?php echo get_the_term_list($post->ID,'fps_cat','','，');?></td>
					<td><?php echo get_the_term_list($post->ID,'problem_tag','','，');?></td>
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