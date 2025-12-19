<?php

// Register the custom menu page
add_action('admin_menu', 'register_modern_plugin_manager');

function register_modern_plugin_manager() {
    add_menu_page(
        'Custom Manager',
        'Plugin Manager',
        'activate_plugins', // Only users who can manage plugins see this
        'modern-plugin-manager',
        'render_modern_plugin_manager',
        'dashicons-admin-plugins',
        65
    );
}

// Handle plugin actions (Activate/Deactivate/Delete)
add_action('admin_init', 'handle_modern_plugin_actions');

function handle_modern_plugin_actions() {
    if (!isset($_GET['page']) || $_GET['page'] !== 'modern-plugin-manager') return;
    if (!isset($_GET['plugin_action']) || !isset($_GET['plugin'])) return;

    $action = $_GET['plugin_action'];
    $plugin = $_GET['plugin'];

    // Security Check
    check_admin_referer('modern_plugin_action_' . $plugin);

    if ($action === 'activate') {
        activate_plugin($plugin);
    } elseif ($action === 'deactivate') {
        deactivate_plugins($plugin);
    } elseif ($action === 'delete') {
        delete_plugins(array($plugin));
    }

    // Redirect back to clean URL
    wp_redirect(admin_url('admin.php?page=modern-plugin-manager&status=success'));
    exit;
}

