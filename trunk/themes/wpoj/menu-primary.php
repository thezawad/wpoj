<?php
/**
 * Primary Menu Template
 *
 * Displays the Primary Menu if it has active menu items.
 *
 * @package Retro-fitted
 * @subpackage Template
 */

if ( has_nav_menu( 'primary' ) ) : ?>

	<?php do_atomic( 'before_menu_primary' ); // retro-fitted_before_menu_primary ?>

	<div id="menu-primary" class="menu-container">

		<div class="wrap">

			<?php do_atomic( 'open_menu_primary' ); // retro-fitted_open_menu_primary ?>
			<div class="menu">
				<ul id="menu-primary-items" class="sf-js-enabled">
					<li id="page-home" class="menu-item"><a href="/">Home</a></li>
					<li id="page-blogs" class="menu-item"><a href="/?oj=blogs">Blogs</a></li>
					<li id="page-problem" class="menu-item"><a href="/?oj=problems">Problems</a></li>
					<li id="page-status" class="menu-item"><a href="/?oj=statusl">Status</a></li>
					<li id="page-faqs" class="menu-item"><a href="/?oj=faqs">F.A.Q.s</a></li>
				</ul>
			</div>

			<?php //wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu', 'menu_class' => '', 'menu_id' => 'menu-primary-items', 'fallback_cb' => '' ) ); ?>

			<?php do_atomic( 'close_menu_primary' ); // retro-fitted_close_menu_primary ?>

		</div>

	</div><!-- #menu-primary .menu-container -->

	<?php do_atomic( 'after_menu_primary' ); // retro-fitted_after_menu_primary ?>

<?php endif; ?>