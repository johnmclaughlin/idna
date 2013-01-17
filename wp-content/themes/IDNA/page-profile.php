<?php get_header(); ?>
<div class="flexslider" id="assessment-slider">
	<a class="flex-prev">Prev</a>
	<a class="flex-next">Next</a>
	<ul class="slides">
			<li>
			<div class="heading-test clearfix">
				<div class="heading-left">
					<h1><?php the_title(); ?></h1>
				</div>
			</div>
			<div class="profile_left clearfix">
				<h1><?php the_field('profile_heading'); ?></h1>
				<h2><?php the_field('profile_subheader'); ?></h2>
				<p><?php the_field('profile_textarea'); ?></p>
				<?php if(get_field('profile_averages')): ?>
					<h3><?php the_field('profile_title'); ?></h3>
					<?php while(the_repeater_field('profile_averages')): ?>
						<div class="profile_score_wrap clearfix">
							<p class="profile_score"><?php the_sub_field('score'); ?></p>
							<div class="profile_score_text_wrap">
								<p><?php the_sub_field('category'); ?></p>
								<a href="<?php the_sub_field('category_link'); ?>">
									<?php the_sub_field('category_link_title'); ?>
								</a>
							</div>
						</div><!-- .profile_score_wrap -->
					<?php endwhile; ?>
				<?php endif; ?>			
			</div>
			<div class="profile_right">
				<p><?php the_field('profile_textarea_right'); ?></p>
				<?php if(get_field('profile_graph')): ?>
					<?php while(the_repeater_field('profile_graph')): ?>
					<?php $profilegraph = wp_get_attachment_image_src(get_sub_field('profile_graph_image'), 'profilegraph'); ?>
					<img src="<?php echo $profilegraph[0]; ?>" width="<?php echo $profilegraph[1]; ?>" height="<?php echo $profilegraph[2]; ?>" alt=""/>
					<?php endwhile; ?>
				<?php endif; ?>
			</div>
		</li>
	</ul>
	</div>
<?php get_footer(); ?>