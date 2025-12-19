<?php


/* ---------------------------------------------
   ADMIN BRANDING CUSTOMIZATIONS
----------------------------------------------*/

// 1. Change footer text
add_filter('admin_footer_text', function () {
    return ;
});

// 2. Remove WP version (or change it)
add_filter('update_footer', function () {
    return ''; // empty or your custom text
}, 9999);

// 3. Replace "Howdy" with "Welcome" + username
add_filter('admin_bar_menu', function ($wp_admin_bar) {
    $user = wp_get_current_user();
    $account = $wp_admin_bar->get_node('my-account');
    if(!$account) return;

    $new_title = str_replace('Howdy,', 'Welcome,', $account->title);
    $account->title = $new_title;
    $wp_admin_bar->add_node($account);
}, 25);

// 4. Remove WP logo from admin bar
add_action('admin_bar_menu', function ($wp_admin_bar) {
    $wp_admin_bar->remove_node('wp-logo');
}, 999);

// 5. Remove update/version nags
add_filter('update_nag', '__return_empty_string');
add_filter('core_update_footer', '__return_empty_string');










