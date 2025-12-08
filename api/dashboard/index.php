<?php
/**
 * Dashboard Statistics API
 */

require_once __DIR__ . '/../../includes/auth.php';

$auth = new Auth();
$db = Database::getInstance();

// Require admin role
$auth->requireRole('admin');

// Get request method
$requestMethod = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? $action ?? 'stats';

switch ($requestMethod) {
    case 'GET':
        if ($action === 'stats') {
            try {
                // Get total users count
                $totalUsers = $db->fetch("SELECT COUNT(*) as count FROM users")['count'];
                
                // Get orders this month
                $ordersThisMonth = $db->fetch(
                    "SELECT COUNT(*) as count FROM orders 
                     WHERE strftime('%Y-%m', created_at) = strftime('%Y-%m', 'now')"
                )['count'];
                
                // Get active referrals (pending, accepted, in_progress)
                $activeReferrals = $db->fetch(
                    "SELECT COUNT(*) as count FROM referrals 
                     WHERE status IN ('pending', 'accepted', 'in_progress')"
                )['count'];
                
                // Get total products/supplies distributed (from order_items)
                $suppliesDistributed = $db->fetch(
                    "SELECT COALESCE(SUM(quantity), 0) as total FROM order_items"
                )['total'];
                
                // Get users by role for pie chart
                $usersByRole = $db->fetchAll(
                    "SELECT role, COUNT(*) as count FROM users WHERE status = 'active' GROUP BY role"
                );
                
                // Format user role data
                $roleData = [];
                $roleLabels = [
                    'client' => 'Clients',
                    'outreach_worker' => 'Workers',
                    'service_provider' => 'Providers',
                    'admin' => 'Admins'
                ];
                
                foreach ($usersByRole as $row) {
                    $roleData[] = [
                        'role' => $roleLabels[$row['role']] ?? $row['role'],
                        'count' => $row['count']
                    ];
                }
                
                // Get recent users (last 10)
                $recentUsers = $db->fetchAll(
                    "SELECT id, username, first_name, last_name, email, role, status, created_at 
                     FROM users 
                     ORDER BY created_at DESC 
                     LIMIT 10"
                );
                
                // Get distribution overview (orders per day for last 7 days)
                $distributionData = $db->fetchAll(
                    "SELECT 
                        date(created_at) as order_date,
                        COUNT(*) as order_count
                     FROM orders 
                     WHERE created_at >= date('now', '-7 days')
                     GROUP BY date(created_at)
                     ORDER BY order_date ASC"
                );
                
                // Fill in missing days with 0
                $last7Days = [];
                for ($i = 6; $i >= 0; $i--) {
                    $date = date('Y-m-d', strtotime("-$i days"));
                    $last7Days[$date] = 0;
                }
                
                foreach ($distributionData as $row) {
                    if (isset($last7Days[$row['order_date']])) {
                        $last7Days[$row['order_date']] = $row['order_count'];
                    }
                }
                
                $distributionChart = [];
                $dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                foreach ($last7Days as $date => $count) {
                    $dayOfWeek = date('w', strtotime($date));
                    $distributionChart[] = [
                        'day' => $dayNames[$dayOfWeek],
                        'date' => $date,
                        'count' => $count
                    ];
                }
                
                Response::success([
                    'stats' => [
                        'total_users' => $totalUsers,
                        'orders_this_month' => $ordersThisMonth,
                        'active_referrals' => $activeReferrals,
                        'supplies_distributed' => $suppliesDistributed
                    ],
                    'users_by_role' => $roleData,
                    'recent_users' => $recentUsers,
                    'distribution_chart' => $distributionChart
                ]);
            } catch (Exception $e) {
                Response::error($e->getMessage(), 500);
            }
        } else {
            Response::error('Invalid action', 400);
        }
        break;
        
    default:
        Response::error('Method not allowed', 405);
}
