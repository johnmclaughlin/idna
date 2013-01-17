<?php get_header(); ?>
<div class="flexslider" id="assessment-slider">
	<a class="flex-prev">Prev</a>
	<a class="flex-next">Next</a>
	<ul class="slides">
		<li>
			<div class="intro_left">
				
			</div>	
			<div class="intro_right">
				<h1><?php the_field('intro_header'); ?></h1>
				<p><?php the_field('intro_copy'); ?></p>
			</div>	
		</li>
	</ul>
	</div>
<?php get_footer(); ?>