<?php

add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_page', '__return_false', 10);


add_action('admin_enqueue_scripts', function() {
    wp_deregister_style('dashicons');

    wp_dequeue_style('wp-admin');
    wp_dequeue_style('admin-menu');
    wp_dequeue_style('colors');

    wp_dequeue_script('admin-menu');
});

add_filter('show_admin_bar', '__return_false');