<?php get_header(); ?>
	<?php if(get_field('section')): ?>
	<div class="summary_sidebar">
		<?php while(the_repeater_field('section')): ?>
			<h3><?php the_sub_field('sidebar_header'); ?></h3>
				<?php $charcode = get_sub_field('character'); ?>
				<?php $categories = get_sub_field('category'); ?>
				<?php foreach($categories as $category); ?>
				<p><?php echo $category['title']; ?></p>
				<span data-color="<?php echo $category['color']; ?>">
					<?php echo $charcode; ?>
				</span>
			<?php endwhile; ?>
			</div>
		<?php endif; ?>
	<?php if(get_field('section')): ?>
	<div class="summary_content">
			<div class="summary_wrap_right clearfix">
				<?php while(the_repeater_field('section')): ?>
				<?php $charcode = get_sub_field('character'); ?>
				<?php $categories = get_sub_field('category'); ?>
				<?php foreach($categories as $category); ?>
				<span id="summary_title_icon" data-color="<?php echo $category['color']; ?>">
					<?php echo $charcode; ?>
				</span>
				<h1><?php echo $category['title']; ?></h1>
				<div class="summary_content_wrap">
					<div class=""></div>
					<span class="clearfix">
						<h4 id="left_column_title">Question</h4>
						<h4 id="right_column_title">Your Response</h4>
					</span>
					<span class="summary_list clearfix">
						<p>I express confidence in others' ability to generate and pursue innovative ideas.</p>
						<h3>Strongly Agree</h3>
						<p>I express confidence in others' ability to generate and pursue innovative ideas.</p>
						<h3>Strongly Agree</h3>
						<p>I express confidence in others' ability to generate and pursue innovative ideas.</p>
						<h3>Strongly Agree</h3>
						<p>I express confidence in others' ability to generate and pursue innovative ideas.</p>
						<h3>Strongly Agree</h3>
					</span>
				<?php endwhile; ?>
				</div>
			</div>
	</div>
	<?php endif; ?>
<?php get_footer(); ?>