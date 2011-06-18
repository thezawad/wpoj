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
get_header();
 // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // retro-fitted_before_content ?>
	<div class="content-wrapper">
	<div id="content" class="hentry">

	<?php 
	global $oj,$wpdb,$oju;
	$sql="SELECT user_id,user_login,submit,solved,solved/submit as ratio FROM {$oj->prefix}users_meta ORDER BY solved , ratio DESC";
	$users=$wpdb->get_results($sql);
	$number=0;
	?>	
	<table class="tb-statusl">
		<tr><th>NO.</th><th>User</th><th>Nick Name</th><th>AC</th><th>Submit</th><th>Ration</th></tr>
		<?php foreach ($users as $user){$number++;?>
		<tr>
		<td class="AC"><?php echo $number;?></td>
		<td><a href="<?php echo site_url().'/?author='.$user->user_id;?>"><?php echo $user->user_login;?></a></td>
		<td><?php echo get_user_meta($user->user_id, 'nickname',true);?></td>
		<td><?php echo $user->solved;?></td>
		<td><?php echo $user->submit;?></td>
		<td><?php printf("%.03lf%%",100*$user->ratio);?></td>
		</tr>
		<?php }?>
	</table>
	</div><!-- #content -->
	</div>
	<?php do_atomic( 'after_content' ); // retro-fitted_after_content ?>
	
	<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

	<?php get_sidebar( 'secondary' ); // Loads the sidebar-secondary.php template. ?>

	<?php do_atomic( 'close_main' ); // retro-fitted_close_main ?>

<?php get_footer(); // Loads the footer.php template. ?>