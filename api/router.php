<?php
/**
 * ROUTER TRUNG TÂM - Xử lý tất cả request trên Vercel
 */

// 1. Setup session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Load helpers
require_once __DIR__ . '/../Config/helpers.php';

// 3. Define BASE_URL (Vercel-safe)
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define("BASE_URL", $protocol . "://" . $host . "/");
}

// 4. Get the requested path
$request_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_path = trim($request_path, '/');

// 5. Route handler
$routes = [
    '' => 'Public/index.php',
    'feed' => 'App/Views/feed.php',
    'login' => 'App/Views/auth/login.php',
    'register' => 'App/Views/auth/register.php',
    'forgot-password' => 'App/Views/auth/forgotpassword.php',
    'profile' => 'App/Views/profile.php',
    'admin' => 'App/Views/admin/index.php',
    'admin/dashboard' => 'App/Views/admin/dashboard.php',
];

// 6. Handle form submissions (POST requests to process files)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (strpos($request_path, 'process-login') !== false || $request_path === 'auth/process-login') {
        require_once __DIR__ . '/../Config/Database.php';
        require_once __DIR__ . '/../App/Controllers/AuthController.php';
        $database = new Database();
        $db = $database->connect();
        $auth = new \App\Controllers\AuthController($db);
        $auth->loginProcess();
        exit;
    } elseif (strpos($request_path, 'process-register') !== false || $request_path === 'auth/process-register') {
        require_once __DIR__ . '/../Config/Database.php';
        require_once __DIR__ . '/../App/Controllers/AuthController.php';
        $database = new Database();
        $db = $database->connect();
        $auth = new \App\Controllers\AuthController($db);
        $auth->registerProcess();
        exit;
    } elseif (strpos($request_path, 'process-forgot') !== false || $request_path === 'auth/process-forgot') {
        require_once __DIR__ . '/../Config/Database.php';
        require_once __DIR__ . '/../App/Controllers/AuthController.php';
        $database = new Database();
        $db = $database->connect();
        $auth = new \App\Controllers\AuthController($db);
        $auth->forgotPasswordProcess();
        exit;
    }
}

// 7. Route to file if exists in routes array
if (isset($routes[$request_path])) {
    $file = __DIR__ . '/../' . $routes[$request_path];
    if (file_exists($file)) {
        require $file;
        exit;
    }
}

// 8. Fallback to home page
require __DIR__ . '/../Public/index.php';
exit;

