<?php get_header(); ?>
<div class="top_nav clearfix">

	<?php
	$pagelist = get_pages('sort_column=menu_order&sort_order=asc');
	$pages = array();
	foreach ($pagelist as $page) {
	   $pages[] += $page->ID;
	}

	$current = array_search(get_the_ID(), $pages);
	$prevID = $pages[$current-1];
	$nextID = $pages[$current+1];
	?>

	<?php if (!empty($prevID)) { ?>
	<a class="top-prev" href="<?php echo get_permalink($prevID); ?>"
	  title="<?php echo get_the_title($prevID); ?>">&laquo; Back: </a>
	<?php }
	if (!empty($nextID)) { ?>
	<a class="top-next" href="<?php echo get_permalink($nextID); ?>" 
	 title="<?php echo get_the_title($nextID); ?>">Next:  &raquo;</a>
	<?php } ?>
</div>
<div class="flexslider">
	<a class="flex-prev">Prev</a>
	<a class="flex-next">Next</a>
	<ul class="slides">
		<?php if(get_field('skills')): ?>
			<?php while(the_repeater_field('skills')): ?>
			<li>
			<div class="heading-test clearfix">
				<div class="heading-left">
					<h1><?php the_title(); ?></h1>
				</div>
				<div class="heading-right">
					<?php if(get_sub_field('header_icons')): ?>
						<div class="disc_icon">
							<?php while(the_repeater_field('header_icons')): ?>
								<?php $icons = wp_get_attachment_image_src(get_sub_field('icon'), 'icon'); ?>
								<img src="<?php echo $icons[0]; ?>" width="<?php echo $icons[1]; ?>" height="<?php echo $icons[2]; ?>" />
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if(get_sub_field('scores')): ?>
			<div class="discovery_main clearfix">
				<?php $count=1; ?>
				<a href="" class="reset_anchor">Reset</a>
				<?php while(the_repeater_field('scores')): ?>
					<?php // Calc score %
					$raw_score = get_sub_field('disc_h1');
					$percentage = ($raw_score / 7) * 100;
					?>
					<a href="#disc_link_<?php echo $count; ?>" class="disc_scores">
						<?php $charactercode = get_sub_field('character_code'); ?>
						<p id="score_<?php echo $count; ?>" data-color="<?php the_sub_field('color_picker'); ?>" data-size="<?php echo $percentage; ?>" class="icon-circle score_circles">
							<?php echo $charactercode; ?>
						</p>
						<h3>
							<?php the_sub_field('title'); ?>
						</h3>
					</a>
				<?php $count++; ?>
				<?php endwhile; ?>
			</div>
			<?php endif; ?>
			<?php if(get_sub_field('lower_content')): ?>
				<div class="callouts_scroller">
					<a id="prevrep">Previous</a>
					<a id="nextrep">Next</a>
					<?php $count=1; ?>
					<ul id="report_nav" class="clearfix">
						<?php while(the_repeater_field('lower_content')): ?>
							<li id="disc_link_<?php echo $count; ?>" class="disc_wrapper clearfix">
								<?php while(the_repeater_field('slides')): ?>
									<div class="disc_callouts clearfix">
										<h1><?php the_sub_field('heading'); ?></h1>
										<div id="chart_<?php echo $count; ?>" class="chart">

										</div>
										<p><?php the_sub_field('paragraph_text'); ?></p>
									</div>
								<?php endwhile; ?>
							</li>
						<?php $count++; ?>
						<?php endwhile; ?>
					</ul>
				</div><!-- .callouts_scroller -->					
			<?php endif;?>
		</li>
		<?php endwhile; endif; ?>
	</ul>
	</div>
<?php get_footer(); ?>