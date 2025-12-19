<?php

// Add logo + front-end + logout buttons
add_action('admin_menu', function() {


    // 2. Add front-end button
    add_menu_page(
        'Visit Site',
        'Front-end',
        'read',
        'front-end-link',
        function() {
            wp_redirect(home_url());
            exit;
        },
        'dashicons-admin-site',
        1
    );

    // 3. Add logout button
    add_menu_page(
        'Logout',
        'Logout',
        'read',
        'logout-link',
        function() {
            wp_logout();
            wp_redirect(home_url('/wp-admin'));
            exit;
        },
        'dashicons-shield-alt',
        2
    );
});