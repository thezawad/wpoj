<?php
/**
 * Primary Sidebar Template
 *
 * Displays widgets for the Primary dynamic sidebar if any have been added to the sidebar through the 
 * widgets screen in the admin by the user.  Otherwise, nothing is displayed.
 *
 * @package Retro-fitted
 * @subpackage Template
 */

if ( is_active_sidebar( 'primary' ) ) : ?>

	<?php do_atomic( 'before_sidebar_primary' ); // retro-fitted_before_sidebar_primary ?>

	<div id="sidebar-blog-primary" class="sidebar">

		<?php do_atomic( 'open_sidebar_primary' ); // retro-fitted_open_sidebar_primary ?>

		<?php dynamic_sidebar( 'blog-primary' ); ?>

		<?php do_atomic( 'close_sidebar_primary' ); // retro-fitted_close_sidebar_primary ?>

	</div><!-- #sidebar-primary .aside -->

	<?php do_atomic( 'after_sidebar_primary' ); // retro-fitted_after_sidebar_primary ?>

<?php endif; ?>