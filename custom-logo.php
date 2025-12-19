<?php

/*
add_action('plugins_loaded', function () {
    define('WHITE_LABEL_PLUGIN_URL', plugin_dir_url(__FILE__));
});
*/

add_action('adminmenu', function() {
    $logo = plugin_dir_url(__FILE__) . 'imgs/1.png';
    echo '<div style="order: -1; width: 100%; margin: auto" class="custom-admin-logo">
            <img style="width: 100%" src="' . esc_url($logo) . '" alt="Logo">
          </div>';
});

/*
add_action('wp_before_admin_bar_render', function() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_node('wp-logo');
});
*/