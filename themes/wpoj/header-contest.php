<?php
/**
 * Header Template
 *
 * The header template is generally used on every page of your site. Nearly all other templates call it 
 * somewhere near the top of the file. It is used mostly as an opening wrapper, which is closed with the 
 * footer.php file. It also executes key functions needed by the theme, child themes, and plugins. 
 *
 * @package Retro-fitted
 * @subpackage Template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php hybrid_document_title(); ?></title>

<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="all" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); // wp_head ?>

</head>

<body class="<?php hybrid_body_class(); ?>">

	<?php do_atomic( 'open_body' ); // retro-fitted_open_body ?>

	<div id="container">

		<?php do_atomic( 'before_header' ); // retro-fitted_before_header ?>

		<div id="header">

			<?php do_atomic( 'open_header' ); // retro-fitted_open_header ?>

			<div class="wrap">

				<div id="branding">
					<?php hybrid_site_title(); ?>
					<div id="site-description"><span>比赛正在进行：<?php echo $_GET['ctitle'];?></span></div>
				</div><!-- #branding -->

				<?php do_atomic( 'header' ); // retro-fitted_header ?>

			</div><!-- .wrap -->

			<?php do_atomic( 'close_header' ); // retro-fitted_close_header ?>

		</div><!-- #header -->

		<?php do_atomic( 'after_header' ); // retro-fitted_after_header ?>

		<?php get_template_part( 'menu', 'primary' ); // Loads the menu-primary.php template. ?>

		<?php do_atomic( 'before_main' ); // retro-fitted_before_main ?>

		<div id="main">

			<div class="wrap">

			<?php do_atomic( 'open_main' ); // retro-fitted_open_main ?>
			
			<?php oj_list_menu('contest','clearfix');?>
			<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail(); ?>