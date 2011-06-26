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
	<div class="content-wrapper">
	<div id="content" class="hentry">
		
	<?php oj_list_user_status($_GET['uid']);?>

	</div><!-- #content -->
	</div>
	<?php do_atomic( 'after_content' ); // retro-fitted_after_content ?>
	<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

	<?php get_sidebar( 'secondary' ); // Loads the sidebar-secondary.php template. ?>

	<?php do_atomic( 'close_main' ); // retro-fitted_close_main ?>
<?php get_footer(); // Loads the footer.php template. ?>