<?php
if (!defined('BASE_URL')) {
    define("BASE_URL", "http://localhost:3000/");
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu | Social Network</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>Public/assets/CSS/login-style.css">
</head>
<body>

    <div class="login-container">
        <h2>Quên mật khẩu?</h2>
        <p class="subtitle">Nhập email đăng ký để thiết lập mật khẩu mới</p>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div style="color: #dc3545; padding: 8px; margin-bottom: 15px; font-size: 14px; text-align: center; background: rgba(220, 53, 69, 0.1); border-radius: 4px;">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="process-forgot.php" method="POST">
            <div class="form-group">
                <label for="email"><i class="fa-regular fa-envelope"></i> Email đã đăng ký</label>
                <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email của bạn" required>
            </div>

            <div class="form-group">
                <label for="new_password"><i class="fa-solid fa-lock"></i> Mật khẩu mới</label>
                <input type="password" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới" required>
            </div>

            <div class="form-group">
                <label for="confirm_password"><i class="fa-solid fa-shield-halved"></i> Xác nhận mật khẩu</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
            </div>

            <button type="submit" class="btn-login">XÁC NHẬN ĐỔI MẬT KHẨU</button>
        </form>

        <div class="extra-links" style="justify-content: center; margin-top: 15px;">
            <a href="<?php echo BASE_URL; ?>Views/auth/login.php"><i class="fa-solid fa-angle-left"></i> Quay lại Đăng nhập</a>
        </div>
    </div>

</body>
</html>