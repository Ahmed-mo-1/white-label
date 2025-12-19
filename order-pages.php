<?php

// 1. Register the Sidebar Menus
add_action('admin_menu', 'register_modern_order_system');
function register_modern_order_system() {
    // Main Listing Page
    add_menu_page(
        'Modern Orders',
        'Modern Orders',
        'manage_woocommerce',
        'modern-orders-list',
        'render_modern_orders_list',
        'dashicons-cart',
        56
    );

    // Hidden Editor Page (Accessed via Edit links)
    add_submenu_page(
        null,
        'Edit Order',
        'Edit Order',
        'manage_woocommerce',
        'modern-order-editor',
        'render_modern_order_editor'
    );
}

// 2. Handle the Save Action
add_action('admin_init', 'handle_modern_order_save');
function handle_modern_order_save() {
    if (isset($_POST['modern_order_save']) && check_admin_referer('modern_order_action')) {
        $order_id = intval($_POST['order_id']);
        $order = wc_get_order($order_id);

        if ($order) {
            // Update Status
            $order->update_status(sanitize_text_field($_POST['order_status']));
            
            // Update Receipt Meta (The "Receipt" Column data)
            if (isset($_POST['order_receipt'])) {
                $order->update_meta_data('_receipt_link', sanitize_text_field($_POST['order_receipt']));
            }

            $order->save();

            // Redirect back to list or editor with success
            wp_redirect(admin_url('admin.php?page=modern-orders-list&status=updated'));
            exit;
        }
    }
}

