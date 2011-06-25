<?php get_header('contest');?>
<div class="content-wrapper hentry clearfix">
	<div id="content" role="main">
	<?php oj_list_statusd($_GET['pid']);?>
	</div><!-- #content -->
</div>
	<?php do_atomic( 'after_content' ); // retro-fitted_after_content ?>
	
	<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

	<?php get_sidebar( 'secondary' ); // Loads the sidebar-secondary.php template. ?>

	<?php do_atomic( 'close_main' ); // retro-fitted_close_main ?>
<?php get_footer(); ?>