function render_modern_plugin_manager() {
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $all_plugins = get_plugins();
    ?>
    <style>
        #wpcontent { background: var(--background-color); margin-left: 0; }
        .plugin-manager-wrap { padding: 40px; color: #f8fafc; font-family: 'Inter', system-ui, sans-serif; max-width: 100%; }
        .header { margin-bottom: 40px; border-left: 4px solid #38bdf8; padding-left: 20px; }
        .header h1 { color: #fff; font-size: 32px; margin: 0; }
        
        /* Grid Layout */
        .plugin-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }
        
        /* Modern Cards */
        .plugin-card { 
            background: #202020; border-radius: 16px; padding: 25px; 
            border: 1px solid #334155; position: relative; 
            display: flex; flex-direction: column; justify-content: space-between;
        }
        .plugin-card.active { border-left: 5px solid #10b981; }
        .plugin-card h3 { margin: 0 0 10px 0; color: #fff; font-size: 18px; }
        .plugin-card .version { font-size: 12px; color: #64748b; background: var(--background-color); padding: 2px 8px; border-radius: 4px; }
        .plugin-card .desc { color: #94a3b8; font-size: 14px; line-height: 1.5; margin: 15px 0; min-height: 60px; }

        /* Modern Buttons */
        .actions { display: flex; gap: 10px; margin-top: auto; padding-top: 20px; border-top: 1px solid #334155; }
        .btn { 
            text-decoration: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; 
            transition: all 0.2s; text-align: center; flex: 1;
        }
        .btn-activate { background: #10b981; color: white; }
        .btn-activate:hover { background: #059669; }
        .btn-deactivate { background: #334155; color: #e2e8f0; }
        .btn-deactivate:hover { background: #475569; }
        .btn-delete { background: #ef4444; color: white; }
        .btn-delete:hover { background: #dc2626; }
    </style>

    <div class="plugin-manager-wrap">
        <div class="header">
            <h1>Plugin Commander</h1>
            <p>Modern management for your WordPress ecosystem.</p>
        </div>

        <div class="plugin-grid">
            <?php foreach ($all_plugins as $file => $data) : 
                $is_active = is_plugin_active($file);
                $nonce_url = wp_create_nonce('modern_plugin_action_' . $file);
                $base_url = admin_url('admin.php?page=modern-plugin-manager&plugin=' . $file . '&_wpnonce=' . $nonce_url);
            ?>
                <div class="plugin-card <?php echo $is_active ? 'active' : ''; ?>">
                    <div>
                        <h3><?php echo esc_html($data['Name']); ?> <span class="version">v<?php echo $data['Version']; ?></span></h3>
                        <p class="desc"><?php echo wp_trim_words($data['Description'], 20); ?></p>
                    </div>

                    <div class="actions">
                        <?php if ($is_active) : ?>
                            <a href="<?php echo $base_url . '&plugin_action=deactivate'; ?>" class="btn btn-deactivate">Deactivate</a>
                        <?php else : ?>
                            <a href="<?php echo $base_url . '&plugin_action=activate'; ?>" class="btn btn-activate">Activate</a>
                            <a href="<?php echo $base_url . '&plugin_action=delete'; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this plugin?')">Delete</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}










/**
 * Modern Dark Mode Post & Product Manager
 */

add_action('admin_menu', 'register_modern_content_manager');

function register_modern_content_manager() {
    add_menu_page(
        'Content Manager',
        'Quick Manage',
        'edit_posts',
        'modern-content-manager',
        'render_modern_content_manager',
        'dashicons-store',
        4
    );
}

function render_modern_content_manager() {
    $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'products';
    $post_type = ($current_tab == 'products') ? 'product' : 'post';

    // Fetch Items
    $items = get_posts([
        'post_type' => $post_type,
        'posts_per_page' => 12,
        'post_status' => 'any'
    ]);

    ?>
    <style>
        #wpcontent { background: var(--background-color); }
        .manager-wrap { padding: 30px; color: #f8fafc; font-family: 'Inter', sans-serif; }
        
        /* Tabs Styling */
        .tab-nav { display: flex; gap: 10px; margin-bottom: 30px; border-bottom: 1px solid #334155; padding-bottom: 15px; }
        .tab-nav a { 
            text-decoration: none; color: #94a3b8; padding: 10px 20px; border-radius: 8px; 
            font-weight: 600; transition: 0.3s;
        }
        .tab-nav a.active { background: #38bdf8; color: #fff; }

        /* Grid */
        .content-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        
        .item-card { 
            background: #202020; border: 1px solid #334155; border-radius: 12px; 
            overflow: hidden; display: flex; flex-direction: column;
        }
        .item-thumb { 
            height: 160px; background: var(--background-color); display: flex; align-items: center; 
            justify-content: center; position: relative; 
        }
        .item-thumb img { width: 100%; height: 100%; object-fit: cover; opacity: 0.7; }
        
        .item-details { padding: 20px; flex-grow: 1; }
        .item-details h3 { margin: 0 0 10px 0; font-size: 16px; color: #fff; height: 45px; overflow: hidden; }
        
        .badge { font-size: 11px; padding: 3px 8px; border-radius: 4px; font-weight: bold; text-transform: uppercase; }
        .badge-instock { background: #10b98122; color: #10b981; }
        .badge-draft { background: #f59e0b22; color: #f59e0b; }
        .badge-price { color: #38bdf8; font-size: 18px; font-weight: 800; display: block; margin-top: 10px; }

        .item-actions { 
            display: grid; grid-template-columns: 1fr 1fr; gap: 1px; 
            background: #334155; border-top: 1px solid #334155; 
        }
        .item-actions a { 
            background: #303030; color: #94a3b8; text-align: center; 
            padding: 12px; text-decoration: none; font-size: 12px; transition: 0.2s;
        }
        .item-actions a:hover { background: #334155; color: #fff; }
        .btn-trash:hover { color: #ef4444 !important; }
    </style>

    <div class="manager-wrap">
        <header>
            <h1 style="color:#fff;">Content Commander</h1>
            <div class="tab-nav">
                <a href="?page=modern-content-manager&tab=products" class="<?php echo $current_tab == 'products' ? 'active' : ''; ?>">Products</a>
                <a href="?page=modern-content-manager&tab=posts" class="<?php echo $current_tab == 'posts' ? 'active' : ''; ?>">Blog Posts</a>
            </div>
        </header>

        <div class="content-grid">
            <?php foreach ($items as $item) : 
                $img = get_the_post_thumbnail_url($item->ID, 'medium');
                $status = get_post_status($item->ID);
                //$edit_link = get_edit_post_link($item->ID);
				$edit_link = admin_url('admin.php?page=modern-editor&id=' . $item->ID);
                $view_link = get_permalink($item->ID);
                $delete_link = get_delete_post_link($item->ID);
            ?>
                <div class="item-card">
                    <div class="item-thumb">
                        <?php if ($img) : ?>
                            <img src="<?php echo $img; ?>">
                        <?php else : ?>
                            <span class="dashicons dashicons-format-image" style="font-size: 40px; color: #334155;"></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="item-details">
                        <span class="badge <?php echo $status == 'publish' ? 'badge-instock' : 'badge-draft'; ?>">
                            <?php echo $status; ?>
                        </span>
                        <h3><?php echo esc_html($item->post_title); ?></h3>
                        
                        <?php if ($current_tab == 'products' && function_exists('wc_get_product')) : 
                            $product = wc_get_product($item->ID); ?>
                            <span class="badge-price"><?php echo $product->get_price_html(); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="item-actions">
                        <a href="<?php echo $edit_link; ?>">Edit</a>
                        <a href="<?php echo $view_link; ?>" target="_blank">View</a>
                        <a href="<?php echo $delete_link; ?>" class="btn-trash" onclick="return confirm('Move to trash?')">Trash</a>
                        <a href="#" style="opacity: 0.5; pointer-events: none;">Duplicate</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}









/**
 * Modern Dark Mode Content Editor
 */

add_action('admin_menu', 'register_modern_editor_page');

function register_modern_editor_page() {
    add_submenu_page(
        null, // No parent (hidden from menu, accessed via link)
        'Modern Editor',
        'Modern Editor',
        'edit_posts',
        'modern-editor',
        'render_modern_editor'
    );
}

// Handle the Save Action
add_action('admin_init', 'handle_modern_editor_save');
function handle_modern_editor_save() {
    if (isset($_POST['modern_editor_save']) && check_admin_referer('modern_editor_action')) {
        $post_id = intval($_POST['post_id']);
        
        $updated_post = array(
            'ID'           => $post_id,
            'post_title'   => sanitize_text_field($_POST['post_title']),
            'post_content' => wp_kses_post($_POST['post_content']),
            'post_status'  => sanitize_text_field($_POST['post_status']),
        );

        wp_update_post($updated_post);

        // If it's a WooCommerce Product, update the price
        if (isset($_POST['product_price']) && function_exists('wc_get_product')) {
            update_post_meta($post_id, '_regular_price', sanitize_text_field($_POST['product_price']));
            update_post_meta($post_id, '_price', sanitize_text_field($_POST['product_price']));
        }

        wp_redirect(admin_url('admin.php?page=modern-content-manager&status=updated'));
        exit;
    }
}

function render_modern_editor() {
    $post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $post = get_post($post_id);

    if (!$post) {
        echo '<div class="wrap"><h1>Post not found.</h1></div>';
        return;
    }

    $is_product = (get_post_type($post_id) == 'product');
    $price = $is_product ? get_post_meta($post_id, '_regular_price', true) : '';

    ?>
    <style>
        #wpcontent { background: var(--background-color); }
        .editor-wrap { padding: 40px; color: #f8fafc; font-family: 'Inter', sans-serif; max-width: 900px; margin: 0 auto; }
        .editor-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .back-link { color: #38bdf8; text-decoration: none; font-size: 14px; }
        
        .edit-card { background: #202020; border-radius: 16px; padding: 40px; border: 1px solid #334155; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; color: #94a3b8; margin-bottom: 10px; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; }
        
        .modern-input { 
            width: 100%; background: var(--background-color); border: 1px solid #334155; border-radius: 8px; 
            padding: 12px 15px; color: #fff; font-size: 16px; transition: 0.3s;
        }
        .modern-input:focus { border-color: #38bdf8; outline: none; box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.2); }
        
        textarea.modern-input { min-height: 300px; line-height: 1.6; font-family: inherit; }
        
		.save-btn {
			background: #38bdf8;
			color: #fff;
			border: none;
			padding: 15px 40px;
			border-radius: 8px;
			font-size: 16px;
			font-weight: 700;
			cursor: pointer;
			transition: 0.2s;
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: 400;
		}
        .save-btn:hover { background: #0ea5e9; transform: translateY(-2px); }
        
        .status-select { cursor: pointer; }
    </style>

    <div class="editor-wrap">
        <div class="editor-header">
            <div>
                <a href="?page=modern-content-manager" class="back-link">← Back to Manager</a>
                <h1 style="color:#fff; margin-top:10px;">Edit <?php echo ucfirst(get_post_type($post_id)); ?></h1>
            </div>
            <div class="badge-instock" style="background: #38bdf822; color: #38bdf8; padding: 5px 12px; border-radius: 20px; font-size: 12px;">
                ID: #<?php echo $post_id; ?>
            </div>
        </div>

        <form method="post" action="">
            <?php wp_nonce_field('modern_editor_action'); ?>
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

            <div class="edit-card">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="post_title" class="modern-input" value="<?php echo esc_attr($post->post_title); ?>">
                </div>

                <?php if ($is_product) : ?>
                <div class="form-group">
                    <label>Price ($)</label>
                    <input type="number" step="0.01" name="product_price" class="modern-input" value="<?php echo esc_attr($price); ?>">
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Status</label>
                    <select name="post_status" class="modern-input status-select">
                        <option value="publish" <?php selected($post->post_status, 'publish'); ?>>Published</option>
                        <option value="draft" <?php selected($post->post_status, 'draft'); ?>>Draft</option>
                        <option value="private" <?php selected($post->post_status, 'private'); ?>>Private</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Content</label>
                    <textarea name="post_content" class="modern-input"><?php echo esc_textarea($post->post_content); ?></textarea>
                </div>

                <button type="submit" name="modern_editor_save" class="save-btn">Save Changes</button>
            </div>
        </form>
    </div>
    <?php
}
