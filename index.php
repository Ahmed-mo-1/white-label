<?php
/*
	Plugin name: white label
*/

if (!defined('ABSPATH')) {
    exit;
}



include_once plugin_dir_path(__FILE__) . 'dequeue-default.php'; 
include_once plugin_dir_path(__FILE__) . 'new-links.php'; 
include_once plugin_dir_path(__FILE__) . 'custom-logo.php'; 

add_action('admin_head', function () {

	include_once plugin_dir_path(__FILE__) . 'root-style.php';
	include_once plugin_dir_path(__FILE__) . 'side-menu.php';
	include_once plugin_dir_path(__FILE__) . 'dropdown-script.php';

});


include_once plugin_dir_path(__FILE__) . 'clean-dashboard.php'; 
include_once plugin_dir_path(__FILE__) . 'new-reports.php'; 
include_once plugin_dir_path(__FILE__) . 'new-posts-pages.php'; 
include_once plugin_dir_path(__FILE__) . 'remove-links.php'; 
include_once plugin_dir_path(__FILE__) . 'ajax-load-pages.php'; 
include_once plugin_dir_path(__FILE__) . 'order-pages.php'; 





add_action( 'admin_footer', 'amtf_inject_toggle_logic' );
function amtf_inject_toggle_logic() {
    ?>
    <style>
        /* 1. HIDE BUTTON BY DEFAULT (Desktop) */
        #amtf-fab {
            display: none; 
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #2271b1;
            color: white;
            border-radius: 50%;
            z-index: 999999;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            cursor: pointer;
            font-size: 24px;
            border: none;
        }

        /* 2. MOBILE ONLY SETTINGS (Below 782px) */
        @media screen and (max-width: 782px) {
            #amtf-fab {
                display: flex; /* Show only on mobile */
            }

            /* Ensure content takes up full width when menu is hidden */
            #wpbody {
                margin-left: 0 !important;
            }

            /* Hide the menu off-screen by default on mobile */
            #adminmenumain {
                display: none !important;
            }

            /* When the toggle class is active... */
            body.amtf-active #adminmenumain {
                display: block !important;
                position: fixed !important;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 999998;
                width: 190px !important;
                overflow-y: auto;
            }

            /* Dim the background content when menu is open */
            body.amtf-active #wpbody {
                opacity: 0.3;
                pointer-events: none; /* Prevent clicking content while menu is open */
            }
        }
    </style>

    <button id="amtf-fab" type="button">☰</button>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var fab = document.getElementById('amtf-fab');
        var body = document.body;

        fab.addEventListener('click', function() {
            body.classList.toggle('amtf-active');
            
            // Toggle icon between Burger and X
            if (body.classList.contains('amtf-active')) {
                fab.innerHTML = '✕';
            } else {
                fab.innerHTML = '☰';
            }
        });

        // Close menu if user clicks the dimmed content area
        document.getElementById('wpbody').addEventListener('click', function() {
            if (body.classList.contains('amtf-active')) {
                body.classList.remove('amtf-active');
                fab.innerHTML = '☰';
            }
        });
    });
    </script>
    <?php
}











// 1. Register Menus
/*
add_action('admin_menu', 'mat_register_menus');
function mat_register_menus() {
    add_menu_page('Modern Users', 'Modern Users', 'manage_options', 'modern-users', 'mat_render_user_manager', 'dashicons-admin-users', 60);
    add_submenu_page('modern-users', 'Install Plugins', 'Install Plugins', 'install_plugins', 'modern-plugins', 'mat_render_plugin_installer');
    add_submenu_page(null, 'Edit User', 'Edit User', 'manage_options', 'modern-user-editor', 'mat_render_user_editor');
add_submenu_page('modern-users', 'General Settings', 'Settings', 'manage_options', 'modern-settings', 'mat_render_settings');
add_submenu_page('modern-users', 'Permalinks', 'Permalinks', 'manage_options', 'modern-permalinks', 'mat_render_permalinks');
}*/

