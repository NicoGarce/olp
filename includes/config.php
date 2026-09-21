<?php
// OLP â€” Online Payment Portal â€” STANDALONE config
// All payment data uses LOCAL includes/dbconnect.php (mysqli $con â†’ UPHSedu_onlinepayment)
// Admin auth uses LOCAL includes/auth.php + app/config/database.php (PDO â†’ UPHSedu_main) â€” NOT UPHSedu folder
if (session_status() === PHP_SESSION_NONE) session_start();

// --- Payments DB (LOCAL) ---
require_once __DIR__ . '/dbconnect.php';
require_once __DIR__ . '/campus_table_manager.php';

if (isset($con)) {
    @mysqli_set_charset($con, 'utf8mb4');
    ensureCampusTablesExist($con);
    ensureTmpStudentTablesExist($con);
}

// --- Base path for this standalone hub ---
// Detect environment (folder-agnostic - supports pay, online_payment, olpay, olp, etc.)
//  - pay.uphsl.edu.ph or online_payment.uphsl.edu.ph => '/'
//  - uphsl.edu.ph/pay or /online_payment etc. => '/pay/' or '/online_payment/' (auto-detected)
//  - localhost/<any> => '/<folder>/' inferred from SCRIPT_NAME
if (!isset($GLOBALS['payments_base'])) {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $uri  = $_SERVER['REQUEST_URI'] ?? '';
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $reqPath = parse_url($uri, PHP_URL_PATH) ?: '';
    // Dedicated subdomain = root
    if (strpos($host, 'pay.uphsl.edu.ph') !== false || strpos($host, 'online_payment') !== false) {
        $GLOBALS['payments_base'] = '/';
    } else {
        // Use the executed script directory first so nested deployments such as
        // /online_payment_copy/online_payment/ keep assets inside this portal.
        $scriptDir = $script !== '' ? str_replace('\\', '/', dirname($script)) : '';
        if (preg_match('#^[A-Za-z]:/#', $scriptDir)) {
            $scriptDir = '';
        }
        // Admin pages live one directory below the payment portal root.
        // Do not make /admin/ part of payments_base or auth redirects become
        // /admin/admin/login.
        if ($scriptDir !== '' && preg_match('#/admin$#', rtrim($scriptDir, '/'))) {
            $scriptDir = dirname(rtrim($scriptDir, '/'));
        }
        $candidates = $scriptDir !== '' && $scriptDir !== '.' ? [$scriptDir, $reqPath] : [$reqPath];
        foreach (array_unique(array_filter($candidates)) as $candidate) {
            if ($candidate === '/') {
                $GLOBALS['payments_base'] = '/';
                break;
            }
            if ($candidate !== $scriptDir && strpos($candidate, '/') !== false) {
                $candidate = '/' . trim(dirname($candidate), '/') . '/';
            }
            if (preg_match('#^/([^/]+)(?:/|$)#', $candidate, $m)) {
                $GLOBALS['payments_base'] = rtrim($candidate, '/') . '/';
                break;
            }
        }
        if (!isset($GLOBALS['payments_base'])) {
            $GLOBALS['payments_base'] = '/';
        }
    }
}
$payments_base = $GLOBALS['payments_base'];

// --- Admin auth â€” load LOCAL auth ---
require_once __DIR__ . '/auth.php';

function paymentsRequireAdmin() {
    olp_requireAdmin();
}

