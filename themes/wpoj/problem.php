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

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // retro-fitted_before_content ?>
	<div class="content-wrapper">
	<div id="content">

		<?php get_sidebar( 'before-content' ); // Loads the sidebar-before-content.php template. ?>

		<?php do_atomic( 'open_content' ); // retro-fitted_open_content ?>

		<div class="hfeed">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); $post=oj_fill_object_metas($post)?>

					<?php do_atomic( 'before_entry' ); // retro-fitted_before_entry ?>

					<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">
					<?php 
						global $oj,$oju;
						$url_common_parm='&title='.$post->post_title.'&pid='.$post->ID;
						$url_submit=$oju->url('submitpage').$url_common_parm.'&language='.$_GET['language'];
						$url_status=$oju->url('statusl').$url_common_parm;
					?>
						<div class='problem-feature problem-feature-top'>
							<a class="feature f-submit" href="<?php echo $url_submit;?>">Submit</a>
							<a class="feature f-status" href="<?php echo $url_status;?>">Status</a>
						</div>
						<?php do_atomic( 'open_entry' ); // retro-fitted_open_entry ?>

						<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>

						<?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __( 'By [entry-author] on [entry-published] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</div>' ); ?>
						<div class="problem-section-title"><h3><span class="inner">Description</span></h3></div>
						<div class="entry-content">
							<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', hybrid_get_textdomain() ) ); ?>
							<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', hybrid_get_textdomain() ), 'after' => '</p>' ) ); ?>
						</div><!-- .entry-content -->
						<div class="problem-section-title"><h3><span class="inner">Input</span></h3></div>
						<div class="problem-section-content"><?php echo $post->input;?></div>
						<div class="problem-section-title"><h3><span class="inner">Ouput</span></h3></div>
						<div class="problem-section-content"><?php echo $post->output;?></div>
						<div class="problem-section-title"><h3><span class="inner">Sample Input</span></h3></div>
						<div class="problem-section-content"><?php echo $post->sample_input;?></div>
						<div class="problem-section-title"><h3><span class="inner">Sample output</span></h3></div>
						<div class="problem-section-content"><?php echo $post->sample_output;?></div>
						<?php do_atomic( 'close_entry' ); // retro-fitted_close_entry ?>
						<div class='problem-feature problem-feature-bottom'>
							<a class="feature f-submit" href="<?php echo $url_submit;?>">Submit</a>
							<a class="feature f-status" href="<?php echo $url_status;?>">Status</a>
						</div>
					</div><!-- .hentry -->
					<?php do_atomic( 'after_entry' ); // retro-fitted_after_entry ?>

					<?php get_sidebar( 'after-singular' ); // Loads the sidebar-after-singular.php template. ?>

					<?php do_atomic( 'after_singular' ); // retro-fitted_after_singular ?>

					<?php //comments_template( '/comments.php', true ); // Loads the comments.php template. ?>
					

				<?php endwhile; ?>

			<?php endif; ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // retro-fitted_close_content ?>

		<?php get_sidebar( 'after-content' ); // Loads the sidebar-after-content.php template. ?>

		<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template. ?>

	</div><!-- #content -->
	</div>
	<?php do_atomic( 'after_content' ); // retro-fitted_after_content ?>
	
	<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

	<?php get_sidebar( 'secondary' ); // Loads the sidebar-secondary.php template. ?>

	<?php do_atomic( 'close_main' ); // retro-fitted_close_main ?>

<?php get_footer(); // Loads the footer.php template. ?>