function render_modern_orders_list() {
    $query = new WC_Order_Query(array(
        'limit'   => 20,
        'orderby' => 'date',
        'order'   => 'DESC',
    ));
    $orders = $query->get_orders();

    ?>
    <style>
        #wpcontent { background: #0f172a; }
        .list-wrap { padding: 40px; color: #f8fafc; font-family: 'Inter', sans-serif; max-width: 1200px; margin: 0 auto; }
        .orders-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-top: 30px; }
        
        .order-card { 
            background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 25px; 
            text-decoration: none; color: inherit; transition: 0.3s; display: flex; flex-direction: column;
            position: relative;
        }
        .order-card:hover { border-color: #38bdf8; transform: translateY(-3px); background: #232e42; }
        
        .order-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
        .order-id { font-size: 20px; font-weight: 800; color: #fff; }
        .status-badge { font-size: 11px; padding: 4px 10px; border-radius: 6px; text-transform: uppercase; font-weight: 700; background: #334155; }
        
        .cust-name { color: #94a3b8; margin-bottom: 5px; font-size: 14px; }
        .order-total { font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 15px; }

        /* Receipt Style */
        .receipt-container { margin-top: 10px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .receipt-preview-trigger { color: #38bdf8; cursor: pointer; text-decoration: underline; }
        .dot { height: 8px; width: 8px; border-radius: 50%; display: inline-block; }

        /* Action Buttons Area */
        .egpay-actions-footer { margin-top: 20px; padding-top: 15px; border-top: 1px solid #334155; display: flex; gap: 10px; }
        .egpay-btn { flex: 1; padding: 10px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 12px; transition: 0.2s; }
        .egpay-approve-btn { background: #10b981; color: white; }
        .egpay-approve-btn:hover { background: #059669; }
        .egpay-reject-btn { background: #ef4444; color: white; }
        .egpay-reject-btn:hover { background: #dc2626; }
        .egpay-btn:disabled { opacity: 0.5; cursor: not-allowed; }

        /* Modal for Receipt */
        .egpay-modal { display: none; position: fixed; z-index: 99999; inset: 0; background: rgba(0,0,0,0.8); align-items: center; justify-content: center; }
        .egpay-modal img { max-width: 90%; max-height: 80%; border-radius: 8px; border: 4px solid #fff; }
        .egpay-modal-close { position: absolute; top: 30px; right: 40px; color: #fff; font-size: 40px; cursor: pointer; }
    </style>

    <div class="list-wrap">
        <h1 style="color:#fff;">Store Orders</h1>
        <div class="orders-grid">
            <?php foreach ($orders as $order) : 
                $order_id = $order->get_id();
                $receipt = $order->get_meta('_egpay_receipt'); // Meta key from your other plugin
                $status = $order->get_status();
                $method = $order->get_payment_method();
                $nonce = wp_create_nonce('egpay_action_' . $order_id);
                $edit_url = admin_url('admin.php?page=modern-order-editor&id=' . $order_id);
            ?>
                <div class="order-card" id="order-card-<?php echo $order_id; ?>">
                    <a href="<?php echo $edit_url; ?>" style="text-decoration:none; color:inherit;">
                        <div class="order-header">
                            <span class="order-id">#<?php echo $order->get_order_number(); ?></span>
                            <span class="status-badge" id="badge-<?php echo $order_id; ?>"><?php echo $status; ?></span>
                        </div>
                        <div class="cust-name"><?php echo $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(); ?></div>
                        <div class="order-total"><?php echo $order->get_formatted_order_total(); ?></div>
                    </a>

                    <div class="receipt-container">
                        <?php if($receipt): ?>
                            <span class="dot" style="background:#10b981;"></span>
                            <span class="receipt-preview-trigger" data-img="<?php echo esc_url($receipt); ?>">View Receipt</span>
                        <?php else: ?>
                            <span class="dot" style="background:#64748b;"></span>
                            <span style="color:#64748b;">No Receipt</span>
                        <?php endif; ?>
                    </div>

                    <?php if ($method === 'EGPay' && $status === 'pending-review') : ?>
                        <div class="egpay-actions-footer" id="actions-wrap-<?php echo $order_id; ?>">
                            <button class="egpay-btn egpay-approve-btn js-egpay-action" 
                                    data-id="<?php echo $order_id; ?>" 
                                    data-action="approve" 
                                    data-nonce="<?php echo $nonce; ?>">Approve</button>
                            
                            <button class="egpay-btn egpay-reject-btn js-egpay-action" 
                                    data-id="<?php echo $order_id; ?>" 
                                    data-action="reject" 
                                    data-nonce="<?php echo $nonce; ?>">Reject</button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="receipt-modal" class="egpay-modal">
        <span class="egpay-modal-close">&times;</span>
        <img src="" id="receipt-modal-img">
    </div>

    <script>
    jQuery(function($) {
        // 1. Receipt Modal Handle
        $('.receipt-preview-trigger').on('click', function() {
            const img = $(this).data('img');
            $('#receipt-modal-img').attr('src', img);
            $('#receipt-modal').css('display', 'flex');
        });

        $('#receipt-modal, .egpay-modal-close').on('click', function() {
            $('#receipt-modal').hide();
        });

        // 2. Ajax Order Actions (Approve/Reject)
        $('.js-egpay-action').on('click', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const orderId = $btn.data('id');
            const actionType = $btn.data('action');
            const nonce = $btn.data('nonce');

            if (actionType === 'reject' && !confirm('Reject this payment?')) return;

            $btn.closest('.egpay-actions-footer').find('button').prop('disabled', true);
            $btn.text('...');

            $.post(ajaxurl, {
                action: 'egpay_order_action',
                order_id: orderId,
                action_type: actionType,
                nonce: nonce
            }, function(res) {
                if (res.success) {
                    // Update the status badge in the UI
                    $(`#badge-${orderId}`).text(res.data.new_status);
                    // Remove the action buttons since the task is done
                    $(`#actions-wrap-${orderId}`).fadeOut();
                } else {
                    alert(res.data || 'Error');
                    $btn.closest('.egpay-actions-footer').find('button').prop('disabled', false);
                    $btn.text(actionType === 'approve' ? 'Approve' : 'Reject');
                }
            });
        });
    });
    </script>
    <?php
}

// 4. Render the Modern Editor Page
function render_modern_order_editor() {
    $order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $order = wc_get_order($order_id);

    if (!$order) {
        echo '<div class="wrap"><h1 style="color:#fff;">Order not found.</h1></div>';
        return;
    }

    $receipt_val = $order->get_meta('_receipt_link');

    ?>
    <style>
        #wpcontent { background: #0f172a; }
        .editor-wrap { padding: 40px; color: #f8fafc; font-family: 'Inter', sans-serif; max-width: 800px; margin: 0 auto; }
        .back-link { color: #38bdf8; text-decoration: none; display: inline-block; margin-bottom: 20px; }
        .edit-card { background: #1e293b; border-radius: 16px; padding: 40px; border: 1px solid #334155; }
        .form-group { margin-bottom: 25px; }
        label { display: block; color: #94a3b8; margin-bottom: 10px; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; }
        .modern-input { 
            width: 100%; background: #0f172a; border: 1px solid #334155; border-radius: 8px; 
            padding: 12px 15px; color: #fff; font-size: 16px; transition: 0.3s;
        }
        .modern-input:focus { border-color: #38bdf8; outline: none; box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.2); }
        .save-btn { 
            background: #38bdf8; color: #fff; border: none; padding: 15px 40px; 
            border-radius: 8px; font-size: 16px; font-weight: 700; cursor: pointer; width: 100%;
        }
    </style>

    <div class="editor-wrap">
        <a href="?page=modern-orders-list" class="back-link">← Back to Orders</a>
        <h1 style="color:#fff;">Edit Order #<?php echo $order_id; ?></h1>

        <form method="post" action="">
            <?php wp_nonce_field('modern_order_action'); ?>
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">

            <div class="edit-card">
                <div class="form-group">
                    <label>Order Status</label>
                    <select name="order_status" class="modern-input">
                        <?php foreach (wc_get_order_statuses() as $key => $label) : ?>
                            <option value="<?php echo str_replace('wc-', '', $key); ?>" <?php selected('wc-'.$order->get_status(), $key); ?>>
                                <?php echo $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Receipt Link / ID</label>
                    <input type="text" name="order_receipt" class="modern-input" value="<?php echo esc_attr($receipt_val); ?>" placeholder="Enter receipt URL or reference...">
                    <?php if($receipt_val): ?>
                        <p style="font-size:12px; margin-top:8px;"><a href="<?php echo esc_url($receipt_val); ?>" target="_blank" style="color:#38bdf8;">Open Current Receipt ↗</a></p>
                    <?php endif; ?>
                </div>

                <div style="background:#0f172a; padding:20px; border-radius:8px; margin-bottom:30px;">
                    <p style="margin:0; color:#94a3b8;">Customer: <span style="color:#fff;"><?php echo $order->get_billing_email(); ?></span></p>
                    <p style="margin:10px 0 0 0; color:#94a3b8;">Total Amount: <span style="color:#fff; font-size:18px;"><?php echo $order->get_formatted_order_total(); ?></span></p>
                </div>

                <button type="submit" name="modern_order_save" class="save-btn">Save Order Changes</button>
            </div>
        </form>
    </div>
    <?php
}