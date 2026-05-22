<?php
// 1. Khởi động session để hứng và hiển thị thông báo lỗi/thành công từ Controller chuyển hướng về
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Load helpers
require_once __DIR__ . '/../../../Config/helpers.php';

// 3. Định nghĩa hằng số đường dẫn gốc hệ thống
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define("BASE_URL", $protocol . "://" . $host . "/");
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập | Social Network</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= assetUrl('CSS/login-style.css') ?>">
</head>
<body>

    <div class="login-container">
        <h2>Xin chào!</h2>
        <p class="subtitle">Vui lòng đăng nhập để kết nối với bạn bè</p>

        <?php if(isset($_SESSION['error'])): ?>
            <div style="color: #dc3545; padding: 8px; margin-bottom: 15px; font-size: 14px; text-align: center; background: rgba(220, 53, 69, 0.08); border-radius: 4px;">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div style="color: #198754; padding: 8px; margin-bottom: 15px; font-size: 14px; text-align: center; background: rgba(25, 135, 84, 0.08); border-radius: 4px;">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= url('login') ?>" method="POST">
            <div class="form-group">
                <label for="username"><i class="fa-regular fa-user"></i> Tài khoản</label>
                <input type="text" id="username" name="username" placeholder="Tên đăng nhập hoặc Email" required>
            </div>

            <div class="form-group">
                <label for="password"><i class="fa-solid fa-lock"></i> Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Mật khẩu của bạn" required>
            </div>

            <button type="submit" class="btn-login">ĐĂNG NHẬP NGAY</button>
        </form>

        <div class="divider">
            <span>HOẶC</span>
        </div>

        <div class="extra-links">
            <a href="<?= url('forgot-password') ?>">Quên mật khẩu?</a>
            <a href="<?= url('register') ?>">Chưa có tài khoản? Đăng ký</a>
        </div>
    </div>

</body>
</html>
