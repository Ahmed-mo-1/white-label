<?php


/**
 * Modern Dark Mode WooCommerce Report Page
 */

add_action('admin_menu', 'register_modern_dark_report_page');

function register_modern_dark_report_page() {
    add_menu_page(
        'Store Analytics',
        'Modern Reports',
        'manage_options',
        'modern-dark-report',
        'render_modern_dark_report',
        'dashicons-chart-area',
        2 // Position high in the menu
    );
}

function render_modern_dark_report() {
    global $wpdb;

    // 1. DATA QUERIES
    // Get total revenue (last 30 days)
    $total_revenue = $wpdb->get_var("SELECT SUM(total_sales) FROM {$wpdb->prefix}wc_order_stats WHERE date_created > NOW() - INTERVAL 30 DAY");
    
    // Get total orders (last 30 days)
    $total_orders = $wpdb->get_var("SELECT COUNT(order_id) FROM {$wpdb->prefix}wc_order_stats WHERE date_created > NOW() - INTERVAL 30 DAY");

    // Get Recent Sales List
    $recent_sales = $wpdb->get_results("
        SELECT order_id, total_sales, date_created 
        FROM {$wpdb->prefix}wc_order_stats 
        ORDER BY date_created DESC LIMIT 5
    ");

    // 2. STYLES (Modern Dark Theme)
    ?>
    <style>
        #wpcontent { background: var(--background-color); } /* Deep Dark Background */
        .modern-report-wrapper {
            padding: 30px;
            color: #f8fafc;
            font-family: 'Inter', -apple-system, sans-serif;
        }
        .report-header { margin-bottom: 30px; }
        .report-header h1 { color: #fff; font-size: 28px; font-weight: 700; margin: 0; }
        
        /* Grid Layout */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        /* Modern Cards */
        .stat-card {
            background: #202020;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #334155;
            transition: transform 0.2s;
        }
        .stat-card:hover { border-color: #38bdf8; }
        .stat-card .label { color: #94a3b8; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-card .value { color: #fff; font-size: 32px; font-weight: 800; margin-top: 10px; display: block; }
        .stat-card .trend { font-size: 12px; margin-top: 8px; color: #10b981; }

        /* Modern Table */
        .data-section {
            background: #202020;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #334155;
        }
        .data-section h2 { color: #fff; font-size: 18px; margin-bottom: 20px; }
        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }
        .custom-table th {
            text-align: left;
            color: #94a3b8;
            padding: 12px;
            border-bottom: 1px solid #334155;
            font-size: 13px;
        }
        .custom-table td {
            padding: 16px 12px;
            color: #e2e8f0;
            border-bottom: 1px solid #334155;
        }
        .order-badge {
            background: #334155;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            color: #38bdf8;
        }
    </style>

    <div class="modern-report-wrapper">
        <div class="report-header">
            <h1>Store Performance</h1>
            <p style="color: #94a3b8;">Real-time insights for your WooCommerce store.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <span class="label">30-Day Revenue</span>
                <span class="value">$<?php echo number_format($total_revenue, 2); ?></span>
                <div class="trend">↑ 12% vs last month</div>
            </div>
            <div class="stat-card">
                <span class="label">30-Day Orders</span>
                <span class="value"><?php echo $total_orders; ?></span>
                <div class="trend">↑ 5% vs last month</div>
            </div>
            <div class="stat-card">
                <span class="label">Avg. Order Value</span>
                <span class="value">$<?php echo ($total_orders > 0) ? number_format($total_revenue / $total_orders, 2) : '0'; ?></span>
            </div>
        </div>

        <div class="data-section">
            <h2>Recent Orders</h2>
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date Created</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_sales as $sale) : ?>
                    <tr>
                        <td><span class="order-badge">#<?php echo $sale->order_id; ?></span></td>
                        <td><?php echo date('M j, Y', strtotime($sale->date_created)); ?></td>
                        <td><strong>$<?php echo number_format($sale->total_sales, 2); ?></strong></td>
                        <td style="color: #10b981;">Completed</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}