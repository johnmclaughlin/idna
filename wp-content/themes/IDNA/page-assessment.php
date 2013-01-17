<?php get_header(); ?>
<?php
if (!is_user_logged_in()) {
	wp_login_form();
} else { ?>
<div id="progress-bar">
	<div id="progress-display"></div>
	<span id="progress-count"></span>
</div>
<div class="flexslider" id="assessment-slider">
<?php /*<a class="flex-prev">Prev</a>
<a class="flex-next">Next</a> */ ?>
	<ul class="slides clearfix" id="assessment-slides">
<?php //error_reporting(E_ALL ^ E_NOTICE); ?>
<?php
// Grab current WP user ID
$current_user = wp_get_current_user();
$user_id = $current_user->ID; 

// Search for an active assessment
global $wpdb;
$current_assessment = $wpdb->get_results("SELECT * FROM assessments WHERE (user_id = $user_id AND complete IS NULL) ORDER BY start DESC");
$complete_sorted = array(); // Hold array of previously completed questions - $question_id => $response

if(count($current_assessment) > 0){
    $pid = $current_assessment[0]->PID;

    // Grab an array of all questions previously answered in this assessment
    $completed_questions = $wpdb->get_results("SELECT q, response FROM results WHERE (pid LIKE '$pid') ORDER BY q");
    foreach($completed_questions as $completed_question){
        $key = $completed_question->q;
        $complete_sorted[$key] = $completed_question->response;
    }
} else {
    // If no assessment in progress, generate new PID
    $cur_time = time();
    $pid = md5($cur_time . $current_user->email);
    $new_assessment = $wpdb->query("INSERT INTO assessments(PID, user_id, start) VALUES ('$pid', $user_id, $cur_time)");
}
$total = 0; ?>

		<div id="notecard_left"></div>
		<div id="notecard_right"></div>
<li>
	<!-- INTRO PAGE -->
	<?php $intro_args = array(
		'post_type' => 'page',
		'post__in' => array(321),
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
	<?php $total++; ?>
</li>

<?//php $pre_assessment = get_page(25); ?>
<?php $pretest_args = array(
		'post_type' => 'page',
		'post__in' => array(25),
		'orderby' => 'menu_order',
		'order' => 'ASC'
); ?>
<?php $pretest_query = new WP_Query( $pretest_args ); ?>
	<?php if($pretest_query->have_posts()): ?>
	<?php while($pretest_query->have_posts()): $pretest_query->the_post(); ?>
		<?php if(get_field('form_right')): ?>
			<li class="pretest">
				<div class="test-container-left">
					<h1 class="top-header"><?php the_title(); ?></h1>
					<?php the_content(); ?>
				</div>
				<div class="test-container-right">
					<?php $gravform = get_field('form_right'); ?>
					<?php echo do_shortcode("[gravityform id=" . $gravform->id . " title=false description=false ajax=true field_values='pid=" . $pid . "']"); ?>
				</div>
			</li>
			<?php $total++; ?>
		<?php else: ?>
			<li class="pretest">
				<h1 class="top-header"><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</li>
		<?php $total++; ?>
		<?php endif; ?>
	<?php endwhile; endif; ?>
<?php
	if(count($current_assessment) > 0){
		$questions_args = array(
			'posts_per_page' => -1,
			'orderby' => 'none',
			'post_type' => 'questions'
		);
	} else {
		$questions_args = array(
			'posts_per_page' => -1,
			'orderby' => 'rand',
			'post_type' => 'questions'
		);
	}
?>
<?php $questions_query = new WP_Query( $questions_args ); ?>
<?php
if(isset($new_assessment)){
	$sort_order = array();
}
?>
<pre style="display:none;">
	<?php print_r($complete_sorted); ?>
	<?php print_r($questions_query); ?>
</pre>
    <?php if($questions_query->have_posts()): ?> 
	<?php $count=1; $review=''; $na_count=0; ?>
	<?php $completed = array(); ?>
    <?php while($questions_query->have_posts()): $questions_query->the_post(); ?>
    	<?php
    	if(isset($new_assessment)){
    		$sort_order[] = $post->ID;
    	}
    	$is_negative = get_field('negative');
    	$frequency_question = get_field('frequency_question');
    	/* Add additional block before if/else - if(get_field('custom_labels')) - and create custom key/val array with custom labels */
    	if(get_field('forced_range')){ // Custom labels
    		$values = get_field('forced_range');
    		$response_values = array(
    			1 => $values[0]['label'],
    			$values[1]['label'],
    			$values[2]['label'],
    			$values[3]['label'],
    			$values[4]['label'],
    			$values[5]['label'],
    			$values[6]['label']
    		);
    	} elseif(1 == $frequency_question){
    		$response_values = array(
	    		1 => 'Once Every <br/>Few Years',
	    		'Once a <br/>Year',
	    		'Every <br/>6 Months',
	    		'Every <br/>3 Months',
	    		'Every <br/>Month',
	    		'Every <br/>Week',
	    		'Every <br/>Day'
	    	);
	    } elseif('forced_choice' == get_field('type')){
    		$response_values = array(
    			1 => get_field('forced_choice_delivery'),
    			get_field('forced_choice_discovery')
   			);
   			if(1 == $is_negative){
   				$response_values = array_reverse($response_values, true);
   			}
    	} elseif(1 == $is_negative){
    		$response_values = array(
	    		1 => 'Strongly <br/>Agree',
	    		'Agree',
	    		'Slightly <br/>Agree',
	    		'Neutral',
	    		'Slightly <br/>Disagree',
	    		'Disagree',
	    		'Strongly <br/>Disagree'
	    	);
	    	$response_values = array_reverse($response_values, true);

    	} else {
    		$response_values = array(
	    		1 => 'Strongly <br/>Disagree',
	    		'Disagree',
	    		'Slightly <br/>Disagree',
	    		'Neutral',
	    		'Slightly <br/>Agree',
	    		'Agree',
	    		'Strongly <br/>Agree'
	    	);
    	}

    	?>
        <?php
        $question_id = get_field('question_system_id');
        $display_answer = '';
        $na_checked = '';
        if(array_key_exists($question_id, $complete_sorted)){
        	if(array_key_exists($complete_sorted[$question_id], $response_values)){
	            $display_answer = $response_values[$complete_sorted[$question_id]];
	        } elseif($complete_sorted[$question_id] == 0) {
	        	$display_answer = 'N/A';
        		$na_count++;
        	}
        }

        ?>
        <?php
        $review .= '<div class="clearfix review-block">';
        ?>
    	<li id="question-<?php the_field('question_system_id'); ?>" class="question-page clearfix">
    		<span class="question-number">Question: <?php echo $count; ?></span>
    		<form method="post" id="assessment_form_q_<?php the_field('question_system_id'); ?>" action="<?php bloginfo('url'); ?>/submit.php">
			<input type="hidden" name="pid" class="pid" value="<?php echo $pid; ?>" />
            <input type="hidden" name="user_id" class="userid" value="<?php echo $user_id; ?>" />
			<input type="hidden" name="action" value="update" />
    		<p class="questions"><?php the_title(); ?></p>
    	   	<div class="answer">Your Answer<div class=""><?php echo $display_answer; ?></div>
    		</div>
    		<?php $review .= '<h4>' . get_the_title() . '</h4>'; ?>
	    	<div class="radio-container clearfix<?php if('forced_choice' == get_field('type')){ echo ' forced_choice'; } ?>">
	    		<?php foreach($response_values as $i => $response_value): ?>

                <?php
                $checked_string = '';
                if(array_key_exists($question_id, $complete_sorted)){
                    if($complete_sorted[$question_id] == $i){
                        $checked_string = ' checked="checked"';
                        $completed[$total] = true;
                    } elseif($complete_sorted[$question_id] == 0){
                    	$na_checked = ' checked="checked"';
                    	
                    }
                }

                $review .= '<div class="radio-wrap"><input class="review" id="answer_' . $i . '_' . get_field('question_system_id') . '_review" name="answer[' . get_field('question_system_id') . ']" type="radio" value="' . $i . '"' . $checked_string . '/>';
                $review .= '<label for="answer_' . $i . '_' . get_field('question_system_id') . '_review">' . $response_value . '</label></div>';
                ?>
		    	<div class="radio-wrap">
                    <input class="response" id="answer_<?php echo $i; ?>_<?php the_field('question_system_id'); ?>" name="answer[<?php the_field('question_system_id'); ?>]" type="radio" value="<?php echo $i; ?>" data-plaintext="<?php echo $response_value; ?>"<?php echo $checked_string; ?> />
		    		<label for="answer_<?php echo $i; ?>_<?php the_field('question_system_id'); ?>"><?php echo $response_value; ?></label>
		    	</div>
		    	<?php endforeach; ?>
	    	</div><!-- .radio-container -->
	    	<?php if('forced_choice' != get_field('type')){ ?>
	    	<div class="not-applicable">
	    		<label for="answer_na_<?php the_field('question_system_id'); ?>">Not Applicable.<span>(This question doesn't apply to me.)</span></label>
	    		<input class="response-na response" id="answer_na_<?php the_field('question_system_id'); ?>" name="answer[<?php the_field('question_system_id'); ?>]" type="checkbox" value="0" data-plaintext="N/A" />
	    		<?php
	    		$review .= '<div class="radio-wrap"><input class="review required" id="answer_0_' . get_field('question_system_id') . '_review" name="answer[' . get_field('question_system_id') . ']" type="radio" value="0"' . $na_checked . '/>';
	    		$review .= '<label for="answer_0_' . get_field('question_system_id') . '_review">Not Aplicable</label></div>';
	    		?>
	    	</div>
	    	<?php } ?>
	    	<!-- <input type="submit" class="no-js-submit" />
    		<button class="reset" name="answer[<?php the_field('question_system_id'); ?>]">Clear</button> -->
    		</form>
    	</li>
    	
    	 	
    	<?php $review .= '</div>'; ?>
    <?php $count++; $total++; ?>	
<?php endwhile; ?>
<?php
if(isset($new_assessment)){
	print_r($user_id);
	print_r($sort_order);
	update_user_meta($user_id, 'question_order', $sort_order);
}
?>
<?php endif; ?>
<?php $posttest_args = array(
		'post_type' => 'page',
		'post__in' => array(28, 75, 109, 45),
		'orderby' => 'menu_order',
		'order' => 'ASC'
); ?>
<?php $posttest_query = new WP_Query( $posttest_args ); ?>
	<?php if($posttest_query->have_posts()): ?>
	<?php while($posttest_query->have_posts()): $posttest_query->the_post(); ?>
		<?php if(75 == $post->ID): ?>
		<li>
			<div class="heading-test clearfix">
				<div class="heading-left">
					<h1 class="top-header"><?php the_title(); ?></h1>
					<?php the_content(); ?>
				</div>
			</div>
			<form action="<?php bloginfo('url'); ?>/submit.php" method="post" class="clearfix" id="review-form">
	    		<input type="submit" class="no-js-submit review-button" />
				<input type="hidden" name="pid" class="pid" value="<?php echo $pid; ?>" />
	            <input type="hidden" name="user_id" class="userid" value="<?php echo $user_id; ?>" />
				<input type="hidden" name="action" value="update" />
				<div id="review-wrap">
					<?php echo $review; ?>
				</div>
				<input type="submit" class="no-js-submit review-button" />
			</form>
		</li>
		<li>
		   	<div id="ballsWaveG">
				<div id="ballsWaveG_1" class="ballsWaveG">
				</div>
				<div id="ballsWaveG_2" class="ballsWaveG">
				</div>
				<div id="ballsWaveG_3" class="ballsWaveG">
				</div>
				<div id="ballsWaveG_4" class="ballsWaveG">
				</div>
				<div id="ballsWaveG_5" class="ballsWaveG">
				</div>
				<div id="ballsWaveG_6" class="ballsWaveG">
				</div>
				<div id="ballsWaveG_7" class="ballsWaveG">
				</div>
				<div id="ballsWaveG_8" class="ballsWaveG">
				</div>
				<div class="loading_copy">
					<p>Processing your assessment results.</p>
					<p>This will only take a few moments.</p>
				</div>
			</div>
		</li>
		<?php $total+2; ?>
		<?php elseif(109 == $post->ID || 45 == $post->ID): ?>
		<li>
			<div class="heading-test clearfix">
				<div class="heading-left">
					<h1 class="top-header"><?php the_title(); ?></h1>
				</div>
			</div>
			<div class="page_left clearfix">
				<h1><?php the_field('page_header'); ?></h1>
				<?php the_field('page_textarea'); /*?>
				<a href="<?php the_field('page_url'); ?>"><?php the_field('page_url_text') ?></a>
				*/ ?>
			</div>
			<div class="page_right">
			<?php if(109 == $post->ID){ ?>
				<?php $initial_completed = count($completed); ?>

		
				<div class="question_wrap" id="ac_answered">
					<h2><?php echo $initial_completed - $na_count; ?></h2>
					<h3>questions answered</h3>
				</div>
				<div class="question_wrap" id="ac_notapply">
					<h2><?php echo $na_count; ?></h2>
					<h3>questions did not apply to you</h3>
				</div>
				<?php $assessment_count = $wpdb->get_results("SELECT * FROM assessments WHERE user_id = $user_id");
				if(count($assessment_count) > 1){
				?>
					<a href="<?php bloginfo('url'); ?>">View my assessments</a>
				<?php } else { ?>
					<a href="<?php echo get_permalink(120); ?>?pid=<?php echo $pid; ?>">View my report</a>
				<?php } ?>
				<?php /*if(get_field('question_results')): ?>
					<?php while(the_repeater_field('question_results')): ?>
						<div class"question_wrap">
							<h2><?php the_sub_field('question_count'); ?></h2>
							<h3><?php the_sub_field('question_value'); ?></h3>
						</div>
				<?php endwhile; endif;*/ ?>
				<form method="post" id="assessment_complete" action="<?php bloginfo('url'); ?>/submit.php">
	                <input type="hidden" name="pid" class="pid" value="<?php echo $pid; ?>" />
	                <input type="hidden" name="user_id" class="userid" value="<?php echo $user_id; ?>" />
	                <input type="hidden" name="action" value="complete" />
	                <?php if(is_page(109)): ?>
	                	<input type="submit" value="Process My Assessment" />
	                <?php endif; ?>
	            </form>
	        <?php } ?>
	        <?php if(45 == $post->ID): ?>
		        <a id="thank_you_1" href="">Continue To My Asessment Report</a>
		        <a id="thank_you_2" href="">Return to My Assessments >></a>
	    	<?php endif; ?>
			</div>
		</li>
		<?php $total++; ?>
		<?php elseif(get_field('form_right')): ?>
			<li class="posttest">
				<div class="test-container-left">
					<h1 class="top-header"><?php the_title(); ?></h1>
					<?php the_content(); ?>
				</div>
				<div class="test-container-right">
					<?php $gravform = get_field('form_right'); ?>
					<?php echo do_shortcode("[gravityform id=" . $gravform->id . " title=false description=false ajax=true field_values='pid=" . $pid . "' tabindex=32]"); ?>
				</div>
			</li>
		<?php $total++; ?>
		<?php else: ?>
			<li class="posttest">
				<h1 class="top-header"><?php the_title(); ?></h1>
				<p class="question-text"><?php the_content(); ?></p>

			</li>
		<?php $total++; ?>
		<?php endif; ?>
	<?php endwhile; endif; ?>
	</ul>
	</div>
	<div class="nav-wrapper clearfix">
		<div class="nav-sub-wrapper">
			<h2>Innovator’s DNA Assessment</h2>
			<div class="nav-counter-wrap clearfix">
				<span id="nav-complete" class="nav-counters">
					<h4>Completed</h4>
					<h3><?php echo $initial_completed; ?></h3>
				</span>
				<span id="nav-remain" class="nav-counters">
					<h4>Remaining</h4>
					<h3><?php echo $count - $initial_completed - 1; ?></h3>
				</span>
			</div>
			<ul class="sidebar_index">
				<li>1-10</li>
				<li>11-20</li>
				<li>21-30</li>
				<li>31-40</li>
				<li>41-50</li>
				<li>51-60</li>
				<li>61-65</li>
			</ul>
			<ul class="flexslider-control-nav clearfix">
				<?php for($x = 0; $x < $total; $x++){ ?>
					<?php
					$class = '';
					if(($x < 1) || ($x > ($total-3))){
						$class = 'hidden';
					} elseif(1 == $x){
						$class = 'pre-assessment-nav';
					} elseif($total - 3 == $x){
						$class = 'post-assessment-nav';
					} if(array_key_exists($x, $completed)){
						$class = 'complete';
					}
					?>
					<li class="<?php echo $class; ?>">
						<a><?php echo $x; ?></a>
						<?php if($class == 'pre-assessment-nav'): ?>
							<span>Pre-Assessment: Demographics</span>
						<?php elseif($class == 'post-assessment-nav'): ?>
							<span>Post-Assessment: Experiences</span>
						<?php endif; ?>
					</li>
				<?php } ?>
			</ul>
			<p>Click any box to jump to a position in the assessment</p>
		</div>
		<a class="control-toggle">Show Navigation</a>
	</div>
<?php } // Logged in conditional ?>
    </div><!-- end of #wrapper -->
    <?php responsive_wrapper_end(); // after wrapper hook ?>
</div><!-- end of #container -->
<?php responsive_container_end(); // after container hook ?>
<?php wp_footer(); ?>
</body>
</html>