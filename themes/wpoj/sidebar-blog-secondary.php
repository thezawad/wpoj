<?php
/**
 * Secondary Sidebar Template
 *
 * Displays widgets for the Secondary dynamic sidebar if any have been added to the sidebar through the 
 * widgets screen in the admin by the user.  Otherwise, nothing is displayed.
 *
 * @package Retro-fitted
 * @subpackage Template
 */

if ( is_active_sidebar( 'secondary' ) ) : ?>

	<?php do_atomic( 'before_sidebar_secondary' ); // retro-fitted_before_sidebar_secondary ?>

	<div id="sidebar-blog-secondary" class="sidebar">

		<?php do_atomic( 'open_sidebar_secondary' ); // retro-fitted_open_sidebar_secondary ?>

		<?php dynamic_sidebar( 'blog-secondary' ); ?>

		<?php do_atomic( 'close_sidebar_secondary' ); // retro-fitted_close_sidebar_secondary ?>

	</div><!-- #sidebar-secondary .aside -->

	<?php do_atomic( 'after_sidebar_secondary' ); // retro-fitted_after_sidebar_secondary ?>

<?php endif; ?>