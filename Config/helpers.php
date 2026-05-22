<?php
/**
 * URL Helper - Convert to relative paths for Vercel
 */

if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define("BASE_URL", $protocol . "://" . $host . "/");
}

function url($path = '') {
    /**
     * Generate URLs as relative paths instead of absolute
     * Examples:
     *   url('login') -> /login
     *   url('profile') -> /profile
     *   url('Public/assets/css/style.css') -> /assets/css/style.css
     */
    $path = trim($path, '/');
    
    // Handle Public assets
    if (strpos($path, 'Public/') === 0) {
        $path = str_replace('Public/', '', $path);
        return '/' . $path;
    }
    
    // Handle App/Views paths
    if (strpos($path, 'App/Views/') === 0) {
        $path = str_replace('App/Views/', '', $path);
        $path = str_replace('.php', '', $path);
        // auth/login.php -> /login
        if (strpos($path, 'auth/') === 0) {
            $path = str_replace('auth/', '', $path);
        }
        return '/' . $path;
    }
    
    return '/' . $path;
}

function assetUrl($path) {
    /**
     * Generate asset URLs
     * Examples:
     *   assetUrl('css/style.css') -> /assets/css/style.css
     *   assetUrl('img/avatar.jpg') -> /assets/img/avatar.jpg
     */
    return '/assets/' . trim($path, '/');
}

function imageUrl($path) {
    /**
     * Generate image URLs for user uploads
     */
    if (empty($path)) {
        return assetUrl('img/default-avatar.jpg');
    }
    
    if (str_starts_with($path, "http://") || str_starts_with($path, "https://")) {
        return $path;
    }
    
    // Remove Public/ prefix if exists
    $path = str_replace("Public/", "", $path);
    $path = str_replace("uploads/", "", $path);
    
    return '/uploads/' . ltrim($path, '/');
}
