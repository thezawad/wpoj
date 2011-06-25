<?php
get_header(); ?>
	<div class="content-wrapper hentry clearfix">
	<div id="content" role="main">
			<table class="tb-contests">
			<thead>
				<tr>
				<th>ID</th><th>Name</th><th>Status</th><th>Private</th>
				</tr>
			</thead>
			<tbody>
			<?php
			global $wp_query,$oj;
			$oj->current_page="contests";
			$posts=$wp_query->query(array("post_type" => "contest",'posts_per_page' =>20 ,'paged'=> trim($_GET['paged'])));
			
			foreach ($posts as $post):setup_postdata($post);
			
			$start_time=strtotime($post->start_time);
			$end_time=strtotime($post->end_time);
			$now=time();
			if ($now>$end_time) $status=sprintf('<span class="c-end">Ended@%s</span>',$post->end_time);
			else if ($now<$start_time) $status=sprintf('<span class="c-start">Start@%s</span>',$post->start_time);
			else $status='<span class="c-run">Running</span>';

			if (intval($post->private)==0) $private='<span class="c-pub">Public</span>';
			else $private='<span class="c-pri">Private</span>';
			
			$contest_url=site_url().'?oj=contest-problems&cid='.$post->ID.'&ctitle='.$post->post_title;
			?>
				<tr>
					<td><?php echo $post->ID;?></td>
					<td><a href="<?php echo $contest_url;?>"><?php the_title(); ?></a></td>
					<td><?php echo $status; ?></td>
					<td><?php echo $private;?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
			</table>
			<?php loop_pagination( array( 'prev_text' => __( '&larr; Previous', hybrid_get_textdomain() ), 'next_text' => __( 'Next &rarr;', hybrid_get_textdomain() ) ) ); ?>
	</div><!-- #content -->
	</div>
	<?php do_atomic( 'after_content' ); // retro-fitted_after_content ?>
	<?php do_atomic( 'close_main' ); // retro-fitted_close_main ?>
<?php get_footer(); ?>
