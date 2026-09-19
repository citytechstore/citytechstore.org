<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// This is a fetch()-only, read-only data endpoint, not a page navigation —
// so unlike requireRole() (used everywhere else), a logged-out or
// wrong-role request gets a JSON 401/403 here instead of an HTML redirect.
// requireRole() itself is left untouched; these are the same two checks it
// performs internally, just with a different response on failure.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_session']['role'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}

if (!in_array($_SESSION['user_session']['role'], ['admin', 'worker'], true)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden.']);
    exit;
}

require_once 'app/models/Database.php';
require_once 'app/models/Order.php';

$allowedDays = [7, 30, 90];
$days = filter_var($_GET['days'] ?? null, FILTER_VALIDATE_INT);
if (!in_array($days, $allowedDays, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid range.']);
    exit;
}

$dailyRevenueRows = $OrderModel->getDailyRevenueForLastNDays($days);
$revenueByDay = [];
foreach ($dailyRevenueRows as $row) {
    $revenueByDay[$row['day']] = (float) $row['revenue'];
}

$labels = [];
$data = [];
for ($i = $days - 1; $i >= 0; $i--) {
    $day = date('Y-m-d', strtotime("-{$i} days"));
    $labels[] = date('M j', strtotime($day));
    $data[] = $revenueByDay[$day] ?? 0;
}

echo json_encode(['success' => true, 'days' => $days, 'labels' => $labels, 'data' => $data]);
