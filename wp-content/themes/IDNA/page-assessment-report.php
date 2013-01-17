<?php get_header(); ?>
<?php
if (!is_user_logged_in()) {
	wp_login_form();
} else { ?>
<div class="flexslider" id="report-slider">
<?php /*<a class="flex-prev">Prev</a>
<a class="flex-next">Next</a> */ ?>
	<ul class="slides clearfix">
<?php
// Grab current WP user ID
$current_user = wp_get_current_user();
$user_id = $current_user->ID; 

// Search for a completed assessment
$pid = $_GET['pid'];
global $wpdb;
$current_assessment = $wpdb->get_results("SELECT * FROM reports JOIN profiles ON reports.profile_id = profiles.profile_id WHERE reports.pid = '$pid'");

if(count($current_assessment) > 0){
    $assessment = $current_assessment[0];
    $assessment_json = json_encode($assessment);
	   //print_r($assessment);

?>
<script>
	var assessment = <?php echo $assessment_json; ?>;
</script>

<?php
    $demos = mysql_query("SELECT demo FROM reports WHERE pid = '$pid'") or die(mysql_error());

	while($d = mysql_fetch_assoc($demos)) {

		$demo_json = $d['demo'];


	    $demo = json_decode($d['demo']);

	}
?>
<script>
	var demographics = <?php echo $demo_json; ?>;
</script>
<li>
	<!-- INTRO PAGE -->
	<?php $intro_args = array(
		'post_type' => 'page',
		'post__in' => array(117),
		'orderby' => 'menu_order',
		'order' => 'ASC'
	); ?>
	<?php $intro_query = new WP_Query( $intro_args ); ?>
	<?php if($intro_query->have_posts()): ?>
		<?php while($intro_query->have_posts()): $intro_query->the_post(); ?>
			<div class="intro_left">
						
			</div>	
			<div class="intro_right">
				<h1><?php the_field('intro_header'); ?></h1>
					<?php the_field('intro_copy'); ?>
			</div>	
		<?php endwhile; ?>
	<?php endif; ?>
	<!-- END OF INTRO PAGE -->
</li>



<?php 	$characteristic_args = array(
		'page_id' => 122 
	);
$characteristic_query = new WP_Query($characteristic_args);
while($characteristic_query->have_posts()): $characteristic_query->the_post();
?>
<li>
	<div class="heading-test clearfix">
		<div class="heading_links clearfix">
			<?php if(get_field('heading_nav_left')): ?>
				<a id="heading_nav_left" class="heading-prev">&laquo; Back: <?php the_field('heading_nav_left'); ?></a>
			<?php endif; ?>
			<?php if(get_field('heading_nav_left')): ?>
				<a id="heading_nav_right" class="heading-next">Next: <?php the_field('heading_nav_right'); ?> &raquo;</a>
			<?php endif; ?>
		</div>
		
		<div class="heading-left">
			<h1><?php the_title(); ?></h1>
		</div>
	</div>
	<div class="characteristic_left">
		<h1><?php the_field('left_header'); ?></h1>
			<?php the_field('left_copy'); ?>
		<a href="<?php the_field('left_url'); ?>">
			<?php the_field('left_url_title'); ?>
		</a>
	</div>
	<?php if(get_field('development_repeater')): ?>
		<?php $characteristic_count = 3; ?>
		<div class="characteristic_wrap_right">
			<?php while(the_repeater_field('development_repeater')): ?>
				<div class="characteristic_right">
					<?php $development_media = wp_get_attachment_image_src(get_sub_field('development_media'), 'development_media'); ?>
					<h2><?php the_sub_field('right_header'); ?></h2>
					<!--
					<a class="char_box" rel="char_box" target='_top' href="<?php // the_sub_field('youtube_video'); ?>" class="overlay"><img src="<?php // echo $development_media[0]; ?>" width="<?php // echo $development_media[1]; ?>" height="<?php // echo $development_media[2]; ?>" alt="" /></a>
					-->
					<div class="development_right_text">
							<?php the_sub_field('right_copy'); ?>
						<a class="characteristic_nav" data-move="<?php echo $characteristic_count; ?>">
							<?php the_sub_field('right_url_title'); ?>
						</a>
					</div>
				</div>
				<?php $characteristic_count++; ?>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>
</li>
<?php endwhile; ?>

<?php 	$profile_args = array(
		'page_id' => 93
	);
$profile_query = new WP_Query($profile_args);
while($profile_query->have_posts()): $profile_query->the_post();
?>
<li>
	<div class="heading-test clearfix">
		<div class="heading_links clearfix">
			<?php if(get_field('heading_nav_left')): ?>
				<a id="heading_nav_left" class="heading-prev">&laquo; Back: <?php the_field('heading_nav_left'); ?></a>
			<?php endif; ?>
			<?php if(get_field('heading_nav_left')): ?>
				<a id="heading_nav_right" class="heading-next">Next: <?php the_field('heading_nav_right'); ?> &raquo;</a>
			<?php endif; ?>
		</div>

		<div class="heading-left">
			<h1><?php the_title(); ?></h1>
		</div>
	</div>
	<div class="profile_left clearfix">

		<?php
		$profile = $assessment->profile_id;
		$x = $assessment->c_percentile;
		$y = $assessment->b_percentile;
		?>
		<h1><?php echo $assessment->profile_name; ?></h1>
		<h2><?php echo $assessment->profile_title; ?></h2>
		<p><?php echo $assessment->profile_description; ?></p>
		<?php if(get_field('profile_averages')): ?>
			<h3><?php the_field('profile_title'); ?></h3>
			<div class="profile_scores_wrapper clearfix">
			<div class="profile_score_wrap clearfix">
				<p class="profile_score"><?php echo $assessment->a; ?></p>
				<div class="profile_score_text_wrap">
					<p>Courage To Innovate</p>
					<a id="skip-courage" href="<?php //the_sub_field('category_link'); ?>">
						Detail
					</a>
				</div>
			</div><!-- .profile_score_wrap -->
			<div class="profile_score_wrap clearfix">
				<p class="profile_score"><?php echo $assessment->b; ?></p>
				<div class="profile_score_text_wrap">
					<p>Discovery Skills</p>
					<a id="skip-discovery" href="<?php the_sub_field('category_link'); ?>">
						Detail
					</a>
				</div>
			</div><!-- .profile_score_wrap -->
			<div class="profile_score_wrap clearfix">
				<p class="profile_score"><?php echo $assessment->c; ?></p>
				<div class="profile_score_text_wrap">
					<p>Delivery Skills</p>
					<a id="skip-delivery" href="<?php the_sub_field('category_link'); ?>">
						Detail
					</a>
				</div>
			</div><!-- .profile_score_wrap -->
			</div><!-- .profile_scores_wrapper -->
		<?php endif; ?>			
	</div>
	<div class="profile_right">
			<p class="graph-intro"><?php the_field('profile_textarea_right'); ?></p>
			<!--
			<a id="dem_toggle" href="">Compare</a>
			-->
			<div class="chart-wrap clearfix">
				<div class="demographic_wrap clearfix">
					<h4>Compare your score with averages from your peers</h4>
					<ul>
						<li><p>Geographical Averages</p></li>
						<li><a href="">Asia</a></li>
						<li><a href="">Europe</a></li>
						<li><a href="">N. America</a></li>
						<li><a href="">S. America</a></li>
						<li><a href="">Africa</a></li>
					</ul>
					<ul>
						<li><p>Demographic Averages</p></li>
						<li><a href="">20-35 yrs</a></li>
						<li><a href="">35-50 yrs</a></li>
						<li><a href="">50-65 yrs</a></li>
						<li><a href="">66+ yrs</a></li>
					</ul>
					<ul>
						<li><p>Industry Averages</p></li>
						<li><a href="">Finance</a></li>
						<li><a href="">Engineering</a></li>
						<li><a href="">Design</a></li>
						<li><a href="">Business</a></li>
					</ul>
				</div>
			
				<div id="profile_container"></div>
			</div>
		
	</div>
</li>
<?php endwhile; ?>

<!-- $$$$$$$ REPORT PAGES $$$$$$$ -->

<?php $report_args = array(
		'post_type' => 'page',
		'post__in' => array(255, 145, 80, 291, 308),
		'orderby' => 'menu_order',
		'order' => 'ASC'
); ?>
<?php $report_query = new WP_Query( $report_args ); ?>
<?php $chart_count = 1; ?>
	<?php if($report_query->have_posts()): ?>
	<?php while($report_query->have_posts()): $report_query->the_post(); ?>
	<?php global $post; ?>
	<?php $charactercode = get_field('character_code'); ?>
		<li>
			<div class="heading-test clearfix">
				<div class="heading_links clearfix">
					<?php if(get_field('heading_nav_left')): ?>
						<a id="heading_nav_left" class="heading-prev">&laquo; Back: <?php the_field('heading_nav_left'); ?></a>
					<?php endif; ?>
					<?php if(get_field('heading_nav_left')): ?>
						<a id="heading_nav_right" class="heading-next">Next: <?php the_field('heading_nav_right'); ?> &raquo;</a>
					<?php endif; ?>
				</div>
				<div class="heading-left">
					<h1 class="header-w-cycle"><?php the_title(); ?></h1>
				</div>
				<div class="heading-right">
					<?php if(get_field('header_icons')): ?>
						<div class="disc_icon">
							<?php //<span class="addl-media">Additional Media &rarr;</span> ?>
							<?php while(the_repeater_field('header_icons')): ?>
								<?php $icons = wp_get_attachment_image_src(get_sub_field('icon'), 'icon'); ?>
								<img src="<?php echo $icons[0]; ?>" width="<?php echo $icons[1]; ?>" height="<?php echo $icons[2]; ?>" />
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if(get_field('score_categories')): ?>
			<div class="discovery_main clearfix">
				<?php $count=1;
					$score_cats = get_field('score_categories');
					$score_cat_count = count($score_cats);

				?>
				<a href="" class="reset_anchor">Reset</a>
				<?php while(the_repeater_field('score_categories')): ?>
					<?php // Calc score %		
					if(308 == $post->ID){
						switch ($count){
							case 1:
								$charactercode = 'a';
								break;
							case 2:
								$charactercode = 'b';
								break;
							case 3:
								$charactercode = 'c';
								break;
						}
					} 
					$array_key = $charactercode . $count;
					$raw_score = $assessment->$array_key;
					$percentage = ($raw_score / 7) * 134;
					?>
					<a <?php /*href="#disc_link_<?php echo $charactercode; ?>_<?php echo $count; ?>"*/?> class="disc_scores">
						<h2><?php echo $raw_score; ?></h2>
						<p id="score_<?php echo $charactercode; ?>_<?php echo $count; ?>" data-color="<?php the_sub_field('color'); ?>" data-size="<?php echo $percentage; ?>" data-char="<?php echo $charactercode; ?>" class="icon-circle score_circles">
							<?php echo $charactercode; ?>
						</p>
						<h3>
							<?php the_sub_field('title'); ?>
						</h3>
						<?php if($count < $score_cat_count){ ?>
						<span class="sep">+</span>
					<?php } ?>
					</a>
					

					<?php $subcattitle = get_sub_field('title'); 
						  $subcattitle = strtolower($subcattitle);
						  $subcattitle = str_replace(' ','-',$subcattitle); ?>
				<?php $count++; ?>
				<?php endwhile; ?>
			</div>
			<?php endif; ?>
			<?php if(get_field('score_categories')): ?>
				<div class="callouts_scroller">
					<?php if(308 != $post->ID): ?>
					<a id="prevrep_<?php echo $charactercode; ?>" class="callout_prev">Previous</a>
					<a id="nextrep_<?php echo $charactercode; ?>" class="callout_next">Next</a>
					
					<?php $count=1; ?>
					<ul id="report_nav_<?php echo $charactercode; ?>" class="clearfix report_nav">
						<li id="disc_link_<?php echo $charactercode; ?>_<?php echo $count; ?>" class="disc_wrapper clearfix">								
							<div class="disc_callouts clearfix">
								<h1><?php the_title(); ?></h1>
								<?php
									$chart_name = 'chart' . $chart_count;
									if('chart1' == $chart_name){
										$chart_name = 'chart';
									}
								?>
								<div id="chart_<?php echo $charactercode; ?>_<?php echo $count; ?>" class="chart" data-chart="<?php echo $chart_name; ?>">

								</div>
								<a class="graph-filter-toggle">Compare Results</a>
								<?php $chart_count++; ?>
								<div class="demographic_wrap clearfix cat-demo-group" data-section="d_<?php echo $charactercode; ?>">
									<h4>Compare your score with averages from your peers</h4>
									<a class="close">Close</a>
									<form method="get" action="<?php bloginfo('url'); ?>/report_3.php" class="demo-filter-form">
									<div class="demo_age_wrap">
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>">Age</label>
										<div class="custom-checkbox">
										<input type="checkbox" name="age[]" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_0" value="a1" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_0">18-25 yrs</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="age[]" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_1" value="a2" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_1">26-35 yrs</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="age[]" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_2" value="a3" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_2">36-45 yrs</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="age[]" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_3" value="a4" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_3">46-55 yrs</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="age[]" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_4" value="a5" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_4">56-59 yrs</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="age[]" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_5" value="a6" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_5">65+ yrs</label>
										</div>
									</div>
									<div class="demo_geo_wrap">
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>">Geographic</label>
										<div class="custom-checkbox">
										<input type="checkbox" name="geography[]" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_6" value="g1" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_6">Africa</label>
										</div>
									<div class="custom-checkbox">
										<input type="checkbox" name="geography[]" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_7" value="g2" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_7">Asia</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="geography[]" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_8" value="g3" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_8">Europe</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="geography[]" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_9" value="g4" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_9">North America</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="geography[]" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_10" value="g5" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_10">South America</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="geography[]" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_11" value="g6" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_11">Oceania</label>
										</div>
									</div>
									<div class="demo_industry_wrap">
										<label for="industry_<?php echo $charactercode; ?>_<?php echo $count; ?>">Industry</label>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_12" value="i1" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_12">Aerospace & Defense</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_13" value="i2" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_13">Automotive</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_14" value="i3" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_14">Business Services</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_15" value="i4" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_15">Construction</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_16" value="i5" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_16">Consumer Products</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_17" value="i6" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_17">Education</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_18" value="i7" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_18">Energy</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_19" value="i8" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_19">Financial Services</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_20" value="i9" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_20">Food/Accomodations</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_21" value="i10" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_21">Government</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_22" value="i11" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_22">Health Care</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_23" value="i12" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_23">Information</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_24" value="i13" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_24">Manufacturing</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_25" value="i14" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_25">Non Governmental</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_26" value="i15" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_26">Non Profit Organization</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_27" value="i16" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_27">Other</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_28" value="i17" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_28">Pharmaceuticals</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_29" value="i18" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_29">Transportation</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_30" value="i19" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_30">Utilities</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_31" value="i20" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_31">Wholesale/Retail</label>
										</div>
									</div>
									</form>
									<?php /*
									<div class="demo_age_wrap">
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>">Age</label>

										<input type="radio" name="age_<?php echo $charactercode; ?>_<?php echo $count; ?>" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_0" value="0" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_0">18-25 yrs</label>
										<input type="radio" name="age_<?php echo $charactercode; ?>_<?php echo $count; ?>" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_1" value="1" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_1">26-35 yrs</label>
										<input type="radio" name="age_<?php echo $charactercode; ?>_<?php echo $count; ?>" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_2" value="2" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_2">36-45 yrs</label>
										<input type="radio" name="age_<?php echo $charactercode; ?>_<?php echo $count; ?>" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_3" value="3" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_3">46-55 yrs</label>
										<input type="radio" name="age_<?php echo $charactercode; ?>_<?php echo $count; ?>" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_4" value="4" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_4">56-59 yrs</label>
										<input type="radio" name="age_<?php echo $charactercode; ?>_<?php echo $count; ?>" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_5" value="5" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_5">65+ yrs</label>
									</div>
									<div class="demo_geo_wrap">
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>">Geographic</label>

										<input type="radio" name="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_6" value="6" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_6">Africa</label>
										<input type="radio" name="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_7" value="7" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_7">Asia</label>
										<input type="radio" name="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_8" value="8" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_8">Europe</label>
										<input type="radio" name="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_9" value="9" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_9">North America</label>
										<input type="radio" name="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_10" value="10" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_10">South America</label>
										<input type="radio" name="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_11" value="11" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_11">Oceania</label>
									</div>
									<div class="demo_industry_wrap">
										<label for="industry_<?php echo $charactercode; ?>_<?php echo $count; ?>">Industry</label>
										<select class="demo-filter industry-filter" data-obj="d_<?php echo $charactercode; ?>" name="industry_<?php echo $charactercode; ?>_<?php echo $count; ?>" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>">
											<option value="">--Select One--</option>
											<option value="12">Aerospace and Defense</option>
											<option value="13">Automotive</option>
											<option value="14">Business Services</option>
											<option value="15">Construction</option>
											<option value="16">Consumer Products</option>
											<option value="17">Education</option>
											<option value="18">Energy</option>
											<option value="19">Financial Services</option>
											<option value="20">Food/Accomodations</option>
											<option value="21">Government</option>
											<option value="22">Health Care</option>
											<option value="23">Information</option>
											<option value="24">Manufacturing</option>
											<option value="25">Non Governmental Organization</option>
											<option value="26">Non Profit Organization</option>
											<option value="27">Other</option>
											<option value="28">Pharmaceuticals</option>
											<option value="29">Transportation</option>
											<option value="30">Utilities</option>
											<option value="31">Wholesale/Retail</option>

										</select>
									</div>
									*/ ?>
								</div>
							</div>
							<div class="disc_callouts clearfix middle_content">
								<?php the_field('overview_middle_column'); ?>
							</div>
							<div class="disc_callouts clearfix right_content">
								<?php the_field('overview_right_column'); ?>
							</div>
						</li>
						<?php $count++; ?>
						<?php while(the_repeater_field('score_categories')): ?>
							<li id="disc_link_<?php echo $charactercode; ?>_<?php echo $count; ?>" class="disc_wrapper clearfix">								
								<div class="disc_callouts clearfix">
									<h1 style="color:<?php the_sub_field('color'); ?>;"><?php the_sub_field('title'); ?></h1>
									<?php
										$chart_name = 'chart' . $chart_count;
										if('chart1' == $chart_name){
											$chart_name = 'chart';
										}
									?>
									<div id="chart_<?php echo $charactercode; ?>_<?php echo $count; ?>" class="chart" data-chart="<?php echo $chart_name; ?>">

									</div>
									<a class="graph-filter-toggle">Compare Results</a>
									<?php $chart_count++; ?>
									<div class="demographic_wrap clearfix cat-demo-group" data-section="d_<?php echo $charactercode; echo $count - 1; ?>">
										<h4>Compare your score with averages from your peers</h4>
									<a class="close">Close</a>
									<form method="get" action="<?php bloginfo('url'); ?>/report_3.php" class="demo-filter-form">
									<div class="demo_age_wrap">
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>">Age</label>
										<div class="custom-checkbox">
										<input type="checkbox" name="age[]" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_0" value="a1" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_0">18-25 yrs</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="age[]" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_1" value="a2" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_1">26-35 yrs</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="age[]" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_2" value="a3" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_2">36-45 yrs</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="age[]" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_3" value="a4" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_3">46-55 yrs</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="age[]" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_4" value="a5" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_4">56-59 yrs</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="age[]" id="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_5" value="a6" class="demo-filter age-filter" />
										<label for="age_<?php echo $charactercode; ?>_<?php echo $count; ?>_5">65+ yrs</label>
										</div>
									</div>
									<div class="demo_geo_wrap">
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>">Geographic</label>
										<div class="custom-checkbox">
										<input type="checkbox" name="geography[]" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_6" value="g1" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_6">Africa</label>
										</div>
									<div class="custom-checkbox">
										<input type="checkbox" name="geography[]" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_7" value="g2" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_7">Asia</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="geography[]" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_8" value="g3" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_8">Europe</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="geography[]" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_9" value="g4" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_9">North America</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="geography[]" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_10" value="g5" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_10">South America</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="geography[]" id="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_11" value="g6" class="demo-filter geo-filter" />
										<label for="geo_<?php echo $charactercode; ?>_<?php echo $count; ?>_11">Oceania</label>
										</div>
									</div>
									<div class="demo_industry_wrap">
										<label for="industry_<?php echo $charactercode; ?>_<?php echo $count; ?>">Industry</label>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_12" value="i1" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_12">Aerospace and Defense</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_13" value="i2" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_13">Automotive</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_14" value="i3" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_14">Business Services</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_15" value="i4" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_15">Construction</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_16" value="i5" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_16">Consumer Products</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_17" value="i6" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_17">Education</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_18" value="i7" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_18">Energy</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_19" value="i8" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_19">Financial Services</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_20" value="i9" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_20">Food/Accomodations</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_21" value="i10" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_21">Government</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_22" value="i11" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_22">Health Care</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_23" value="i12" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_23">Information</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_24" value="i13" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_24">Manufacturing</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_25" value="i14" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_25">Non Governmental</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_26" value="i15" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_26">Non Profit Organization</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_27" value="i16" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_27">Other</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_28" value="i17" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_28">Pharmaceuticals</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_29" value="i18" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_29">Transportation</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_30" value="i19" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_30">Utilities</label>
										</div>
										<div class="custom-checkbox">
										<input type="checkbox" name="industry[]" id="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_31" value="i20" class="demo-filter ind-filter" />
										<label for="ind_<?php echo $charactercode; ?>_<?php echo $count; ?>_31">Wholesale/Retail</label>
										</div>
									</div>
									</form>
									</div>
								</div>
								<div class="disc_callouts clearfix middle_content">
									<?php the_sub_field('middle_details'); ?>
								</div>
								<div class="disc_callouts clearfix right_content">
									<?php the_sub_field('right_details'); ?>
									<?php if(get_sub_field('left_panel_copy')): ?>
									<?php $subcattitle = get_sub_field('title'); 
										  $subcattitle = strtolower($subcattitle);
										  $subcattitle = str_replace(' ','-',$subcattitle); ?>
									<?php if(145 != $post->ID){ ?>
										<a class="panel_box" rel="panel_box" href="#<?php echo $post->post_name . '-' . $subcattitle; ?>-overlay">
											Learn More
										</a>
									<?php } ?>
								</div>
									
										<div id="<?php echo $post->post_name . '-' . $subcattitle; ?>-overlay" class="panel_overlays panel_box clearfix">
											<?php $paneltitle = get_sub_field('title'); ?>
											<h1><?php echo $paneltitle; ?></h1>
											<div class="left_panel_popup">
												<?php the_sub_field('left_panel_copy'); ?>
											</div>
											<div class="right_panel_popup">
												<?php the_sub_field('right_panel_copy'); ?>
											</div>
										<?php endif; ?>
									</div>
							</li>
						<?php $count++; ?>
						<?php endwhile; // End repeater field 'score_categories' ?>
					</ul>
					<?php else: ?>
					<ul class="clearfix report_nav">
						<li class="disc_wrapper clearfix">								
							<div class="disc_callouts clearfix build_dna_chart">
								<h1><?php echo $assessment->profile_name; ?></h1>
								<h2>- <?php echo $assessment->profile_title; ?></h2>
								<div id="profile_container2"></div>
							</div>
							<div class="disc_callouts clearfix build_dna_detail">
								<h1>Putting Your Profile to Work</h1>
								<?php echo $assessment->profile_description; ?>
							</div>
						</li>
					</ul>

					<?php endif; ?>
				</div><!-- .callouts_scroller -->					
			<?php endif; // End get field 'score_categories' ?>

			<!-- <?//php if(get_field('footer_heading')): ?>
				<div class="footer_wrap">
				<h1><?//php the_field('footer_heading'); ?></h1>
				<?//php while(the_repeater_field('footer_content')): ?>
					<div class="footer_column">
						<h2><?//php the_sub_field('sub_heading'); ?></h2>
						<p><?//php the_sub_field('paragraph_column'); ?></p>
					</div>
				<?//php endwhile; ?>
				</div>
			<?//php endif; ?> -->

		<?php if(get_field('section')): ?>
			<div class="summary_sidebar clearfix">
				<?php while(the_repeater_field('section')): ?>
					<ul class="clearfix">
					<h3><?php the_sub_field('sidebar_header'); ?></h3>
						<?php $charcode = get_sub_field('character'); ?>
						<?php $categories = get_sub_field('category'); ?>
							<?php foreach($categories as $category): ?>
								<li class="sidebar_title_glyph clearfix">
									<?php $cat_anchor = strtolower($category['title']);
									$cat_anchor = str_replace(' ', '_', $cat_anchor);
									$cat_anchor = 'nav_' . $cat_anchor; ?>
									<a href="#<?php echo $cat_anchor; ?>">
									<p><?php echo $category['title']; ?></p>
									<span data-color="<?php echo $category['color']; ?>" style="color:<?php echo $category['color']; ?>;">
										<?php echo $charcode; ?>
									</span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	<?php if(get_field('section')): ?>
	<div class="summary_content">
			<div class="summary_wrap_right clearfix">
				<?php
				$summaries = mysql_query("SELECT 
	wp_postmeta.meta_value AS question_number, 
	wp_terms.`name`, 
	results.response,
	wp_posts.post_title
FROM wp_postmeta INNER JOIN wp_posts ON wp_postmeta.post_id = wp_posts.ID
	 INNER JOIN wp_term_relationships ON wp_posts.ID = wp_term_relationships.object_id
	 INNER JOIN wp_terms ON wp_term_relationships.term_taxonomy_id = wp_terms.term_id
	 INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id
	 INNER JOIN results ON wp_postmeta.meta_value = results.q
WHERE wp_postmeta.meta_key = 'question_system_id' AND wp_posts.post_status = 'publish' AND wp_term_taxonomy.parent != 0 AND results.pid = '$pid'
ORDER BY wp_terms.`name` ASC") or die(mysql_error());
				$summary = array();
				while($s = mysql_fetch_assoc($summaries)) {
					echo '<pre style="display:none;">';
					print_r($s);
					echo '</pre>';

					switch($s['name']){
						case 'Analyzing':
							$summary['analyzing'][] = $s;
							break;
						case 'Associating':
							$summary['associating'][] = $s;
							break;
						case 'Challenging the Status Quo':
							$summary['challenging_the_status_quo'][] = $s;
							break;
						case 'Creative Confidence':
							$summary['creative_confidence'][] = $s;
							break;
						case 'Detail Oriented':
							$summary['detail_oriented'][] = $s;
							break;
						case 'Experimenting':
							$summary['experimenting'][] = $s;
							break;
						case 'Networking':
							$summary['networking'][] = $s;
							break;
						case 'Observing':
							$summary['observing'][] = $s;
							break;
						case 'Planning':
							$summary['planning'][] = $s;
							break;
						case 'Questioning':
							$summary['questioning'][] = $s;
							break;
						case 'Risk Taking':
							$summary['risk_taking'][] = $s;
							break;
						case 'Self Disciplined':
							$summary['self_disciplined'][] = $s;
							break;
					}
				}  ?>

				<?php while(the_repeater_field('section')): ?>
				<?php $charcode = get_sub_field('character'); ?>
				<?php $categories = get_sub_field('category'); ?>
				<?php foreach($categories as $category): ?>
				<?php

					$answers = array(
					'Not Aplicable',
					'Strongly Disagree',
		    		'Disagree',
		    		'Slightly Disagree',
		    		'Neutral',
		    		'Slightly Agree',
		    		'Agree',
		    		'Strongly Agree');
					$summary_key = $category['title'];
					$summary_key = strtolower($summary_key);
					$summary_key = str_replace(' ', '_', $summary_key);
					$responses = $summary[$summary_key];
					echo '<pre style="display:none;">';
					print_r($summary[$summary_key]);
					echo '</pre>';
				?>
				<span class="summary_title_icon" data-color="<?php echo $category['color']; ?>" style="color:<?php echo $category['color']; ?>;">
					<?php echo $charcode; ?>
				</span>
				<h1 id="nav_<?php echo $summary_key; ?>"><?php echo $category['title']; ?></h1>
				<div class="summary_content_wrap">
					<div class=""></div>
					<span class="summary_column clearfix">
						<h4 id="left_column_title">Question</h4>
						<h4 id="right_column_title">Your Response</h4>
					</span>
					<span class="summary_list clearfix">
						<?php foreach($responses as $response): ?>
						<p><?php echo $response['post_title']; ?></p>
						<h3><?php echo $answers[$response['response']]; ?></h3>
						<?php endforeach; ?>
					</span>
					<?php endforeach; ?>
				</div>
				<?php endwhile; ?>
			</div>
	</div>
	<?php endif; ?>

		</li>


<?php endwhile; endif; // End Report query ?>

<?php 	$development_args = array(
'page_id' => 334 
	);
$development_query = new WP_Query($development_args);
while($development_query->have_posts()): $development_query->the_post();
?>
<li>
	<div class="heading-test clearfix">
		<div class="heading_links clearfix">
			<?php if(get_field('heading_nav_left')): ?>
				<a id="heading_nav_left" class="heading-prev">&laquo; Back: <?php the_field('heading_nav_left'); ?></a>
			<?php endif; ?>
			<?php if(get_field('heading_nav_left')): ?>
				<a id="heading_nav_right" class="heading-next">Next: <?php the_field('heading_nav_right'); ?> &raquo;</a>
			<?php endif; ?>
		</div>
		<div class="heading-left">
			<h1><?php the_title(); ?></h1>
		</div>
	</div>
	<div class="development_left clearfix">
		<h1><?php the_field('left_header'); ?></h1>
		<p><?php the_field('left_copy'); ?><p>
		<a href="<?php the_field('left_url'); ?>">
			<?php the_field('left_url_title'); ?>
		</a>
	</div>
	<?php if(get_field('development_repeater')): ?>
		<div class="development_wrap">
			<?php while(the_repeater_field('development_repeater')): ?>
				<div class="development_right_1 clearfix">
					<?php $development_media = wp_get_attachment_image_src(get_sub_field('development_media'), 'development_media'); ?>
					<img src="<?php echo $development_media[0]; ?>" width="<?php echo $development_media[1]; ?>" height="<?php echo $development_media[2]; ?>" alt="" />
					<div class="development_right_text">
						<h2><?php the_sub_field('right_header'); ?></h2>
						<?php the_sub_field('right_copy'); ?>
						<?php /*<a href="<?php the_sub_field('right_url'); ?>">
							<?php the_sub_field('right_url_title'); ?>
						</a>
						*/ ?>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>
</li>
<?php endwhile; ?>
</ul>
</div><!-- end flexslider -->
<?php

} else {

	echo 'not found';
/*    // If no assessment in progress, generate new PID
    $cur_time = time();
    $pid = md5($cur_time . $current_user->email);
    $new_assessment = $wpdb->query("INSERT INTO assessments(PID, user_id, start) VALUES ('$pid', $user_id, $cur_time)");*/
}
$total = 0; ?>

<?php } // Logged in conditional ?>
    </div><!-- end of #wrapper -->
    <?php responsive_wrapper_end(); // after wrapper hook ?>
</div><!-- end of #container -->


<?php responsive_container_end(); // after container hook ?>

<?php 	$footerino_args = array(
'page_id' => 360 
	);
$footerino_query = new WP_Query($footerino_args);
while($footerino_query->have_posts()): $footerino_query->the_post();
?>

<div id="innovation_footer" class="footer_wrap">
	<a class="footer_control">Show Footer</a>
	<div class="footer_heading_wrap">
		<div class="footer_title_wrap">
			<h1 class="footer_heading"><?php the_field('footer_heading'); ?></h1>
			<span>(click on the bar to learn more)</span>
		</div>
	</div>
	<div class="innovation_footer_wrap">
		<?php if(get_field('innovation_footer_repeater')): ?>
			<?php while(the_repeater_field('innovation_footer_repeater')): ?>
				<div class="innovation_repeater_wrap">
					<h2><?php the_sub_field('innovation_title'); ?></h2>
				     <p><?php the_sub_field('innovation_subheader'); ?></p>
					    <?php the_sub_field('innovation_copy'); ?>
					<?php $innofootergraph = wp_get_attachment_image_src(get_sub_field('innovation_footer_graph'), 'innofootergraph'); ?>
					<img src="<?php echo $innofootergraph[0]; ?>" width="<?php echo $innofootergraph[1]; ?>" height="<?php echo $innofootergraph[2]; ?>" alt="" />
				</div>
			<?php endwhile; ?>
		<?php endif; ?>
	</div>
</div>
<?php endwhile; ?>

<?php 	$footerino_args = array(
'page_id' => 370 
	);
$footerino_query = new WP_Query($footerino_args);
while($footerino_query->have_posts()): $footerino_query->the_post();
?>

<div class="nav-wrapper clearfix">
	<div class="nav-sub-wrapper">
		<h2>Your Innovator's DNA</h2>
		<div class="clearfix">
			<span id="nav-complete" class="nav-counters">
			</span>

			<ul id="nav-cats">
				<li>
					<div class="nav-cats-title-wrap">
						<a class="nav-cats-title" href="">Introduction</a>
					</div>
				</li>

				<li>
					<div class="nav-cats-title-wrap">
						<a class="nav-cats-title" href="">Characteristics of Successful Innovators</a>
					</div>
				</li>
				
				<li>
					<div class="nav-cats-title-wrap">
						<a class="nav-cats-title" href="">Profile</a>
					</div>
				</li>

				<li class="nav-main-cats">
					<div class="nav-cats-title-wrap">
						<span class="nav-cats-glpyh">a</span>
						<a class="nav-cats-title" href="">Courage To Innovate</a>
					</div>
					<ul>
						<li><span id="courage1" class="nav-cats-glpyh-sub">a</span><a href="">Challenging The Status Quo</a></li>
						<li><span id="courage2" class="nav-cats-glpyh-sub">a</span><a href="">Risk Taking</a></li>
						<li><span id="courage3" class="nav-cats-glpyh-sub">a</span><a href="">Creative Confidence</a></li>
					</ul>
				</li>
				
				<li class="nav-main-cats">
					<div class="nav-cats-title-wrap">
						<span class="nav-cats-glpyh">b</span>
						<a class="nav-cats-title" href="">Discovery Skills</a>
					</div>
					<ul>
						<li><span id="discovery1" class="nav-cats-glpyh-sub">b</span><a href="">Questioning</a></li>
						<li><span id="discovery2" class="nav-cats-glpyh-sub">b</span><a href="">Observing</a></li>
						<li><span id="discovery3" class="nav-cats-glpyh-sub">b</span><a href="">Networking</a></li>
						<li><span id="discovery4" class="nav-cats-glpyh-sub">b</span><a href="">Experimenting</a></li>
						<li><span id="discovery5" class="nav-cats-glpyh-sub">b</span><a href="">Associating</a></li>
					</ul>
				</li>

				<li class="nav-main-cats">
					<div class="nav-cats-title-wrap">
						<span class="nav-cats-glpyh">c</span>
						<a class="nav-cats-title" href="">Delivery Skills</a>
					</div>
					<ul>
						<li><span id="delivery1" class="nav-cats-glpyh-sub">c</span><a href="">Analyzing</a></li>
						<li><span id="delivery2" class="nav-cats-glpyh-sub">c</span><a href="">Planning</a></li>
						<li><span id="delivery3" class="nav-cats-glpyh-sub">c</span><a href="">Detail Oriented</a></li>
						<li><span id="delivery4" class="nav-cats-glpyh-sub">c</span><a href="">Self Disciplined</a></li>
					</ul>
				</li>

				<li>
					<div class="nav-cats-title-wrap">
						<a class="nav-cats-title" href="">Building Your Innovator's DNA</a>
					</div>
				</li>

				<li>
					<div class="nav-cats-title-wrap">
						<a class="nav-cats-title" href="">Summary of Responses</a>
					</div>
				</li>

				<li>
					<div class="nav-cats-title-wrap">
						<a class="nav-cats-title" href="">Development Opportunities</a>
					</div>
				</li>

			</ul>

		</div>
	</div>
		<a class="control-toggle">Show Navigation</a>
</div>

<div id="discovery_footer" class="footer_wrap">
	<a class="footer_control">Show Footer</a>
	<div class="footer_heading_wrap">
		<div class="footer_title_wrap">
			<h1 class="footer_heading">How to Use the Inovator's DNA to Solve Problems</h1>
		</div>
	</div>
	<div class="innovation_footer_wrap">
		<div class="build_footer_wrap">
			<h1>Putting Your Courage to Innovate to Work</h1>
			<p>The <em>Courage to Innovate</em> is the starting point for generating new ideas. Here are few suggestions for strengthening your <em>Courage to Innovate</em>.</p>
			<p><em>#1: Pick a Problem (or Opportunity)</em><br/>
			Do you face problems right now—either personal or professional—for which you honestly don’t have solutions? Identifying a problem is the first step to challenging the status quo. It’s an acknowledgment that the way things are is not the way they should be.</p>
			<p><em>#2: Care Enough About the Problem to Take a Risk</em><br/>
			Make sure the problem you’ve chosen to solve REALLY matters to you. . Richard Branson, founder of Virgin, shared some wise advice when it comes to the <em>Courage to Innovate</em>, “Care enough about something to do something about it.” People who care deeply about something will take the risks to do something about it. Building an emotional connection to a problem gives us the power to take the risks necessary to change the world.</p>
			<p><em>#3: Do Something About the Problem</em><br/>
			Our creative confidence increases by doing something about a problem that matters to us. By actively engaging the five discovery skills, your creative confidence will increase. Confidence comes from competence. Use the Q+ONE+A process to generate a creative solution to your problem—and strengthen your creative confidence (See Discovery Skills suggestions for the Q+ONE+A process); then apply your delivery skills to move the idea to implementation.</p>
		</div>
		<div class="clear">
			<h1>Putting Your Discovery Skills to Work</h1>
		</div>

		<div class="discovery_repeater_wrap">
			<h2>Q</h2>
			<div class="disc_footer_scores">
				<h4><?php echo $assessment->b1; ?></h4>
				<h3 class="disc_star">Questioning</h3>
			</div>
			<p>Are you asking the right questions often enough?  
				Innovative thinking starts with a question. Highly successful innovators constantly question, asking “what is” and then “what if?”  They do this because they are constantly searching for the “right question” to start unlocking the problem or opportunity they face.  Work on this skill until your questioning score is 5.7 or above. (See The Innovator's DNA chapter 3 for tips on improving your questioning skills).</p>
		</div>
		<?php
		$o_top = $n_top = $e_top = false;
		$o_string = '<span>O</span>';
		$n_string = '<span>N</span>';
		$e_string = '<span>E</span>';
		$o_score = $assessment->b2;
		$n_score = $assessment->b3;
		$e_score = $assessment->b4;
		$high_score = max($o_score, $n_score, $e_score);
		if( $assessment->b2 == $high_score ){
			$o_top = true;
			$o_string = 'O';
		}
		if( $assessment->b3 == $high_score ){
			$n_top = true;
			$n_string = 'N';
		}
		if( $assessment->b4 == $high_score ){
			$e_top = true;
			$e_string = 'E';
		}
		?>
		<div class="discovery_repeater_wrap">
			<h2><?php echo $o_string; ?><span>-</span><?php echo $n_string; ?><span>-</span><?php echo $e_string; ?></h2>
			<div class="disc_footer_scores">
				<h4><?php echo $assessment->b2; ?></h4>
				<h3<?php if($o_top){ echo ' class="disc_star"'; } ?>>Observing</h3>
			</div>
			<div class="disc_footer_scores">
				<h4><?php echo $assessment->b3; ?></h4>
				<h3<?php if($n_top){ echo ' class="disc_star"'; } ?>>Networking</h3>
			</div>
			<div class="disc_footer_scores">
				<h4><?php echo $assessment->b4; ?></h4>
				<h3<?php if($e_top){ echo ' class="disc_star"'; } ?>>Experimenting</h3>
			</div>
			<p>Do you regularly engage at least one of these discovery skills- Observing, Networking or Experimenting-to enter the Innovative Zone?
			Highly successful innovators leverage their own unique approach to these three skills. In fact, most only excel at one or two of them. Work on developing your strongest of these three skills to generate even more creative ideas. If your strongest skill (of these three) is already solidly in the innovation zone, you may want to work on your next strongest skill (not yet in the innovation zone). Work on at least one of these skills until it scores at least 5.7 or above. (See The Innovator's DNA chapters 4-6 for tips on improving these skills).
			</p>
		</div>

		<div class="discovery_repeater_wrap">
			<h2>A</h2>
			<div class="disc_footer_scores">
				<h4><?php echo $assessment->b5; ?></h4>
				<h3 class="disc_star">Associating</h3>
			</div>
			<p>Do you consistently combine existing knowledge to create novel connections?
			Lastly, tie together what you learn by frequent questioning as you observe, network, and/or experiment to trigger new creative associations. Associational thinking reflects your cognitive ability to connect ideas, problems, and solutions together in surprising new ways. Work on this skill until it hits 5.7 or greater. (See The Innovator's DNA chapter 2 for tips on improving this skill).</p>
		</div>
		<div class="clear">
			<h1>Putting Your Delivery Skills to Work</h1>
			<p>Use the Delivery Skills to Implement Your Creative Solution.</p>
			<p>Now that you’ve picked a problem and used your discovery skills to generate a potential solution, it’s time to move your solution further along. If you haven’t already tried out your solution, consider implementing a low- cost pilot or prototype (to test your solution) before implementing a fully-developed solution. To implement a creative new solution requires the active engagement of each delivery skill, often in this sequence.</p>
			<p>#1 <em>Analyze the best ways to implement your solution</em>—ways that will create the least resistance and generate the most useful data on what is working (or not working) and why. To do this often requires rigorous, data- driven analysis, combined with intuition, to help you decide the best approach for taking a new idea forward.</p>
			<p>#2 <em>Plan what exactly needs to be done to build your solution</em> (your pilot or prototype) and implement it. Enlist others as necessary to create a detailed plan for implementing your solution. Take the time to break down bigger objectives into smaller tasks. Make sure someone takes responsibility for each task and that deadlines are set.</p>
			<p>#3 <em>Pay attention to the specific details needed to complete each task</em> on time and with high quality. The saying that the “devil is in the details” holds especially true when it comes to implementing new ideas.<br/>
			Opposition to bold new ideas occurs at very step of the way to full implementation. Paying attention to all the specific tasks that must be done often quiets the opposition.</p>
			<p>#4 <em>Be self-disciplined and follow through on key tasks</em> to see your solution succeed. Don’t get sidetracked but stick to your deadlines. Hold yourself strictly accountable for completing the required tasks for your solution to work. Self-starters must be self-finishers to make sure a new idea gets 100%, not just 95%, done.</p>

		</div>
	</div>
</div>
<?php endwhile; ?>

<?php wp_footer(); ?>

<script type="text/javascript">
		jQuery(document).ready(function ($) {
		    var chart;
		    $(document).ready(function() {
		        chart = new Highcharts.Chart({
		            chart: {
		                renderTo: 'profile_container',
		                type: 'scatter',
						plotBackgroundImage: '<?php bloginfo('stylesheet_directory'); ?>/images/profile_<?php echo $profile; ?>.png',
		                zoomType: 'xy',
		                marginTop: 0,
		                marginRight: 0,
		                spacingTop: 0,
		                spacingRight: 0
		            },
			    	credits:{enabled:false},
		            title: false,
			    	exporting: false,
			    	tooltip: {
						borderRadius: 0,
						formatter: function() {
							//return 'Industry Average: Finance';
						},
						enabled: false
					},		
		            xAxis: {
		                title: {
		                    enabled: true,
		                    text: 'Delivery Skills',
				    		align: 'high',
				    		style: {
								color: '#cccccc',
								fontSize: '14px',
								fontWeight: 'normal',
								fontFamily: 'Helvetica, sans-serif'
							}
		                },
		                startOnTick: true,
		                endOnTick: false,
		                showLastLabel: false,
						max: 110,
						min: 0,
						labels: false,
						tickColor: false,
		            },
		            yAxis: {
		                title: {
		                    text: 'Discovery Skills',
				    		align: 'high',
				    		style: {
								color: '#cccccc',
								fontSize: '14px',
								fontWeight: 'normal',
								fontFamily: 'Helvetica, sans-serif'
							}
		                },
		                startOnTick: true,
		                endOnTick: false,
		                showLastLabel: false,
						max: 110,
						min: 0,
						gridLineColor: false,
						labels: false,
		            },
		            //legend: false,
		            legend: {
		            	borderWidth: 0,
		            	symbolWidth: 35,
		            	align: 'left',
		            	floating: true,
		            	y: 5
		            },
		            plotOptions: {
		                scatter: {
		                    states: {
		                        hover: {
		                            marker: {
		                                enabled: false
		                            }
		                        }
		                    }
		                }
		            },	    
		            series: [{
		                name: 'Your Profile',
		                data: [[<?php echo $x+10; ?>, <?php echo $y+10; ?>]],
						marker: {
							symbol: 'url(<?php bloginfo('stylesheet_directory'); ?>/images/star.png)',
		                    radius: 5,
		                }
		            }/*, {
		                name: 'Industry Average: Finance',
		                color: 'rgba(35, 31, 32)',
		                data: [[70, 50]],		
						marker: {
							symbol: 'square',
		                    radius: 6,
		                    states: {
		                        hover: {
		                            enabled: true,
		                        }
		                    }
		                }
		            }*/]
		        });
				var profilechart;
				profilechart = new Highcharts.Chart({
		            chart: {
		                renderTo: 'profile_container2',
		                type: 'scatter',
						plotBackgroundImage: '<?php bloginfo('stylesheet_directory'); ?>/images/profile_<?php echo $profile; ?>.png',
		                zoomType: 'xy',
		                marginTop: 0,
		                marginRight: 0,
		                spacingTop: 0,
		                spacingRight: 0
		            },
			    	credits:{enabled:false},
		            title: false,
			    	exporting: false,
			    	tooltip: {
						borderRadius: 0,
						formatter: function() {
							//return 'Industry Average: Finance';
							return false;
						},
						enabled: false
					},		
		            xAxis: {
		                title: {
		                    enabled: true,
		                    text: 'Delivery Skills',
				    		align: 'high',
				    		style: {
								color: '#cccccc',
								fontSize: '12px',
								fontWeight: 'normal',
								fontFamily: 'Helvetica, sans-serif'
							}
		                },
		                startOnTick: true,
		                endOnTick: false,
		                showLastLabel: false,
						max: 110,
						min: 0,
						labels: false,
						tickColor: false,
		            },
		            yAxis: {
		                title: {
		                    text: 'Discovery Skills',
				    		align: 'high',
				    		style: {
								color: '#cccccc',
								fontSize: '12px',
								fontWeight: 'normal',
								fontFamily: 'Helvetica, sans-serif'
							}
		                },
		                startOnTick: true,
		                endOnTick: false,
		                showLastLabel: false,
						max: 110,
						min: 0,
						gridLineColor: false,
						labels: false,
		            },
		            legend: false,
		            plotOptions: {
		                scatter: {
		                    states: {
		                        hover: {
		                            marker: {
		                                enabled: false
		                            }
		                        }
		                    }
		                }
		            },	    
		            series: [{
		                name: 'Your Profile',
		                data: [[<?php echo $x+10; ?>, <?php echo $y+10; ?>]],
						marker: {
							symbol: 'url(<?php bloginfo('stylesheet_directory'); ?>/images/star.png)',
		                    radius: 5,
		                }
		            }/*, {
		                name: 'Industry Average: Finance',
		                color: 'rgba(35, 31, 32)',
		                data: [[70, 50]],		
						marker: {
							symbol: 'square',
		                    radius: 6,
		                    states: {
		                        hover: {
		                            enabled: true,
		                        }
		                    }
		                }
		            }*/]
		        });
		    });
		});
		</script>
<div id="screen-cover" style="display:none;"></div>
</body>
</html>