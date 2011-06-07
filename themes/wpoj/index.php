<?php
/**
 * Index Template
 *
 * This is the default template.  It is used when a more specific template can't be found to display
 * posts.  It is unlikely that this template will ever be used, but there may be rare cases.
 *
 * @package Retro-fitted
 * @subpackage Template
 */

get_header(); // Loads the header.php template. ?>
<link type="text/css" rel="stylesheet" href="http://acm.hust.edu.cn/blog/wp-content/themes/boldy/css/nivo-slider.css"/>
<script type="text/javascript" src="http://acm.hust.edu.cn/blog/wp-content/themes/boldy/js/jquery.nivo.slider.pack.js"></script>
<div class="home-banner">
	<div id="slider">
	  <a href="http://acm.hust.edu.cn/blog/?page_id=486"> <img class="alignnone size-full wp-image-501" title="HUST ACM/ICPC TEAM常年招新，点击图片查看详情！" src="http://acm.hust.edu.cn/blog/wp-content/uploads/2011/03/JoinAD.jpg" alt="" width="960" height="370" /></a>
	  <img class="alignnone size-full wp-image-503" title="HUST Erbao队在杭州赛区夺得第6，将代表集训队出征World Final2011，祝贺他们！ " src="http://acm.hust.edu.cn/blog/wp-content/uploads/2011/03/TeamAD.jpg" alt="" width="960" height="370" />		
	</div>
	<div class="shadow"></div>
</div>
<div class="home-content clear">
	<div class="hentry widgets">
		<?php wp_list_bookmarks();?>
	</div>
	<div class="hentry news">
		<h2>News</h2>
		<ul>
		<?php while(have_posts()):the_post();?>
		<li>[<?php echo get_the_category_list(' ,'); ?>] <a href="<?php the_permalink(); ?>"> <?php the_title();?></a></li>
		<?php endwhile;?>
		</ul>
	</div>
</div>
<?php wp_list_pages(array('post_type'=>'problem'));?>


<script type="text/javascript">
jQuery(window).load(function() {
	jQuery('#slider').nivoSlider({
		effect:'random', //Specify sets like: 'fold,fade,sliceDown'
		slices:15,
		animSpeed:500,
		pauseTime:3000,
		startSlide:0, //Set starting Slide (0 index)
		directionNav:true, //Next & Prev
		directionNavHide:true, //Only show on hover
		controlNav:true, //1,2,3...
		controlNavThumbs:false, //Use thumbnails for Control Nav
		controlNavThumbsFromRel:false, //Use image rel for thumbs
		controlNavThumbsSearch: '.jpg', //Replace this with...
		controlNavThumbsReplace: '_thumb.jpg', //...this in thumb Image src
		keyboardNav:true, //Use left & right arrows
		pauseOnHover:true, //Stop animation while hovering
		manualAdvance:false, //Force manual transitions
		captionOpacity:0.8, //Universal caption opacity
		beforeChange: function(){},
		afterChange: function(){},
		slideshowEnd: function(){} //Triggers after all slides have been shown
	});
});
</script>
<?php get_footer(); // Loads the footer.php template. ?>