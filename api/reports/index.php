<?php
/**
 * Reports API - Analytics & Reporting
 */

require_once __DIR__ . '/../../includes/auth.php';

$auth = new Auth();
$db = Database::getInstance();

// Require authentication
if (!$auth->isLoggedIn()) {
    Response::error('Authentication required', 401);
}

$auth->requireRole(['outreach_worker', 'service_provider', 'admin']);

switch ($requestMethod) {
    case 'GET':
        if ($action === 'kpi') {
            // Get KPI dashboard metrics
            $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
            $endDate = $_GET['end_date'] ?? date('Y-m-d');
            
            // Total clients
            $totalClients = $db->fetch("SELECT COUNT(*) as count FROM client_profiles")['count'];
            
            // New clients this period
            $newClients = $db->fetch("
                SELECT COUNT(*) as count FROM client_profiles 
                WHERE created_at BETWEEN :start_date AND :end_date
            ", ['start_date' => $startDate, 'end_date' => $endDate])['count'];
            
            // Total orders
            $totalOrders = $db->fetch("
                SELECT COUNT(*) as count FROM orders 
                WHERE created_at BETWEEN :start_date AND :end_date
            ", ['start_date' => $startDate, 'end_date' => $endDate])['count'];
            
            // Active orders
            $activeOrders = $db->fetch("
                SELECT COUNT(*) as count FROM orders 
                WHERE status IN ('pending', 'processing', 'in_transit')
            ")['count'];
            
            // Completed orders
            $completedOrders = $db->fetch("
                SELECT COUNT(*) as count FROM orders 
                WHERE status = 'delivered' AND created_at BETWEEN :start_date AND :end_date
            ", ['start_date' => $startDate, 'end_date' => $endDate])['count'];
            
            // Total appointments
            $totalAppointments = $db->fetch("
                SELECT COUNT(*) as count FROM appointments 
                WHERE created_at BETWEEN :start_date AND :end_date
            ", ['start_date' => $startDate, 'end_date' => $endDate])['count'];
            
            // Completed appointments
            $completedAppointments = $db->fetch("
                SELECT COUNT(*) as count FROM appointments 
                WHERE status = 'completed' AND created_at BETWEEN :start_date AND :end_date
            ", ['start_date' => $startDate, 'end_date' => $endDate])['count'];
            
            // No-show rate
            $noShows = $db->fetch("
                SELECT COUNT(*) as count FROM appointments 
                WHERE status = 'no_show' AND created_at BETWEEN :start_date AND :end_date
            ", ['start_date' => $startDate, 'end_date' => $endDate])['count'];
            
            // Active referrals
            $activeReferrals = $db->fetch("
                SELECT COUNT(*) as count FROM referrals 
                WHERE status IN ('pending', 'accepted', 'in_progress')
            ")['count'];
            
            // Low stock products
            $lowStockProducts = $db->fetch("
                SELECT COUNT(*) as count FROM products 
                WHERE stock_quantity <= reorder_level AND is_active = TRUE
            ")['count'];
            
            Response::success([
                'total_clients' => $totalClients,
                'new_clients' => $newClients,
                'total_orders' => $totalOrders,
                'active_orders' => $activeOrders,
                'completed_orders' => $completedOrders,
                'total_appointments' => $totalAppointments,
                'completed_appointments' => $completedAppointments,
                'no_shows' => $noShows,
                'no_show_rate' => $totalAppointments > 0 ? round(($noShows / $totalAppointments) * 100, 2) : 0,
                'active_referrals' => $activeReferrals,
                'low_stock_products' => $lowStockProducts
            ]);
            
        } elseif ($action === 'demographics') {
            // Client demographics report
            
            // Age distribution (SQLite compatible)
            $ageDistribution = $db->fetchAll("
                SELECT 
                    CASE 
                        WHEN CAST((julianday('now') - julianday(date_of_birth)) / 365.25 AS INTEGER) < 18 THEN 'Under 18'
                        WHEN CAST((julianday('now') - julianday(date_of_birth)) / 365.25 AS INTEGER) BETWEEN 18 AND 24 THEN '18-24'
                        WHEN CAST((julianday('now') - julianday(date_of_birth)) / 365.25 AS INTEGER) BETWEEN 25 AND 34 THEN '25-34'
                        WHEN CAST((julianday('now') - julianday(date_of_birth)) / 365.25 AS INTEGER) BETWEEN 35 AND 44 THEN '35-44'
                        WHEN CAST((julianday('now') - julianday(date_of_birth)) / 365.25 AS INTEGER) BETWEEN 45 AND 54 THEN '45-54'
                        WHEN CAST((julianday('now') - julianday(date_of_birth)) / 365.25 AS INTEGER) BETWEEN 55 AND 64 THEN '55-64'
                        ELSE '65+'
                    END as age_group,
                    COUNT(*) as count
                FROM client_profiles
                WHERE date_of_birth IS NOT NULL
                GROUP BY age_group
            ");
            
            // Housing status distribution
            $housingStatus = $db->fetchAll("
                SELECT housing_status, COUNT(*) as count
                FROM client_profiles
                GROUP BY housing_status
            ");
            
            // Risk level distribution
            $riskLevel = $db->fetchAll("
                SELECT risk_level, COUNT(*) as count
                FROM client_profiles
                GROUP BY risk_level
            ");
            
            Response::success([
                'age_distribution' => $ageDistribution,
                'housing_status' => $housingStatus,
                'risk_level' => $riskLevel
            ]);
            
        } elseif ($action === 'inventory-usage') {
            // Inventory usage report
            $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
            $endDate = $_GET['end_date'] ?? date('Y-m-d');
            
            $usage = $db->fetchAll("
                SELECT p.name, p.category, p.unit,
                       SUM(CASE WHEN it.transaction_type = 'out' THEN it.quantity ELSE 0 END) as total_out,
                       SUM(CASE WHEN it.transaction_type = 'in' THEN it.quantity ELSE 0 END) as total_in,
                       p.stock_quantity as current_stock
                FROM products p
                LEFT JOIN inventory_transactions it ON p.id = it.product_id 
                    AND it.created_at BETWEEN :start_date AND :end_date
                GROUP BY p.id
                ORDER BY total_out DESC
            ", ['start_date' => $startDate, 'end_date' => $endDate]);
            
            Response::success(['usage' => $usage]);
            
        } elseif ($action === 'order-fulfillment') {
            // Order fulfillment metrics
            $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
            $endDate = $_GET['end_date'] ?? date('Y-m-d');
            
            $metrics = $db->fetchAll("
                SELECT 
                    status,
                    COUNT(*) as count,
                    AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours
                FROM orders
                WHERE created_at BETWEEN :start_date AND :end_date
                GROUP BY status
            ", ['start_date' => $startDate, 'end_date' => $endDate]);
            
            // Orders by type
            $ordersByType = $db->fetchAll("
                SELECT order_type, COUNT(*) as count
                FROM orders
                WHERE created_at BETWEEN :start_date AND :end_date
                GROUP BY order_type
            ", ['start_date' => $startDate, 'end_date' => $endDate]);
            
            Response::success([
                'metrics' => $metrics,
                'orders_by_type' => $ordersByType
            ]);
            
        } elseif ($action === 'worker-productivity') {
            // Worker productivity metrics
            $auth->requireRole(['admin']);
            
            $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
            $endDate = $_GET['end_date'] ?? date('Y-m-d');
            
            $productivity = $db->fetchAll("
                SELECT 
                    u.id, u.first_name, u.last_name,
                    COUNT(DISTINCT cp.id) as assigned_clients,
                    COUNT(DISTINCT o.id) as orders_placed,
                    COUNT(DISTINCT r.id) as referrals_made,
                    COUNT(DISTINCT cn.id) as case_notes
                FROM users u
                LEFT JOIN client_profiles cp ON u.id = cp.assigned_worker_id
                LEFT JOIN orders o ON u.id = o.placed_by AND date(o.created_at) BETWEEN :start_date AND :end_date
                LEFT JOIN referrals r ON u.id = r.referred_by AND date(r.created_at) BETWEEN :start_date AND :end_date
                LEFT JOIN case_notes cn ON u.id = cn.created_by AND date(cn.created_at) BETWEEN :start_date AND :end_date
                WHERE u.role = 'outreach_worker'
                GROUP BY u.id, u.first_name, u.last_name
            ", ['start_date' => $startDate, 'end_date' => $endDate]);
            
            Response::success(['productivity' => $productivity]);
            
        } else {
            Response::error('Invalid report type', 400);
        }
        break;
        
    default:
        Response::error('Method not allowed', 405);
}
