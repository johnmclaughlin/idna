<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Pages Template
 *
 *
 * @file           page.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2012 ThemeID
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/responsive/page.php
 * @link           http://codex.wordpress.org/Theme_Development#Pages_.28page.php.29
 * @since          available since Release 1.0
 */
?>
<?php get_header(); ?>


        
<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
        
        <?php $options = get_option('responsive_theme_options'); ?>
        
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="post-title"><?php the_title(); ?></h1>
                
                <div class="post-entry">
                    <?php the_content(__('Read more &#8250;', 'responsive')); ?>
                    <?php
                    if (!is_user_logged_in()) {
                        wp_login_form();
                    } elseif(is_front_page()) {
                        // Grab current WP user ID
                        $current_user = wp_get_current_user();
                        $user_id = $current_user->ID; 

                        // Search for an active assessment
                        global $wpdb;
                        $current_assessment = $wpdb->get_results("SELECT * FROM assessments WHERE (user_id = $user_id AND complete IS NULL) ORDER BY start DESC");
                        if(count($current_assessment) > 0){ ?>
                            <a href="<?php echo get_permalink(19); ?>">Continue your unfinished assessment</a>
                        <?php } else { ?>
                            <a href="<?php echo get_permalink(19); ?>">Start new assessment</a>
                        <?php }

                        $complete_assessments = $wpdb->get_results("SELECT * FROM assessments WHERE (user_id = $user_id AND complete IS NOT NULL) ORDER BY start DESC");
                        if(count($complete_assessments) > 0){
                            echo '<ul class="prev-assessment-list">';
                            foreach($complete_assessments as $complete_assessment): ?>
                                <li><a href="<?php echo get_permalink(120); ?>/?pid=<?php echo $complete_assessment->PID; ?>">View assessment completed <?php echo date('M d, Y', $complete_assessment->complete); ?></a></li>

                            <?php endforeach;
                            echo '</ul>';
                        }
                    }
                    ?>
                </div><!-- end of .post-entry -->
                        

            </div><!-- end of #post-<?php the_ID(); ?> -->
            
            
        <?php endwhile; ?> 


<?php endif; ?>  
      


    </div><!-- end of #wrapper -->
    <?php responsive_wrapper_end(); // after wrapper hook ?>
</div><!-- end of #container -->
<?php responsive_container_end(); // after container hook ?>
<?php wp_footer(); ?>
</body>
</html>