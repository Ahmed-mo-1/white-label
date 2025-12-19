<?php

add_action('admin_menu', 'remove_unwanted_admin_menus', 999);

function remove_unwanted_admin_menus() {

    /* ---- WordPress Core ---- */
    remove_menu_page('index.php');                  // Dashboard
    remove_menu_page('edit.php');                   // Posts
    remove_menu_page('edit.php?post_type=page');    // Pages
    remove_menu_page('upload.php');                 // Media
    remove_menu_page('edit-comments.php');          // Comments
    remove_menu_page('themes.php');                 // Appearance
    remove_menu_page('plugins.php');                // Plugins
    remove_menu_page('users.php');                  // Users
    remove_menu_page('tools.php');                  // Tools
    remove_menu_page('options-general.php');        // Settings

    /* ---- WooCommerce ---- */
    remove_menu_page('woocommerce');                // WooCommerce
    remove_menu_page('edit.php?post_type=product'); // Products
    remove_menu_page('edit.php?post_type=shop_order'); // Orders
    remove_menu_page('edit.php?post_type=shop_coupon'); // Coupons
    remove_menu_page('wc-admin');                   // WooCommerce Admin

    /* ---- Elementor ---- */
    remove_menu_page('elementor');                  // Elementor
    remove_menu_page('edit.php?post_type=elementor_library'); // Templates
    remove_menu_page('elementor-app');               // Elementor App (new versions)

}



function remove_admin_menus() {

    // Core menu items
    remove_menu_page('edit.php');                  // Posts
    remove_menu_page('edit.php?post_type=page');   // Pages
    remove_menu_page('edit-comments.php');         // Comments
    remove_menu_page('tools.php');                 // Tools
    remove_menu_page('options-general.php');       // Settings

}
add_action('admin_menu', 'remove_admin_menus', 999);

function remove_admin_submenus() {

    // Settings submenus
    remove_submenu_page('options-general.php', 'options-writing.php');
    remove_submenu_page('options-general.php', 'options-reading.php');
    remove_submenu_page('options-general.php', 'options-discussion.php');

    // Tools submenus
    remove_submenu_page('tools.php', 'import.php');
    remove_submenu_page('tools.php', 'export.php');

}
add_action('admin_menu', 'remove_admin_submenus', 999);

add_action('wp_before_admin_bar_render', function () {
    global $wp_admin_bar;

    $wp_admin_bar->remove_node('new-post');
    $wp_admin_bar->remove_node('comments');
});