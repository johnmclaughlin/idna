<?php
function load_scripts() {
	wp_enqueue_script(
		'flexslider',
		get_stylesheet_directory_uri() . '/js/jquery.flexslider-min.js',
		array('jquery'),
		1,
		true
	);
    if(!is_page(19)):
    wp_enqueue_script(
        'cycle',
        get_stylesheet_directory_uri() . '/js/jquery.cycle.all.js',
        array('jquery'),
        1,
        true
    );
    wp_enqueue_script(
        'highcharts',
        get_stylesheet_directory_uri() . '/js/highcharts.js',
        array('jquery'),
        1,
        true
    );
    wp_enqueue_script(
        'colorbox-min',
        get_stylesheet_directory_uri() . '/js/jquery.colorbox-min.js',
        array('jquery', 'validator'),
        1,
        true
    );
    endif;
    wp_enqueue_script(
        'jquery-ui',
        'http://code.jquery.com/ui/1.9.1/jquery-ui.js',
        array('jquery'),
        1,
        true
    );
    wp_enqueue_script(
        'validator',
        get_stylesheet_directory_uri() . '/js/jquery.validate.min.js',
        array('jquery'),
        1,
        true
    );
    wp_enqueue_script(
        'validator-methods',
        get_stylesheet_directory_uri() . '/js/additional-methods.min.js',
        array('jquery', 'validator'),
        1,
        true
    );

    if(is_page(19)):
        wp_enqueue_script(
            'custom',
            get_stylesheet_directory_uri() . '/js/assessment-script.js',
            array('jquery'),
            1,
            true
        );
    else:
    	wp_enqueue_script(
    		'custom',
    		get_stylesheet_directory_uri() . '/js/script.js',
    		array('jquery'),
    		1,
    		true
    	);
    endif;

	wp_enqueue_style(
		'jason',
		get_stylesheet_directory_uri() . '/jason.css'
	);
    wp_enqueue_style(
        'jqui',
        'http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css'
    );
    wp_dequeue_script('responsive-js');
    wp_dequeue_script('responsive-plugins');
    wp_dequeue_script('comment-reply');
}
add_action('wp_enqueue_scripts', 'load_scripts', 15);

add_image_size( 'icon', 34, 26, true);
add_image_size('legendglyph', 15, 15, true );
add_image_size('profilegraph', 303, 362, true );
add_image_size('development_media', 160, 160, true );
add_image_size('innofootergraph', 129, 129, true );
 
if(function_exists('register_field'))
{ 
	register_field('Gravity_Forms_field', dirname(__File__) . '/gravity_forms.php');
}

add_action('gform_after_submission', 'post_to_third_party', 10, 2);
function post_to_third_party($entry, $form) {
    
    $post_url = get_bloginfo('url') . '/gform.php';
    if($form['id'] == 1){
        $action = 'preassessment';
        $pid_field = 11; // Field ID for PID
    } elseif($form['id'] == 2) {
        $action = 'postassessment';
        $pid_field = 16; // Field ID for PID
    }

    $responses = array();
    foreach($entry as $field_id => $entry_value){
        if(is_numeric($field_id) && ($field_id != $pid_field)){
            $responses[$field_id] = $entry_value;
        }
    }
    $body = array(
        'pid' => $entry[$pid_field],
        'action' => $action,
        'responses' => $responses
    );

    $request = new WP_Http();
    $response = $request->post($post_url, array('body' => $body));
//    echo '<pre>';
//    print_r($response);
//    echo '</pre>';
}
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
if (!function_exists('disableAdminBar')) {
    function disableAdminBar(){
        remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 ); // for the front end

        function remove_admin_bar_style_frontend() { // css override for the frontend
          echo '<style type="text/css" media="screen">
          html { margin-top: 0px !important; }
          * html body { margin-top: 0px !important; }
          </style>';
        }
        add_filter('wp_head','remove_admin_bar_style_frontend', 99);
    }
}
// add_filter('admin_head','remove_admin_bar_style_backend'); // Original version
if($user_id != 1){
    add_action('init','disableAdminBar'); // New version
}

function my_custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url('.get_bloginfo('stylesheet_directory').'/images/login_logo.png) !important; height:136px!important;background-size:auto!important;}
    </style>';
}

add_action('login_head', 'my_custom_login_logo');