<?php
/**
 * Template Name: problem-list-template
 *
 * A list page of custom post type
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

<div id="container">
<div id="content" role="main">
	<table>
	<thead>
		<tr>
		<th>Problem ID</th><th>Title</th><th>Source</th><th>AC</th><th>Submit</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	$posts=get_posts(array("post_type" => "problem",'numberposts' =>20));
	foreach ($posts as $post):setup_postdata($post); $post=oj_fill_object_metas($post);?>
		<tr>
			<td><?php echo $post->ID;?></td>
			<td><a href="<?php the_permalink();?>"><?php the_title(); ?></a></td>
			<td><?php echo $post->source;?></td>
			<td><?php echo $post->accepted;?></td>
			<td><?php echo $post->submit;?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
	</table>
</div><!-- #content -->
</div><!-- #container -->
<?php get_footer(); ?>
