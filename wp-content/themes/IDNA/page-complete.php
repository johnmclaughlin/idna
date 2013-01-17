<?php get_header(); ?>
<div class="flexslider" id="assessment-slider">
	<ul class="slides">
			<li>
			<div class="heading-test clearfix">
				<div class="heading-left">
					<h1 class="top-header"><?php the_title(); ?></h1>
				</div>
			</div>
			<div class="page_left clearfix">
				<h1><?php the_field('page_header'); ?></h1>
				<p><?php the_field('page_textarea'); ?></p>
				<a href="<?php the_field('page_url'); ?>"><?php the_field('page_url_text') ?></a>
			</div>
			<div class="page_right">
				<?php if(get_field('question_results')): ?>
					<?php while(the_repeater_field('question_results')): ?>
						<div class"question_wrap">
							<h2><?php the_sub_field('question_count'); ?></h2>
							<h3><?php the_sub_field('question_value'); ?></h3>
						</div>
				<?php endwhile; endif; ?>
				<a href="<?php the_field('process_url'); ?>">
					<?php the_field('process_url_text'); ?>
				</a>
			</div>
		</li>
	</ul>
	</div>
<?php get_footer(); ?>