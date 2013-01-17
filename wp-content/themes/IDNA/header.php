<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Header Template
 *
 *
 * @file           header.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2012 ThemeID
 * @license        license.txt
 * @version        Release: 1.2
 * @filesource     wp-content/themes/responsive/header.php
 * @link           http://codex.wordpress.org/Theme_Development#Document_Head_.28header.php.29
 * @since          available since Release 1.0
 */
?>
<!doctype html>
<!--[if lt IE 7 ]> <html class="no-js ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8 ]>    <html class="no-js ie" <?php language_attributes(); ?>> <![endif]-->
<!--[if !(IE)]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>

<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes" />


<title><?php wp_title('&#124;', true, 'right'); ?><?php bloginfo('name'); ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/images/favicon.ico" />

<?php wp_enqueue_style('responsive-style', get_stylesheet_uri(), false, '1.7.9');?>

<?php wp_head(); ?>
<script type="text/javascript" src="http://fast.fonts.com/jsapi/db6a5d5c-42c8-4d10-8b63-26aa91d1db78.js"></script>
</head>

<body <?php body_class(); ?> onload="window.top.scrollTo(0,1);">
<div id="top_banner">
    <div class="header_footer_wrap">
        <div id="logo">
            <a href="<?php echo home_url('/'); ?>"><img src="<?php header_image(); ?>" width="<?php if(function_exists('get_custom_header')) { echo get_custom_header() -> width;} else { echo HEADER_IMAGE_WIDTH;} ?>" height="<?php if(function_exists('get_custom_header')) { echo get_custom_header() -> height;} else { echo HEADER_IMAGE_HEIGHT;} ?>" alt="<?php bloginfo('name'); ?>" /></a>
        </div><!-- end of #logo -->
        <?php if(is_page(19)): ?>
        	<h1 class="page_title">Innovator’s DNA Self Assessment</h1>
    	<?php elseif(is_page(120)): ?>
            <h1 class="page_title">Innovator’s DNA Self Assessment Feedback Report</h1>
        <?php else: ?>
        	<h1 class="page_title"><?php the_title(); ?></h1>
    	<?php endif; ?>
    </div>
</div>                 
<?php responsive_container(); // before container hook ?>
<div id="container" class="hfeed">
         
    <?php responsive_header(); // before header hook ?>
    <div id="header" class="clearfix">
    
        <?php if (has_nav_menu('top-menu', 'responsive')) { ?>
	        <?php wp_nav_menu(array(
				    'container'       => '',
					'fallback_cb'	  =>  false,
					'menu_class'      => 'top-menu',
					'theme_location'  => 'top-menu')
					); 
				?>
        <?php } ?>
        
    <?php responsive_in_header(); // header hook ?>
   
	   
    <?php get_sidebar('top'); ?>
			<?php if(!is_page(array(19, 80, 93, 109, 117))){ // Don't show menu on assessment page ?>
				<?php /*wp_nav_menu(array(
				    'container'       => '',
					'theme_location'  => 'header-menu')
					); */
				?>
            <?php } ?>
            <?php /*if (has_nav_menu('sub-header-menu', 'responsive')) { ?>
	            <?php wp_nav_menu(array(
				    'container'       => '',
					'menu_class'      => 'sub-header-menu',
					'theme_location'  => 'sub-header-menu')
					); 
				?>
            <?php }*/ ?>
 
    </div><!-- end of #header -->
    <?php responsive_header_end(); // after header hook ?>
    
	<?php responsive_wrapper(); // before wrapper ?>
    <div id="wrapper" class="clearfix">
        <div id="notecard_left"></div>
        <div id="notecard_right"></div>
    <?php responsive_in_wrapper(); // wrapper hook ?>
