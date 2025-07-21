<?php 
add_action('rest_api_init', 'my_more_posts');

function my_more_posts() {
    register_rest_route('myplugin/v1', '/myposts', array(
        'methods' => 'GET',
        'callback' => 'my_more_posts_callback',
    ));
}

function my_more_posts_callback( WP_REST_Request $request ) {
    $args = array(
        'posts_per_page' => -1,
    );
    return get_posts($args);
}