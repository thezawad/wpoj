<?php
get_header(); ?>

	<div id="content" role="main">
			<table>
			<thead>
				<tr>
				<th>Problem ID</th><th>Title</th><th>Source</th><th>AC</th><th>Submit</th><th>Category</th><th>Tagged</th>
				</tr>
			</thead>
			<tbody>
			<?php
			global $wp_query,$oj_context;
			$current_query=$wp_query;
			$oj_context="problems";
			$posts=$wp_query->query(array("post_type" => "problem",'numberposts' =>20 ,'paged'=> get_query_var( 'paged' ),'orderby'=>'submit'));
			foreach ($posts as $post):setup_postdata($post);?>
				<tr>
					<td><?php echo $post->ID;?></td>
					<td><a href="<?php the_permalink();?>"><?php the_title(); ?></a></td>
					<td><?php echo $post->source;?></td>
					<td><?php echo $post->accepted;?></td>
					<td><?php echo $post->submit;?></td>
					<td><?php echo get_the_term_list($post->ID,'problem_cat','','，');?></td>
					<td><?php echo get_the_term_list($post->ID,'problem_tag','','，');?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
			</table>
			<?php loop_pagination( array( 'prev_text' => __( '&larr; Previous', hybrid_get_textdomain() ), 'next_text' => __( 'Next &rarr;', hybrid_get_textdomain() ) ) ); ?>
	</div><!-- #content -->
	<?php do_atomic( 'after_content' ); // retro-fitted_after_content ?>
	
	<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

	<?php get_sidebar( 'secondary' ); // Loads the sidebar-secondary.php template. ?>

	<?php do_atomic( 'close_main' ); // retro-fitted_close_main ?>
<?php get_footer(); ?>