add_action('admin_menu', 'mat_register_menus');

function mat_register_menus() {
    // 1. Modern Users (Main)
    add_menu_page('Modern Users', 'Modern Users', 'manage_options', 'modern-users', 'mat_render_user_manager', 'dashicons-admin-users', 60);

    // 2. Install Plugins
    add_menu_page('Install Plugins', 'Install Plugins', 'install_plugins', 'modern-plugins', 'mat_render_plugin_installer', 'dashicons-admin-plugins', 61);

    // 3. Edit User (Hidden from menu but accessible via URL)
    // Note: Since you had 'null' before, this remains a "hidden" page unless you give it a position.
    add_submenu_page(null, 'Edit User', 'Edit User', 'manage_options', 'modern-user-editor', 'mat_render_user_editor');

    // 4. General Settings
    add_menu_page('Modern Settings', 'Settings', 'manage_options', 'modern-settings', 'mat_render_settings', 'dashicons-admin-generic', 62);

    // 5. Permalinks
    add_menu_page('Modern Permalinks', 'Permalinks', 'manage_options', 'modern-permalinks', 'mat_render_permalinks', 'dashicons-admin-links', 63);
}

// 2. Shared CSS Styles
function mat_get_styles() {
    ?>
    <style>
        #wpcontent { background: #0f172a; padding: 0; }
        .admin-wrap { padding: 40px; color: #f8fafc; font-family: 'Inter', sans-serif; max-width: 1100px; margin: 0 auto; }
        .card-bg { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 25px; margin-bottom: 25px; }
        .modern-table { width: 100%; border-collapse: collapse; }
        .modern-table th { text-align: left; padding: 12px; border-bottom: 2px solid #334155; color: #94a3b8; font-size: 12px; text-transform: uppercase; }
        .modern-table td { padding: 15px 12px; border-bottom: 1px solid #334155; font-size: 14px; }
        .btn-primary { background: #38bdf8; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; font-weight: 600; display: inline-block; }
        .btn-danger { background: #ef4444; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; border: none; cursor: pointer; font-size: 11px; }
        .input-dark { background: #0f172a; border: 1px solid #334155; color: #fff; padding: 10px; border-radius: 6px; width: 100%; max-width: 400px; margin-top: 5px; }
        label { display: block; color: #94a3b8; font-size: 13px; margin-top: 15px; }
        .app-pass-box { background: #064e3b; color: #34d399; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 18px; margin-bottom: 20px; border: 1px solid #059669; }
        .empty-state { color: #64748b; font-style: italic; font-size: 13px; text-align: center; padding: 20px; }
    </style>
    <?php
}

// 3. PAGE: User Manager (List)
function mat_render_user_manager() {
    mat_get_styles();
    $users = get_users(['number' => 20]);
    ?>
    <div class="admin-wrap">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h1>User Management</h1>
            <a href="<?php echo admin_url('user-new.php'); ?>" class="btn-primary">Add New User</a>
        </div>
        <div class="card-bg">
            <table class="modern-table">
                <thead>
                    <tr><th>User</th><th>Email</th><th>Role</th><th>Phone</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : 
                        $phone = get_user_meta($user->ID, 'billing_phone', true) ?: '—';
                    ?>
                    <tr>
                        <td><strong><?php echo $user->display_name; ?></strong></td>
                        <td><?php echo $user->user_email; ?></td>
                        <td><span style="background:#334155; padding:4px 8px; border-radius:4px; font-size:11px;"><?php echo $user->roles[0]; ?></span></td>
                        <td><?php echo $phone; ?></td>
                        <td><a href="<?php echo admin_url('admin.php?page=modern-user-editor&user_id='.$user->ID); ?>" style="color:#38bdf8; font-weight:600;">Manage</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

// 4. PAGE: User Editor & Password Manager
function mat_render_user_editor() {
    mat_get_styles();
    $user_id = intval($_GET['user_id']);
    $user = get_userdata($user_id);
    if (!$user) return;

    // -- ACTION: Save Profile --
    if (isset($_POST['mat_save_user'])) {
        wp_update_user([
            'ID' => $user_id,
            'display_name' => sanitize_text_field($_POST['d_name']),
            'user_email' => sanitize_email($_POST['email']),
            'role' => sanitize_text_field($_POST['role'])
        ]);
        update_user_meta($user_id, 'billing_phone', sanitize_text_field($_POST['phone']));
        echo '<div class="notice notice-success"><p>Profile updated successfully.</p></div>';
        $user = get_userdata($user_id);
    }

    // -- ACTION: Generate App Password --
    $new_pass_display = '';
    if (isset($_POST['mat_gen_app_pass'])) {
        $app_name = sanitize_text_field($_POST['app_name']);
        $created = WP_Application_Passwords::create_new_application_password($user_id, ['name' => $app_name]);
        if (!is_wp_error($created)) {
            $new_pass_display = $created[0];
        }
    }

    // -- ACTION: Revoke App Password --
    if (isset($_GET['revoke_uuid'])) {
        WP_Application_Passwords::delete_application_password($user_id, sanitize_text_field($_GET['revoke_uuid']));
        echo '<div class="notice notice-warning"><p>Application password revoked.</p></div>';
    }

    // Get current passwords
    $existing_passes = WP_Application_Passwords::get_user_application_passwords($user_id);

    ?>
    <div class="admin-wrap">
        <a href="<?php echo admin_url('admin.php?page=modern-users'); ?>" style="color:#94a3b8; text-decoration:none;">← Back to Users</a>
        <h1 style="margin-top:10px;">Edit User: <?php echo $user->user_login; ?></h1>
        
        <div style="display:grid; grid-template-columns: 400px 1fr; gap: 30px; align-items: start;">
            
            <div class="card-bg">
                <h3 style="margin-top:0;">Profile Details</h3>
                <form method="post">
                    <label>Display Name</label>
                    <input type="text" name="d_name" class="input-dark" value="<?php echo esc_attr($user->display_name); ?>">
                    
                    <label>Email Address</label>
                    <input type="email" name="email" class="input-dark" value="<?php echo esc_attr($user->user_email); ?>">
                    
                    <label>Phone (Billing)</label>
                    <input type="text" name="phone" class="input-dark" value="<?php echo esc_attr(get_user_meta($user_id, 'billing_phone', true)); ?>">
                    
                    <label>System Role</label>
                    <select name="role" class="input-dark">
                        <?php wp_dropdown_roles($user->roles[0]); ?>
                    </select>
                    
                    <div style="margin-top:25px;">
                        <button type="submit" name="mat_save_user" class="btn-primary" style="width:100%;">Save All Changes</button>
                    </div>
                </form>
            </div>

            <div>
                <div class="card-bg">
                    <h3 style="margin-top:0;">Manage Application Passwords</h3>
                    
                    <?php if ($new_pass_display) : ?>
                        <div class="app-pass-box">
                            <span style="font-size:12px; color:#fff; display:block; margin-bottom:5px;">New Password Generated:</span>
                            <strong><?php echo $new_pass_display; ?></strong>
                            <p style="font-size:11px; margin-top:10px; color:#a7f3d0;">Copy this now. It will not be shown again.</p>
                        </div>
                    <?php endif; ?>

                    <table class="modern-table">
                        <thead>
                            <tr><th>Name</th><th>Created</th><th>Last Used</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($existing_passes)) : foreach ($existing_passes as $pass) : ?>
                            <tr>
                                <td><strong><?php echo esc_html($pass['name']); ?></strong></td>
                                <td style="color:#94a3b8;"><?php echo date('M d, Y', $pass['created']); ?></td>
                                <td style="color:#94a3b8;"><?php echo $pass['last_used'] ? date('M d, Y', $pass['last_used']) : 'Never'; ?></td>
                                <td>
                                    <a href="<?php echo add_query_arg('revoke_uuid', $pass['uuid']); ?>" 
                                       class="btn-danger" 
                                       onclick="return confirm('Revoke access for this application?');">Revoke</a>
                                </td>
                            </tr>
                            <?php endforeach; else : ?>
                                <tr><td colspan="4" class="empty-state">No active application passwords found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <hr style="border:0; border-top:1px solid #334155; margin:30px 0;">
                    
                    <h4>Generate New Password</h4>
                    <form method="post" style="display:flex; gap:10px;">
                        <input type="text" name="app_name" class="input-dark" placeholder="e.g. iPhone App" required style="margin:0;">
                        <button type="submit" name="mat_gen_app_pass" class="btn-primary" style="background:#10b981;">Generate</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <?php
}

// 5. PAGE: Plugin Installer (Library Search)
function mat_render_plugin_installer() {
    mat_get_styles();
    ?>
    <div class="admin-wrap">
        <h1>Plugin Marketplace</h1>
        <div class="card-bg" style="display:flex; gap:10px;">
            <input type="text" id="p-search" class="input-dark" placeholder="Search official library..." style="flex:1;">
            <button id="p-btn" class="btn-primary">Search</button>
        </div>
        <div id="p-results" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:20px;"></div>
    </div>
    <script>
    jQuery(function($) {
        $('#p-btn').on('click', function() {
            const val = $('#p-search').val();
            if(!val) return;
            $(this).text('Searching...');
            $.post(ajaxurl, { action: 'mat_search_plugins', term: val }, function(res) {
                $('#p-btn').text('Search');
                $('#p-results').html(res.data);
            });
        });
    });
    </script>
    <?php
}

// 6. AJAX: Plugin Search Handler
add_action('wp_ajax_mat_search_plugins', 'mat_ajax_plugin_search');
function mat_ajax_plugin_search() {
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    $api = plugins_api('query_plugins', ['search' => sanitize_text_field($_POST['term']), 'per_page' => 12]);
    ob_start();
    if($api->plugins) {
        foreach ($api->plugins as $p) {
            $link = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin='.$p->slug), 'install-plugin_'.$p->slug);
            ?>
            <div style="background:#1e293b; padding:20px; border-radius:12px; border:1px solid #334155; display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                    <img src="<?php echo $p->icons['default']; ?>" style="width:40px; border-radius:6px; margin-bottom:10px;">
                    <h4 style="margin:0;"><?php echo $p->name; ?></h4>
                </div>
                <a href="<?php echo $link; ?>" class="btn-primary" style="font-size:11px; margin-top:15px; text-align:center;">Install Now</a>
            </div>
            <?php
        }
    }
    wp_send_json_success(ob_get_clean());
}





function mat_render_settings() {
    mat_get_styles();
    
    if (isset($_POST['mat_save_settings'])) {
        update_option('blogname', sanitize_text_field($_POST['blogname']));
        update_option('blogdescription', sanitize_text_field($_POST['blogdescription']));
        update_option('WPLANG', sanitize_text_field($_POST['site_lang']));
        echo '<div class="notice notice-success"><p>Site settings updated.</p></div>';
    }

    ?>
    <div class="admin-wrap">
        <h1>General Settings</h1>
        <div class="card-bg" style="max-width: 600px;">
            <form method="post">
                <label>Website Name</label>
                <input type="text" name="blogname" class="input-dark" value="<?php echo esc_attr(get_option('blogname')); ?>">

                <label>Tagline</label>
                <input type="text" name="blogdescription" class="input-dark" value="<?php echo esc_attr(get_option('blogdescription')); ?>">

                <label>Site Language</label>
                <select name="site_lang" class="input-dark">
                    <?php
                    require_once ABSPATH . 'wp-admin/includes/translation-install.php';
                    $translations = wp_get_available_translations();
                    $current_lang = get_option('WPLANG');
                    
                    echo '<option value="">English (United States)</option>';
                    foreach ($translations as $code => $data) {
                        $selected = ($current_lang === $code) ? 'selected' : '';
                        echo '<option value="'.esc_attr($code).'" '.$selected.'>'.esc_html($data['native_name']).'</option>';
                    }
                    ?>
                </select>

                <div style="margin-top:25px;">
                    <button type="submit" name="mat_save_settings" class="btn-primary" style="width:100%;">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
    <?php
}





function mat_render_permalinks() {
    mat_get_styles();

    if (isset($_POST['mat_update_perms'])) {
        $struct = sanitize_text_field($_POST['selection']);
        if ($struct === 'custom') {
            $struct = sanitize_text_field($_POST['custom_struct']);
        }
        
        update_option('permalink_structure', $struct);
        flush_rewrite_rules(true);
        echo '<div class="notice notice-success"><p>Permalinks updated and flushed.</p></div>';
    }

    $current = get_option('permalink_structure');
    $options = [
        'Plain' => '',
        'Day and name' => '/%year%/%monthnum%/%day%/%postname%/',
        'Month and name' => '/%year%/%monthnum%/%postname%/',
        'Numeric' => '/archives/%post_id%',
        'Post name' => '/%postname%/',
    ];
    ?>
    <div class="admin-wrap">
        <h1>Permalink Settings</h1>
        <div class="card-bg">
            <form method="post">
                <table class="modern-table">
                    <?php foreach ($options as $label => $value) : ?>
                    <tr>
                        <td width="30">
                            <input type="radio" name="selection" value="<?php echo esc_attr($value); ?>" <?php checked($current, $value); ?>>
                        </td>
                        <td>
                            <strong style="color:#fff;"><?php echo $label; ?></strong><br>
                            <code style="color:#94a3b8; font-size:11px;"><?php echo home_url() . ($value ?: '/?p=123'); ?></code>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td>
                            <input type="radio" name="selection" value="custom" <?php if(!in_array($current, $options)) echo 'checked'; ?>>
                        </td>
                        <td>
                            <strong style="color:#fff;">Custom Structure</strong><br>
                            <input type="text" name="custom_struct" class="input-dark" value="<?php echo esc_attr($current); ?>" placeholder="/%category%/%postname%/">
                        </td>
                    </tr>
                </table>

                <div style="margin-top:25px;">
                    <button type="submit" name="mat_update_perms" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <?php
}
































// Add this to your theme's functions.php or a custom plugin

function redirect_admin_after_login($redirect_to, $request, $user) {
    // Check if the user object exists and is an administrator
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('administrator', $user->roles)) {
            // Redirect to your custom admin page
            return admin_url('admin.php?page=modern-dark-report');
        }
    }
    // Default redirect for other users
    return $redirect_to;
}
add_filter('login_redirect', 'redirect_admin_after_login', 10, 3);


// Add this to your theme's functions.php or a custom plugin
function redirect_admin_first_time() {
    // Only redirect in the admin area and not AJAX requests
    if (is_admin() && !defined('DOING_AJAX')) {
        $user = wp_get_current_user();

        // Only for administrators
        if (in_array('administrator', (array) $user->roles)) {
            $current_url = $_SERVER['REQUEST_URI'];

            // Check if URL is exactly /wp-admin or /wp-admin/ (may include site path)
            $wp_admin_path = '/' . trim(parse_url(admin_url(), PHP_URL_PATH), '/') . '/';
            if (rtrim($current_url, '/') === rtrim($wp_admin_path, '/')) {
                wp_redirect(admin_url('admin.php?page=modern-dark-report'));
                exit;
            }
        }
    }
}
add_action('admin_init', 'redirect_admin_first_time');