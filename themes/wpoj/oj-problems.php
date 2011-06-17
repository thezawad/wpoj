<?php
get_header(); 
global $wp_query,$oj;
$oj->current_page="problems";
if(empty($_GET['s'])){
	$posts=$wp_query->query(array("post_type" => "problem",'posts_per_page' =>20 ,'paged'=> trim($_GET['paged'])));
}else{
	$posts=$wp_query->query(array("post_type" => "problem",'posts_per_page' =>20 ,'s'=>$_GET['s']));
}
get_posts();
?>
	<div class="content-wrapper hentry clearfix">
	<div id="content" role="main">
			<div id="problems-filters">
				<div class="widget search widget-search"><div class="widget-wrap widget-inside"><h3 class="widget-title">Search Problems</h3><form id="search-form" class="search-form" method="get"><div><input type="hidden" name="oj" value="problems"/><label for="search-text">Search Problems</label><input type="text" onblur="if(this.value=='')this.value=this.defaultValue;" onfocus="if(this.value==this.defaultValue)this.value='';" value="asdf" id="search-text" name="s" class="search-text"></div></form><!-- .search-form --></div></div>
				<?php loop_pagination( array('show_all' => true ,'prev_next' => false) ); ?>
			</div>
			<table>
			<thead>
				<tr>
				<th>Problem ID</th><th>Title</th><th>Source</th><th>AC</th><th>Submit</th><th>FPS Category</th><th>Tagged</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ($posts as $post):setup_postdata($post);
				$problem_url=site_url().'?oj=problem&pid='.$post->ID;
			?>
				<tr>
					<td><?php echo $post->ID;?></td>
					<td><a href="<?php echo $problem_url;?>"><?php the_title(); ?></a></td>
					<td><?php echo $post->source;?></td>
					<td><?php echo $post->accepted;?></td>
					<td><?php echo $post->submit;?></td>
					<td><?php echo get_the_term_list($post->ID,'fps_cat','','，');?></td>
					<td><?php echo get_the_term_list($post->ID,'problem_tag','','，');?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
			</table>
			<?php loop_pagination( array('show_all' => true ,'prev_next' => false) ); ?>
	</div><!-- #content -->
	</div>
	<?php do_atomic( 'after_content' ); // retro-fitted_after_content ?>
	
	<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

	<?php get_sidebar( 'secondary' ); // Loads the sidebar-secondary.php template. ?>

	<?php do_atomic( 'close_main' ); // retro-fitted_close_main ?>
<?php get_footer(); ?>
