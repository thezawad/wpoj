<?php
/**
 * Comments Template
 *
 * Lists comments and calls the comment form.  Individual comments have their own templates.  The 
 * hierarchy for these templates is $comment_type.php, comment.php.
 *
 * @package Retro-fitted
 * @subpackage Template
 */

/* Kill the page if trying to access this template directly. */
if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
	die( __( 'Please do not load this page directly. Thanks!', hybrid_get_textdomain() ) );
	
function oj_clarication_redirect(){
	echo '<input type="hidden" name="redirect_to" value="'.$_SERVER['HTTP_REFERER'].'"/>';
}
add_action('comment_form_top', 'oj_clarication_redirect');

?>

<div id="comments-template">

	<div class="comments-wrap">

		<div id="comments">

			<?php if ( have_comments() ) : ?>

				<h3 id="comments-number" class="comments-header"><?php comments_number( __( 'No Responses', hybrid_get_textdomain() ), __( 'One Response', hybrid_get_textdomain() ), __( '% Responses', hybrid_get_textdomain() ) ); ?></h3>

				<?php do_atomic( 'before_comment_list' );// retro-fitted_before_comment_list ?>

				<?php if ( get_option( 'page_comments' ) ) : ?>
					<div class="comments-nav">
						<span class="page-numbers"><?php printf( __( 'Page %1$s of %2$s', hybrid_get_textdomain() ), ( get_query_var( 'cpage' ) ? absint( get_query_var( 'cpage' ) ) : 1 ), get_comment_pages_count() ); ?></span>
						<?php previous_comments_link(); ?>
						<?php next_comments_link(); ?>
					</div><!-- .comments-nav -->
				<?php endif; ?>

				<ol class="comment-list">
					<?php wp_list_comments( hybrid_list_comments_args() ); ?>
				</ol><!-- .comment-list -->

				<?php do_atomic( 'after_comment_list' ); // retro-fitted_after_comment_list ?>

			<?php endif; ?>

			<?php if ( pings_open() && !comments_open() ) : ?>

				<p class="comments-closed pings-open">
					<?php printf( __( 'Comments are closed, but <a href="%1$s" title="Trackback URL for this post">trackbacks</a> and pingbacks are open.', hybrid_get_textdomain() ), get_trackback_url() ); ?>
				</p><!-- .comments-closed .pings-open -->

			<?php elseif ( !comments_open() ) : ?>

				<p class="comments-closed">
					<?php _e( 'Comments are closed.', hybrid_get_textdomain() ); ?>
				</p><!-- .comments-closed -->

			<?php endif; ?>

		</div><!-- #comments -->

		<?php comment_form(); // Loads the comment form. ?>

	</div><!-- .comments-wrap -->

</div><!-- #comments-template -->